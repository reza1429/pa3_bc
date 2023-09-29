<?php

namespace App\ServiceRepositoryPattern\Services;

use App\ServiceRepositoryPattern\Repositories\AuthRepository;

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
		// return $credentials;
		$user = $this->authRepository->getAllPass();
        return auth()->attempt($user);
    }
}