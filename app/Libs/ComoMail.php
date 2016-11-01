<?php

/**
 * Created by PhpStorm.
 * User: xwk
 * Date: 2016/10/29
 * Time: 23:28
 *
 *  $mail = new \ComoMail();
	$rtn = $mail->send([
	'to' => 'iamxwk@qq.com',
	'subject' => '测试邮件标题',
	'content' => '测试邮件内容',
	]);
	dd($rtn);
 */
class ComoMail {
	private $from = '';
	private $fromName = '';
	private $replyTo = '';
	private $Mandrill;

	function __construct() {
		$this->Mandrill = new Mandrill('Je4xw67TtCba_Pp3ZuFSqw');
		$this->from = 'newsletter@a-c.cn';
		$this->replyTo = $this->from;
		$this->fromName = 'AC Test';
	}

	function send($options = array()) {
		if(empty($options['to'])) {
			die('Email error missing to');
		}

		if(is_null($this->Mandrill)) {
			die('Email error missing Mandrill');
		}

		$fromName = !empty($options['fromName']) ? $options['fromName'] : $this->fromName;
		$replyTo = !empty($options['replyTo']) ? $options['replyTo'] : $this->replyTo;

		$message = array(
			'html' => $options['content'],
			'subject' => $options['subject'],
			'from_email' => $this->from,
			'from_name' => $fromName,
			'inline_css' => true,
			'to' => array()
		);
		if(is_array($options['to'])) {
			foreach($options['to'] as $v) {
				$message['to'][] = array(
					'email' => $v
				);
			}
		} else {
			$message['to'][] = array(
				'email' => $options['to']
			);
		}
		$message['headers'] = array(
			'Reply-To' => $replyTo
		);

		$async = true;
//		$result = $this->Mandrill->messages->send($message, $async);

		return $result;
	}
}