<?php
namespace app\index\controller;
use think\Controller;

class Common extends Controller
{
    public function comments($art_id){
        $first = model('Comments')->where(['article_id' => $art_id, 'p_id' => 0])->order('create_time desc')->select()->toArray();
        $comments = 0;
        if (!empty($first)) {
            $comment_id = implode(',', array_map(function($row){
                return $row['id'];
            }, $first));
            $next = model('Comments')->where('p_id', 'in', $comment_id)->order('create_time desc')->select();
            $comments = [];
            foreach ($first as $key => $value) {
                $comments[$key] = [
                    'id' => $value['id'],
                    'content' => json_decode($value['content'], true),
                    'avatar' => $value['avatar'],
                    'name' => $value['name'],
                    'create_time' => $value['create_time']
                ];
                $have = 0;
                foreach ($next as $k => $v) {
                    if ($value['id'] == $v['p_id']) {
                        $comments[$key]['son'][$k] = [
                                    'id' => $v['id'],
                                    'content' => json_decode($v['content'], true),
                                    'avatar' => $v['avatar'],
                                    'name' => $v['name'],
                                    'create_time' => $v['create_time'],
                                ];
                        $have = 1;
                    }
                }
                if (empty($have)) {
                    $comments[$key]['son'] = 0;
                }
            }
        }
        return $comments;
    }
}
