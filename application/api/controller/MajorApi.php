<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/10
 * Time: 9:08
 */

namespace app\api\controller;

use think\Db;

class MajorApi
{
    /**
     * 获取专业信息（根据ID获取）
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMajor($id)
    {
        $major = Db::name('major')->where('id', $id)->find();

        return $major;
    }

    /**
     * 获取专业信息列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMajorList()
    {
        $list = Db::name('major')->order('order')->select();

        return $list;
    }

    /**
     * 获取专业数量
     * @return int|string
     */
    public function countMajor() {
        $count = Db::name('major')->order('order')->count();

        return $count;
    }

    /**
     * 添加专业信息
     * @param $data
     * @return \think\response\Json
     */
    public function addMajor($data)
    {
        $response = [
            "status" => true,
            "message" => ""
        ];

        if (Db::name('major')->where('name', $data["name"])->count()) {
            $response["status"] = false;
            $response["message"] = "该专业已存在";
        } else {
            Db::name('major')->insert($data);
        }

        return json($response);

    }

    /**
     * 更新专业信息
     * @param $data
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateMajor($data)
    {
        $result = Db::name('major')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        return json($response);
    }

    /**
     * 删除专业
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteMajor($id)
    {

        $result = Db::name('major')->delete($id);
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
     * 获取专业信息列表
     * @param string $where  条件
     * @param string $order  排序
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getList($where='',$order='id asc',$limit=''){
        $list = Db::name('major')->where($where)->order($order)->limit($limit)->select();

        return $list;
    }


}