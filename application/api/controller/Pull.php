<?php

namespace app\api\controller;
use think\Controller;

/**
 * 自动更新git
 */
class Pull extends Controller
{
    public function index(){
        $res = shell_exec('cd /var/www/blog && sudo git pull origin master');
        var_dump($res);
    }
}
