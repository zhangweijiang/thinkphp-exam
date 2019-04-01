<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/16
 * Time: 16:40
 */

namespace app\api\controller;


use think\Db;

class PaperQuestionApi
{
    /**
     * 获取试卷-试题信息（根据ID获取）
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getPaperQuestion($id)
    {
        $paper = Db::name('paper_question')->where('id', $id)->find();

        return $paper;
    }


    /**
     * 获取试卷-试题信息（根据试卷ID获取）
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getPaperQuestionByPaperId($id)
    {
        $paper = Db::name('paper_question')->where('paper_id', $id)->order('order')->select();

        return $paper;
    }

    /**
     * 获取试卷-试题信息列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getPaperQuestionList()
    {
        $list = Db::name('paper_question')->select();

        return json($list);
    }

    /**
     * 添加试卷-试题信息
     * @param $data
     * @param $paperId
     * @param $order
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addPaperQuestion($data, $paperId, $order)
    {

        // 新增试题
        Db::name('Question')->insert($data);

        // 获取新增试题的ID
        $questionId = Db::name('Question')->getLastInsID();

        // 增加试卷ID、试题ID以及试题排序
        $data["paper_id"] = $paperId;
        $data["question_id"] = $questionId;
        $data["order"] = $order;

        // 将新增的试题添加到试卷-试题表中
        Db::name('paper_question')->insert($data);

        // 返回新增加到试卷-试题表的试题ID
        $paperQuestionId = Db::name('paper_question')->getLastInsID();

        // 根据新增试题ID获取该试题全部信息
        $question = Db::name('paper_question')->where('id', $paperQuestionId)->find();

        // 整合返回数据
        $response = [
            "status" => true,
            "result" => $question,
            "message" => ""
        ];

        // 返回JSON对象数据
        return json($response);
    }

    /**
     * 更新试卷-试题信息
     * @param $data
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updatePaperQuestion($data)
    {
        $result = Db::name('paper_question')->update($data);

        $response = [
            "status" => true,
            "result" => $result,
            "message" => ""
        ];

        return json($response);
    }

    /**
     * 删除试卷-试题信息
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deletePaperQuestion($id)
    {
        // 删除指定ID的试题
        $result = Db::name('paper_question')->delete($id);

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

    /**
     * 根据试卷ID删除试卷试题
     * @param $paper_id
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deletePaperQuestionByPaper($paper_id) {
        $where['paper_id'] = $paper_id;
        // 删除指定ID的试题
        $result = Db::name('paper_question')->where($where)->delete();

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

    /**
     * 获取试卷-试题信息列表
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($where = '', $order = 'id asc', $limit = '')
    {
        $list = Db::name('paper_question')->where($where)->order($order)->limit($limit)->select();

        return $list;
    }


}