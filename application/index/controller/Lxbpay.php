<?php
/**
 * 订单支付
 */
namespace app\index\controller;

use think\Loader;

class Lxbpay extends Auth
{
    /*=============【订单支付】==================*/
    public function paying(){
        ini_set('date.timezone','Asia/Shanghai');
        Loader::import('WxPay.lib.WxPay#Config');
        Loader::import('WxPay.lib.WxPay#Api');
        Loader::import('WxPay.lib.WxPay#JsApiPay');

        /* +++++++++[获取配置]+++++++++ */
        $setting_info = model('Setting')->get_setting();
        $new_set_arr = [];
        if(!empty($setting_info)&&is_array($setting_info)){
            foreach ($setting_info as $k=>$v){
                $new_set_arr[$v['key']] = $v['value'];
            }
        }

        /* ++++++++++订单基本信息++++++++++ */
        $pay_datas = [];
        $pay_datas['open_id'] = $this->user_info['open_id'];
        $pay_datas['member_id'] = $this->user_info['id'];
        $pay_datas['number'] = date('Ymd',time()).$this->user_info['id'].rand(00000000,99999999);
        $pay_datas['num'] = $new_set_arr['pay'];
        $pay_datas['status'] ='-1';
        //支付详情
        $pay_info = model('Pay')->insert_pay($pay_datas);

        if(!$pay_info){
            return $this->error('支付ID错误');
        }
        $shoping_name = '留言板商品购买';

        /* ++++++++++发起支付下单++++++++++ */
        /* ①、获取用户openid */
        $tools = new \JsApiPay();
        $openId = $this->user_info['id'];
        $orderno = $pay_datas['number'];
        $domain=request()->domain();
        /* ②、统一下单 */
        $notify_url=$domain."/index/wxpaynotify/index";

        $input = new \WxPayUnifiedOrder();
        $input->SetBody($shoping_name); /* 商品名称 */
        $input->SetOut_trade_no($orderno); /* 订单号 */
        $input->SetTotal_fee($pay_datas['num']*100); /* 支付金额,单位:分 */
        //$input->SetTotal_fee(1); /* test测试  支付金额,单位:分 */
        $input->SetNotify_url($notify_url); /* 支付回调验证地址 */
        $input->SetTrade_type("JSAPI"); /* 支付类型 */
        $input->SetOpenid($openId); /* 用户openID */
        $input->SetProfitSharing('Y'); /* 是否分账 */
        $order = \WxPayApi::unifiedOrder($input); /* 统一下单 */
        $jsApiParameters = $tools->GetJsApiParameters($order);
        //模板数据
        $datas = [];
        $datas['jsApiParameters'] = $jsApiParameters;
        $datas['shoping_name'] = $shoping_name;
        $datas['number'] = $pay_datas['number'];
        $datas['number_time'] = date('Y-m-d H:i:s');
        $datas['money'] = $pay_datas['pay_money'];
        $datas['jumpurl'] = $domain;
        return view('',$datas);
    }


    

}