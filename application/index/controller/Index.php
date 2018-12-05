<?php
namespace app\index\controller;
use app\common\controller\Base;

class Index extends Base
{
    public function index()
    {
        $class_name = '最新文章';
        $class_id = input('class_id');
        $s = input('s');
        if (!empty($class_id)) {
            $class = model('Classfiy')->get($class_id);
            $class_name = $class->name;
        }
        if (!empty($s)) {
            $class_name = $s;
        }
        $res = model('Article')->where('delete', 1)->where(function($query)use($class_id, $s){
            if (!empty($class_id)) {
                $query->where('class_id', $class_id);
            }
            if (!empty($s)) {
                $query->where('title', 'like', '%'.$s.'%');
            }
            // if (!empty($s) && !empty($class_id)) {
            //     $query->where('class_id', $class_id)->where('title', 'like', '%'.$s.'%');
            // }
        })->order('create_time desc')->paginate(10);
        $this->assign('class_name', $class_name);
        $this->assign('list', $res);
        return $this->fetch();
    }
    public function details(){
        $res = model('Article')->get(input('id'));
        $common = new \app\index\controller\Common;
        $comments = $common->comments(input('id'));
        // dump($comments);die();
        $next = model('Article')->order('create_time desc')->where('delete', 1)->where('create_time < '.strtotime($res->create_time))->find();
        if (empty($next)) {
            $next = 0;
        } else {
            $next = $next->toArray();
        }
        $this->assign('next', $next);
        $this->assign('comments', $comments);
        $this->assign('data', $res);
        return $this->fetch();
    }
    public function adds(){
        $data = input();
        unset($data['/index/index/adds_html']);
        if ($data['name'] == '爱写代码的鱼' && $data['email'] != '123@qq.com') {
            return 106;
        }
        if (empty($data['name'])) {
            return 101;
        }
        if (empty($data['email'])) {
            return 102;
        }
        if (empty($data['content'])) {
            return 103;
        }
        $data['content'] = json_encode(['at' => '0', 'text' => $data['content']]);
        // $user = model('Comments')->get(['name' => $data['name'], 'article_id' => $data['article_id']]);
        $user = model('User')->get(['name' => $data['name']]);
        if (!empty($user)) {
            if ($user->email != $data['email']) {
                return 104;
            }
            if ($user->status == 0) {
                return 107;
            }
        }
        if (empty($user)) {
            model('User')->save(['name' => $data['name'], 'email' => $data['email'], 'create_time'=>time()]);
        }
        if (!empty($data['p_id'])) {
            $comment = model('Comments')->get(['article_id' => $data['article_id'], 'id' => $data['p_id']]);
            $data['p_id'] = $comment->id;
            if (!empty($comment->p_id)) {
                $data['p_id'] = $comment->p_id;
                $data['content'] = json_encode(['at' => '@'.$comment->name, 'text' => input('content')]);
            }
        }
        $data['avatar'] = '/avatar/'.rand(1, 126).'.jpg';
        if ($data['name'] == '爱写代码的鱼') {
            $data['avatar'] = '/wp-content/uploads/2016/09/777775B55-4731-A28B-BBD86294D3FC.jpg';
        }
        $you = model('Comments')->get(['article_id' => $data['article_id'], 'name' => $data['name']]);
        if (!empty($you)) {
            $data['avatar'] = $you->avatar;
        }
        $data['create_time'] = time();
        // dump($data);die();
        $res = model('Comments')->save($data);
        if (empty($res)) {
            return 105;
        }
        return 200;
    }
    public function lists(){
        $article = model('Article')->where('delete', 1)->with('comments')->order('create_time desc')->select()->toArray();
        $res = [];
        foreach ($article as $key => $value) {
            $res[date('Y年m月', strtotime($value['create_time']))]['article'][] = [
                'title' => $value['title'],
                'id' => $value['id'],
                'time' => date('d日', strtotime($value['create_time'])),
                'count' => count($value['comments']),
            ];
            $res[date('Y年m月', strtotime($value['create_time']))]['count'] = count($res[date('Y年m月', strtotime($value['create_time']))]['article']);
        }
        $this->assign('data', $res);
        // dump($res);die();
        return $this->fetch();
    }
    public function classfiy(){
        $data = model('Classfiy')->with(['article' => function($query){
            $query->where('delete', 1);
        }])->select();
        foreach ($data as $key => $value) {
            $data[$key]['count'] = count($value['article']);
        }
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function test(){
        // dump(strtotime('2018-9-20 09:55:07'));
        $mail = mail("turbocode@163.com","asdasdasd",'asdadasdasdascontent');
        dump($mail);
    }
    // public function my_dir() {
    //     $dir = ROOT_PATH . 'public' . '/avatar';
    //     // dump($dir);die();
    //     $files = array();
    //     if(@$handle = opendir($dir)) { //注意这里要加一个@，不然会有warning错误提示：）
    //         while(($file = readdir($handle)) !== false) {
    //             if($file != ".." && $file != ".") { //排除根目录；
    //                 if(is_dir($dir."/".$file)) { //如果是子文件夹，就进行递归
    //                     $files[$file] = my_dir($dir."/".$file);
    //                 } else { //不然就将文件的名字存入数组；
    //                     $files[] = $file;
    //                 }

    //             }
    //         }
    //         closedir($handle);
    //     }
    //     // dump(count($files));
    //     for ($i=1; $i <= count($files); $i++) {
    //         rename($dir.'/'.$files[$i],$dir.'/'.$i.'.jpg');
    //     }
    //     dump($files);
    // }
}
