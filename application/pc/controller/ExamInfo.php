<?php
namespace app\pc\controller;
use app\api\controller\ExamApi;
use app\api\controller\UserApi;
use app\api\controller\UserExamApi;
class ExamInfo extends BaseController
{
    /**
     * 考试报名页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        //获取考试id
        $id = input('get.id');
        //创建试卷api接口的实例
        $ExamApi = new ExamApi();
        //通过试卷id获取试卷基本信息
        $data = $ExamApi->getExam($id);
        //定义data的模板变量，传输到模板视图中
        $this->assign('data',$data);

        //创建考生-试卷api接口的实例
        $UserExamApi = new UserExamApi();
        $where = [
            'user_id' => session('user')['id'],
            'exam_id' => $id
        ];
        //获取考生-试卷的基本信息
        $result = $UserExamApi->getData($where);
        if($result){
            $status = 1;//已报名
        }else{
            $status = 0;//未报名
        }


        //定义status的模板变量,传输到模板视图中
        $this->assign('status',$status);
        // 返回当前控制器对应的视图模板index.html
        return $this->fetch();
    }

    /**
     * 考生报名
     * @return array|\think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function sign_up(){
        //创建试卷pi接口的实例
        $ExamApi = new ExamApi();
        //获取考试id
        $id = input('post.id');

        //判断用户是否登录,未登录则用session存储待跳转页面,已登录继续执行以下代码
        if ($this->isLogin() === false) {//未登录
            session('url',url('pc/exam_info/index')."?id=$id");

            $response = [
                "status" => false,
                "message" => "需要登录后才可报名考试~",
                "url" => "/pc/login/index.html"
            ];

            return $response;
        }

        $data['id'] = $id;//考试id
        $data['user_id'] = session('user')['id'];  //考生id
        $data['username'] =  session('user')['username']; //考生姓名
        //调用$ExamApi实例的signUp生成考试对应的考试-试卷表的信息，并且考试表的报名考生人数加一
        $response = $ExamApi->signUp($data);

        return $response;
    }

}
