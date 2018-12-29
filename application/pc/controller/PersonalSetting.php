<?php
namespace app\pc\controller;
use app\api\controller\UserApi;
use app\api\controller\UserExamApi;

class PersonalSetting extends BaseController
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
        // 返回当前控制器对应的视图模板index.html
        return $this->fetch();
    }

    /**
     * 保存个人信息
     */
    public function save(){
        //获取前台post的表单数据
        $data = input('post.');
        //获取修改时间
        $data['update_time'] = date('Y-m-d H:i:s');
        //创建考生api接口的实例
        $UserApi = new UserApi();
        //修改考生个人信息
        $int = $UserApi->updateUser($data);
        if($int){
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
