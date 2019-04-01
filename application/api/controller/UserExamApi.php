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
     * 获取考生-考试信息列表(带考试信息)
     * @return \think\response\Json
     */
    public function getUserExamListWithExam()
    {
        $list = Db::query("SELECT T.*, E.title,E.is_check, u.username FROM user_exam T LEFT JOIN exam E ON T.exam_id = E.ID LEFT JOIN user u ON T.user_id = u.id");

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
     * 更新考生-考试信息
     * @param $where
     * @param $data
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateUserExamByWhere($where, $data)
    {
        $result = Db::name('UserExam')->where($where)->update($data);

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
     * @param string $where 条件
     * @param string $order 排序
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getList($where = '', $order = 'id asc', $limit = '')
    {
        $list = Db::name('UserExam')->where($where)->order($order)->limit($limit)->select();

        return $list;
    }

    /**
     * 获取考试信息-单条
     * @param string $where 查询条件
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getData($where = '')
    {
        $UserExam = Db::name('UserExam')->where($where)->find();

        return $UserExam;
    }

    /**
     * 结束审卷
     * @param $examid
     * @param $pass_score
     * @return mixed
     */
    public function checkOver($examid, $pass_score)
    {

//        $result = Db::query("update user_question t set t.final_score = t.score where t.exam_id ='".$examid."' and t.final_score = 0");
        $result = Db::query("UPDATE user_exam t SET t.score = ( SELECT sum(q.final_score) FROM user_question q WHERE q.exam_id = '" . $examid . "' AND q.user_id = t.user_id ), t.pass = ( CASE WHEN t.score > '" . $pass_score . "' THEN 1 ELSE 0 END ),t.status = 5 WHERE t.exam_id = '" . $examid . "'");

        return $result;
    }


}