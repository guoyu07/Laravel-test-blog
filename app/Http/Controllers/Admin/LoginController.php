<?php
/**
 * Copyright (c) 2016.
 *                                            __                       __
 *   ____  ____   _____   ____   ____   _____/  |___  _  _____________|  | __
 * _/ ___\/  _ \ /     \ /  _ \ /    \_/ __ \   __\ \/ \/ /  _ \_  __ \  |/ /
 * \  \__(  <_> )  Y Y  (  <_> )   |  \  ___/|  |  \     (  <_> )  | \/    <
 *  \___  >____/|__|_|  /\____/|___|  /\___  >__|   \/\_/ \____/|__|  |__|_ \
 *      \/            \/            \/     \/                              \/
 *                                                Contact: info@comonetwork.com
 *                                                 Website: www.comonetwork.com
 *                                                  Code by: 2380567@gmail.com
 *                                                               by: xwk
 *
 */

namespace App\Http\Controllers\Admin;


use App\Models\Admin;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Mews\Captcha\Facades\Captcha;
use App\Http\Controllers\Controller;
use Pinyin;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Xinax\LaravelGettext\Facades\LaravelGettext;

class LoginController extends Controller {
	public function login(Request $request) {
		$rules = [
			'username' => 'required',
			'password' => 'required',
			'code' => 'required|captcha',
		];
		$message = [
			'code.captcha' => '验证码不正确'
		];
		if($input = Input::all()) {
			$validator = Validator::make($input, $rules, $message);
			if($validator->fails()) {
				return back()->withErrors($validator);
			} else {
				$admin = Admin::where('name', $request->input('username'))->first();
				if(!$admin || !Hash::check($input['password'], $admin->password)) {
					return back()->with('errors', '用户名或密码错误');
				}
				session(['admin' => $admin]);
				return redirect(route('admin.home'));
			}
		} else {
			return view('admin.login');
		}
		/*
		if($request->input()){
			if(!Captcha::check($request->input('code'))){
				return back()->with('msg','验证码错误');
			};
			$admin = Admin::where('name',$request->input('username'))->first();
			if(!$admin || !Hash::check($request->input('password'), $admin->password)){
				return back()->with('msg','用户名或密码错误');
			}
			session(['admin'=>$admin]);
			return redirect(route('admin.home'));
		};
		return view('admin.login');*/
	}

	public function logout() {
		session(['admin' => null]);
		return redirect(route('front.home'));
	}

	function user($username) {
		echo 'hello ' . $username;
	}
}
