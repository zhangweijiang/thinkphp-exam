<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/25
 * Time: 16:37
 */

namespace app\admin\controller;

use app\api\controller\CourseApi;
use app\api\controller\MajorApi;
use app\api\controller\PaperApi;
use app\api\controller\PaperQuestionApi;
use app\api\controller\QuestionApi;
use think\Db;

class Paper extends BaseController
{
    /**
     * 试卷信息列表页面
     * @return mixed
     */
    public function lists()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 试卷信息列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function paperList()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取所有试卷信息数据
        $list = $PaperApi->getPaperList();
        $courseApi = new CourseApi();
        $majorApi = new MajorApi();
        foreach($list as &$val){
            if(!empty($val['major_id'])){
                $val['major'] = $majorApi->getMajor($val['major_id'])['name'];
            }else{
                $val['major'] = '无';
            }
            if(!empty($val['course_id'])){
                $val['course'] = $courseApi->getCourse($val['course_id'])['name'];
            }else{
                $val['course'] = '无';
            }
        }

        // 返回数据
        return json($list);
    }

    /**
     * 专业/课程
     * @return mixed
     */
    public function major_course(){
        // 分别创建专业、课程和试卷API接口的实例
        $majorApi = new MajorApi();
        $courseApi = new CourseApi();

        // 获取所有的试卷信息，获取所有专业信息，根据列表第一条专业的ID获取课程信息
        $majors = $majorApi->getMajorList();
        $course = $courseApi->getCourseListByMajor($majors[0]['id']);

        // 将获取的数据分别定义majors、courses、papers的模板变量
        $this->assign('majors', $majors);
        $this->assign('courses', $course);
    }

    /**
     * 添加试卷信息页面
     * @return mixed
     */
    public function add()
    {
        $this->major_course();
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 添加试卷信息
     * @return mixed
     */
    public function addPaper()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取前台传过来的数据
        $paper["name"] = trim(input('post.name'));
        $paper["pass_score"] = trim(input('post.pass_score'));
        $paper["status"] = trim(input('post.status'));
        $paper["major_id"] = trim(input('post.major_id'));
        $paper["course_id"] = trim(input('post.course_id'));

        // 向数据库中新增数据
        $response = $PaperApi->addPaper($paper);

        // 返回新增数据的结果
        return $response;
    }

    /**
     * 试卷信息更新页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取前台传过来的试卷ID
        $id = trim(input('post.id'));

        // 根据试卷ID获取对应的试卷信息
        $paper = $PaperApi->getPaper($id);

        // 定义paper模板变量存储试卷信息
        $this->assign('paper', $paper);

        // 获取专业和课程信息
        $courseApi = new CourseApi();
        $majorApi = new MajorApi();
        $majors = $majorApi->getMajorList();
        $course = $courseApi->getCourseListByMajor($paper['major_id']);

        // 定义majors、courses、exam和papers模板变量，传输到模板视图中
        $this->assign('majors', $majors);
        $this->assign('courses', $course);

        // 返回当前控制器对应模板视图add.html
        return $this->fetch('add');
    }

    /**
     * 更新试卷信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editPaper()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取前台传过来的所有数据
        $paper["id"] = trim(input('post.id'));
        $paper["name"] = trim(input('post.name'));
        $paper["pass_score"] = trim(input('post.pass_score'));
        $paper["status"] = trim(input('post.status'));
        $paper["major_id"] = trim(input('post.major_id'));
        $paper["course_id"] = trim(input('post.course_id'));

        // 更新数据库中对应的试卷信息
        $response = $PaperApi->updatePaper($paper);

        // 返回更新数据的结果
        return $response;
    }


    /**
     * 删除试卷信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();
        $PaperQuestionApi = new PaperQuestionApi();

        // 获取前台传过来的要删除的试卷ID
        $id = trim(input('post.id'));

        // 根据试卷ID删除数据库中对应的试卷信息
        $response = $PaperApi->deletePaper($id);
        $response = $PaperQuestionApi->deletePaperQuestionByPaper($id);

        // 返回删除数据的结果
        return $response;
    }

    /**
     * 添加试卷-试题页面
     * @return mixed
     */
    public function addQuestion()
    {
        $question["order"] = trim(input('post.order'));
        $question["paper_id"] = trim(input('post.paper_id'));

        $this->assign("question", $question);

        return $this->fetch();
    }

    /**
     * 添加试卷-试题信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addPaperQuestion()
    {
        // 创建试题API接口的实例
        $PaperQuestionApi = new PaperQuestionApi();

        // 获取前台传来的所有数据，并整合
        $question["name"] = trim(input('post.name'));
        $question["title"] = trim(input('post.title'));
        $question["options"] = trim(input('post.options'));
        $question["answer"] = trim(input('post.answer'));
        $question["keyword"] = trim(input('post.keyword'));
        $question["keyword_imp"] = trim(input('post.keyword_imp'));
        $question["score"] = trim(input('post.score'));
        $question["type"] = trim(input('post.type'));
        $question["analysis"] = trim(input('post.analysis'));
        $order = trim(input('post.order'));
        $paperId = trim(input('post.paper_id'));

        // 向数据库新增试题信息
        $response = $PaperQuestionApi->addPaperQuestion($question, $paperId, $order);

        // 返回新增数据的结果
        return $response;
    }

    /**
     * 更新试卷-试题页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function editPaperQuestion()
    {

        $id = trim(input('post.id'));

        $PaperQuestionApi = new PaperQuestionApi();

        $question = $PaperQuestionApi->getPaperQuestion($id);

        // 定义question变量的answerString属性为空字符串
        $question['answerString'] = "";

        if ($question['type'] == 2) { //如果当前试题的类型为单选题
            // 根据`||`字符串来将options分割为数组赋值给optionsList
            $question['optionsList'] = explode('||', $question['options']);

        } else if ($question['type'] == 3) { //如果当前试题的类型为多选题
            // 根据`||`字符串来将options(选项)分割为数组赋值给optionsList
            $question['optionsList'] = explode('||', $question['options']);

            // 将answer(答案)中`||`字符串替换为`,`赋值给answerString
            $question['answerString'] = str_replace('||', ',', $question['answer']);

        } else if ($question['type'] == 4) { // 如果当前试题的类型为填空题

            // 将answer(答案)中`||`字符串替换为`,`赋值给answerString
            $question['answerString'] = explode('||', $question['answer']);
        }

        $this->assign("question", $question);

        return $this->fetch("addQuestion");
    }

    /**
     * 更新试卷-试题信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updatePaperQuestion()
    {
        $PaperQuestionApi = new PaperQuestionApi();

        // 获取前台传过来的数据并整合
        $question["id"] = trim(input('post.id'));
        $question["name"] = trim(input('post.name'));
        $question["title"] = trim(input('post.title'));
        $question["options"] = trim(input('post.options'));
        $question["answer"] = trim(input('post.answer'));
        $question["keyword"] = trim(input('post.keyword'));
        $question["keyword_imp"] = trim(input('post.keyword_imp'));
        $question["score"] = trim(input('post.score'));
        $question["type"] = trim(input('post.type'));
        $question["analysis"] = trim(input('post.analysis'));

        // 更新数据库中对应的试题信息
        $response = $PaperQuestionApi->updatePaperQuestion($question);

        // 返回更新数据的结果
        return $response;
    }

    /**
     * 试卷-试题信息更新页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function editQuestions()
    {
        // 获取前台传过来的试卷ID
        $id = input('post.id');

        $PaperQuestionApi = new PaperQuestionApi();

        $List = $PaperQuestionApi->getPaperQuestionByPaperId($id);

        // 根据试卷ID获取对应的试题列表信息
//        $List = Db::name('paper_question')->where(['paper_id' => $id])->select();

        $letter = ["A", "B", "C", "D"];

        // 循环所有试题，对所有试题进行类型判断，并对该试题对应的选项和答案进行处理
        foreach ($List as &$v) {
            $v['answer1'] = '';

            if ($v['type'] == 1) { //判断题
                $v['answer1'] = $letter[$v['answer'] - 1];
//                if ($v['answer'] == 1) {
//                    $v['answer1'] = '正确';
//                } else if ($v['answer'] == 2) {
//                    $v['answer1'] = '错误';
//                }
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
            }
            if ($v['type'] == 2) { //单选题
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
                $v['answer1'] = $letter[$v['answer'] - 1];
//                $v['answer1'] = $optionList[$v['answer'] - 1];
            }
            if ($v['type'] == 3) { //多选题
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
                $answerList = explode('||', $v['answer']);
                $v['answerList'] = $answerList;
                $answer1 = [];
                foreach ($answerList as $item) {
                    array_push($answer1, $letter[$item - 1]); //$optionList[$item - 1]
                }

                $v['answer1'] = implode('', $answer1); //$answer1; //
            }

            if ($v['type'] == 4) { //填空题
                $optionList = explode('||', $v['options']);
                $v["optionsList"] = $optionList;
                $answerList = explode('||', $v['answer']);
                $v['answerList'] = $answerList;
                $v['answer1'] = str_replace('||', ',', $v['answer']);
            }

            if ($v['type'] == 5) { //简答题
                $v['answer1'] = $v['answer'];
            }
        }

        // 定义List模板变量，用于模板视图展示对应数据
        $this->assign('List', $List);
        $this->assign('paperId', $id);
        $this->assign('letter', $letter);

        // 返回对应控制器模板视图的questions.html
        return $this->fetch('questions');
    }


    /**
     * 保存试卷详细信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function saveQuestions()
    {
        $question = input('post.')['formData'];

        $PaperQuestionApi = new PaperQuestionApi();
        $PaperApi = new PaperApi();

        $paper["id"] = $question["id"];
        $paper["score"] = $question["totalScore"];

        // 根据试卷ID获取对应的试卷信息
        $paperObj = $PaperApi->getPaper($paper["id"]);

        if($paperObj["pass_score"] > $paper["score"]) {
            $response = json(["status" => false, "message" => "试卷总分不得低于及格分！"]);
        }else {
            foreach ($question["questions"] as $item) {
                $PaperQuestionApi->updatePaperQuestion($item);
            }

            $response = $PaperApi->updatePaper($paper);
        }

        return $response;
    }


    /**
     * 删除试卷-试题
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteQuestions()
    {
        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();

        // 获取前台要删除的试题ID
        $id = input('post.id');

        // 删除数据库中对应的试题ID
        $response = $PaperApi->deletePaperQuestion($id);

        // 返回删除数据的结果
        return $response;
    }

    /**
     * 试题选择列表
     * @return mixed
     */
    public function selectQuestions(){
        // 获取前台传过来的试卷ID
        $id = input('post.id');
        $this->assign('id',$id);

        // 创建试卷API接口的实例
        $PaperApi = new PaperApi();
        // 根据试卷ID获取对应的试卷信息
        $paper = $PaperApi->getPaper($id);

        // 试题类型（1-判断题 2-单选题 3-多选题 4-填空题 5-简答题）
        $QuestionList1 = db('question')->where(['type'=>1,'major_id'=>$paper['major_id'],'course_id'=>$paper['course_id']])->select();
        $QuestionList2 = db('question')->where(['type'=>2,'major_id'=>$paper['major_id'],'course_id'=>$paper['course_id']])->select();
        $QuestionList3 = db('question')->where(['type'=>3,'major_id'=>$paper['major_id'],'course_id'=>$paper['course_id']])->select();
        $QuestionList4 = db('question')->where(['type'=>4,'major_id'=>$paper['major_id'],'course_id'=>$paper['course_id']])->select();
        $QuestionList5 = db('question')->where(['type'=>5,'major_id'=>$paper['major_id'],'course_id'=>$paper['course_id']])->select();

        $this->assign('QuestionList1',$QuestionList1);
        $this->assign('QuestionList2',$QuestionList2);
        $this->assign('QuestionList3',$QuestionList3);
        $this->assign('QuestionList4',$QuestionList4);
        $this->assign('QuestionList5',$QuestionList5);

        $PaperQuestion = db('paper_question')->where(['paper_id'=>$id])->select();
        $PaperQuestionId = array_column($PaperQuestion,'question_id');
        $this->assign('PaperQuestionId',$PaperQuestionId);

        // 返回对应控制器模板视图的questions.html
        return $this->fetch('select_questions');
    }

    /**
     * 保存试题选择列表
     * @return mixed
     */
    public function saveSelectQuestions(){

        // 创建试题API接口和试卷API接口的实例
        $QuestionApi = new QuestionApi();

        $questions = input('post.')['row'];
        $id = input('post.id');
        $data = [];
        foreach($questions as $val){
            if(isset($val['select']) && $val['select']=='checked'){
                $data[] = $val;
            }
        }
        unset($val);
        if(empty($data)){
            $response = [
                "status" => false,
                "message" =>"选择不能为空"
            ];
            return json($response);
        }

        $score = 0;
        $update_num = 0;
        $insert_num = 0;
        foreach($data as $val){
            if($val['score']==0){
                $response = [
                    "status" => false,
                    "message" =>"ID={$val['id']}这道题未设置分数"
                ];
                return json($response);
            }
            $score += $val['score'];
            $question = $QuestionApi->getQuestion($val['id']);
            $PaperQuestion = db('paper_question')->where(['paper_id'=>$id,'question_id'=>$val['id']])->find();
            if(empty($PaperQuestion)){
                $insert_num ++;
                $insert = [
                    'paper_id'=>$id,
                    'score'=>$val['score'],
                    'order'=>$val['order'],
                    'question_id'=>$question['id'],
                    'name'=>$question['name'],
                    'title'=>$question['title'],
                    'type'=>$question['type'],
                    'options'=>$question['options'],
                    'answer'=>$question['answer'],
                    'analysis'=>$question['analysis'],
                    'keyword'=>$question['keyword'],
                    'keyword_imp'=>$question['keyword_imp'],
                    'create_time'=>time()
                ];
                db('paper_question')->insert($insert);
            }else{
                if($PaperQuestion['score']!=$val['score'] || $PaperQuestion['order']!=$val['order']){
                    $update_num ++;
                }
                db('paper_question')->where(['paper_id'=>$id,'question_id'=>$val['id']])->update(['score'=>$val['score'],'order'=>$val['order']]);

            }
        }
        unset($val);
        db('paper')->where(['id'=>$id])->update(['score'=>$score]);

        //判断是否删除题目
        $question_id = array_column($data,'id');
        $PaperQuestion = db('paper_question')->where('paper_id',$id)->select();
        $delete_num = 0;
        foreach($PaperQuestion as $val){
            if(!in_array($val['question_id'],$question_id)){
                $delete_num ++;
                db('paper_question')->where('id',$val['id'])->delete();
            }
        }
        unset($val);

        $response = [
            "status" => true,
            "message" =>"新增{$insert_num}道题，更新{$update_num}道题，删除{$delete_num}道题"
        ];
        return json($response);

    }

    /**
     * 预览试卷
     * @return mixed
     */
    public function previewPaper(){
        $id = input('post.id');
        $paper_name = db('paper')->where(['id'=>$id])->find()['name'];
        $this->assign('paper_name',$paper_name);

        $replace = [
            '1'=>'A',
            '2'=>'B',
            '3'=>'C',
            '4'=>'D'
        ];
        $paperQuestion = db('paper_question')->where(['paper_id'=>$id])->order('order','asc')->select();
        foreach($paperQuestion as &$val){
            if($val['type']==1){
                $val['type_name'] = '判断题';
            }elseif($val['type']==2){
                $val['type_name'] = '单选题';
            }elseif($val['type']==3){
                $val['type_name'] = '多选题';
            }elseif($val['type']==4){
                $val['type_name'] = '填空题';
            }elseif($val['type']==5){
                $val['type_name'] = '简答题';
            }
            if($val['type']==1){
                $val['content'] = '';
                $val['options'] = [];
                if($val['answer']==1){
                    $val['answer'] = '对';
                }else{
                    $val['answer'] = '错';
                }
            }else if($val['type']=='2'){
                $options_list = explode('||',$val['options']);
                $options = [];
                foreach($options_list as $k=>$v){
                    $options[] = $replace[$k+1]."、$v";
                }
                unset($v);
                $val['options'] = $options;
                $val['answer'] = $replace[$val['answer']];
            }else if($val['type']=='3'){
                $options_list = explode('||',$val['options']);
                $options = [];
                foreach($options_list as $k=>$v){
                    $options[] = $replace[$k+1]."、$v";
                }
                unset($v);
                $val['options'] = $options;

                $answer = explode('||',$val['answer']);
                foreach($answer as &$v){
                    $v = $replace[$v];
                }
                unset($v);
                $val['answer'] = implode(',',$answer);
            }else if($val['type']=='4'){
                $val['options'] = [];
                $val['answer'] = '';
            }else if($val['type']='5'){
                $val['options'] = [];
                $val['answer'] = '';
            }
        }
        unset($val);
        $this->assign('paperQuestion',$paperQuestion);
        // 返回对应控制器模板视图的questions.html
        return $this->fetch('preview_paper');
    }

    /**
     * @title 导出word
     * @description 参考链接：https://blog.csdn.net/qq_24562495/article/details/104516695
     */
    public function wordexport(){
        $id = input('get.id');
        $paperQuestion = db('paper_question')->where(['paper_id'=>$id])->order('order','asc')->select();
        $replace = [
            '1'=>'A',
            '2'=>'B',
            '3'=>'C',
            '4'=>'D'
        ];
        foreach($paperQuestion as &$val){
            if($val['type']==1){
                $val['type_name'] = '判断题';
            }elseif($val['type']==2){
                $val['type_name'] = '单选题';
            }elseif($val['type']==3){
                $val['type_name'] = '多选题';
            }elseif($val['type']==4){
                $val['type_name'] = '填空题';
            }elseif($val['type']==5){
                $val['type_name'] = '简答题';
            }
            if($val['type']==1){
                $val['content'] = '';
                $val['options'] = [
                    'A、对',
                    'B、错'
                ];
                if($val['answer']==1){
                    $val['answer'] = 'A';
                }else{
                    $val['answer'] = 'B';
                }
            }else if($val['type']=='2'){
                $options_list = explode('||',$val['options']);
                $options = [];
                foreach($options_list as $k=>$v){
                    $options[] = $replace[$k+1]."、$v";
                }
                unset($v);
                $val['options'] = $options;
                $val['answer'] = $replace[$val['answer']];
            }else if($val['type']=='3'){
                $options_list = explode('||',$val['options']);
                $options = [];
                foreach($options_list as $k=>$v){
                    $options[] = $replace[$k+1]."、$v";
                }
                unset($v);
                $val['options'] = $options;

                $answer = explode('||',$val['answer']);
                foreach($answer as &$v){
                    $v = $replace[$v];
                }
                unset($v);
                $val['answer'] = implode(',',$answer);
            }else if($val['type']=='4'){
                $val['options'] = [];
                $val['answer'] = '';
            }else if($val['type']='5'){
                $val['options'] = [];
                $val['answer'] = '';
            }
        }
        unset($val);
        $paper_name = db('paper')->where(['id'=>$id])->find()['name'];

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        //调整页面样式
        $sectionStyle = array('orientation' => null,
            'marginLeft' => 400,
            'marginRight' => 400,
            'marginTop' => 400,
            'marginBottom' => 400);
        $section = $phpWord->addSection($sectionStyle);
        //添加页眉
        $header=$section->addHeader();
        $k=$header->addTextRun();
        //添加页脚
        $footer = $section->addFooter();
        $f=$footer->addTextRun();

        $section->addText(
            "$paper_name",
            array('name' => '黑体', 'size' => 15),
            array('align'=>'center')
        );
        //添加换行符
        $section->addTextBreak(2);

        //添加文本，处理文本

        foreach($paperQuestion as $k=>$question){
            if($question['type']==1||$question['type']==2||$question['type']==3){
                // 添加题目
                $section->addText(
                    $k+1 ."、({$question['type_name']})".$question['name'],
                    array('name' => 'Arial', 'size' => 12),
                    array('lineHeight'=>1.3,'indent'=>1)
                );
                foreach ($question['options'] as $k=>$v){
                    $section->addText(
                        $v,
                        array('name' => 'Arial', 'size' => 11),
                        array('lineHeight'=>1,'indent'=>1)
                    );
                }
                $section->addTextBreak(1); //添加换行
            }

            if($question['type']==4||$question['type']==5){
                // 添加题目
                $section->addText(
                    $k+1 ."、{$question['name']}",
                    array('name' => 'Arial', 'size' => 12),
                    array('lineHeight'=>1.3,'indent'=>1)
                );
                $section->addText(
                    "答：",
                    array('name' => 'Arial', 'size' => 11),
                    array('lineHeight'=>1,'indent'=>1)
                );
                $section->addTextBreak(1); //添加换行
            }
        }


        $name="{$paper_name}".".docx";
        $phpWord->save($name,"Word2007",true);
        exit();

    }



}