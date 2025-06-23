<?php

namespace App\Controllers;

use Myth\Auth\Entities\User;
use \Myth\Auth\Models\UserModel;
use \Myth\Auth\Password;

class Page extends BaseController
{

	public function index()
	{
		echo view("landing_page");
	}

	public function Login()
	{
		echo view("login");
	}

	public function register()
	{
		echo view("register");
	}

	public function profile()
	{
		echo view("profile", ['user' => user()]);
	}

	public function profile_update()
	{
		$userModel = new UserModel();
		$id = user_id();

		$data = [
			'id' => $id,
			'username' => $this->request->getPost('username'),
			'email' => $this->request->getPost('email'),
			'phone_number' => $this->request->getPost('phone_number'),
		];

		$userModel->save($data);
		return redirect()->to(base_url('profile'))->with('success', 'Profile berhasil diupdate');
	}
}
