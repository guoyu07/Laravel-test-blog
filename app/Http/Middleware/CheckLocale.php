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

class CheckLocale {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if($request->get('admin_locale')) {
			//后台切换界面语言
			session(['admin_locale' => $request->get('admin_locale')]);
			$_url = $request->getPathInfo();
			$_queryArr = $request->except('admin_locale');
			if(!empty($_queryArr)) {
				$_queryTmp = array();
				foreach($_queryArr as $k => $v) {
					$_queryTmp[] = $k . '=' . $v;
				}
				$_url .= '?' . implode('&', $_queryTmp);
			}
			return redirect($_url);
		}
		$locale = Request::segment(1);
		//语言不在允许的数组里
		if(config('app.locales') && !array_key_exists($locale, config('app.locales'))) {
			$locale = '';//初始化语言
			//检查是否有cookie里的语言
			if(array_get($_COOKIE, 'front_locale')) {//在cookie里
				if(array_key_exists(array_get($_COOKIE, 'front_locale'), config('app.locales'))) {
					//cookie语言在允许的数组里
					$locale = array_get($_COOKIE, 'front_locale');
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
			if($locale != array_get($_COOKIE, 'front_locale')) {
				setcookie('front_locale', $locale, time() + (3600 * 24 * 365), '/', null, false, true);
			}
		}

		/** 设置内容语言*/
		App::setLocale($locale);

		/** 设置界面语言*/
		if(Request::segment(2) == 'admin') {
			//后台
			if(session('admin_locale')) {
				$locale = session('admin_locale');
			} else {
				session(['admin_locale' => $locale]);
			}
		}
		$this->set_laravel_gettext($locale);

		return $next($request);
	}

	/**
	 * 设置界面语言
	 * @param $locale
	 */
	private function set_laravel_gettext($locale) {
		$sess_key = config('laravel-gettext.session-identifier') . '-locale';
		if(session($sess_key) !== $locale) {
			session([$sess_key => $locale]);
			LaravelGettext::setLocale($locale);
		}
	}
}
