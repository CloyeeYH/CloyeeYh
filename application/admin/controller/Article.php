<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Session;

class Article extends Base
{
    public function index(){
        $res = model('Article')->where('delete', 1)->with('comments')->order('create_time desc')->field('image,id,create_time,title')->paginate(5)->each(function($item, $key){
            $item['count'] = count($item['comments']);
            return $item;
        });
        // dump($res);die();
        $this->assign('data', $res);
        return $this->fetch();
    }
    public function adds(){
        // die('123123');
        if ($this->request->isAjax()) {
            $data = input();
            unset($data['/admin/article/adds_html']);
            // dump($data);die();
            if (empty($data['class_id'])) {
                return 101;
            }
            if (empty($data['title'])) {
                return 102;
            }
            if (empty($data['spec'])) {
                return 103;
            }
            if (empty($data['image'])) {
                return 104;
            }
            $data['create_time'] = time();
            unset($data['art_id']);
            $res = model('Article')->save($data);
            if (empty($res)) {
                return 105;
            }
            return 200;
        }
        $class = model('Classfiy')->where('delete', 1)->select();
        $this->assign('class', $class);
        return $this->fetch();
    }
    public function edit(){
        if ($this->request->isAjax()) {
            $data = input();
            unset($data['/admin/article/edit_html']);
            if (empty($data['class_id'])) {
                return 101;
            }
            if (empty($data['title'])) {
                return 102;
            }
            if (empty($data['spec'])) {
                return 103;
            }
            if (empty($data['image'])) {
                return 104;
            }
            // $data['create_time'] = time();
            unset($data['art_id']);
            $res = model('Article')->save($data, ['id' => input('art_id')]);
            if (empty($res)) {
                return 105;
            }
            return 200;
        }
        $res = model('Article')->get(input('id'));
        $class = model('Classfiy')->where('delete', 1)->select();
        $this->assign('class', $class);
        $this->assign('data', $res);
        return $this->fetch();
    }
    public function del(){
        $res = model('Article')->save(['delete'=>0], ['id'=>input('id')]);
        if (empty($res)) {
            return 101;
        }
        return 200;
    }
}
