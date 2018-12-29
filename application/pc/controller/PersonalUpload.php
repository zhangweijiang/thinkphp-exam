<?php
namespace app\pc\controller;
use app\api\controller\UserApi;
use app\api\controller\UserExamApi;

class PersonalUpload extends BaseController
{
    public function index()
    {

        //创建考试api接口的实例
        $UserApi = new UserApi();
        //创建考试-考试api接口的实例
        $UserExamApi = new UserExamApi();
        //通过session获取考生id
        $id = session('user')['id'];
        //获取考生基本信息
        $data = $UserApi->getUser($id);

        //考试场次
        $where['user_id'] = $id;
        //获取考生-考试列表
        $user_exam = $UserExamApi->getList($where);
        if($user_exam){
            $data['exam_count'] = count($user_exam);
        }else{
            $data['exam_count'] = 0;
        }

        //通过场次
        $data['pass_count'] = 0;
        foreach($user_exam as $v){
            if($v['pass']==1){
                $data['pass_count']++;
            }
        }

        //定义data模板变量，传输到模板视图中
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存个人信息
     */
    public function save(){
        //获取考生id
        $id = input('post.id');
        $data['id'] = $id;
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('avatar');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            //上传图片
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            if($info){
                // 成功上传后 获取上传信息;
                // 输出 20171224/42a79759f284b767dfcb2a0197904287.jpg
                $data['avatar'] =  $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                $this->error($info->getError());
            }
        }
        //创建考生api接口的实例
        $UserApi = new UserApi();
        //修改考生头像
        $result = $UserApi->updateUser($data);
        if($result!==FALSE){
            $response = [
                "status" => true,
                "message" => "保存成功"
            ];
        }else{
            $response = [
                "status" => false,
                "message" => "保存失败"
            ];
        }
        return json($response);
    }
}
