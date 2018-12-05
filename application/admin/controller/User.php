<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Session;

class User extends Base
{
    public function index(){
        $res = model('User')->paginate(10);
        // dump($res);die();
        $this->assign('data', $res);
        return $this->fetch();
    }
    public function modify(){
        $status = 0;
        if (input('type') == 1) {
            $status = 1;
        }
        $res = model('User')->save(['status'=>$status], ['id' => input('id')]);
        if (empty($res)) {
            return 101;
        }
        return 200;
    }
}
