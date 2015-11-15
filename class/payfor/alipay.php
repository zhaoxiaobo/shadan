<?php
(!defined('IN_JB') || IN_JB !== true) && exit('error');
class payfor_alipay{
	private $id, $key, $url;
	public $status, $name;
	public function __construct(){
		$payfor_config = cache::get_array('payfor_config', true);
		if ($info = db::one('payfor_interface', '*', "ename='alipay'")) {
			$this->username   = $info['username'];
			$this->id     = $info['userid'];
			$this->key    = $info['userpwd'];
			$this->status = $info['status'];
		} else {
			$this->status = false;
			$this->name   = '未知充值方式';
		}
		$this->url  = 'https://shenghuo.alipay.com/send/payment/fill.htm';
	}
	public function payfor($id, $alipayMoney){
		global $weburl, $sys_debug;	
		
		if ($this->status) {
			//if ($sys_debug) $money = 0.01;
			$v_mid       = $this->id;
			$v_oid       = $id;
			$v_amount    = $alipayMoney;							
			$args = array(
				'url'   => $this->url,
				'type'  => 'post',				
				'username'  => $this->username,
				'v_oid'  => $id,
				'alipayMoney'  => $alipayMoney,					
				'datas' => array(
					'v_mid'       => $v_mid,
					'v_oid'       => $v_oid,
					'v_url'       => $v_url
				)
			);
			return $args;
		}
		return false;
	}
}
?>