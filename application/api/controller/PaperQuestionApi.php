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
        $paper = Db::name('PaperQuestion')->where('id', $id)->find();

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
        $paper = Db::name('PaperQuestion')->where('paper_id', $id)->find();

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
        $list = Db::name('PaperQuestion')->select();

        return json($list);
    }

    /**
     * 添加试卷-试题信息
     * @param $data
     * @return \think\response\Json
     */
    public function addPaperQuestion($data)
    {
        $response = [
            "status" => true,
            "message" => ""
        ];
        Db::name('PaperQuestion')->insert($data);

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
        $result = Db::name('PaperQuestion')->update($data);

        $response = [
            "status" => true,
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

        $result = Db::name('PaperQuestion')->delete($id);
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
     * @param string $where  条件
     * @param string $order  排序
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getList($where='',$order='id asc',$limit=''){
        $list = Db::name('PaperQuestion')->where($where)->order($order)->limit($limit)->select();

        return $list;
    }

    
}