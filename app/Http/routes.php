<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/** 前台 **/
Route::group(['prefix' => \Illuminate\Support\Facades\Request::segment(1), 'middleware' => ['check_locale']], function() {
	Route::get('/', function() {
		return view('welcome');
	})->name('front.home');

	/** 验证码 **/
	Route::get('/captcha/{config?}.html', '\Mews\Captcha\CaptchaController@getCaptcha');

	/** 后台 **/
//登录
	Route::match(['get', 'post'], '/admincomo', 'Admin\LoginController@login');

	Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['admin.login']], function() {
		//首页
		Route::get('/', 'HomeController@index')->name('admin.home');
		//登出
		Route::get('/logout.html', 'LoginController@logout')->name('admin.logout');
		//修改密码
		Route::any('/user/change_password.html', 'UserController@change_password')->name('admin.user.change_password');
		Route::any('/user/edit/{admin}.html', 'UserController@edit')->name('admin.user.edit');

		//分类添加
		Route::match(['get', 'post'], '/category/create.html', 'CategoryController@create')->name('admin.category.create');
		//分类列表
		Route::get('/category.html', 'CategoryController@showlist')->name('admin.category.showlist');
		Route::post('/category/showlist_pagination.html', 'CategoryController@showlist_pagination')->name('admin.category.showlist_pagination');

		//文章添加
//	Route::match(['get', 'post'], 'article/create', 'articleController@create')->name('admin.article.create');
		//文章列表
//	Route::get('article', 'ArticleController@showlist')->name('admin.article.showlist');
	});

	Route::auth();

	Route::get('/home', 'HomeController@index');
//	Route::get('{user}', 'Admin\LoginController@user');
});