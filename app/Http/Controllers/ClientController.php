<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($pageSize = null, $orderBy = null)
    {
        try {
            $clientDB = Clients::query();

            if($orderBy) {
                $clientDB->orderBy($orderBy['column'], $orderBy['direction']);
            }

            if($pageSize) {
                $clientDB = $clientDB->paginate($pageSize);
            } else {
                $clientDB = $clientDB->get();
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $clientDB,
                'message' => 'sucesso'
            ]) ;
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
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required'
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
            $clientDB = Clients::query()->create($requestValidated);

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $clientDB,
                'message' => 'Cliente Adicionado com sucesso'
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
            $clientDB = Clients::query()->findOrFail($id);

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $clientDB,
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
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required'
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
            $clientDB = Clients::query()->findOrFail($id);
            $clientDB->update($requestValidated);

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $clientDB,
                'message' => 'Cliente atualizado com sucesso'
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $clientDB = Clients::query()->find($id);
            $clientDB->delete();

            return  response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Cliente deletado com sucesso !',
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
