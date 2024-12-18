<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return  response()->json([
                'status' => 400,
                'error' => 'validation',
                'message' => $validator->errors(),
            ]);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['user'] =  $user;

        return  response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $user,
            'message' => 'Usuário cadastrado com sucesso'
        ]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
//            dd($token);
//            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
            return  response()->json([
                'status' => 400,
                'error' => 'Nao autorizado',
                'message' => ['error'=>'Unauthorised'],
            ]);
        }

        $success = $this->respondWithToken($token);

//        return $this->sendResponse($success, 'User login successfully.');

        return  response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $success,
            'message' => 'Usuário logado com sucesso'
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $success = auth()->user();

        return  response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $success,
            'message' => 'Usuário atualizado atualizado com sucesso'
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return  response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Usuário deslogado com sucesso'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $success = $this->respondWithToken(auth()->refresh());

        return  response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $success,
            'message' => 'Atualização do token feito com sucesso.'
        ]);

//        return $this->sendResponse($success, 'Refresh token return successfully.');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
//            'expires_in' => auth()->factory()->getTTL() * 60
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }
}
