<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/25
 * Time: 16:37
 */

namespace app\admin\controller;

use app\api\controller\QuestionApi;

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

        // 返回数据
        return $list;
    }

    /**
     * 添加试题信息页面
     * @return mixed
     */
    public function add()
    {
        // 返回模板视图
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
        }

        // 定义question模板变量，用于在模板视图中展示数据
        $this->assign('question', $question);

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

}