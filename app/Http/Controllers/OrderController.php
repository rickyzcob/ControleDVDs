<?php

namespace App\Http\Controllers;

use App\Jobs\CheckAvailableJob;
use App\Models\Clients;
use App\Models\Order;
use App\Models\OrdersProducts;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orderDB = Order::query()->with(['client','items'])->orderBy('created_at', 'desc')->get();

            $return = [];

            foreach ($orderDB as $key => $itemOrder) {
                $return[$key]['id'] = $itemOrder['id'];
                $return[$key]['name'] = $itemOrder['client']['name'];
                $return[$key]['price'] = $itemOrder['total_price'];
                $return[$key]['status'] = $itemOrder['status'];
                foreach($itemOrder['items'] as $index => $item){
                    $return[$key]['items'][$index]['product'] = $item['product']['title'];
                    $return[$key]['items'][$index]['quantity'] = $item['product']['quantity'];
                    $return[$key]['items'][$index]['price'] = $item['product']['price'];
                    $return[$key]['items'][$index]['total_price'] = $item['product']['total_price'];
                }
            }

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $return,
                'message' => 'sucesso'
            ]);
        } catch (Exception $exception){
            return  response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'erro na requisiçao'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'products.*.product_id' =>'required',
            'products.*.quantity' => 'required',
        ]);

        if ($validator->fails()) {
            return  response()->json([
                'status' => 400,
                'error' => 'validation',
                'message' => $validator->errors(),
            ]);
        }

        $requestValidated = $validator->validated();

        try {

            $clientDB = Clients::query()->find($requestValidated['client_id']);
            if(!$clientDB) {
                return  response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Cliente não cadastrado'
                ]);
            }

            DB::beginTransaction();

            $orderDB = Order::query()->with(['items'])->create(['client_id' => $requestValidated['client_id']]);

            foreach ($requestValidated['products'] as $itemData) {
                $productDB = Products::query()->with(['stock'])->findOrFail($itemData['product_id']);

                if($itemData['quantity'] > $productDB['stock']['quantity']) {
                    return  response()->json([
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'O Produto ' .$productDB['title']. ' tem quantidade maior que disponível no estoque .'
                    ]);
                }

                OrdersProducts::query()->create([
                    'order_id' => $orderDB['id'],
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'price' => $productDB['price'],
                    'total_price' => $itemData['quantity'] * $productDB['price']
                ]);

                $productDB['stock']->decrement('quantity', $itemData['quantity']);
            }

            $orderDB->update(['total_price' => $orderDB['items']->sum('total_price')]);

            $batch = Bus::batch([
                new CheckAvailableJob($orderDB['id']),
            ])->dispatch();

            DB::commit();
            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $orderDB,
                'message' => 'Pedido Adicionado com sucesso'
            ]);
        } catch (Exception $exception) {
            return  response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'erro na requisiçao'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $orderDB = Order::query()->with(['client','items'])->find($id);
            if(!$orderDB) {
                return  response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Pedido nao encontrado'
                ]);
            }

            $return = [];

            $return['id'] = $orderDB['id'];
            $return['name'] = $orderDB['client']['name'];
            $return['price'] = $orderDB['total_price'];
            $return['status'] = $orderDB['status'];
            foreach($orderDB['items'] as $index => $item){
                $return['items'][$index]['product'] = $item['product']['title'];
                $return['items'][$index]['quantity'] = $item['product']['quantity'];
                $return['items'][$index]['price'] = $item['product']['price'];
                $return['items'][$index]['total_price'] = $item['product']['total_price'];
            }
            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $return,
            ]);
        } catch (Exception $exception) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'erro na requisiçao'
            ];
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|string',
            'products.*.product_id' =>'required',
            'products.*.quantity' => 'required',
        ]);

        if ($validator->fails()) {
            return  response()->json([
                'status' => 400,
                'error' => 'validation',
                'message' => $validator->errors(),
            ]);
        }

        $requestValidated = $validator->validated();

        try {
            $order = Order::query()->with(['items', 'client'])->find($id);
            if(!$order) {
                return  response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Pedido nao encontrado'
                ]);
            }

            if($order['status'] == 'return') {
                return  response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Pedido já foi feito a devolução e nao pode ser editado.'
                ]);
            }

            foreach ($requestValidated['products'] as $key => $value) {
                $id = $requestValidated['products'][$key]['id'] ?? false;

                if ($id) {
                    OrdersProducts::query()->find($id)->update([
                        'quantity' => $requestValidated['products'][$key]['quantity'],
                    ]);
                } else {
                    OrdersProducts::query()->create([
                        'order_id' => $id,
                        'product_id' => $requestValidated['products'][$key]['product_id'],
                        'quantity' => $requestValidated['products'][$key]['quantity'],
                    ]);
                }
            }

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $order,
                'message' => 'Pedido atualizado com sucesso'
            ]);
        } catch (Exception $exception) {
            return  response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'erro na requisiçao'
            ]);
        }
    }

    public function updateStatus(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $orderDB = Order::query()->find($id);

            if(!$orderDB) {
                return  response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Pedido nao encontrado'
                ]);
            }

            if($orderDB['status'] == 'return') {
                return  response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'O pedido selecionado já foi efetuado a devolução'
                ]);
            }

            foreach ($orderDB['items'] as $item) {
                $productDB = Products::query()->with(['stock'])->findOrFail($item['product_id']);

                $productDB['stock']->increment('quantity', $item['quantity']);

                if($productDB['stock']['quantity'] > 0) {
                    $productDB->update(['available' => 'yes']);
                }
            }

            $orderDB->update(['status' => 'return']);

            DB::commit();

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $orderDB,
                'message' => 'Devolução realizada com sucesso'
            ]);
        } catch (Exception $exception) {
            DB::rollback();
            return  response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'erro na requisiçao'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $orderDB = Order::query()->with(['items'])->find($id);

            if(!$orderDB) {
                return [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Pedido nao encontrado'
                ];
            }

            if($orderDB['status'] == 'withdrawn') {
            foreach ($orderDB['items'] as $itemProduct){
                    $productDB = Products::query()->with(['stock'])->find($itemProduct['product_id']);
                    $productDB['stock']->increment('quantity', $itemProduct['quantity']);
                }
            }

            $orderDB->delete();

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Pedido deletado com sucesso !',
            ];
        } catch (Exception $exception) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'erro na requisiçao'
            ];
        }
    }
}
