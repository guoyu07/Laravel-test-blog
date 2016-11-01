<?php
/**
 * Created by PhpStorm.
 * User: xwk
 * Date: 2016/10/29
 * Time: 15:47
 */
class ComoTool {
	/**
	 * 返回可读性更好的文件尺寸
	 */
	public static function Filesize($bytes, $decimals = 2) {
		$size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
		$factor = floor((strlen($bytes) - 1) / 3);

		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
	}

	/**
	 * 字符串加密
	 * @param $string 需要加密的字符串
	 * @param int $expiry 过期时间(秒)
	 * @param string $cryptkey 加密key
	 * @return string
	 */
	public static function StringEncode($string, $expiry = 0, $cryptkey = '') {
		return self::AuthString($string, 'ENCODE', $expiry, $cryptkey);
	}

	/**
	 * 字符串解密
	 * @param $string 加密后的字符串
	 * @param string $cryptkey 加密key
	 * @return string
	 */
	public static function StringDecode($string, $cryptkey = '') {
		return self::AuthString($string, 'DECODE', 0, $cryptkey);
	}

	/**
	 * @param string $string 原文或者密文
	 * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
	 * @param string $key 密钥
	 * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
	 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
	 *
	 * @example
	 *
	 *  $a = __ComoAuthString('abc', 'ENCODE', 'key');
	 *  $b = __ComoAuthString($a, 'DECODE', 'key');  // $b(abc)
	 *
	 *  $a = __ComoAuthString('abc', 'ENCODE', 'key', 3600);
	 *  $b = __ComoAuthString('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
	 */
	private static function AuthString($string, $operation = 'DECODE', $expiry = 0, $key = '') {
		if($operation == 'DECODE') {
			$string = str_replace(array('-', '_'), array('+', '/'), $string);
		}
		$ckey_length = 4;
		// 随机密钥长度 取值 0-32;
		// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
		// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
		// 当此值为 0 时，则不产生随机密钥

		$key = md5($key ? $key : config('app.key')); //这里可以填写默认key值
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya . md5($keya . $keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return 'ERROR';
			}
		} else {
			$rtn = $keyc . str_replace('=', '', base64_encode($result));
			$rtn = str_replace(array('+', '/'), array('-', '_'), $rtn);
			return $rtn;
		}
	}

	/**
	 * ID加密
	 */
	public static function IDEncode($num = null, $cryptkey = '') {
		if(!is_numeric($num)) {
			return 'ERROR';
		}
		$str = self::AuthID($num, 'ENCODE', $cryptkey);
		return substr(md5($str . $cryptkey), 0, 3) . $str;
	}

	/**
	 * ID解密
	 */
	public static function IDDecode($num, $cryptkey = '') {
		if(empty($num) || is_null($num) || strlen($num) < 3) {
			return 'ERROR';
		}
		$num_md5 = substr($num, 0, 3);
		$num = substr($num, 3);
		if(substr(md5($num . $cryptkey), 0, 3) != $num_md5) {
			return 'ERROR';
		}
		$str = self::AuthID($num, 'DECODE', $cryptkey);
		return $str;
	}

	/**
	 * ID加解密
	 * @param $strtoencrypt
	 * @param string $operation
	 * @param string $key
	 * @return string
	 */
	private static function AuthID($strtoencrypt, $operation = 'DECODE', $key = '') {
		$ralphabet = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$alphabet = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$key = $key ? $key : config('app.key'); //这里可以填写默认key值
		$key = preg_replace('/\W/', '', $key);
		$key = str_replace('_', '', $key);
		for($i = 0; $i < strlen($key); $i++) {
			$cur_pswd_ltr = substr($key, $i, 1);
			$pos_alpha_ary[] = substr(strstr($alphabet, $cur_pswd_ltr), 0, strlen($ralphabet));
		}
		$i = 0;
		$n = 0;
		$nn = strlen($key);
		$c = strlen($strtoencrypt);
		$authed_string = '';
		while($i < $c) {
			if($operation == 'DECODE') {
				$authed_string .= substr($ralphabet, strpos($pos_alpha_ary[$n], substr($strtoencrypt, $i, 1)), 1);
			} else {
				$authed_string .= substr($pos_alpha_ary[$n], strpos($ralphabet, substr($strtoencrypt, $i, 1)), 1);
			}
			$n++;
			if($n == $nn)
				$n = 0;
			$i++;
		}
		return $authed_string;
	}

	/**
	 * 将大数转换为较短的字符串
	 * @param $in
	 * @param bool $to_num
	 * @param bool $pad_up
	 * @param null $pass_key
	 * @return number|string
	 */
	public function AlphaID($in, $to_num = false, $pad_up = false, $pass_key = null) {
		$out = '';
		$index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$base = strlen($index);
		if($pass_key !== null) {
			// Although this function's purpose is to just make the
			// ID short - and not so much secure,
			// with this patch by Simon Franz (http://blog.snaky.org/)
			// you can optionally supply a password to make it harder
			// to calculate the corresponding numeric ID
			for($n = 0; $n < strlen($index); $n++) {
				$i[] = substr($index, $n, 1);
			}
			$pass_hash = hash('sha256', $pass_key);
			$pass_hash = (strlen($pass_hash) < strlen($index) ? hash('sha512', $pass_key) : $pass_hash);
			for($n = 0; $n < strlen($index); $n++) {
				$p[] = substr($pass_hash, $n, 1);
			}
			array_multisort($p, SORT_DESC, $i);
			$index = implode($i);
		}
		if($to_num) {
			// Digital number  <<--  alphabet letter code
			$len = strlen($in) - 1;
			for($t = $len; $t >= 0; $t--) {
				$bcp = bcpow($base, $len - $t);
				$out = $out + strpos($index, substr($in, $t, 1)) * $bcp;
			}
			if(is_numeric($pad_up)) {
				$pad_up--;

				if($pad_up > 0) {
					$out -= pow($base, $pad_up);
				}
			}
		} else {
			// Digital number  -->>  alphabet letter code
			if(is_numeric($pad_up)) {
				$pad_up--;

				if($pad_up > 0) {
					$in += pow($base, $pad_up);
				}
			}
			for($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
				$bcp = bcpow($base, $t);
				$a = floor($in / $bcp) % $base;
				$out = $out . substr($index, $a, 1);
				$in = $in - ($a * $bcp);
			}
		}
		return $out;
	}
}