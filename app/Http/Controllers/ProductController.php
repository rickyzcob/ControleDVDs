<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($pageSize = null, $orderBy = null)
    {
        try {
            $productDB = Products::query();

            if($orderBy) {
                $productDB->orderBy($orderBy['column'], $orderBy['direction']);
            }

            if($pageSize) {
                $productDB = $productDB->paginate($pageSize);
            } else {
                $productDB = $productDB->get();
            }

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $productDB,
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
            'title' => 'required',
            'gender' => 'required',
            'availability' => 'required',
            'price' => 'required',
            'quantity' => 'required'
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

            $productDB = Products::query()->with(['stock'])->create($requestValidated);
            $productDB->stock()->create(['quantity' => $requestValidated['quantity']]);

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $productDB,
                'message' => 'Produto Adicionado com sucesso'
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
            $productDB = Products::query()->find($id);

            if(!$productDB){
                return  response()->json([
                    'status' => 'error',
                    'code' => 200,
                    'message' => 'Produto não encontrado !',
                ]);
            }
            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $productDB,
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
            'title' => 'required',
            'gender' => 'required',
            'availability' => 'required',
            'price' => 'required',
            'quantity' => 'required'
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
            $productDB = Products::query()->find($id);
            if(!$productDB){
                return  response()->json([
                    'status' => 'error',
                    'code' => 200,
                    'message' => 'Produto não encontrado !',
                ]);
            }
            $productDB->update($requestValidated);

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $productDB,
                'message' => 'Produto atualizado com sucesso'
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $productDB = Products::query()->find($id);

            if(!$productDB){
                return  response()->json([
                    'status' => 'error',
                    'code' => 200,
                    'message' => 'Produto não encontrado !',
                ]);
            }
            $productDB->delete();

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Produto deletado com sucesso !',
            ]);
        } catch (Exception $exception) {
            return  response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'erro na requisiçao'
            ]);
        }
    }
}
