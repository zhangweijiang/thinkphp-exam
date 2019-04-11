<?php


namespace app\admin\controller;


use app\api\controller\ExamApi;
use app\api\controller\PaperApi;
use app\api\controller\UserExamApi;
use app\api\controller\UserQuestionApi;

class Achievement extends BaseController
{

    public function lists()
    {

        return $this->fetch();
    }

    /**
     * 考生-考试信息列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function examList()
    {
        // 创建考试API接口的实例
        $UserExamApi = new UserExamApi();

        // 获取所有考试信息的数据
        $list = $UserExamApi->getUserExamListWithExam();

        // 返回数据
        return $list;
    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkPaper()
    {
        $where["exam_id"] = trim(input('post.examId'));
        $where["user_id"] = trim(input('post.userId'));


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


        return $this->fetch();

    }

    /**
     * 保存审卷结果
     * @return \think\response\Json
     */
    public function saveAchievement()
    {

        $UserQuestionApi = new UserQuestionApi();
        $UserExamApi = new UserExamApi();
        $ExamApi = new ExamApi();
        $PaperApi = new PaperApi();

        $param = input('post.')['formData'];

        foreach ($param["questions"] as $item) {
            $UserQuestionApi->updateUserQuestion($item);
        }

        $where["exam_id"] = $param["examId"];
        $where["user_id"] = $param["userId"];

        // 获取考试信息，用于判断得分是否大于及格分
        $exam = $ExamApi->getExam($param["examId"]);
        $paper = $PaperApi->getPaper($exam["paper_id"]);

        // 整合更新数据
        if ($param["totalScore"] >= $paper["pass_score"]) {
            $update["pass"] = 1;
        } else {
            $update["pass"] = 0;
        }
        $update["score"] = $param["totalScore"];
        $update["status"] = 5;

        // 更新考生试卷
        $response = $UserExamApi->updateUserExamByWhere($where, $update);

        return $response;
    }


    /**
     * 删除指定ID的成绩信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete()
    {
        // 获取id
        $id = trim(input('post.id'));

        // 创建考试API接口的实例
        $UserExamApi = new UserExamApi();

        // 删除指定的成绩信息
        $response = $UserExamApi->deleteUserExam($id);

        // 返回数据
        return $response;
    }
}