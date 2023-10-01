<?php
namespace App\ServiceRepositoryPattern\Repositories;

use App\Models\User;

class AuthRepository
{
	public function register($data)
	{
		return User::create($data);
	}
}