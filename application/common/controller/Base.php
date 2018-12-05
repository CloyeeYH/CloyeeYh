<?php
namespace app\common\controller;
use think\Controller;

class Base extends Controller
{
   public function _initialize()
   {
       parent::_initialize();
       $article = model('Article')->where('delete', 1)->count();
       $class = model('Classfiy')->count();
       $this->assign('article', $article);
       $this->assign('class', $class);
       $this->assign('controller', request()->controller());
   }
}
