<?php
namespace app\index\controller;
use app\common\controller\Base;

class Infomation extends Base
{
    public function details()
    {
        $common = new \app\index\controller\Common;
        $comments = $common->comments(0);
        $this->assign('comments', $comments);
        return $this->fetch();
    }
}
