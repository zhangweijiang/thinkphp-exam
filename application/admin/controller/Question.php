<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/25
 * Time: 16:37
 */

namespace app\admin\controller;

use app\api\controller\CourseApi;
use app\api\controller\MajorApi;
use app\api\controller\QuestionApi;
use think\Collection;

class Question extends BaseController
{
    /**
     * 试题信息列表页面
     * @return mixed
     */
    public function lists()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 试题信息列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function questionList()
    {
        // 创建试题API接口的实例
        $QuestionApi = new QuestionApi();

        // 获取数据库中所有试题信息
        $list = $QuestionApi->getQuestionList();
        $courseApi = new CourseApi();
        $majorApi = new MajorApi();
        $list = $list->getData();
        foreach($list as &$val){
            if(!empty($val['major_id'])){
                $val['major'] = $majorApi->getMajor($val['major_id'])['name'];
            }else{
                $val['major'] = '无';
            }
            if(!empty($val['course_id'])){
                $val['course'] = $courseApi->getCourse($val['course_id'])['name'];
            }else{
                $val['course'] = '无';
            }
        }
        // 返回数据
        return json($list);
    }

    /**
     * 专业/课程
     * @return mixed
     */
    public function major_course(){
        // 分别创建专业、课程和试卷API接口的实例
        $majorApi = new MajorApi();
        $courseApi = new CourseApi();

        // 获取所有的试卷信息，获取所有专业信息，根据列表第一条专业的ID获取课程信息
        $majors = $majorApi->getMajorList();
        $course = $courseApi->getCourseListByMajor($majors[0]['id']);

        // 将获取的数据分别定义majors、courses、papers的模板变量
        $this->assign('majors', $majors);
        $this->assign('courses', $course);
    }

    /**
     * 添加试题信息页面
     * @return mixed
     */
    public function add()
    {
        $this->major_course();
        return $this->fetch();
    }

    /**
     * 添加试题信息
     * @return mixed
     */
    public function addQuestion()
    {
        // 创建试题API接口的实例
        $QuestionApi = new QuestionApi();

        // 获取前台传来的所有数据，并整合
        $question["name"] = trim(input('post.name'));
        $question["title"] = trim(input('post.title'));
        $question["options"] = trim(input('post.options'));
        $question["answer"] = trim(input('post.answer'));
        $question["keyword"] = trim(input('post.keyword'));
        $question["keyword_imp"] = trim(input('post.keyword_imp'));
        $question["score"] = trim(input('post.score'));
        $question["order"] = trim(input('post.order'));
        $question["type"] = trim(input('post.type'));
        $question["analysis"] = trim(input('post.analysis'));
        $question["major_id"] = trim(input('post.major_id'));
        $question["course_id"] = trim(input('post.course_id'));

        // 向数据库新增试题信息
        $response = $QuestionApi->addQuestion($question);

        // 返回新增数据的结果
        return $response;
    }

    /**
     * 试题信息更新页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        // 创建试题API接口的实例
        $QuestionApi = new QuestionApi();

        // 获取要编辑的试题ID
        $id = trim(input('post.id'));

        // 根据试题ID获取对应的试题信息
        $question = $QuestionApi->getQuestion($id);

        // 定义question变量的answerString属性为空字符串
        $question['answerString'] = "";

        if ($question['type'] == 2) {//如果当前试题的类型为单选题
            // 根据`||`字符串来将options分割为数组赋值给optionsList
            $question['optionsList'] = explode('||', $question['options']);
        } else if ($question['type'] == 3) { //如果当前试题的类型为多选题
            // 根据`||`字符串来将options(选项)分割为数组赋值给optionsList
            $question['optionsList'] = explode('||', $question['options']);

            // 将answer(答案)中`||`字符串替换为`,`赋值给answerString
            $question['answerString'] = str_replace('||', ',', $question['answer']);
        } else if ($question['type'] == 4) { // 如果当前试题的类型为填空题

            // 将answer(答案)中`||`字符串替换为`,`赋值给answerString
            $question['answerString'] = explode('||',  $question['answer']);
        }

        // 定义question模板变量，用于在模板视图中展示数据
        $this->assign('question', $question);

        // 获取专业和课程信息
        $courseApi = new CourseApi();
        $majorApi = new MajorApi();
        $majors = $majorApi->getMajorList();
        $course = $courseApi->getCourseListByMajor($question['major_id']);

        // 定义majors、courses、exam和papers模板变量，传输到模板视图中
        $this->assign('majors', $majors);
        $this->assign('courses', $course);

        // 返回当前控制器对应的模板视图add.html
        return $this->fetch('add');
    }

    /**
     * 更新试题信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editQuestion()
    {
        // 创建试题API接口的实例
        $QuestionApi = new QuestionApi();

        // 获取前台传过来的数据并整合
        $question["id"] = trim(input('post.id'));
        $question["name"] = trim(input('post.name'));
        $question["title"] = trim(input('post.title'));
        $question["options"] = trim(input('post.options'));
        $question["answer"] = trim(input('post.answer'));
        $question["keyword"] = trim(input('post.keyword'));
        $question["keyword_imp"] = trim(input('post.keyword_imp'));
        $question["score"] = trim(input('post.score'));
        $question["order"] = trim(input('post.order'));
        $question["type"] = trim(input('post.type'));
        $question["analysis"] = trim(input('post.analysis'));
        $question["course_id"] = trim(input('post.course_id'));
        $question["major_id"] = trim(input('post.major_id'));

        // 更新数据库中对应的试题信息
        $response = $QuestionApi->updateQuestion($question);

        // 返回更新数据的结果
        return $response;
    }

    /**
     * 删除试题信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete()
    {
        // 创建试题API接口的实例
        $QuestionApi = new QuestionApi();

        // 获取要删除的试题ID
        $id = trim(input('post.id'));

        // 根据试题ID删除对应的试题信息
        $response = $QuestionApi->deleteQuestion($id);

        // 返回删除数据的结果
        return $response;
    }

    function saveImage()
    {

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');

        $response = [
            'status'  => true,
            'result'  => "",
            'message' => "文件上传成功",
        ];

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            if ($info) {
                // 成功上传后 获取上传信息;
                // 输出 20171224/42a79759f284b767dfcb2a0197904287.jpg
                $response['result'] = "/upload/" . $info->getSaveName();
            } else {
                // 上传失败获取错误信息
                $response['status'] = false;
                $response['message'] = "文件信息错误";
            }
        } else {
            $response['status'] = false;
            $response['message'] = "请选择文件！";
        }

        return json($response);
    }

}