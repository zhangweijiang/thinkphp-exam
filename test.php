//专业信息--取七条
$CourseApi = new CourseApi();
$CourseList = $CourseApi->getCourseList();
$this->assign('CourseList',$CourseList);