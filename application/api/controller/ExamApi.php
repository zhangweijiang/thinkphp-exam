<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/16
 * Time: 16:40
 */

namespace app\api\controller;


use think\Db;

class ExamApi
{
    /**
     * 获取考试信息（根据ID获取）
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getExam($id)
    {
        $exam = Db::name('exam')->where('id', $id)->find();

        return $exam;
    }

    /**
     * 获取考试信息（根据$where获取）
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData($where='')
    {
        $exam = Db::name('exam')->where($where)->find();

        return $exam;
    }

    /**
     * 获取考试信息列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getExamList()
    {
        $list = Db::name('exam')->select();

        return json($list);
    }

    /**
     * 添加考试信息
     * @param $data
     * @return \think\response\Json
     */
    public function addExam($data)
    {
        $response = [
            "status" => true,
            "message" => ""
        ];
        Db::name('exam')->insert($data);

        return json($response);

    }

    /**
     * 更新考试信息
     * @param $data
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateExam($data)
    {
        $result = Db::name('exam')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        return json($response);
    }

    /**
     * 删除考试信息
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteExam($id)
    {
        $result = Db::name('exam')->delete($id);
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
     * 获取考试信息列表
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($where='',$order='id asc',$limit=''){

        $list = Db::name('exam')->where($where)->order($order)->limit($limit)->select();

        return $list;
    }

    /**
     * 报名考试
     * @param $data
     * @return array|\think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function signUp($data){
        Db::startTrans();//开启事务
        //考试表的考生人数加一
        $flag1 = Db::name('exam')->where(['id'=>$data['id']])->setInc('count',1);
        if($flag1===FALSE){
            $response = [
                "status" => false,
                "message" => "考试表报考人数修改失败"
            ];
            Db::rollback();//事务回滚
            return $response;
        }

        //考生-考试表(user-exam)插入数据
        $user_exam['exam_id'] = $data['id'];
        $user_exam['user_id'] = $data['user_id'];
        $user_exam['username'] = $data['username'];
        $ExamInfo = Db::name('exam')->where(['id'=>$data['id']])->find();
//        $user_exam['exam_time'] = $ExamInfo['start_date'];//考试开始时间
        $user_exam['status'] = 1;//状态（未报名则无记录，1-已报名 2-考试中 3-考试完成 4-缺考）
        $user_exam['create_time'] = date('Y-m-d H:i:s');
        $flag2 = Db::name('UserExam')->insert($user_exam);
        if(!$flag2){
            $response = [
                "status" => false,
                "message" => "考生-考试表数据插入失败"
            ];
            Db::rollback();//事务回滚
            return $response;
        }
        /*
        //考生-试题表(user_question)插入数据
        $QuestionInfo = Db::name('paper_question')->where(['paper_id'=>$ExamInfo['paper_id']])->select();
        foreach($QuestionInfo as $v){
            $user_question = array();
            $user_question['user_id'] = $data['user_id'];
            $user_question['exam_id'] = $data['id'];
            $user_question['title'] = $v['title'];
            $user_question['type'] = $v['type'];
            $user_question['options'] = $v['options'];
            $user_question['answer'] = $v['answer'];
            $user_question['analysis'] = $v['analysis'];
            $user_question['keyword'] = $v['keyword'];
            $user_question['keyword_imp'] = $v['keyword_imp'];
            $user_question['create_time'] = date('Y-m-d H:i:s');
            $flag3 = Db::name('user_question')->insert($user_question);
            if(!$flag3){
                $response = [
                    "status" => false,
                    "message" => "考生-试题表数据插入失败"
                ];
                Db::rollback();//事务回滚
                return $response;
            }
        }*/

        $response = [
            "status" => true,
            "message" => "报名成功"
        ];
        Db::commit();//事务提交

        return json($response);


    }

}