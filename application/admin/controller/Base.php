<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;

class Base extends Controller
{
    public function _initialize()
   {
       parent::_initialize();
       $res = Session::get('user');
       if (empty($res)) {
           $this->redirect('Login/index');
       }
       $this->assign('controller', request()->controller());
       $this->assign('action', request()->action());
   }
}
