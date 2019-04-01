<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/25
 * Time: 16:37
 */

namespace app\admin\controller;

use app\api\controller\PaperApi;
use app\api\controller\PaperQuestionApi;
use think\Db;

class Paper extends BaseController
{
    /**
     * 试卷信息列表页面
     * @return mixed
     */
    public function lists()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 试卷信息列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function paperList()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取所有试卷信息数据
        $list = $PaperApi->getPaperList();

        // 返回数据
        return json($list);
    }

    /**
     * 添加试卷信息页面
     * @return mixed
     */
    public function add()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 添加试卷信息
     * @return mixed
     */
    public function addPaper()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取前台传过来的数据
        $paper["name"] = trim(input('post.name'));
        $paper["pass_score"] = trim(input('post.pass_score'));
        $paper["status"] = trim(input('post.status'));

        // 向数据库中新增数据
        $response = $PaperApi->addPaper($paper);

        // 返回新增数据的结果
        return $response;
    }

    /**
     * 试卷信息更新页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取前台传过来的试卷ID
        $id = trim(input('post.id'));

        // 根据试卷ID获取对应的试卷信息
        $paper = $PaperApi->getPaper($id);

        // 定义paper模板变量存储试卷信息
        $this->assign('paper', $paper);

        // 返回当前控制器对应模板视图add.html
        return $this->fetch('add');
    }

    /**
     * 更新试卷信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editPaper()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取前台传过来的所有数据
        $paper["id"] = trim(input('post.id'));
        $paper["name"] = trim(input('post.name'));
        $paper["pass_score"] = trim(input('post.pass_score'));
        $paper["status"] = trim(input('post.status'));

        // 更新数据库中对应的试卷信息
        $response = $PaperApi->updatePaper($paper);

        // 返回更新数据的结果
        return $response;
    }


    /**
     * 删除试卷信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();
        $PaperQuestionApi = new PaperQuestionApi();

        // 获取前台传过来的要删除的试卷ID
        $id = trim(input('post.id'));

        // 根据试卷ID删除数据库中对应的试卷信息
        $response = $PaperApi->deletePaper($id);
        $response = $PaperQuestionApi->deletePaperQuestionByPaper($id);

        // 返回删除数据的结果
        return $response;
    }

    /**
     * 添加试卷-试题页面
     * @return mixed
     */
    public function addQuestion()
    {
        $question["order"] = trim(input('post.order'));
        $question["paper_id"] = trim(input('post.paper_id'));

        $this->assign("question", $question);
        

        return $this->fetch();
    }

    /**
     * 添加试卷-试题信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addPaperQuestion()
    {
        // 创建试题API接口的实例
        $PaperQuestionApi = new PaperQuestionApi();

        // 获取前台传来的所有数据，并整合
        $question["name"] = trim(input('post.name'));
        $question["title"] = trim(input('post.title'));
        $question["options"] = trim(input('post.options'));
        $question["answer"] = trim(input('post.answer'));
        $question["keyword"] = trim(input('post.keyword'));
        $question["keyword_imp"] = trim(input('post.keyword_imp'));
        $question["score"] = trim(input('post.score'));
        $question["type"] = trim(input('post.type'));
        $question["analysis"] = trim(input('post.analysis'));
        $order = trim(input('post.order'));
        $paperId = trim(input('post.paper_id'));

        // 向数据库新增试题信息
        $response = $PaperQuestionApi->addPaperQuestion($question, $paperId, $order);

        // 返回新增数据的结果
        return $response;
    }

    /**
     * 更新试卷-试题页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function editPaperQuestion()
    {

        $id = trim(input('post.id'));

        $PaperQuestionApi = new PaperQuestionApi();

        $question = $PaperQuestionApi->getPaperQuestion($id);

        // 定义question变量的answerString属性为空字符串
        $question['answerString'] = "";

        if ($question['type'] == 2) { //如果当前试题的类型为单选题
            // 根据`||`字符串来将options分割为数组赋值给optionsList
            $question['optionsList'] = explode('||', $question['options']);

        } else if ($question['type'] == 3) { //如果当前试题的类型为多选题
            // 根据`||`字符串来将options(选项)分割为数组赋值给optionsList
            $question['optionsList'] = explode('||', $question['options']);

            // 将answer(答案)中`||`字符串替换为`,`赋值给answerString
            $question['answerString'] = str_replace('||', ',', $question['answer']);

        } else if ($question['type'] == 4) { // 如果当前试题的类型为填空题

            // 将answer(答案)中`||`字符串替换为`,`赋值给answerString
            $question['answerString'] = explode('||', $question['answer']);
        }

        $this->assign("question", $question);

        return $this->fetch("addQuestion");
    }

    /**
     * 更新试卷-试题信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updatePaperQuestion()
    {
        $PaperQuestionApi = new PaperQuestionApi();

        // 获取前台传过来的数据并整合
        $question["id"] = trim(input('post.id'));
        $question["name"] = trim(input('post.name'));
        $question["title"] = trim(input('post.title'));
        $question["options"] = trim(input('post.options'));
        $question["answer"] = trim(input('post.answer'));
        $question["keyword"] = trim(input('post.keyword'));
        $question["keyword_imp"] = trim(input('post.keyword_imp'));
        $question["score"] = trim(input('post.score'));
        $question["type"] = trim(input('post.type'));
        $question["analysis"] = trim(input('post.analysis'));

        // 更新数据库中对应的试题信息
        $response = $PaperQuestionApi->updatePaperQuestion($question);

        // 返回更新数据的结果
        return $response;
    }

    /**
     * 试卷-试题信息更新页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function editQuestions()
    {
        // 获取前台传过来的试卷ID
        $id = input('post.id');

        $PaperQuestionApi = new PaperQuestionApi();

        $List = $PaperQuestionApi->getPaperQuestionByPaperId($id);

        // 根据试卷ID获取对应的试题列表信息
//        $List = Db::name('paper_question')->where(['paper_id' => $id])->select();

        $letter = ["A", "B", "C", "D"];

        // 循环所有试题，对所有试题进行类型判断，并对该试题对应的选项和答案进行处理
        foreach ($List as &$v) {
            $v['answer1'] = '';

            if ($v['type'] == 1) { //判断题
                $v['answer1'] = $letter[$v['answer'] - 1];
//                if ($v['answer'] == 1) {
//                    $v['answer1'] = '正确';
//                } else if ($v['answer'] == 2) {
//                    $v['answer1'] = '错误';
//                }
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
            }
            if ($v['type'] == 2) { //单选题
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
                $v['answer1'] = $letter[$v['answer'] - 1];
//                $v['answer1'] = $optionList[$v['answer'] - 1];
            }
            if ($v['type'] == 3) { //多选题
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
                $answerList = explode('||', $v['answer']);
                $v['answerList'] = $answerList;
                $answer1 = [];
                foreach ($answerList as $item) {
                    array_push($answer1, $letter[$item - 1]); //$optionList[$item - 1]
                }

                $v['answer1'] = implode('', $answer1); //$answer1; //
            }

            if ($v['type'] == 4) { //填空题
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
                $answerList = explode('||', $v['answer']);
                $v['answerList'] = $answerList;
                $v['answer1'] = str_replace('||', ',', $v['answer']);
            }

            if ($v['type'] == 5) { //简答题
                $v['answer1'] = $v['answer'];
            }
        }

        // 定义List模板变量，用于模板视图展示对应数据
        $this->assign('List', $List);
        $this->assign('paperId', $id);
        $this->assign('letter', $letter);

        // 返回对应控制器模板视图的questions.html
        return $this->fetch('questions');
    }


    /**
     * 保存试卷详细信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function saveQuestions()
    {
        $question = input('post.')['formData'];

        $PaperQuestionApi = new PaperQuestionApi();
        $PaperApi = new PaperApi();

        $paper["id"] = $question["id"];
        $paper["score"] = $question["totalScore"];

        // 根据试卷ID获取对应的试卷信息
        $paperObj = $PaperApi->getPaper($paper["id"]);

        if($paperObj["pass_score"] > $paper["score"]) {
            $response = json(["status" => false, "message" => "试卷总分不得低于及格分！"]);
        }else {
            foreach ($question["questions"] as $item) {
                $PaperQuestionApi->updatePaperQuestion($item);
            }

            $response = $PaperApi->updatePaper($paper);
        }

        return $response;
    }


    /**
     * 删除试卷-试题
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteQuestions()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取前台要删除的试题ID
        $id = input('post.id');

        // 删除数据库中对应的试题ID
        $response = $PaperApi->deletePaperQuestion($id);

        // 返回删除数据的结果
        return $response;
    }

}