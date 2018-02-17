<?php

if (!class_exists('msPaymentInterface')) {
	require_once dirname(dirname(dirname(__FILE__))) . '/model/minishop2/mspaymenthandler.class.php';
}

class Sberbank extends msPaymentHandler implements msPaymentInterface {
	public $config;
	public $modx;

	function __construct(xPDOObject $object, $config = array()) {
		$this->modx = & $object->xpdo;

		$siteUrl = $this->modx->getOption('site_url');
		$assetsUrl = $this->modx->getOption('minishop2.assets_url', $config, $this->modx->getOption('assets_url').'components/minishop2/');
		$paymentUrl = $siteUrl . substr($assetsUrl, 1) . 'payment/sberbank.php';

		$this->config = array_merge(array(
			'paymentUrl' => $paymentUrl
			,'checkoutUrl' => $this->modx->getOption('ms2_payment_sbrbnk_url', null, 'https://3dsec.sberbank.ru/payment/rest/', true)
			,'login' => $this->modx->getOption('ms2_payment_sbrbnk_login')
			,'pass' => $this->modx->getOption('ms2_payment_sbrbnk_pass')
			//,'pass2' => $this->modx->getOption('ms2_payment_sbrbnk_pass2')
			,'currency' => $this->modx->getOption('ms2_payment_sbrbnk_currency', null, 'RUB', true)
			,'culture' => $this->modx->getOption('ms2_payment_sbrbnk_culture', null, 'ru', true)
			,'json_response' => false
		), $config);
	}


	/* @inheritdoc} */
	public function send(msOrder $order) {
		$link = $this->getPaymentLink($order);

		return $this->success('', array('redirect' => $link));
	}


	public function getPaymentLink(msOrder $order) {
		$id = $order->get('id');
		$sum = number_format($order->get('cost'), 2, '.', '') * 100;
		$request = array(
			'userName' => $this->config['login']
			,'password' => $this->config['pass']
			,'orderNumber' => $order->get('num')
			,'amount' => $sum
			,'currency' => $this->config['currency']
			,'returnUrl' => $this->config['paymentUrl']
			,'failUrl' => $this->config['paymentUrl']
			,'description' => 'Payment #'.$id
			,'language' => $this->config['culture']
		);

		$link = $this->config['checkoutUrl'] . 'register.do' .'?'. http_build_query($request);
		if (!isset($_SESSION['paymentLink-'.$id])) {
		    $respond = $this->modx->fromJSON(file_get_contents($link));
		    $_SESSION['paymentLink-'.$id] = $respond['formUrl'];
		}
		if (isset($_SESSION['paymentLink-'.$id]) && $_SESSION['paymentLink-'.$id]) {
		    $link = $_SESSION['paymentLink-'.$id];
		}
		return $link;
	}


	/* @inheritdoc} */
	public function receive(msOrder $order, $params = array()) {
		$request = array(
			'userName' => $this->config['login']
			,'password' => $this->config['pass']
			,'orderId' => $params['orderId']
			,'language' => $this->config['culture']
		);

		$link = $this->config['checkoutUrl'] . 'getOrderStatus.do' .'?'. http_build_query($request);
        $respond = file_get_contents($link);
        $respond = $this->modx->fromJSON($respond);
		if ($respond['OrderStatus'] == 2) {
			/* @var miniShop2 $miniShop2 */
			$miniShop2 = $this->modx->getService('miniShop2');
			@$this->modx->context->key = 'mgr';
        	if ($order = $this->modx->getObject('msOrder', array('num' => $respond['OrderNumber']))) {
        	    $respond['OrderNumber'] = $order->get('id');
        	}
			$miniShop2->changeOrderStatus($respond['OrderNumber'], 2);
			$_REQUEST['action'] = 'success';
			$_REQUEST['OrderNumber'] = $respond['OrderNumber'];
			//exit('OK');
		}
		else {
		    $_REQUEST['action'] = 'failure';
			//$this->paymentError('Err: wrong signature.', $params);
		}
		return;
	}


	public function paymentError($text, $request = array()) {
		$this->modx->log(modX::LOG_LEVEL_ERROR,'[miniShop2:Sberbank] ' . $text . ', request: '.print_r($request,1));
		header("HTTP/1.0 400 Bad Request");

		die('ERR: ' . $text);
	}
}
