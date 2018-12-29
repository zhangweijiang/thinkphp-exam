<?php
namespace app\admin\controller;

use app\api\controller\CourseApi;
use app\api\controller\MajorApi;
use app\api\controller\QuestionApi;
use app\api\controller\UserApi;

class Index extends BaseController
{
    public function index()
    {
        // 创建考生、专业、课程和试题API接口的实例
        $userApi = new UserApi();
        $majorApi = new MajorApi();
        $courseApi = new CourseApi();
        $questionApi = new QuestionApi();

        // 获取对应考生、专业、课程和试题的数量
        $indexData["userCount"] = $userApi->countUser();
        $indexData["majorCount"] = $majorApi->countMajor();
        $indexData["courseCount"] = $courseApi->countCourse();
        $indexData["questionCount"] = $questionApi->countQuestion();

        // 定义indexData的模板变量，用于页面展示各部分数据的数量
        $this->assign('indexData', $indexData);

        // 返回模板视图
        return $this->fetch();
    }
}
