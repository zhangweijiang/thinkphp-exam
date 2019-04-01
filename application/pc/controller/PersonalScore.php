<?php
namespace app\pc\controller;
use app\api\controller\PaperApi;
use app\api\controller\UserApi;
use app\api\controller\UserExamApi;
use app\api\controller\ExamApi;
use app\api\controller\UserQuestionApi;

class PersonalScore extends BaseController
{
    public function index()
    {
        //创建考试api接口的实例
        $UserApi = new UserApi();
        //创建考试-考试api接口的实例
        $UserExamApi = new UserExamApi();
        //通过session获取考生id
        $id = session('user')['id'];
        //获取考生基本信息
        $data = $UserApi->getUser($id);

        //考试场次
        $where['user_id'] = $id;
        $user_exam = $UserExamApi->getList($where);
        if($user_exam){
            $data['exam_count'] = count($user_exam);
        }else{
            $data['exam_count'] = 0;
        }

        //通过场次
        $data['pass_count'] = 0;
        foreach($user_exam as $v){
            if($v['pass'] == 1 && $v["status"] == 5){
                $data['pass_count']++;
            }
        }

        //所有考试
        $where['user_id'] = $id;
//        $where['status'] = 5;//5表示考试已完成
        //获取考生-考试列表
        $UserExamList = $UserExamApi->getList($where);
        //创建考试api接口的实例
        $ExamApi = new ExamApi();
        foreach($UserExamList as &$vv){
            $vv['exam'] = $ExamApi->getExam($vv['exam_id']);
        }
        //定义UserExamList模板变量，传输到模板视图中
        $this->assign('UserExamList',$UserExamList);

        //定义data模板变量，传输到模板视图中
        $this->assign('data',$data);
        // 返回当前控制器对应的视图模板index.html
        return $this->fetch();
    }

    /**
     * 保存个人信息
     */
    public function save(){
        $data = input('post.');
        $data['password'] = sha256($data['password']);
        $UserApi = new UserApi();
        $result = $UserApi->updateUser($data);
        if($result!==FALSE){
            $response = [
                "status" => true,
                "message" => "保存成功"
            ];
        }else{
            $response = [
                "status" => false,
                "message" => "保存失败"
            ];
        }
        return json($response);
    }

    public function analysis() {

        $where["user_id"] = session("user")["id"];
        $where["exam_id"] = trim(input('get.examId'));

        $ExamApi = new ExamApi();

        $exam = $ExamApi->getExam($where["exam_id"]);

        $UserQuestionApi = new UserQuestionApi();
        $userQuestions = $UserQuestionApi->getListByWhere($where);

        $letter = ["A", "B", "C", "D"];

        // 循环所有试题，对所有试题进行类型判断，并对该试题对应的选项和答案进行处理
        foreach ($userQuestions as &$v) {
            $v['answer1'] = '';
            $v['userAnswer'] = '';

            if ($v['type'] == 1) { //判断题
                $v['answer1'] = $letter[$v['answer'] - 1];
                $v['userAnswer'] = $letter[$v['user_question_answer'] - 1];
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
            }
            if ($v['type'] == 2) { //单选题
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
                $v['answer1'] = $letter[$v['answer'] - 1];
                $v['userAnswer'] = $letter[$v['user_question_answer'] - 1];
            }
            if ($v['type'] == 3) { //多选题
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
                $answerList = explode('||', $v['answer']);
                $v['answerList'] = $answerList;
                $userAnswerList = explode('||', $v["user_question_answer"]);
                $answer1 = [];
                $userAnswer = [];
                foreach ($answerList as $item) {
                    array_push($answer1, $letter[$item - 1]);
                }
                foreach ($userAnswerList as $item) {
                    array_push($userAnswer, $letter[$item - 1]);
                }

                $v['answer1'] = implode('', $answer1);
                $v['userAnswer'] = implode('', $userAnswer);
            }

            if ($v['type'] == 4) { //填空题
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
                $answerList = explode('||', $v['answer']);
                $v['answerList'] = $answerList;
                $v['answer1'] = str_replace('||', ',', $v['answer']);
                $v['userAnswer'] = str_replace('||', ',', $v['user_question_answer']);
            }

            if ($v['type'] == 5) { //简答题
                $v['answer1'] = $v['answer'];
                $v['userAnswer'] = $v['user_question_answer'];
            }
        }

        // 定义List模板变量，用于模板视图展示对应数据
        $this->assign('List', $userQuestions);
        $this->assign('examId', $where["exam_id"]);
        $this->assign('userId', $where["user_id"]);
        $this->assign('letter', $letter);
        $this->assign('exam', $exam);

        return $this->fetch();
    }
}
