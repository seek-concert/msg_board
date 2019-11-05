<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 后台通用工具 ]
namespace app\message_admin\controller;

class Tool extends Base
{

    protected $member_model;
    protected $quick_message_model;
    protected $message_board;
    protected $share_model;
    public function __construct()
    {
        parent::__construct();
        $this->member_model = model('Member');
        $this->quick_message_model = model('QuickMessage');
        $this->message_board = model('MessageBoard');
        $this->share_model = model('Share');
    }

    /**
     * 后台通用上传
     *
     * @return void
     */
    public function upload(){
       // 获取表单上传文件
        $files = request()->file('');
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'static'. DS .'upload','backgroundmusic.mp3');
            if($info){
                $this->success($name = $file->getInfo()['name'],'', 'static/upload/'.$info->getFilename());
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }    
        }
    }
}