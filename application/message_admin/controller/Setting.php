<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 其他设置 ]
namespace app\message_admin\controller;

class Setting extends Base
{

    protected $member_model;
    protected $quick_message_model;
    protected $message_board;
    protected $share_model;
    protected $setting_model;
    public function __construct()
    {
        parent::__construct();
        $this->member_model = model('Member');
        $this->quick_message_model = model('QuickMessage');
        $this->message_board = model('MessageBoard');
        $this->share_model = model('Share');
        $this->setting_model = model('Setting');
    }

    /**
     * 通用设置
     *
     * @return void
     */
    public function setting(){
        if(request()->isPost()){
            $param = input('post.');
            foreach ($param as $key => $value) {
                if($value != ''){
                    //是否确定为设置项
                    $is_setting = $this->setting_model->get_one_data(['key'=>$key]);
                    if($is_setting){
                        $this->setting_model->update_data(['value'=>$value],['key'=>$key]);
                    }
                }
            }
            $this->success('修改设置成功');

        }
        return view();
    }
}