<?php

namespace app\common\model;
use think\Model;

class Article extends Model
{
    // protected $autoWriteTimestamp = true;
    // protected $createTime = 'create_time';
    public function comments()
    {
        return $this->hasMany('Comments', 'article_id', 'id');
    }
}
