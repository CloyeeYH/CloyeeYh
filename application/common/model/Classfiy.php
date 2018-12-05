<?php

namespace app\common\model;
use think\Model;

class Classfiy extends Model
{
    protected $createTime = 'create_time';
    public function article()
    {
        return $this->hasMany('Article', 'class_id', 'id');
    }
}
