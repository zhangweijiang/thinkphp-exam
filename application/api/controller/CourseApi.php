<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/16
 * Time: 16:39
 */

namespace app\api\controller;


use think\Db;

class CourseApi
{
    /**
     * 根据ID获取课程信息
     * @param $id
     * @return mixed
     */
    public function getCourse($id)
    {
        // 编写查询sql
        $sql = "SELECT t.*, b.name majorName, b.id mojorId FROM course t LEFT JOIN major b ON t.major_id = b.id WHERE t.id = ? ";

        // 执行sql语句
        $course = Db::query($sql, [$id]);

        // 返回sql查询的结果
        return $course[0];
    }

    /**
     * 获取课程信息列表
     * @return \think\response\Json
     */
    public function getCourseList()
    {
        // 编写查询sql
        $sql = "SELECT t.*, b.name majorName FROM course t LEFT JOIN major b ON t.major_id = b.id ORDER BY t.order";

        // 执行sql语句
        $list = Db::query($sql);

        // 返回sql查询的结果
        return json($list);
    }

    /**
     * 根据专业获取课程信息列表
     * @param $major_id
     * @return \think\response\Json
     */
    public function getCourseListByMajor($major_id)
    {
        // 编写查询sql
        $sql = "SELECT t.id, t.name FROM course t WHERE t.major_id = ? ORDER BY t.order";

        // 执行sql语句
        $list = Db::query($sql, [$major_id]);

        // 返回sql查询的结果
        return $list;
    }

    /**
     * 统计课程数量
     * @return int|string
     */
    public function countCourse()
    {
        // 统计数据库中课程数量
        $count = Db::name('course')->count();

        // 返回课程数量
        return $count;
    }

    /**
     * 添加课程信息
     * @param $data
     * @return \think\response\Json
     */
    public function addCourse($data)
    {
        // 定义返回结果
        $response = [
            "status" => true,
            "message" => ""
        ];

        // 判断课程是否已经存在于数据库表中
        if (Db::name('course')->where('name', $data["name"])->count()) {
            // 存在，则更新返回结果的状态及消息
            $response["status"] = false;
            $response["message"] = "该课程名称已存在！";
        } else {
            // 不存在，向数据库新增课程信息
            Db::name('course')->insert($data);
        }

        // 返回结果
        return json($response);
    }

    /**
     * 更新课程信息
     * @param $data
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateCourse($data)
    {
        // 执行更新操作
        $result = Db::name('course')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        // 返回结果
        return json($response);
    }

    /**
     * 删除课程信息
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteCourse($id)
    {
        // 删除指定ID的记录
        $result = Db::name('course')->delete($id);
        $response = [
            "status" => true,
            "message" => ""
        ];

        // 如果返回值为0，说明该记录不存在
        if ($result == 0) {
            $response["status"] = false;
            $response["message"] = "该记录已被删除！";
        }

        // 返回删除结果
        return json($response);
    }

    /**
     * 获取课程信息列表
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($where = '', $order = 'id desc', $limit = '')
    {
        // 获取列表数据（where、order、limit具体讲解可查看Thinkphp5完全开发手册）
        $list = Db::name('course')->where($where)->order($order)->limit($limit)->select();

        // 返回数据
        return $list;
    }
}