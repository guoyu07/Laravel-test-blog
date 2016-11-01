<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CategoryController extends Controller {
	public function showlist() {
		return view('admin.category.showlist');
	}

	public function showlist_pagination() {
		return view('admin.category.showlist_pagination');
	}
}
