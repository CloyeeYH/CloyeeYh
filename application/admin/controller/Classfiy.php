<?php
namespace app\admin\controller;
use think\Session;
use think\Controller;

class Classfiy extends Controller
{
    public function index(){
        $data = model('Classfiy')->where('delete', 1)->paginate(10);
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function adds(){
        if ($this->request->isAjax()) {
            $name = input('name');
            if (empty($name)) {
                return 101;
            }
            $res = model('Classfiy')->save(['name' => $name, 'create_time' => time()]);
            if (empty($res)) {
                return 102;
            }
            return 200;
        }
        return $this->fetch();
    }
    public function edit(){
        $data = model('Classfiy')->get(input('id'));
        if ($this->request->isAjax()) {
            $name = input('name');
            if (empty($name)) {
                return 101;
            }
            $res = model('Classfiy')->save(['name' => $name], ['id' => $data['id']]);
            if (empty($res)) {
                return 102;
            }
            return 200;
        }
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function del(){
        $res = model('Classfiy')->save(['delete'=>0], ['id'=>input('id')]);
        if (empty($res)) {
            return 101;
        }
        return 200;
    }
}
