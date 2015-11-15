<?php
class payfor_tenpay{
	public $status, $name;
	private $url;
	public function __construct(){
		if ($info = db::one('payfor_interface', '*', "ename='tenpay'")) {
			$this->name   = $info['name'];
			$this->status = $info['status'];
		} else {
			$this->status = false;
			$this->name   = '未知充值方式';
		}
		$this->url    = common::getUrl('/member/topup/');
	}
	public function payfor($id, $money, $datas){
		global $web_name;
		if ($this->status) {
			db::update('topup', array(
				'remark1' => $datas['tenpay'],
				'remark2' => $datas['tenpayId']
			), "id='$id'");
			$args = array(
				'url'   => $this->url,
				'type'  => 'get',
				'datas' => array(
					'tab' => 'tenpay'
				)
			);
			common::setMsg('财付通转账充值申请已经提交成功，正式转账后联系'.$web_name.'充值专员QQ'.$kefu_qq.'为您入账');
			return $args;
		}
		return false;
	}
}
?>