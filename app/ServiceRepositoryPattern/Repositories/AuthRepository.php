<?php
namespace App\ServiceRepositoryPattern\Repositories;

use App\Models\User;

class AuthRepository
{
	public function register($data)
	{
		return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
	}
	public function getAllEmail()
	{
		return user::all('email');
	}
	public function getAllPass()
	{
		return User::first();
	}
}