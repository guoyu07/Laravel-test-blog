<?php
/**
 * Created by PhpStorm.
 * User: xwk
 * Date: 2016/11/1
 * Time: 12:48
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Xinax\LaravelGettext\Facades\LaravelGettext;

class CheckLanguage {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		$locale = Request::segment(1);
		//语言不在允许的数组里
		if(!array_key_exists($locale, config('app.locales'))) {
			$locale = '';//初始化语言
			//检查是否有cookie里的语言
			if(array_get($_COOKIE,'front_locale')) {//在cookie里
				if(array_key_exists(array_get($_COOKIE,'front_locale'), config('app.locales'))) {
					//cookie语言在允许的数组里
					$locale = array_get($_COOKIE,'front_locale');
				} else {
					//cookie语言不在允许的数组里
					setcookie('front_locale', $locale, time() - 3600, '/', null, false, true);
				}
			}
			if(empty($locale)) {
				//检查浏览器语言
				preg_match('/^([a-z\-]+)/i', strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']), $matches);
				if(strpos($matches[1], 'zh') === 0) {//chinese 这里设置为zh的才显示中文,其他都显示默认语言
					$locale = 'zh-cn';
				}
				setcookie('front_locale', $locale, time() + (3600 * 24 * 365), '/', null, false, true);
			}
			if(empty($locale)) {//在cookie和浏览器里都没有获得语言,则显示默认语言
				$locale = config('app.locale');
			}
			return redirect($locale . '/' . $request->path());
		} else {
			//在语言数组里,看是否和cookie里相等
			if($locale != array_get($_COOKIE,'front_locale')) {
				setcookie('front_locale', $locale, time() + (3600 * 24 * 365), '/', null, false, true);
			}
		}
		App::setLocale($locale);
		if($request->segments(1) == 'admin'){
			//后台
		}else{
			LaravelGettext::setLocale($locale);
		}
		return $next($request);
	}
}
