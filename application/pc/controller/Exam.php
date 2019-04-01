<?php
namespace app\pc\controller;
use app\api\controller\ExamApi;
use app\api\controller\PaperApi;
use app\api\controller\PaperQuestionApi;
use app\api\controller\UserQuestionApi;
use app\api\controller\UserExamApi;
use think\Db;
class Exam extends BaseController
{
    private $first = true;

    /**
     * 考试页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        //获取考试id
        $id = input('get.id');
        //判断用户是否登录,未登录则用session存储待跳转页面,已登录继续执行以下代码
        if ($this->isLogin() === false) {//未登录
            session('url',url('pc/exam/index')."?id=$id");
            $this->redirect('login/index');
        }

        $userId = session('user')["id"];
        $UserExamApi = new UserExamApi();
        if(session("first-{$id}-{$userId}") == null) {
            $userExam["exam_id"] = $id;
            $userExam["user_id"] = $userId;
            $time = (new \DateTime())->format('Y-m-d H:i:s');
            $UserExamApi->updateUserExamByWhere($userExam, ["exam_time" => $time]);

            session("first-{$id}-{$userId}", $time);
        }



        /*获取考试的基本信息*/
        $ExamApi = new ExamApi();
        $exam = $ExamApi->getExam($id);

        //判断时间是否处于考试时间,不是考试时间返回首页（防止用户知道考试链接在不规定时间内进行考试）
        if($exam['status']!==2){
            $this->redirect('Index/index');
        }

        $firstTime = session("first-{$id}-{$userId}");
        //获取考试的结束时间
        $endTime = strtotime($firstTime) + $exam['time']*60;
        $exam['end_time'] = $endTime >= strtotime($exam['max_end_date']) ? strtotime($exam['max_end_date']) : $endTime;
        //定义exam的模板变量，传输到模板视图中
        $this->assign('exam',$exam);

        /*获取试卷-试题的基本信息*/
        //获取试卷id作为查询条件
        $where['paper_id'] = $exam['paper_id'];
        //创建试卷-试题api接口的实例
        $PaperQuestionApi = new PaperQuestionApi();
        //获取考试-试题列表（通过试卷类型升序排序)
        $PaperQuestionList =  $PaperQuestionApi->getList($where,'type asc');
        foreach($PaperQuestionList as &$v){
            if($v['type']==1||$v['type']==2||$v['type']==3){
                //试卷类型为判断题、单选题、多选题时处理信息
                $v['optionsList'] = explode('||',$v['options']);
            }else if($v['type']==4){
                //试卷类型为填空题时处理信息
                $v['optionsList'] = explode('||',$v['answer']);
            }
        }
        //定义UserQuestionList的模板变量，传输到模板视图中
        $this->assign('PaperQuestionList',$PaperQuestionList);

        // 返回当前控制器对应的视图模板index.html
        return $this->fetch();
    }

    /**
     * 交卷
     */
    public function submit()
    {
        Db::startTrans();//开启事务
        //获取前台post的表单数据
        $data = input('post.')['data'];
        //获取考试id
        $id = input('post.')['id'];
        //创建考试api接口的实例
        $ExamApi = new ExamApi();
        //获取考试基本信息
        $exam = $ExamApi->getExam($id);

        //创建试卷-试题api接口的基本信息
        $PaperQuestionApi = new PaperQuestionApi();

        //考生-试题表信息插入
        //创建考试-试题API接口的实例
        $UserQuestionApi = new UserQuestionApi();
        $total_score = 0;//考试总分
        foreach ($data as $v) {
//            $PaperQuestion = array();
            //通过试卷试题id获取试卷-试题基本信息
            $PaperQuestion = $PaperQuestionApi->getPaperQuestion($v['id']);
            $insert = array();
            $insert['name'] = $PaperQuestion['name'];//试题名称
            $insert['title'] = $PaperQuestion['title'];//试题题目
            $insert['score'] = $PaperQuestion['score'];//试题分数
            $insert['analysis'] = $PaperQuestion['analysis'];//试题分析
            $insert['answer'] = $PaperQuestion['answer'];//试题答案
            $insert['keyword'] = $PaperQuestion['keyword'];//简单题-关键字
            $insert['keyword_imp'] = $PaperQuestion['keyword_imp']; //简答题-重要关键字
            $insert['type'] = $PaperQuestion['type']; //试题类型
            $insert['user_question_answer'] = $v['answer'];//考生回答答案
            $insert['create_time'] = time(); //添加时间
            $insert['options'] = $PaperQuestion['options'];
            $insert['user_id'] = session('user')['id']; // 考生id
            $insert['exam_id'] = $id; //考试id

            //计算用户得分
            if ($PaperQuestion['type'] == 1 || $PaperQuestion['type'] == 2 || $PaperQuestion['type'] == 3 || $PaperQuestion['type'] == 4) {  //判断题、单选题、多选题、填空题
                if ($PaperQuestion['answer'] == $v['answer']) {
                    $insert['user_score'] = $PaperQuestion['score'];
                } else {
                    $insert['user_score'] = 0;
                }
            }
            if ($PaperQuestion['type'] == 5) {//多选题
                $keywordList = explode('||', $PaperQuestion['keyword']);
                $keywordImpList = explode('||', $PaperQuestion['keyword_imp']);
                $keywordCount = count($keywordList);
                $keywordImpCount = count($keywordImpList);
                $score = round($v['score'] / ($keywordCount + $keywordImpCount*2), 0);
                $keywordScore = $score;  //一个关键词得分
                $keywordImpScore = $score * 2; //一个重要关键字得分
                $userKeywordScore = 0; //考生关键字的总得分
                $userKeywordImpScore = 0; //考生重点关键字的总得分
                //关键字总得分
                foreach($keywordList as $v1){
                    if(strpos($v['answer'],$v1)!==FALSE){
                        $userKeywordScore += $keywordScore;
                    }
                }

                //重点关键字总得分
                foreach($keywordImpList as $v2){
                    if(strpos($v['answer'],$v2)!==FALSE){
                        $userKeywordImpScore += $keywordImpScore;
                    }
                }
                //考生对应这道试题的得分
                $insert['user_score'] = $userKeywordScore+$userKeywordImpScore;

            }
            $insert["final_score"] = $insert["user_score"];
            //考生的试卷分数
            $total_score += $insert['user_score'];
            $result = $UserQuestionApi->addUserQuestion($insert);
            if($result===FALSE){
                $response = [
                    "status" => false,
                    "message" => "交卷失败"
                ];
                Db::rollback();//事务回滚
                break;
            }
        }


        //考生-试卷表信息修改
        $update = array();
        $update['exam_id'] = $id; //试卷id
        $update['user_id'] = session('user')['id']; //考生id
        if($exam["is_check"] == 0) {
            $update['status'] = 5; //状态:1表示未开始,2表示进行中,3表示考试完成,4-缺考,5-批改完成
        }else {
            $update['status'] = 3;
        }
        $update['score'] = $total_score;  //试卷总分
        //创建考试api接口的实例
        $ExamApi = new ExamApi();
        //创建试卷api接口的实例
        $PaperApi = new PaperApi();
        //获取试卷id
        $paper_id = $ExamApi->getExam($id)['paper_id'];
        //获取及格分数
        $paper = $PaperApi->getPaper($paper_id); //及格分数
        $pass_score = $paper["pass_score"];
        if($total_score >= $pass_score){
            $update['pass'] = 1; //考试通过
        }else{
            $update['pass'] = 0; //考试不通过
        }
        //更新考试对应的试卷score,status,pass
        $result = Db::name('user_exam')->where(['exam_id'=>$update['exam_id'],'user_id'=>$update['user_id']])->update($update);
        if($result===FALSE){
            $response = [
                "status" => false,
                "message" => "交卷失败"
            ];
            Db::rollback();//事务回滚
        }

        $response = [
            "status" => true,
            "message" => "成功交卷"
        ];
        Db::commit();//事务提交
        return json($response);
    }

    //判断用户是否再次提交试卷
    public function check(){
        //获取考试id
        $exam_id = input('post.id');
        //通过session获取考生id
        $user_id = session('user')['id'];
        //获取考生-考试的基本信息
        $UserExam = Db::name('user_exam')->where(['user_id'=>$user_id,'exam_id'=>$exam_id])->find();
        if($UserExam['status']==3 || $UserExam['status']==5){
            $response = [
                "status" => false,
                "message" => "您已交卷"
            ];
        }else{
            $response = [
                "status" => true,
                "message" => "开始考试"
            ];
        }
        return json($response);
    }



}
