<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;


class UserController extends Controller {
	public function change_password(Request $request) {
		if($request->isMethod('POST')) {
			$input = Input::all();
			$validator = Validator::make($input, Admin::getValidationRules(), Admin::getValidationMessages());
			if($validator->fails()) {
				return response()->json(['status' => 'error', 'msg' => $validator->messages()->toArray()]);
			} else {
				$admin = Admin::find(session('admin.id'));
				if(!$admin || !Hash::check($input['password_o'], $admin->password)) {
					return response()->json(['status' => 'error', 'msg' => ['password_o' => ['原密码 错误。']]]);
				} else {
					$admin->password = bcrypt($input['password']);
					$admin->update();
					return response()->json(['status' => 'error', 'msg' => ['password_o' => ['密码 修改成功。']]]);
				}
			}
		} else {
			$validator = JsValidatorFacade::make(Admin::getValidationRules(), Admin::getValidationMessages());
			$admin = Admin::find(session('admin.id'));
			return view('admin.user.change_password')->with([
				'validator' => $validator,
				'admin' => $admin
			]);
		}
	}

	public function edit(Admin $admin) {
		dd($admin);
	}
}
