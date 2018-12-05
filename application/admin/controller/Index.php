<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Session;

class Index extends Base
{
    public function index(){
        return $this->fetch();
    }
    public function chanl(){
        Session::set('user', '');
        $this->redirect('Login/index');
    }
}
