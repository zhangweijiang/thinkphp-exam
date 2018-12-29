<?php
namespace app\pc\controller;
use app\api\controller\MajorApi;
use app\api\controller\CourseApi;
use app\api\controller\ExamApi;

class Index extends BaseController
{
    public function index()
    {
        //专业信息--取七条
        //创建专业api接口的实例
        $MajorApi = new MajorApi();
        //获取专业列表
        $MajorList = $MajorApi->getList('','order','7');
        //定义模板变量MajorList，传输到模板视图中
        $this->assign('MajorList',$MajorList);

        //课程信息--取七条
        //创建课程api接口的实例
        $CourseApi = new CourseApi();
        $CourseList = array();//课程信息
        foreach($MajorList as $v){
            //获取每个专业对应的课程列表
            $CourseList[] = $CourseApi->getList(['major_id'=>$v['id']]);
        }

        $exam = array();//考试信息
        //创建考试api接口的实例
        $ExamApi = new ExamApi();
        foreach($MajorList as $v){
            //获取考试列表
            $ExamList[] = $ExamApi->getList(['major_id'=>$v['id'],'status'=>['neq',3],'state'=>1], null ,4);
        }

        // 最新、最热推荐
        $newExam = $ExamApi->getList(['status'=>['neq',3],'state'=>1], 'create_time desc' ,4);
        $hotExam = $ExamApi->getList(['status'=>['neq',3],'state'=>1], 'count desc' ,4);

        //定义模板变量ExamList，传输到模板视图中
        $this->assign('ExamList',$ExamList);

        // 定义模板变量List，传输到模板视图中
        $this->assign('newExam',$newExam);
        $this->assign('hotExam',$hotExam);

        //考试信息--根据每个专业作为查询条件
        $this->assign('CourseList',$CourseList);

        // 返回当前控制器对应的视图模板index.html
        return $this->fetch();
    }
}
