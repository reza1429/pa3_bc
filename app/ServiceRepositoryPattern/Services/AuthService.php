<?php

namespace App\ServiceRepositoryPattern\Services;

use App\ServiceRepositoryPattern\Repositories\AuthRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService {
	private AuthRepository $authRepository;

	public function __construct() {
		$this->authRepository = new AuthRepository();
	}
	public function registrasi($data)
	{
		return $this->authRepository->register($data);
	}
    public function attempt($credentials){
        return JWTAuth::attempt($credentials);
    }
	public function logout()
	{
		auth()->logout();
	}
	public function user()
	{
		return JWTAuth::user();
	}
}