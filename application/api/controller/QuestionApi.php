<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/12
 * Time: 11:09
 */

namespace app\api\controller;


use think\Db;

class QuestionApi
{
    /**
     * 获取试题信息（根据ID获取）
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getQuestion($id)
    {
        $question = Db::name('question')->where('id', $id)->find();

        return $question;
    }

    /**
     * 获取试题列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getQuestionList() {
        $list = Db::name('question')->select();

        return json($list);
    }

    /**
     * 获取试题数量
     * @return int|string
     */
    public function countQuestion() {
        $count = Db::name('question')->where('status', '1')->count();

        return $count;
    }

    /**
     * 添加试题
     * @param $data
     * @return \think\response\Json
     */
    public function addQuestion($data)
    {
        $response = [
            "status" => true,
            "message" => ""
        ];

        Db::name('question')->insert($data);

        return json($response);

    }

    /**
     * 更新试题信息
     * @param $data
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateQuestion($data)
    {
        Db::name('question')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        return json($response);
    }

    /**
     * 删除试题
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteQuestion($id)
    {

        $result = Db::name('question')->delete($id);
        $response = [
            "status" => true,
            "message" => ""
        ];

        if ($result == 0) {
            $response["status"] = false;
            $response["message"] = "该记录已被删除！";
        }

        return json($response);
    }

    //TODO：待定方法
    public function isExamQuestion($id) {

//        Db::name('question')->alias('A')->join('paper_question B','A.id = B.questionId')->join('exam C','B.paperId = C.paperId')->where('A.id', $id)->find();

    }
}