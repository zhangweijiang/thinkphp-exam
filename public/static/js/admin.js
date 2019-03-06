(function () {


    /* ---------------------------------- 页面布局 --------------------------------------------- */
    // 后台菜单
    var menu = $('#admin-menu');

    // 后台主体内容部分
    var contentBody = $('.content-body');

    // 后台菜单点击事件
    menu.on('click', '.submenu > a', function (e) {
        e.preventDefault();
        var li = $(this).parents('li');
        var submenu = $(this).siblings('ul');
        var submenus = $('#admin-menu li.submenu ul');
        var submenus_parents = $('#admin-menu li.submenu');
        if (li.hasClass('open')) {
            submenu.slideUp();
            li.removeClass('open');
        } else {
            submenus.slideUp();
            submenu.slideDown();
            submenus_parents.removeClass('open');
            li.addClass('open');
        }
    });


    // 后台菜单及内容主体部分，绑定自定义滚动条（使用slimScroll插件，配置参数为高度、滚动条透明度、是否始终显示）
    menu.slimScroll({
        height: "100%", railOpacity: .9, alwaysVisible: !1
    });
    contentBody.slimScroll({
        height: "100%", railOpacity: .9, alwaysVisible: !1
    });

    /* -------------------------------- 试卷添加/修改 ---------------------------------------- */
    var questionListWrapper = $('.question-list-wrapper');

    // 上一步
    $('.ui-step-prev').on('click', function () {
        var step = $('.ui-step li.active'),
            stepPane = $('.ui-step-pane.active');
        step.removeClass('active').prev().addClass('active');
        stepPane.removeClass('active').prev().addClass('active');
    });

    // 下一步
    $('.ui-step-next').on('click', function () {
        var step = $('.ui-step li.active'),
            stepPane = $('.ui-step-pane.active');
        step.removeClass('active').next().addClass('active');
        stepPane.removeClass('active').next().addClass('active');
    });

    // 添加试题
    $('.btn-add-title').on('click', function () {
        $('.btn-add-title').hide();
        $('.addQuestionWrapper').addClass('active').html(questionEditTpl());
    });

    // 选择题型
    $(document).on('change', '.questionForm .question-type', function () {
        var val = $(this).val();
        if (val !== null) {
            $(this).siblings('.question-setting-wrapper').find('.question-type-pane.active').removeClass('active');
            $(this).siblings('.question-setting-wrapper').find('.question-type-pane[data-type="' + val + '"]').addClass('active');
        }
    });

    // 判断题选择初始化
    $(document).on('click', '.questionForm .trueOrFalse', function () {
        $(this).siblings('input[type=radio]').trigger('click');
    });

    // 编辑试题
    questionListWrapper.on('click', '.question-wrapper a.edit', function (e) {
        e.preventDefault();
        var that = $(this);
        $.ajax({
            url: 'fine.json',
            type: 'post',
            data: {id: '123'}
        }).done(function (response) {
            if (response.status) {
                var questionWrapper = that.parent().parent();
                that.parent().hide();
                questionWrapper.append(questionEditTpl());
                that.parent().siblings('.questionForm').find('.question-type').val(response.data.type).trigger('change');
                var html = "";
                // 试题内容
                switch (response.data.type) {
                    case 1:
                        $.each(response.data.options, function (index, item) {
                            html += '       <div class="question-option">';
                            html += '           <input type="radio" name="trueOrFalse" title="判断题选项"' + (item.isAnswer === true ? 'checked' : '') + '>';
                            html += '           <label class="label trueOrFalse">' + item.text + '</label>';
                            html += '       </div>';
                        });
                        break;
                    case 2:
                        $.each(response.data.options, function (index, item) {
                            html += '       <div class="question-option">';
                            html += '            <input type="radio" name="radio" title="单选题选项"' + (item.isAnswer === true ? 'checked' : '') + '>';
                            html += '            <input type="text" title="单选题选项内容" value="' + item.text + '">';
                            html += '       </div>';
                        });
                        html += '       <a class="btn btn-primary add-option" href="javascript:"><i class="icon-plus"></i></a>';
                        break;
                    case 3:
                        $.each(response.data.options, function (index, item) {
                            html += '       <div class="question-option">';
                            html += '             <input type="checkbox" name="checkbox" title="复选题选项" ' + (item.isAnswer === true ? 'checked' : '') + '>';
                            html += '             <input type="text" title="复选题选项内容" value="' + item.text + '">';
                            html += '       </div>';
                        });
                        html += '       <a class="btn btn-primary add-option" href="javascript:"><i class="icon-plus"></i></a>';
                        break;
                    case 4:
                        break;
                    case 5:
                        break;
                    default:
                        break;
                }
                that.parent().siblings('.questionForm').find('.question-type-pane[data-type="' + response.data.type + '"]').html(html);
            } else {
                console.error('无数据');
            }
        }).fail(function () {
            console.error('数据获取异常');
        });
    });

    // 删除试题
    questionListWrapper.on('click', '.question-wrapper a.remove', function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });

    // 上移试题
    questionListWrapper.on('click', '.question-wrapper a.up', function (e) {
        e.preventDefault();
        if ($(this).parent().parent().prevAll().length > 0) {
//                $(this).parent().prev().data('order', $(this).parent().data('order'));
//                $(this).parent().data('order', $(this).parent().data('order') - 1);
            $(this).parent().parent().prev().before($(this).parent().parent());
        }
    });

    // 下移试题
    questionListWrapper.on('click', '.question-wrapper a.down', function (e) {
        e.preventDefault();
        if ($(this).parent().parent().nextAll().length > 0) {
//                $(this).parent().next().data('order', $(this).parent().data('order'));
//                $(this).parent().data('order', $(this).parent().data('order') + 1);
            $(this).parent().parent().next().after($(this).parent().parent());
        }
    });

    // 添加选项（仅单选题及多选题需要）
    $(document).on('click', '.question-type-pane a.add-option', function () {
        var parent = $(this).parents('.question-type-pane'),
            parentType = $(parent).data('type');
        var html = "";
        html += "<div class='question-option'>";
        if (parentType === 2) {
            html += "<input type='radio' name='radio' placeholder='单选题选项'>";
            html += "<input type='text' class='form-control' name='radioText'  placeholder='单选题选项内容'>";
        } else if (parentType === 3) {
            html += "<input type='checkbox' name='checkbox' placeholder='复选题选项'>";
            html += "<input type='text' class='form-control' name='checkboxText' placeholder='复选题选项内容'>";
        } else if(parentType === 4){
            html += '<input class="form-control" type="text" name="blank" placeholder="填空内容">';
        } else  {
            console.error('不允许该部分添加选项！');
            return false;
        }
        html += "</div>";
        $(this).before(html);
    });

    // 保存新增试题
    $(document).on('click', '.addQuestionWrapper .btn-save-title', function () {
        // TODO:ajax发送请求，新增该试题，新增成功返回模拟数据格式的数据并将数据拼接进元素内容
        var that = $(this);
        $.ajax({
            url: 'fine.json',
            type: 'post',
            data: {id: '123'}
        }).done(function (response) {
            if (response.status) {
                var str = questionTpl(response.data);
                questionListWrapper.append(str);
                that.parent().remove();
//                    resetQuestionForm();
                $('.btn-add-title').show();
                $('.addQuestionWrapper').removeClass('active');
            } else {
                console.error('无数据');
            }
        }).fail(function () {
            console.error('数据获取异常');
        });

    });

    // 保存编辑试题
    questionListWrapper.on('click', '.btn-save-title', function () {
        // TODO:ajax发送请求，保存该试题，保存成功返回模拟数据格式的数据并将数据拼接进元素内容
        var that = $(this);
        $.ajax({
            url: 'fine.json',
            type: 'post',
            data: {id: '123'}
        }).done(function (response) {
            if (response.status) {
                var str = questionTpl(response.data);
                //                    resetQuestionForm();
                that.parents('.question-wrapper').before(str).remove();
            } else {
                console.error('无数据');
            }
        }).fail(function () {
            console.error('数据获取异常');
        });
    });

    // 重置新建试题表单
    function resetQuestionForm() {
        $('.addQuestionWrapper .question-type').val('1').trigger('change');
        $('.question-type-pane[data-type="1"]').find('input[type=radio]').eq(0).trigger('click');
        $('.question-type-pane[data-type="2"]').find('input[type=radio]').eq(0).trigger('click');
        $('.question-type-pane[data-type="3"]').find('input[type=checkbox]').prop('checked', false);
        $('.addQuestionWrapper input,.addQuestionWrapper textarea').val('');
    }

    // 试题模板
    function questionTpl(data) {
        if (data !== null && data !== undefined && data !== "") {
            var html = "";
            html += '<div class="question-wrapper" data-id="' + data.id + '">';
            html += '   <div class="question-inner">';
            html += '       <p class="question-title">' + data.title + '</p>';
            html += '       <ul class="option">';
            $.each(data.options, function (index, item) {
                html += '           <li class="' + (item.isAnswer === true ? 'selected' : '') + '">' + item.text + '</li>'
            });
            html += '       </ul>';
            html += '       <p class="question-answer">答案：' + data.answerText + '</p>';
            html += '       <p class="question-analysis">解析：' + data.analysis + '</p>';
            html += '       <div class="question-score-wrapper">';
            html += '           <input type="number" name="score" value="' + data.score + '" title="试题分数">分';
            html += '       </div>';
            html += '       <a class="edit" href="javascript:"><i class="icon-edit"></i>编辑</a>';
            html += '       <a class="remove" href="javascript:"><i class="icon-trash"></i>删除</a>';
            html += '       <a class="up" href="javascript:"><i class="icon-chevron-up"></i>上移</a>';
            html += '       <a class="down" href="javascript:"><i class="icon-chevron-down"></i>下移</a>';
            html += '   </div>';
            html += '</div>';
            return html;
        } else {
            console.error('试题模板数据不能为空！');
        }
    }

    // 试题编辑模板
    function questionEditTpl() {
        var html = "";
        html += '<div class="questionForm">';
        html += '<select class="question-type" style="width: 200px;" title="题型"><option value="1" selected>判断题</option><option value="2">单选题</option><option value="3">多选题</option><option value="4">填空题</option><option value="5">简答题</option></select>';
        html += '<div class="question-setting-wrapper">';
        html += '   <div class="descPane"></div>';
        html += '   <div class="question-type-pane active" data-type="1">';
        html += '       <div class="question-option">';
        html += '           <input type="radio" name="trueOrFalse" title="判断题选项" checked>';
        html += '           <label class="label trueOrFalse">正确</label>';
        html += '       </div>';
        html += '       <div class="question-option">';
        html += '           <input type="radio" name="trueOrFalse" title="判断题选项">';
        html += '           <label class="label trueOrFalse">错误</label>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="question-type-pane" data-type="2">';
        html += '       <div class="question-option">';
        html += '            <input type="radio" name="radio" title="单选题选项" checked>';
        html += '            <input type="text" title="单选题选项内容">';
        html += '       </div>';
        html += '       <div class="question-option">';
        html += '             <input type="radio" name="radio" title="单选题选项">';
        html += '             <input type="text" title="单选题选项内容">';
        html += '       </div>';
        html += '       <div class="question-option">';
        html += '             <input type="radio" name="radio" title="单选题选项">';
        html += '             <input type="text" title="单选题选项内容">';
        html += '       </div>';
        html += '       <div class="question-option">';
        html += '             <input type="radio" name="radio" title="单选题选项">';
        html += '             <input type="text" title="单选题选项内容">';
        html += '       </div>';
        html += '       <a class="btn btn-primary add-option" href="javascript:"><i class="icon-plus"></i></a>';
        html += '   </div>';
        html += '   <div class="question-type-pane" data-type="3">';
        html += '       <div class="question-option">';
        html += '             <input type="checkbox" name="checkbox" title="复选题选项">';
        html += '             <input type="text" title="复选题选项内容">';
        html += '       </div>';
        html += '       <div class="question-option">';
        html += '             <input type="checkbox" name="checkbox" title="复选题选项">';
        html += '             <input type="text" title="复选题选项内容">';
        html += '       </div>';
        html += '       <div class="question-option">';
        html += '              <input type="checkbox" name="checkbox" title="复选题选项">';
        html += '              <input type="text" title="复选题选项内容">';
        html += '       </div>';
        html += '       <div class="question-option">';
        html += '              <input type="checkbox" name="checkbox" title="复选题选项">';
        html += '              <input type="text" title="复选题选项内容">';
        html += '       </div>';
        html += '       <a class="btn btn-primary add-option" href="javascript:"><i class="icon-plus"></i></a>';
        html += '   </div>';
        html += '   <div class="question-type-pane" data-type="4">';
        html += '   </div>';
        html += '   <div class="question-type-pane" data-type="5">';
        html += '   </div>';
        html += '   <div class="analysisPane"></div>';
        html += '</div>';
        html += '<a class="btn btn-primary btn-save-title">保存</a>';
        html += '</div>';
        return html;
    }

    // 保存试卷
    $('#savePaper').on('click', function (e) {
        var selectors = $('.question-list-wrapper .question-wrapper');
        if (selectors.length > 0) {
            if ($('.addQuestionWrapper').hasClass('active')) {
                console.error('请先保存未完成的试题！'); // 提示框
                e.stopPropagation();
            } else {
                $.each(selectors, function (index, item) {
//                        console.log($(item).data('id'));
                });
                $('.ui-step-next').trigger('click');
            }
        } else {
            console.error('试卷试题不能少于1道！'); // 提示框
        }
    });

    /* ----------------------------------------- 试卷页 ------------------------------- */


})();