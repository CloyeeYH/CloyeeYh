<?php
namespace app\admin\controller;
use think\Session;
use think\Controller;

class Login extends Controller
{
    public function index(){
        return $this->fetch();
    }
    public function login(){
        $data['password'] = md5(md5(input('password')));
        $data['name'] = input('name');
        $res = model('Admin')->get($data);
        if (empty($res)) {
            return 101;
        }
        Session::set('user', $data['name']);
        return 200;
    }
}
