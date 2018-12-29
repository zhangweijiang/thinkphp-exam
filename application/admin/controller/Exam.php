<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/24
 * Time: 16:38
 */

namespace app\admin\controller;

use app\api\controller\ExamApi;
use app\api\controller\CourseApi;
use app\api\controller\MajorApi;
use app\api\controller\PaperApi;

class Exam extends BaseController
{
    /**
     * 考试信息列表页面
     * @return mixed
     */
    public function lists()
    {
        // 返回模板视图lists.html
        return $this->fetch();
    }

    /**
     * 考试信息列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function examList() {
        // 创建考试API接口的实例
        $ExamApi = new ExamApi();

        // 获取所有考试信息的数据
        $list = $ExamApi->getExamList();

        // 返回数据
        return $list;
    }

    /**
     * 添加考试信息页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add()
    {
        // 分别创建专业、课程和试卷API接口的实例
        $majorApi = new MajorApi();
        $courseApi = new CourseApi();
        $PaperApi = new PaperApi();

        // 获取所有的试卷信息，获取所有专业信息，根据列表第一条专业的ID获取课程信息
        $papers = $PaperApi->getPaperList();
        $majors = $majorApi->getMajorList();
        $course = $courseApi->getCourseListByMajor($majors[0]['id']);

        // 将获取的数据分别定义majors、courses、papers的模板变量
        $this->assign('majors', $majors);
        $this->assign('courses', $course);
        $this->assign('papers', $papers);

        // 返回视图模板
        return $this->fetch();
    }

    /**
     * 添加考试信息
     * @return mixed
     */
    public function addExam()
    {
        // 分别创建考试API接口的实例
        $ExamApi = new ExamApi();

        // 整理所有前台传过来的数据
        $exam["title"] = trim(input('post.title'));
        $exam["time"] = trim(input('post.time'));
        $exam["course_id"] = trim(input('post.course_id'));
        $exam["course_name"] = trim(input('post.course_name'));
        $exam["major_id"] = trim(input('post.major_id'));
        $exam["major_name"] = trim(input('post.major_name'));
        $exam["paper_id"] = trim(input('post.paper_id'));
        $exam["rule"] = trim(input('post.rule'));
        $exam["focus"] = trim(input('post.focus'));
        $exam["start_date"] = trim(input('post.start_date'));
        $exam["max_end_date"] = trim(input('post.max_end_date'));
        $exam["is_analysis"] = trim(input('post.is_analysis'));
        $exam["is_check"] = trim(input('post.is_check'));
        $exam["state"] = trim(input('post.state'));

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('avatar');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            if ($info) {
                // 成功上传后 获取上传信息;
                // 输出 20171224/42a79759f284b767dfcb2a0197904287.jpg
                $exam['img'] = "/upload/".$info->getSaveName();
            } else {
                // 上传失败获取错误信息
                $this->error($info->getError());
            }
        }

        // 将整合好的考试数据，向数据库新增考试信息记录
        $response = $ExamApi->addExam($exam);

        // 返回新增数据的结果
        return $response;
    }

    /**
     * 考试信息更新页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        // 创建考试、课程、试卷和专业API接口的实例
        $ExamApi = new ExamApi();
        $courseApi = new CourseApi();
        $PaperApi = new PaperApi();
        $majorApi = new MajorApi();

        // 获取前台传过来的考试ID
        $id = trim(input('post.id'));

        // 根据考试ID获取考试信息，获取所有试卷列表和专业列表，再根据考试ID获取该考试对应的专业信息
        $exam = $ExamApi->getExam($id);
        $papers = $PaperApi->getPaperList();
        $majors = $majorApi->getMajorList();
        $course = $courseApi->getCourseListByMajor($exam['major_id']);

        // 定义majors、courses、exam和papers模板变量，传输到模板视图中
        $this->assign('majors', $majors);
        $this->assign('courses', $course);
        $this->assign('exam', $exam);
        $this->assign('papers', $papers);

        // 返回当前控制器对应的视图模板add.html
        return $this->fetch('add');
    }

    /**
     * 更新考试信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editExam()
    {
        // 创建考试API接口的实例
        $ExamApi = new ExamApi();

        // 获取前台传过来的更新数据，并整合
        $exam["id"] = trim(input('post.id'));
        $exam["title"] = trim(input('post.title'));
        $exam["time"] = trim(input('post.time'));
        $exam["course_id"] = trim(input('post.course_id'));
        $exam["course_name"] = trim(input('post.course_name'));
        $exam["major_id"] = trim(input('post.major_id'));
        $exam["major_name"] = trim(input('post.major_name'));
        $exam["paper_id"] = trim(input('post.paper_id'));
        $exam["rule"] = trim(input('post.rule'));
        $exam["focus"] = trim(input('post.focus'));
        $exam["start_date"] = trim(input('post.start_date'));
        $exam["max_end_date"] = trim(input('post.max_end_date'));
        $exam["is_analysis"] = trim(input('post.is_analysis'));
        $exam["is_check"] = trim(input('post.is_check'));
        $exam["state"] = trim(input('post.state'));

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('avatar');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            if ($info) {
                // 成功上传后 获取上传信息;
                // 输出 20171224/42a79759f284b767dfcb2a0197904287.jpg
                $exam['img'] = "/upload/".$info->getSaveName();
            } else {
                // 上传失败获取错误信息
                $this->error($info->getError());
            }
        }

        // 将要更新的考试数据更新到数据库中
        $response = $ExamApi->updateExam($exam);

        // 返回更新数据的结果
        return $response;
    }

    /**
     * 删除考试信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete()
    {
        // 创建考试API接口的实例
        $ExamApi = new ExamApi();

        // 获取前台要删除的考试信息的ID
        $id = trim(input('post.id'));

        // 删除数据库中对应ID的考试信息
        $response = $ExamApi->deleteExam($id);

        // 返回删除数据的结果
        return $response;
    }

    /**
     * 根据专业获取课程信息列表
     * 作用：该部分用于做专业、课程两个下拉菜单的联动效果
     * @return \think\response\Json
     */
    public function getCourseListByMajor() {
        // 创建课程API接口的实例
        $courseApi = new CourseApi();

        // 获取前台传过来的专业ID
        $major_id = trim(input('post.major_id'));

        // 根据专业ID获取课程信息列表
        $response = $courseApi->getCourseListByMajor($major_id);

        // 返回专业ID对应的课程信息列表
        return json($response);
    }

    /**
     * 修改考试的可用状态
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function switchState() {
        // 创建考试API接口的实例
        $ExamApi = new ExamApi();

        // 获取前台传来的ID和状态，并整合
        $data["id"] = trim(input('post.id'));
        $data["state"] = trim(input('post.state'));

        // 向数据库更新考试的状态
        $response = $ExamApi->updateExam($data);

        // 返回更新数据的结果
        return $response;
    }
}