var answered_num = 1; //已答未保存考题数量
var answered_multi_all = []; //所以试题延时提交数据
var answered_all = [];
var answered_multi = {"questionsId": "", "keyList": ""}; //多选延时提交数据
var questionsId;
var webcam_json = {};
var capture_id;
// 对count设置cookie，对应到exam_results_id,刷新页面保持计数
var count = Get_Cookie(exam_results_id);

//若干秒无操作提示，自动交卷
var maxTime = parseInt(restTime) + 10;
var obj = document.activeElement;
var time = maxTime;
$(document).on('keydown click mousemove scroll', function (e) {
    $(".alert-danger").hide();
    time = maxTime; // reset
});
if (timeLimit == 1) {
    var intervalId = setInterval(function () {
        if (time == 0) {
            $(".alert-danger").hide();
            clearInterval(intervalId);
            saveExamFn();
        } else if (time <= 10) {
            var obj = document.activeElement;
            if (obj.tagName == 'IFRAME') {
                if (time == 10) {
                    $(obj).contents().find("body").append('<div class="tip" style="color:#a94442;background-color:#f2dede;border:1px solid #ebccd1;width:200px;padding:5px;">若无操作，' + time + '秒后自动交卷</div>');
                } else {
                    $(obj).contents().find(".tip").replaceWith('<div class="tip" style="color:#a94442;background-color:#f2dede;border:1px solid #ebccd1;width:200px;padding:5px;">若无操作，' + time + '秒后自动交卷</div>');
                }
            } else {
                $(".alert-danger").text("若无操作，" + time + "秒后自动交卷");
                $(".alert-danger").css("display", "block");
            }
        }
        time--;
    }, 1000);
}

$(document).ready(function () {
    // 每30秒请求一次考试时间
    setInterval(heartAjax, 30000);

    // 心跳链接，请求考试时间
    function heartAjax() {
        $.ajax({
            type: "post",
            url: "/exam/getExamEndTime",
            dataType: "json",
            data: "userId=" + examUserId + "&examInfoId=" + exam_info_id + "&examResultId=" + exam_results_id,
            success: function (msg) {
                if (msg.success) {
                    //code:0 未设置，不操作
                    //code:1 重新设置时间
                    //code:2 立即交卷
                    if (msg.bizContent.code == '1') {

                        if (answer_time != msg.bizContent.totalTime) {
                            $("#timeResetModal .delay-time").text(msg.bizContent.delayTimeStamp);
                            $('#timeResetModal').modal({
                                backdrop: "static",
                                keyboard: false
                            });
                        }

                        answer_time = msg.bizContent.totalTime;
                        answer_time_left = answer_time - consume_time;
                    } else if (msg.bizContent.code == '2') {
                        saveExamFn();
                    }
                } else {
                    console.log(msg.desc);
                }
            }
        });
    }


    //被删除的试题标记为已作答
    $('.questionsContent').each(function () {
        if ($(this).find('.qdeleted').length > 0) {
            $(this).attr('hasanswered', 'true');
            var id = $(this).attr('questionsid');
            $('#numberCardDom .answer-card .questions_' + id).parent().addClass('s2').removeClass('s1');
        }
    });

    var dl_size = dl_size_new = parseInt($("dl").css("font-size").replace("px", ""));
    var dl_changesize = dl_size / 10;
    var dt_size = dt_size_new = parseInt($("dt").css("font-size").replace("px", ""));
    var dt_changesize = dt_size / 10;
    var h4_size = h4_size_new = parseInt($(".cont h4").css("font-size").replace("px", ""));
    var h4_changesize = h4_size / 10;
    var h5_size = h5_size_new = parseInt($(".questions h5").css("font-size").replace("px", ""));
    var h5_changesize = h5_size / 10;

    $(".fontsize-btn-l").click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        if (dl_size_new <= dl_size) return false;
        dl_size_new = dl_size_new - dl_changesize;
        dt_size_new = dt_size_new - dt_changesize;
        h4_size_new = h4_size_new - h4_changesize;
        h5_size_new = h5_size_new - h5_changesize;
        $("dl").css("font-size", dl_size_new + "px");
        $("dt").css("font-size", dt_size_new + "px");
        $(".cont h4").css("font-size", h4_size_new + "px");
        $(".questions h5").css("font-size", h5_size_new + "px");
    });
    $(".fontsize-btn-r").click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        if (dl_size_new >= dl_size + dl_size / 2) return false;
        dl_size_new = dl_size_new + dl_changesize;
        dt_size_new = dt_size_new + dt_changesize;
        h4_size_new = h4_size_new + h4_changesize;
        h5_size_new = h5_size_new + h5_changesize;
        $("dl").css("font-size", dl_size_new + "px");
        $("dt").css("font-size", dt_size_new + "px");
        $(".cont h4").css("font-size", h4_size_new + "px");
        $(".questions h5").css("font-size", h5_size_new + "px");
    });

    //显示答题卡
    $(".hasShowCardLink").click(function (e) {
        $("#numberCardDom").show();
        $("#maskDiv").show();
        // $("html,body").animate({scrollTop:"0"},10);
        //$("#content").hide();
    });
    //隐藏答题卡
    $(".card-off").click(function (e) {
        $("#numberCardDom").hide();
        $("#maskDiv").hide();
        //$("#content").show();
    });
    //隐藏答题卡同时定位锚点
    $("a.iconBox").click(function (e) {
        e.stopPropagation();
        //e.preventDefault();
        var pageId = $(this).attr("questionsIdHref");
        $("#numberCardDom").hide();
        $("#maskDiv").hide();
        //$("#content").show();
        $("html,body").animate({scrollTop: $("[questionsId=" + pageId + "]").offset().top - 200}, 200);
    });

    //试题内容第一个P标签加样式
    $(".answers").each(function (index, element) {
        $($(this).find("p")[0]).css("display", "inline").addClass("ue");
    });
    $(".questionsContent").each(function (index, element) {
        $($(this).find("p")[0]).css("display", "inline").addClass("ue");
    });

    //判断是否答过，标记为已答
    $("a.iconBox").each(function (index, element) {
        if ($(this).attr("hasAnswered") == "true") {
            $(this).removeClass("icon_answer_no").addClass("icon_answer_answered");
            $(this).parent("dd").removeClass("s1").addClass("s2");
        }
    });
    //判断是否答过，标记答案
    $(".questionsContent").each(function (index, element) {
        if ($(this).find("input[name=questionsType]").val() == "1" || $(this).find("input[name=questionsType]").val() == "2" || $(this).find("input[name=questionsType]").val() == "3") {
            if ($(this).attr("hasAnswered") == "true") {
                var list = $(this).find("input[name=questionsAnswered]").val();
                list = list.split(",");
                var checkInput = $(this).find("input.radioOrCheck");
                checkInput.each(function (index, element) {
                    var obj = $(this);
                    var name = obj.attr("answerName");
                    $.each(list, function (index, value) {
                        if (value == name) {
                            obj.prop("checked", true);
                        }
                    });
                });
            }
        }
    });
    //标记
    $(".qm").click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        var mark = $(this).find("em");
        questionsMark(mark);
    });

    // 固定题干
    var currentMarginT;
    $(".qfix").click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        var s_obj = $(this).parents(".combContent");
        if ($(this).hasClass("fixed")) {
            $(this).removeClass("fixed");
            $(s_obj).removeClass("stuckMenu").removeClass("isStuck");
            $(s_obj).next().closest('div').css({
                'margin-top': currentMarginT + 'px'
            }, 10);
            $(s_obj).css("position", "relative");
        } else {
            if ($(".fixed").length != 0) {
                var p_obj = $(".fixed").parents(".combContent");
                $(".fixed").removeClass("fixed");
                $(p_obj).removeClass("stuckMenu").removeClass("isStuck");
                $(p_obj).next().closest('div').css({
                    'margin-top': currentMarginT + 'px'
                }, 10);
                $(p_obj).css("position", "relative");
            }
            currentMarginT = parseInt($(s_obj).next().closest('div').css('margin-top'));
            $(this).addClass("fixed");
            $(this).parents(".combContent").stickUp();
        }
    });

    $(".words").click(function () {
        var prev_node = $(this).prev();
        prev_node.prop("checked", !prev_node.prop("checked"));
    });

    //单选、多选、判断答案存储
    $(".radioOrCheck, .words").click(function (e) {
        var questionsType = $(this).parent().children("input").attr("questionsType");
        var parentDiv = $(this).parents(".questionsContent");
        var questionsId = parentDiv.attr("questionsId");
        var keyList = "";
        if (questionsType == "1") { //单选
            $(parentDiv.find(".radioOrCheck")).each(function (index, element) {
                if ($(this).is(":checked")) {
                    keyList = $(this).attr("answerName") + ",";
                    return;
                }
            });
            saveQuestionsCatch(questionsId, keyList);

        } else if (questionsType == "2") { //多选
            $(parentDiv.find(".radioOrCheck")).each(function (index, element) {
                if ($(this).is(":checked")) {
                    keyList += $(this).attr("answerName") + ",";
                }
            });
            saveQuestionsCatch(questionsId, keyList);

        } else if (questionsType == "3") { //判断
            $(parentDiv.find(".radioOrCheck")).each(function (index, element) {
                if ($(this).is(":checked")) {
                    keyList = $(this).attr("answerName") + ",";
                }
            });
            saveQuestionsCatch(questionsId, keyList);
        }
    });

    //填空答案存储
    $(".keyFill").blur(function (e) {
        var parentDiv = $(this).parents(".questionsContent");
        var questionsId = parentDiv.attr("questionsId");
        var keyFillDom = parentDiv.find(".keyFill");
        var keyList = "";
        if (keyFillDom.length == 1) {
            saveQuestionsCatch(questionsId, $(this).val());
        } else {
            keyFillDom.each(function (index, element) {
                if (index + 1 != keyFillDom.length) {
                    keyList += $(this).val() + "||";
                } else {
                    keyList += $(this).val();
                }

            });
            saveQuestionsCatch(questionsId, keyList);
        }

        if (keyList != "") {

        }
        e.stopPropagation();
        e.preventDefault();
    });


    //交卷
    $(".examEndBtn").click(function (e) {
        e.stopPropagation();
        e.preventDefault();

        if (check_subsidiary(domain)) {
            $(".examEndJumpBtn").hide();
        }

        $("#numberCardDom").hide();
        $("#maskDiv").hide();

        if (checkForm()) {
            BootstrapDialog.show({
                title: "",
                message: "是否现在交卷？",
                buttons: [{
                    label: "确认",
                    action: function (dialogItself) {
                        saveExamFn();
                        e.stopPropagation();
                        e.preventDefault();
                        $("#numberCardDom").hide();
                        $("#maskDiv").hide();
                        dialogItself.close();
                    }
                }, {
                    label: "取消",
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
            });

        } else {
            BootstrapDialog.show({
                title: "",
                message: "有试题尚未答题，是否现在交卷？",
                buttons: [{
                    label: "返回答题",
                    cssClass: 'btn-back',
                    action: function (dialogItself) {
                        dialogItself.close();
                        $("#numberCardDom").show();
                        $("#maskDiv").show();
                    }
                }, {
                    label: "交卷",
                    cssClass: 'btn-ok',
                    action: function (dialogItself) {
                        saveExamFn();
                        e.stopPropagation();
                        e.preventDefault();
                        $("#numberCardDom").hide();
                        $("#maskDiv").hide();
                        dialogItself.close();
                    }
                }]
            });
        }
    });

    //若干次切换后自动交卷
    $(function () {
        if (set_full_screen == 0) {
            //这是macos
            if (platform == 'macos') {
                $(window).blur(function () {
                    if (!screenfull.isFullscreen) {
                        //为了解决失去焦点又非全屏的情况，比如快捷键command+t会显示切两次
                        //
                    } else {
                        setTimeout(function () {
                            blurCount();
                        }, 50);
                    }
                });

                if (screenfull.enabled && set_full_screen == 0) {
                    document.addEventListener(screenfull.raw.fullscreenchange, function () {
                        if (!screenfull.isFullscreen) {
                            setTimeout(function () {
                                blurCount();
                            }, 50);
                        }
                    });
                }

            } else {
                // 这是windows
                //窗口失去焦点
                $(window).blur(function () {
                    if (!screenfull.isFullscreen) {
                        //为了解决失去焦点又非全屏的情况，比如快捷键alt+tab，切两次
                    } else {
                        setTimeout(function () {
                            blurCount();
                        }, 50);
                    }
                });

                if (screenfull.enabled && set_full_screen == 0) {
                    document.addEventListener(screenfull.raw.fullscreenchange, function () {
                        if (!screenfull.isFullscreen) {
                            setTimeout(function () {
                                blurCount();
                            }, 50);
                        } else {

                        }
                    });
                }

                if (browserName == 'firefox') {
                    document.onkeydown = function (e) {
                        var KeyCode = e.keyCode || e.which || e.charCode;
                        var ctrlKey = e.ctrlKey || e.metaKey;
                        if (ctrlKey && KeyCode == 84) {
                            //针对火狐浏览器ctrl+t切屏算两次的情况
                        }
                    }
                }

                /*if (parseInt(version) > 45) {*/
                // 这是针对360极速浏览器
                //用户按下ctrl+，认为切屏(极速浏览器中能检测屏幕是否blur，所以不用检测win键)
                /*document.onkeydown = function (e) {
                    if (e.keyCode == 17) {
                        setTimeout(function () {
                            blurCount();
                        }, 50);
                    }
                }*/
                //360经测试没问题

                /*} else {
                    // 这是针对360安全浏览器的极速模式
                    //用户按下win键，或者ctrl+，认为切屏
                    document.onkeydown = function (e) {
                        if ((e.keyCode == 91) || (e.keyCode == 17)) {
                            setTimeout(function () {
                                blurCount();
                            }, 50);
                        }
                    }
                }*/
            }
        }
    });


    //生成每个编辑器
    $('.keyCloze').each(function () {
        var keyCloze = this;
        var editor = new wangEditor(keyCloze);
        var parentDiv = $(keyCloze).parents('.questionsContent');
        var questionsId = parentDiv.attr('questionsId');
        editor.config.uploadImgUrl = '/admin/upload/?userRole=staff&action=uploadimage';
        editor.config.uploadImgFileName = 'upfile';
        editor.config.noPaste = true;
        editor.config.menus = [
            'table',
            '|',
            'img',
            'upload',
            '|',
            'fullscreen'
        ];
        editor.create();
        editor.$txt.blur(function () {
            var txt = $.trim(editor.$txt.html());
            if (txt != '<p><br></p>') {
                var keyList = editor.$txt.html();
                $(keyCloze).parents('.wangEditor-container').next().val(keyList);
                saveQuestionsCatch(questionsId, keyList, false, true);
            }
        })
    });


    // 开启摄像头，拍照防作弊
    if (capture == 1) {
        $("#webcam").show();
        $("#webcam").webcam({
            width: 480,
            height: 360,
            mode: "callback",
            swffile: "/static/plugins/webcam/jscam_canvas_only.swf",
            onLoad: function () {
                var question_total = $(".questionsContent").length;//获取题目总数
                webcam_json = getRandom(1, question_total);//创建json存拍照题号和相应拍照状态
                console.log(webcam_json);
                // 对需拍照的题，在首次点击该题时拍照，并更改拍照状态
                $(".questionsContent").each(function (index, element) {
                    $(this).click(function () {
                        if (webcam_json[index + 1] == 0) {
                            webcam_json[index + 1] = 1;
                            capture_id = $(this).attr("questionsId");
                            webcam.capture(0);
                        }
                    });
                });
            },
            onTick: function (remain) {
            },
            onSave: saveImg(),
            onCapture: function () {
                webcam.save();
            },
            debug: function (type, string) {
                // console.log(type + ": " + string);
                if (type == 'notify' && string == 'Camera started') {
                    $("#cams").hide();
                    setTimeout(function () {
                        camera_animate_hide();
                        $("#captureStatus").css({
                            "opacity": "1",
                            "filter": "opacity(alpha=0)"
                        });
                        //   $("#webcam").css("position","absolute").css("top","-999px").css("left","-999px;");
                    }, 10000);
                }
            }
        });
    }

    //显示摄像头
    $("#captureStatus").click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        camera_animate_show();
        $("#captureStatus").css({
            "opacity": "0",
            "filter": "opacity(alpha=0)"
        });
    });
    // 隐藏摄像头
    $("#webcam .glyphicon-remove").click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        camera_animate_hide();
        $("#captureStatus").css({
            "opacity": "1",
            "filter": "opacity(alpha=100)",
        });
    });

});

// 隐藏摄像头动画
function camera_animate_hide() {
    if (capture == 0) {
        return;
    }
    var pos = document.getElementById("captureStatus").getBoundingClientRect();
    $("#webcam").animate({
        top: pos.top + 10,
        left: pos.left + 60,
        width: '1px',
        height: '1px',
    }, 500, function () {
        $("#webcam").css({
            "opacity": "0",
            "filter": "opacity(alpha=0)",
        });
        $("#webcam .glyphicon-remove").hide();
        $("#webcam object").css({
            "width": "1px",
            "height": "1px",
        })
    });

}

// 显示摄像头动画
function camera_animate_show() {
    $("#webcam").css({
        "display": "block",
        "opacity": "1",
        "filter": "opacity(alpha=100)",
    });
    $("#webcam object").css({
        "width": "480px",
        "height": "360px",
    })
    $("#webcam").animate({
        top: 0,
        left: 0,
        width: '480px',
        height: '360px',
    }, 500, function () {
        $("#webcam .glyphicon-remove").show();
    });
}


//切换
function blurCount() {
    if (set_full_screen == 0 && ($(document.activeElement)[0].tagName != "IFRAME")) {
        count++;
        document.cookie = exam_results_id + "=" + escape(count) + ";expires=Mon, 25 Jan 2020 00:00:00 GMT;path=/;";
        if (count > parseInt(blur_count)) {
            //交卷
            hideblurCount();
            $(".examEndBtn").remove();
            saveExamFn();
            if (check_subsidiary(domain)) {
                $(".examEndJumpBtn").hide();
            }
            $("#numberCardDom").hide();
            $("#maskDiv").hide();
        } else {
            showblurCount();
            $("#blurCount .cont_main").text("已离开" + count + "次，超过" + blur_count + "次将自动交卷！");
            //关闭提示切换次数
            $(".closeBlurCount").click(function (e) {
                e.stopPropagation();
                e.preventDefault();
                hideblurCount();
                if (screenfull.enabled) {
                    screenfull.request();
                }
            });
        }
    }
}

//显示
function showblurCount() {
    $('#blurCount').modal({
        backdrop: "static",
        keyboard: false
    });
}

//关闭
function hideblurCount() {
    $('#blurCount').modal('hide');
}

//标记考题fn
function questionsMark(obj) {
    var num = $(obj).attr("num");
    var hasMarked = $(obj).hasClass("marked");
    if (!hasMarked) {
        $(obj).addClass("marked");
        $(obj).attr("title_tip", "取消标记");
        $(".iconBox").each(function (index, element) {
            var iconNum = $(this).attr("num");
            if (iconNum == num) {
                $(this).addClass("icon_answer_mark");
                $(this).parent("dd").addClass("s4");
                return;
            }
        });
    } else {
        $(obj).removeClass("marked");
        $(obj).attr("title_tip", "标记试题");
        $(".iconBox").each(function (index, element) {
            var iconNum = $(this).attr("num");
            if (iconNum == num) {
                $(this).removeClass("icon_answer_mark");
                $(this).parent("dd").removeClass("s4");
                return;
            }
        });
    }
}

//交卷提交后台异步保存fn
function saveExamFn() {
    //清除考试时间
    clearInterval(timeDownInterval);
    // 若开启拍照防作弊功能，则在交卷之前再拍一次，确保至少有一张
    if (capture == 1) {
        capture_id = $(".questionsContent:last").attr("questionsId");
        webcam.capture(0);
    }

    $("#maskDiv").show();
    $("#maskDiv img").show();
    //判断是否有未保存的考题
    $.each(answered_multi_all, function (index, value) {
        var unsaved_answer = value;
        var has_save = false;
        $.each(answered_all, function (index, value) {
            if (value.test_id == unsaved_answer.test_id) {
                value.test_ans = unsaved_answer.test_ans;
                has_save = true;
                return;
            }
        })
        if (!has_save) {
            answered_all.push(unsaved_answer);
        }
    });
    if (answered_all.length > 0) {
        saveQuestionsCatch("", "", true);
        return;
    } else {
        commit_exam();
    }
}

function commit_exam() {
    window.location.href = "/exam/exam_ending?examInfoId=" + exam_info_id + "&examResultsId=" + exam_results_id;
}

//检查是否是慧科教育的子公司
function check_subsidiary(company_domain) {
    domain_split = company_domain.split(".");
    if (domain_split[1] == "gaoxiaobang" && domain_split[2] == "com") {
        return true;
    }
    else {
        return false;
    }
}

//往慧科教育推送考试信息
var num = 0;
var post_exam_info = function (score) {
    var company_id = domain.split(".")[0];
    var admin_id = "0";
    var examinee_id = examinee_user_name.split("@")[0];

    var post_data = {
        "company_id": company_id,
        "exam_id": exam_info_id,
        "admin_id": admin_id,
        "examinee_id": examinee_id,
        "score": score,
        "result_id": exam_results_id,
        "source": "exam"
    };
    var status;
    $.ajax({
        type: "post",
        url: "http://restful.gaoxiaobang.com/exam/score/api?jwt=" + jwt,
        //url:"http://101.200.196.195:9080/gxb-web/exam/score/api?jwt="+jwt,
        dataType: "json",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(post_data),
        success: function (msg) {
            if (msg.status == 0) {
                //alert("推送成功");
            }
            else {
                alert("推送失败，请联系管理员");
                num = num + 1;
                if (num < 3) {
                    post_exam_info(score);
                }
            }
        }
    })
    if (status == 1) {
        return true;
    } else {
        return false;
    }
}

//验证是否有未答试题
function checkForm() {
    var hasCheck = true;
    $(".questionsContent").each(function (index, element) {
        if ($(this).attr("hasAnswered") != "true") {
            hasCheck = false;
            return false;
        }
    });
    return hasCheck;
}

//延时答题后提交后台异步保存fn
function saveAnswerFn_timeout(overExam) {
    //答案数组
    if (overExam) {
        exam_test_list_json = answered_all;
    }
    else {
        var exam_test_list_json = answered_multi_all;
        answered_multi_all = [];
    }
    exam_test_list = JSON.stringify(exam_test_list_json);
    //将分割URI的&符号转义为十六进制序列
    exam_test_list = encodeURIComponent(exam_test_list);
    //时间戳
    var timeStamp = new Date();
    var dataForm = "examTestList=" + exam_test_list + "&timeStamp=" + timeStamp.getTime();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/exam/exam_start_ing_multi",
        data: dataForm,
        processData: false,
        success: function (msg) {
            if (msg.success == 'true') {
                if (overExam == true) {
                    answered_all = [];
                    commit_exam();
                }
            }
            else if (msg.success == "answered") {
                alert("您已交卷，请关闭页面");
            }
            else {
                alert("操作失败，请联系管理员！");
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $.each(exam_test_list_json, function (index, value) {
                var fail_answer = value;
                var has_save = false;
                $.each(answered_all, function (index, value) {
                    if (value.test_id == fail_answer.test_id) {
                        value.test_ans = fail_answer.test_ans;
                        has_save = true;
                        return;
                    }
                })
                if (!has_save) {
                    answered_all.push(fail_answer);
                }
            });
            asynSubTimeoutFn(exam_test_list_json);
        }
    });
}

//缓存已答未提交考题数据
function saveQuestionsCatch(questionsId, keyList, overExam, editor_blur) {
    //问答题blur保存答案
    if (editor_blur == true) {
        var questionsData = {
            "test_id": questionsId,
            "test_ans": keyList,
            "exam_results_id": exam_results_id,
            "exam_info_id": exam_info_id
        };
        answered_multi_all.push(questionsData);
        $("#numberCardDom a.questions_" + questionsId).removeClass("icon_answer_no").addClass("icon_answer_answered");
        $("#numberCardDom a.questions_" + questionsId).parent("dd").removeClass("s1").addClass("s2");
        $(".questionsContent").each(function (index, element) {
            if ($(this).attr("questionsId") == questionsId) {
                $(this).attr("hasAnswered", "true");
            }
        });
        saveAnswerFn_timeout();
        return;
    }
    //交卷操作时还有未保存的考题
    if (overExam == true) {
        saveAnswerFn_timeout(overExam);
        return;
    }
    var hasSave = false; //是否保存过
    $.each(answered_multi_all, function (index, value) {
        if (value.test_id == questionsId) {
            value.test_ans = keyList;
            hasSave = true;
            return;
        }
    });
    var questionsData = {
        "test_id": questionsId,
        "test_ans": keyList,
        "exam_results_id": exam_results_id,
        "exam_info_id": exam_info_id
    };
    if (!hasSave) {
        answered_multi_all.push(questionsData);
        $("#numberCardDom a.questions_" + questionsId).removeClass("icon_answer_no").addClass("icon_answer_answered");
        $("#numberCardDom a.questions_" + questionsId).parent("dd").removeClass("s1").addClass("s2");
        $(".questionsContent").each(function (index, element) {
            if ($(this).attr("questionsId") == questionsId) {
                $(this).attr("hasAnswered", "true");
            }
        });
    }
    ;
    if (answered_multi_all.length == answered_num) {
        saveAnswerFn_timeout();
    }
    ;
}

//异步提交答案超时FN
function asynSubTimeoutFn(data) {
    $.each(data, function (index, value) {
        var questionsId = value.testId;
        $("#numberCardDom a.questions_" + questionsId).removeClass("icon_answer_answered").addClass("icon_answer_no");
        $("#numberCardDom a.questions_" + questionsId).parent("dd").removeClass("s2").addClass("s1");
        $(".questionsContent").each(function (index, element) {
            if ($(this).attr("questionsId") == questionsId) {
                $(this).attr("hasAnswered", "false");
            }
        });
    });
    alert("由于您网络问题，导致已答的部分试题没有保存成功，请根据答题卡信息重新选择作答。");
}

//获取随机试题
function getRandom(min, max) {
    var random_num = Math.ceil((max - min + 1) / 10);//获取试题数目，按照1/10的比例，至少一题
    var random_arr = [];
    var random_json = {};
    for (var i = 0; i < random_num; i++) {
        var random_int = Math.floor(Math.random() * (max - min + 1)) + min;
        if (!random_json[random_int]) {
            random_json[random_int] = 0;
            random_arr.push(random_int);
        }
    }
    return random_json;
}

function saveImg(data) {
    var pos = 0, ctx = null, saveCB, image = [];
    var canvas = document.createElement("canvas");
    canvas.setAttribute('width', 320);
    canvas.setAttribute('height', 240);
    if (canvas.toDataURL) {
        ctx = canvas.getContext("2d");
        image = ctx.getImageData(0, 0, 320, 240);
        saveCB = function (data) {
            var col = data.split(";");
            var img = image;
            for (var i = 0; i < 320; i++) {
                var tmp = parseInt(col[i]);
                img.data[pos + 0] = (tmp >> 16) & 0xff;
                img.data[pos + 1] = (tmp >> 8) & 0xff;
                img.data[pos + 2] = tmp & 0xff;
                img.data[pos + 3] = 0xff;
                pos += 4;
            }
            if (pos >= 4 * 320 * 240) {
                ctx.putImageData(img, 0, 0);
                $("input[name=questionId]").val(capture_id);
                $("#captureForm").append("<textarea name='file'>" + canvas.toDataURL("image/png") + "</textarea>");
                $("#captureForm").submit();
                $("#captureForm textarea").remove();
                pos = 0;
            }
        };
    }
    return saveCB;
}



