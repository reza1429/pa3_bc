<?php

namespace App\Http\Controller;

use App\Models\User;
use App\ServiceRepositoryPattern\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller {
	private AuthService $authService;
	public function __construct() {
        $this->authService = new AuthService();
    }

	public function register(Request $request)
	{
		$data = $request->validate([
			'name'=>'required|min:4',
			'email'=>'required|email',
			'password'=>'required|min:8',
		]);

		$data['password'] = bcrypt($request->password);

		$token = $this->authService->attempt($data);

		$registrasi = $this->authService->registrasi($data);

        return response()->json([
            "data" => $registrasi,
            "message" => "User berhasil registrasi",
            "success" => true,
        ]);
	}
	
	public function login(Request $request)
	{
		$data = $request->validate([
			'email'=>'required|email',
			'password'=>'required',
		]);

		if (!$token = $this->authService->attempt($data)) {
			return response()->json(['error'=>'Unauthorized'], 401);
		}

		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => JWTAuth::factory()->getTTL() * 60,
		]);
	}

	public function logout()
	{
		$this->authService->logout();

		return response()->json(['message'=>'Successfully logged out'], 200);
	}

	public function data()
	{
		return response()->json($this->authService->user());
	}

	public function refresh()
	{
		return response()->json([
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
	}
}