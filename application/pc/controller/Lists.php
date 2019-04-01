<?php

namespace app\pc\controller;

use app\api\controller\MajorApi;
use app\api\controller\CourseApi;
use app\api\controller\ExamApi;

class Lists extends BaseController
{
    public function index()
    {
        // 专业信息
        // 创建专业api接口的实例
        $MajorApi = new MajorApi();
        // 获取专业列表
        $MajorList = $MajorApi->getList();
        // 定义MajorList模板变量，传输到模板视图中
        $this->assign('MajorList', $MajorList);

        // 考试信息
        // 创建考试api接口的实例
        $ExamApi = new ExamApi();
        $where = array();
        $major_id = input('get.major_id'); // 专业id
        $course_id = input('get.course_id'); // 课程id
        $exam_state = input('get.exam_state'); // 考试状态
        if ($major_id) {
            $where['major_id'] = $major_id;
        }
        if ($course_id) {
            $where['course_id'] = $course_id;
        }
        if ($exam_state) {
            $where['status'] = [['eq', $exam_state], ['neq', 3], ['neq', 4]];
        } else {
            $where['status'] = [['neq', 3], ['neq', 4]];
        }
        $where['state'] = 1;

        // 获取考试列表
        $List = $ExamApi->getList($where);
        //定义List模板变量，传输到模板视图中
        $this->assign('List', $List);

        // 课程信息
        $map = array();
        if ($major_id) {
            $map['major_id'] = $major_id;
        }

        // 创建课程api接口的实例
        $CourseApi = new CourseApi();

        // 获取课程列表
        $CourseList = $CourseApi->getList($map, 'id asc');

        // 定义CourseList模板变量，传输到模板视图中
        $this->assign('CourseList', $CourseList);

        // 返回当前控制器对应的视图模板index.html
        return $this->fetch();
    }
}
