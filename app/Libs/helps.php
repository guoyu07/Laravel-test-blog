<?php
/**
 * Created by PhpStorm.
 * User: xwk
 * Date: 2016/11/1
 * Time: 12:48
 */
/**
 * 重载url方法,增加语言
 *
 * @param  string $path
 * @param  mixed $parameters
 * @param  bool $secure
 * @return Illuminate\Contracts\Routing\UrlGenerator|string
 */
function url($path = null, $parameters = [], $secure = null) {
	if(is_null($path)) {
		return app(\Illuminate\Contracts\Routing\UrlGenerator::class);
	}
	if(config('app.locales')){
		if(!starts_with($path,'/')){
			$path = '/'.$path;
		}
		$path = '/'.\Illuminate\Support\Facades\App::getLocale().$path;
	}
	$path = $path .'.html';
	return app(\Illuminate\Contracts\Routing\UrlGenerator::class)->to($path, $parameters, $secure);
}