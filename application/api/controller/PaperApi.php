<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/12
 * Time: 11:11
 */

namespace app\api\controller;

use think\Db;

class PaperApi
{
    /**
     * 获取试卷信息（根据ID获取）
     * @param $id string 试卷ID
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getPaper($id)
    {
        $paper = Db::name('paper')->where('id', $id)->find();

        return $paper;
    }

    /**
     * 获取试卷列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getPaperList() {
        $list = Db::name('paper')->select();

        return $list;
    }

    /**
     * 添加试卷
     * @param $data array 试卷信息
     * @return \think\response\Json
     */
    public function addPaper($data)
    {
        $response = [
            "status" => true,
            "message" => ""
        ];

        Db::name('paper')->insert($data);

        return json($response);

    }

    /**
     * 更新试卷信息
     * @param $data array 试卷信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updatePaper($data)
    {
        Db::name('paper')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        return json($response);
    }

    /**
     * 删除试卷
     * @param $id string 试卷ID
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deletePaper($id)
    {
        $result = Db::name('paper')->delete($id);
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
     * 获取试卷-试题信息（根据ID获取）
     * @param $id string 索引ID
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getPaperQuestion($id)
    {
        $paper = Db::name('PaperQuestion')->where('id', $id)->find();

        return $paper;
    }

    /**
     * 获取试卷试题（根据试卷ID）
     * @param $paperId string 试卷ID
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getQuestionListByPaper($paperId) {
        $list = Db::name('PaperQuestion')->where('paper_id', $paperId)->select();
        $response = [
            "status" => true,
            "message" => "",
        ];
        return json($list);
    }

    /**
     * 删除试卷试题（根据试题ID）
     * @param $questionId string 试题ID
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deletePaperQuestion($questionId) {
        $result = Db::name('PaperQuestion')->delete($questionId);
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
     * 更新试卷信息
     * @param $data array 试卷信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updatePaperQuestion($data)
    {
        Db::name('PaperQuestion')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        return json($response);
    }

}