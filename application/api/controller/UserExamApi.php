<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/16
 * Time: 16:40
 */

namespace app\api\controller;


use think\Db;

class UserExamApi
{
    /**
     * 获取考生-考试信息（根据ID获取）
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserExam($id)
    {
        $UserExam = Db::name('UserExam')->where('id', $id)->find();

        return $UserExam;
    }

    /**
     * 获取考生-考试信息列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserExamList()
    {
        $list = Db::name('UserExam')->select();

        return json($list);
    }

    /**
     * 添加考生-考试信息
     * @param $data
     * @return \think\response\Json
     */
    public function addUserExam($data)
    {
        $response = [
            "status" => true,
            "message" => ""
        ];
        Db::name('UserExam')->insert($data);

        return json($response);

    }

    /**
     * 更新考生-考试信息
     * @param $data
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateUserExam($data)
    {
        $result = Db::name('UserExam')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        return json($response);
    }

    /**
     * 删除考生-考试信息
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteUserExam($id)
    {

        $result = Db::name('UserExam')->delete($id);
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
     * 获取考生-考试信息列表
     * @param string $where  条件
     * @param string $order  排序
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getList($where='',$order='id asc',$limit=''){
        $list = Db::name('UserExam')->where($where)->order($order)->limit($limit)->select();

        return $list;
    }

    /**
     * 获取考试信息-单条
     * @param string $where查询条件
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getData($where='')
    {
        $UserExam = Db::name('UserExam')->where($where)->find();

        return $UserExam;
    }




}