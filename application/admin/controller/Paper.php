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

        // 获取前台传过来的要删除的试卷ID
        $id = trim(input('post.id'));

        // 根据试卷ID删除数据库中对应的试卷信息
        $response = $PaperApi->deletePaper($id);

        // 返回删除数据的结果
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

        // 根据试卷ID获取对应的试题列表信息
        $List = Db::name('paper_question')->where(['paper_id' => $id])->select();

        // 循环所有试题，对所有试题进行类型判断，并对该试题对应的选项和答案进行处理
        foreach ($List as &$v) {
            $v['answer1'] = '';
            if ($v['type'] == 1) { //判断题
                if ($v['answer'] == 1) {
                    $v['answer1'] = '正确';
                } else if ($v['answer'] == 2) {
                    $v['answer1'] = '错误';
                }
                $v['optionsList'] = explode('||', $v['options']);
            }
            if ($v['type'] == 2) { //单选题
                $v['optionsList'] = explode('||', $v['options']);
                $v['answer1'] = $v['optionsList'][$v['answer'] - 1];
            }
            if ($v['type'] == 3) { //多选题
                $v['optionsList'] = explode('||', $v['options']);
                $answerList = explode('||', $v['answer']);
                $v['answerList'] = $answerList;
                foreach ($answerList as $vv) {
                    static $i = 0;
                    $answer1[] = $v['optionsList'][$i];
                    $i++;
                }
                $v['answer1'] = implode(',', $answer1);
            }

            if ($v['type'] == 4) { //填空题
                $v['optionsList'] = explode('||', $v['options']);
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

        // 返回对应控制器模板视图的questions.html
        return $this->fetch('questions');
    }

    /**
     * 编辑试题信息页面
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function editQuestion()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取前台传来的数据
        $data = input('post.');

        // 根据索引ID获取试题信息
        $data = $PaperApi->getPaperQuestion(input('post.id'));

        // 判断试题类型
        if ($data['type'] == 1 || $data['type'] == 2 || $data['type'] == 3) {
            // 根据`||`分割获取的options试题选项为数组赋值给optionsList
            $data['optionsList'] = explode('||', $data['options']);
        }
        if ($data['type'] == 4) {
            // 根据`||`分割获取的answer试题答案为数组赋值给answerList
            $data['answerList'] = explode('||', $data['answer']);
        }

        // 整合返回的数据
        $response = [
            "status" => true,
            "message" => "",
            "data" => $data
        ];

        // 返回数据
        return $response;
    }

    /**
     * 更新试卷-试题的分数
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editQuestionsSave()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取前台要更新的数据据
        $data = input('post.')['data'];

        // 更新数据库中对应的试卷信息
        $response = $PaperApi->updatePaperQuestion($data);

        // 返回更新数据的结果
        return $response;
    }

    /**
     * 删除试卷-试题的分数
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