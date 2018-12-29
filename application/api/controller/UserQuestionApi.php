<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/16
 * Time: 16:40
 */

namespace app\api\controller;


use think\Db;

class UserQuestionApi
{
    /**
     * 获取考生-试题信息（根据ID获取）
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserQuestion($id)
    {
        $UserQuestion = Db::name('UserQuestion')->where('id', $id)->find();

        return $UserQuestion;
    }

    /**
     * 获取考生-试题信息列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserQuestionList()
    {
        $list = Db::name('UserQuestion')->select();

        return json($list);
    }

    /**
     * 添加考生-试题信息
     * @param $data
     * @return \think\response\Json
     */
    public function addUserQuestion($data)
    {
        $response = [
            "status" => true,
            "message" => ""
        ];
        Db::name('UserQuestion')->insert($data);

        return json($response);

    }

    /**
     * 更新考生-试题信息
     * @param $data
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateUserQuestion($data)
    {
        $result = Db::name('UserQuestion')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        return json($response);
    }

    /**
     * 删除考生-试题信息
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteUserQuestion($id)
    {

        $result = Db::name('UserQuestion')->delete($id);
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
     * 获取考生-试题信息列表
     * @param string $where  条件
     * @param string $order  排序
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getList($where='',$order='id asc',$limit=''){
        $list = Db::name('UserQuestion')->where($where)->order($order)->limit($limit)->select();

        return $list;
    }

    /**
     * 获取试题信息-单条
     * @param string $where查询条件
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getData($where='')
    {
        $UserQuestion = Db::name('UserQuestion')->where($where)->find();

        return $UserQuestion;
    }




}