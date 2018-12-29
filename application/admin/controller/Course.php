<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/24
 * Time: 16:37
 */

namespace app\admin\controller;

use app\api\controller\CourseApi;
use app\api\controller\MajorApi;

class Course extends BaseController
{
    /**
     * 课程信息列表页面
     * @return mixed
     */
    public function lists()
    {
        // 返回对应视图目录中对应方法名的模板，以此为例，返回的模板为当前模块view目录中Course控制器对应的Course目录中lists.html，即admin/view/course/lists.html
        return $this->fetch();
    }

    /**
     * 课程信息列表
     * @return \think\response\Json
     */
    public function courseList() {
        // 获取课程API接口实例
        $CourseApi = new CourseApi();

        // 执行API接口中的getCourseList方法获取课程列表赋值给$list变量
        $list = $CourseApi->getCourseList();

        // 返回$list
        return $list;
    }

    /**
     * 添加课程信息页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add()
    {
        // 获取专业API接口实例
        $MajorApi = new MajorApi();

        // 执行API接口中的getMajorList方法获取专业列表赋值给$majors变量
        $majors = $MajorApi->getMajorList();

        // 定义视图模板变量majors，讲$majors赋值给它，该方法可让开发者在视图模板中调用到该数据
        $this->assign('majors', $majors);

        // 返回当前方法对应视图
        return $this->fetch();
    }

    /**
     * 添加课程信息
     * @return mixed
     */
    public function addCourse()
    {
        // 获取课程API接口实例
        $CourseApi = new CourseApi();

        // 整合前台传过来的数据，用于后续添加到数据库使用
        $course["name"] = trim(input('post.name'));
        $course["major_id"] = trim(input('post.major_id'));
        $course["order"] = trim(input('post.order'));

        // 将整合好的数据作为参数赋给课程API接口中addCourse方法，用户新增数据
        $response = $CourseApi->addCourse($course);

        // 返回新增数据的结果
        return $response;
    }

    /**
     * 课程信息更新页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        // 获取课程API接口实例
        $CourseApi = new CourseApi();

        // 获取页面传来的ID
        $id = trim(input('post.id'));

        // 根据ID获取对应的课程信息
        $course = $CourseApi->getCourse($id);

        // 获取专业API接口实例
        $MajorApi = new MajorApi();

        // 获取所有专业信息，作为下拉菜单数据使用
        $majors = $MajorApi->getMajorList();

        // 定义模板变量majors和course，以便在页面中使用数据
        $this->assign('majors', $majors);
        $this->assign('course', $course);

        // 返回视图，带参数`add`，则返回的模板视图为同一控制器视图目录下的add.html
        return $this->fetch('add');
    }

    /**
     * 更新课程信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editCourse()
    {
        // 获取课程API接口实例
        $CourseApi = new CourseApi();

        // 整合前台传过来的数据，用于后续编辑数据库中数据使用
        $course["id"] = trim(input('post.id'));
        $course["name"] = trim(input('post.name'));
        $course["major_id"] = trim(input('post.major_id'));
        $course["order"] = trim(input('post.order'));

        // 将前台传来的数据更新到数据库中
        $response = $CourseApi->updateCourse($course);

        // 返回更新数据的结果
        return $response;
    }

    /**
     * 删除课程信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete()
    {
        // 获取课程API接口实例
        $CourseApi = new CourseApi();

        // 获取前台传来的要删除课程的ID
        $id = trim(input('post.id'));

        // 根据ID删除数据库中对应的数据
        $response = $CourseApi->deleteCourse($id);

        // 返回删除数据的结果
        return $response;
    }
}