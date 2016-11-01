<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		view()->share('sitename2', '柯穆网络');

		view()->composer('admin.category.showlist_pagination', function($view){
			$this->showlist_pagination($view);
		});

		view()->composer('admin.widgets.test', function($view) {
			$view->with('widget_name', 'asdfadsf');
		});
	}

	private function showlist_pagination($view) {
		$data = Category::orderBy('order_id')->paginate(1);
		$data->appends(Input::all());
		$data->setPath(route('admin.category.showlist_pagination'));
		$view->with('data_category_pagination', $data);
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
