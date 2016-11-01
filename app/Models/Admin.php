<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * 得到验证规则
	 * @return array
	 */
	public static function getValidationRules() {
		return [
			'username' => 'required',
			'password_o' => 'required',
			'password' => 'required|min:6|max:20|confirmed',
		];
	}

	/**
	 * 得到验证规则
	 * @return array
	 */
	public static function getValidationMessages() {
		return [
			'password_o.required' => '原密码 不能为空。',
		];
	}
}
