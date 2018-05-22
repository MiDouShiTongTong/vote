<section class="content">

    <!-- search bar -->
    <?php
    $data = [
        'conditions_info' => [
            [
                'field' => 'question_title',
                'type' => 'text',
                'field_placeholder' => $this->lang->line('question_title')
            ]
        ],
    ];
    $this->load->view('admin/components/search_bar', $data);
    ?>

    <!-- operation -->
    <div class="to-data-action-container text-right mb-3">
        <div class="dropdown">
            <button class="btn btn-primary to-add-question"><?php echo $this->lang->line('add'); ?></button>
        </div>
    </div>

    <!-- data list -->
    <div class="data-container">
        <table class="table table-hover table-striped table-sort dataTable dtb">
            <thead>
            <tr>
                <th><?php echo $this->lang->line('seq'); ?></th>
                <th><?php echo $this->lang->line('question_title'); ?></th>
                <th><?php echo $this->lang->line('start_time'); ?></th>
                <th><?php echo $this->lang->line('end_time'); ?></th>
                <th><?php echo $this->lang->line('status'); ?></th>
                <th><?php echo $this->lang->line('updated_by'); ?></th>
                <th><?php echo $this->lang->line('updated_at'); ?></th>
                <th><?php echo $this->lang->line('operate'); ?></th>
            </tr>
            </thead>
            <tbody class="data">

            </tbody>
        </table>
    </div>

    <!-- data-action container -->
    <div class="data-action-container container-fluid">
        <div class="row">
            <div class="col-xs-12 p-0">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title data-action-title">
                            <!-- dynamic build -->
                        </h3>
                        <button type="button" class="close" data-target="close-data-action-container">&times;</button>
                    </div>
                    <div class="bg-white">
                        <div class="box-body">
                            <form action="" id="question-form" class="form-horizontal">

                                <div class="form-group">
                                    <label for="question-title" class="col-sm-2 col-form-label">
                                        <?php echo $this->lang->line('question_title'); ?>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="question-title" class="form-control" id="question-title">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="question-item" class="col-sm-2 col-form-label">
                                        <?php echo $this->lang->line('question_item'); ?>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="question-item-container">
                                            <!-- dynamic -->
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-info btn-block question-item-add" type="button" role="button">
                                                添加问题
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="" class="col-sm-2 col-form-label">
                                        有效时间
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" name="start-time" class="form-control start-time" id="" placeholder="<?php echo $this->lang->line('start_time'); ?>">
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" name="end-time" class="form-control end-time" id="" placeholder="<?php echo $this->lang->line('end_time'); ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status" class="col-sm-2 col-form-label">
                                        <?php echo $this->lang->line('status'); ?>
                                    </label>
                                    <div class="col-sm-10 form-checked-container">
                                        <?php
                                        if (!empty($status_bases)) {
                                            foreach ($status_bases as $_key => $status_base) {
                                        ?>
                                        <div class="form-checked form-checked-inline">
                                            <label class="form-checked-label">
                                                <input type="radio" name="status" class="minimal form-check-input" value="<?php echo $status_base->status_value; ?>">
                                                <span class="form-checked-label-desc"><?php echo $status_base->status_name; ?></span>
                                            </label>
                                        </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-info pull-right question-save"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- question detail modal -->
    <div class="modal question-detail-modal fade" data-backdrop="false" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span class="aria-hidden">&times;</span>
                    </button>
                    <h5 class="modal-title"><?php echo $this->lang->line('question_detail'); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="question-detail-container">
                                    <!-- dynamic -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- my js -->
    <script>

        $(function () {
            'use strict';
            // elem
            var elem = {
                dtb: $('.dtb'),
                dtbBody: $('.dtb tbody'),

                dataContainer: $('.data-container'),

                dataActionContainer: $('.data-action-container'),
                dataActionTitle: $('.data-action-title'),
                closeDataActionContainer: $('[data-target="close-data-action-container"]'),

                toAddQuestion: $('.to-add-question'),
                questionForm: $('#question-form'),
                saveQuestion: $('.question-save'),

                questionItemContainer: $('.question-item-container'),
                addQuestionItem: $('.question-item-add'),

                questionDetailModel: $('.question-detail-modal'),
                questionDetailContainer: $('.question-detail-container')
            };

            // request
            var request = {
                getQuestion: siteUrl + 'admin/features/get_question',
                saveQuestion: siteUrl + 'admin/features/save_question',
                getQuestionDetail: siteUrl + 'admin/features/get_question_detail'
            };

            // curr add type
            var flag = {
                dataActionType: undefined
            };

            // buf
            var buf = {
                initQuestionItemSize: 2,
                currIsMultipleIncrement: 0,
                questionId: null,
                delQuestionItemId: [],
                delQuestionItemOptionId: []
            };

            // html
            var html = {
                questionItem: function () {
                    return '<div class="card question-item mb-3">\
                            <div class="card-header question-item-display-toggle" role="button">\
                                <h5 class="mb-0">\
                                    问题 <span class="curr-question-item-counter"></span>\
                                </h5>\
                            </div>\
                            <div class="collapse in">\
                                <div class="card-block">\
                                    <div class="question-item-content">\
                                        <div class="question-item-title">\
                                            <div class="form-group mb-2">\
                                                <label for="question-item-title" class="col-xs-2 col-form-label">\
                                                    问题标题\
                                                </label>\
                                                <div class="col-sm-10">\
                                                    <input type="text" name="question-item-title" class="form-control" id="question-item-title">\
                                                </div>\
                                            </div>\
                                             <div class="form-group">\
                                                <label for="status" class="col-sm-2 col-form-label sr-only">\
                                                    单选还是多选\
                                                </label>\
                                                <div class="col-sm-10 col-xs-offset-2 form-checked-container">\
                                                    <div class="form-checked form-checked-inline">\
                                                        <label class="form-checked-label">\
                                                            <input type="radio" name="" class="minimal form-check-input is-multiple" value="0">\
                                                            <span class="form-checked-label-desc">单选</span>\
                                                        </label>\
                                                    </div>\
                                                    <div class="form-checked  form-checked-inline">\
                                                        <label class="form-checked-label">\
                                                            <input type="radio" name="" class="minimal form-check-input is-multiple" value="1">\
                                                            <span class="form-checked-label-desc">多选</span>\
                                                        </label>\
                                                    </div>\
                                                </div>\
                                            </div>\
                                        </div>\
                                        <div class="question-item-option-container">\
                                            <!-- dynamic -->\
                                        </div>\
                                        <div class="d-flex justify-content-end question-item-option-action-container">\
                                            <button class="btn btn-info add-question-item-option" role="button" type="button">\
                                                添加问题选项\
                                            </button>\
                                        </div>\
                                    </div>\
                                </div>\
                                <div class="card-footer d-flex justify-content-end">\
                                    <button class="btn btn-danger del-question-item" type="button" role="button">\
                                        删除问题\
                                    </button>\
                                </div>\
                            </div>\
                        </div>';
                },
                questionItemOption: function () {
                    return '<div class="form-group question-item-option">\
                                <label for="question-item-option" class="col-xs-2 col-form-label">\
                                    选项 <span class="curr-question-item-option-counter"></span>\
                                </label>\
                                <div class="col-sm-10">\
                                    <div class="input-group">\
                                        <input type="text" name="question-item-option" class="form-control" id="question-item-option">\
                                        <div class="input-group-btn">\
                                            <button class="btn btn-danger del-question-item-option" type="button" role="button">\
                                                <i class="fa fa-trash"></i>\
                                            </button>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>';
                },
                questionDetail: function () {
                    return '<div class="question"detail->\
                                <div class="question-detail-desc-list-group container-fluid">\
                                    <div class="question-detail-desc-list-group-item row mb-4">\
                                        <div class="col-xs-1 px-0">投票标题</div>\
                                            <div class="col-xs-11">\
                                                <p>\
                                                    <span class="question-detail-title"></span>\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class="question-detail-desc-list-group-item row mb-4">\
                                            <div class="col-xs-1 px-0">有效时间</div>\
                                            <div class="col-xs-11 text-muted">\
                                                <p>\
                                                    <span class="question-detail-start-time">default</span> 至\
                                                </p>\
                                                <p>\
                                                    <span class="question-detail-end-time">default</span>\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class="question-detail-desc-list-group-item row mb-4">\
                                            <div class="col-xs-1 px-0">投票人数</div>\
                                            <div class="col-xs-11 text-muted">\
                                            <p>\
                                                <span class="question-detail-counter">default</span>人\
                                            </p>\
                                        </div>\
                                    </div>\
                                </div>\
                            <div class="question-detail-item-container"></div>\
                        </div>';
                },
                questionDetailItem: function () {
                    return '<div class="question-detail-item mb-5">\
                                <p class="question-detail-item-title-container">\
                                    <span class="question-detail-item-title">default</span>\
                                </p>\
                                <div class="question-detail-item-option-container">\
                                    \
                                </div>\
                            </div>';
                },
                questionDetailItemOption: function () {
                    return '<div class="question-detail-item-option">\
                                <p>\
                                    <span class="question-detail-item-option-value">default</span>\
                                </p>\
                                <div class="question-detail-item-option-content">\
                                    <div class="question-detail-item-option-content-bar col-xs-10 px-0">\
                                        <div class="question-detail-item-option-content-bar-inner" ></div>\
                                    </div>\
                                    <div class="question-detail-item-option-content-counter-container col-xs-2 px-0">\
                                        <div class="col-xs-6 px-0">\
                                            <span class="question-detail-item-option-counter">default</span>票\
                                        </div>\
                                        <div class="col-xs-6 px-0">\
                                            <span class="question-detail-item-option-percentage">default</span>%\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>';
                }
            };

            // check
            var check = {
                questionForm: function () {
                    elem.questionForm.validate({
                        rules: {
                            'question-title': {
                                required: true,
                                maxlength: 20
                            },
                            'start-time': {
                                required: true
                            },
                            'end-time': {
                                required: true
                            }
                        },
                        messages: {
                            'question-title': {
                                required: '<?php echo $this->lang->line('wish_name') . $this->lang->line('form_validate_required'); ?>',
                                maxlength: '<?php echo $this->lang->line('wish_name') . $this->lang->line('form_validate_max_length'); ?>{0}'
                            },
                            'start-time': {
                                required: '<?php echo $this->lang->line('start_time') . $this->lang->line('form_validate_required'); ?>'
                            },
                            'end-time': {
                                required: '<?php echo $this->lang->line('end_time') . $this->lang->line('form_validate_required'); ?>'
                            }
                        }
                    });
                }
            };

            // event
            var event = {
                initPage: function () {
                    elem.dtb = elem.dtb.DataTable({
                        ajax: {
                            url: request.getQuestion,
                            type: 'POST'
                        },
                        columns: [
                            {
                                data: 'RowId'
                            },
                            {
                                data: 'question_title'
                            },
                            {
                                data: 'start_time'
                            },
                            {
                                data: 'end_time'
                            },
                            {
                                data: 'status'
                            },
                            {
                                data: 'updated_by'
                            },
                            {
                                data: 'updated_at'
                            },
                            {
                                data: 'DT_RowData',
                                orderable: false,
                                render: function (data, type, row, meta) {
                                    return '<button class="btn btn-primary btn-sm to-question-detail">\
                                                <i class="fa fa-eye"></i>\
                                            </button>\
                                            <button class="btn btn-primary btn-sm to-edit-question">\
                                                <i class="fa fa-pencil-alt"></i>\
                                            </button>\
                                            </button>\
                                            <button class="btn btn-primary btn-sm to-del-question">\
                                                <i class="fa fa-trash"></i>\
                                            </button>';
                                }
                            }
                        ]
                    }).on('preXhr.dt', function () {

                    }).on('xhr.dt', function () {

                    });

                    // Datemask
                    $('.start-time, .end-time').flatpickr({

                    });
                },
                initEvent: function () {
                    // iCheck 样式初始化
                    cEvent.initICheckStyle();

                    // 关闭新增修改容器
                    elem.closeDataActionContainer.click(cEvent.closeDataActionContainer);

                    // 添加投票
                    elem.toAddQuestion.click(event.toAddQuestion);

                    // 修改投票
                    elem.dtbBody.on('click', '.to-edit-question', event.toEditQuestion);

                    // 新增修改执行事件
                    elem.saveQuestion.click(event.saveQuestion);

                    // 删除事件
                    elem.dtbBody.on('click', '.to-del-question', event.toDelQuestion);

                    // 新增问题
                    elem.addQuestionItem.on('click', event.addQuestionItem);

                    // 切换投票选项显示
                    elem.dataActionContainer.on('click', '.question-item-display-toggle', event.questionItemContentToggle);

                    // 删除问题
                    elem.dataActionContainer.on('click', '.del-question-item', event.delQuestionItem);

                    // 新增问题选项
                    elem.dataActionContainer.on('click', '.add-question-item-option', event.addQuestionItemOption);

                    // 删除问题选项
                    elem.dataActionContainer.on('click', '.del-question-item-option', event.delQuestionItemOption);

                    // 查看投票调查详情
                    elem.dataContainer.on('click', '.to-question-detail', event.toQuestionDetail);
                },
                initDataActionContainer: function (actionType) {
                    switch (actionType) {
                        case 'add':
                            elem.dataActionTitle.html(lang.add);
                            break;
                        case 'edit':
                            elem.dataActionTitle.html(lang.edit);
                            break;
                    }
                    // 取消 ICheck 插件样式 和 取消选中
                    cVal.setUnCheck('status');
                    // 清空表单
                    elem.questionForm.get(0).reset();
                    // 清空问题
                    elem.questionItemContainer.empty();
                    // 清空删除数据
                    buf.delQuestionItemId = [];
                    buf.delQuestionItemOptionId = [];
                    // 影藏列表 显示操作
                    cEvent.showDataActionContainer();
                },
                toAddQuestion: function () {
                    flag.dataActionType = 'add';

                    // 初始化设置 清空表单值
                    event.initDataActionContainer('add');

                    // 初始化html内容
                    elem.addQuestionItem.trigger('click');

                    // 设置状态字段 为1
                    cVal.setVal('radio', 'status', 1);
                },
                toEditQuestion: function () {
                    var _this = $(this);
                    flag.dataActionType = 'edit';

                    // 初始化设置 清空表单值
                    event.initDataActionContainer('edit');

                    // 当前行数据 未解析
                    var question = elem.dtb.row(_this.parents('tr')).data().DT_RowData;
                    // 解析后的数据 例如 时间戳 已经 格式化
                    var questionParse = elem.dtb.row(_this.parents('tr')).data();

                    buf.questionId = question.question_id;

                    // 初始化html内容
                    $.ajax({
                        url: request.getQuestionDetail,
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            questionId: question.question_id
                        },
                        beforeSend: function () {
//                            tool.showLoad();
                        },
                        success: function (data) {
                            tool.closeLoad();
                            if (data.errCode == 0) {
                                var questionDb = data.question;
                                cVal.setVal('text', 'question-title', questionDb.question_title);
                                cVal.setVal('text', 'start-time', questionParse.start_time);
                                cVal.setVal('text', 'end-time', questionParse.end_time);
                                cVal.setVal('radio', 'status', questionDb.status);

                                // structure question item html
                                $.each(questionDb.question_items, function (index, questionItemVal) {
                                    var questionItem = $(html.questionItem());
                                    // edit config
                                    questionItem.data('edit-question-item-id', questionItemVal.question_item_id);

                                    var questionItemCounter = $('.question-item').length + 1;
                                    questionItem.find('.curr-question-item-counter').text(questionItemCounter);
                                    // set val
                                    questionItem.find('input[name="question-item-title"]').val(questionItemVal.question_item_title);
                                    elem.questionItemContainer.append(questionItem);
                                    cEvent.initICheckStyle();
                                    // set val
                                    questionItem.find('.is-multiple').attr('name', 'is-multiple' + questionItemCounter).filter('[value="' + questionItemVal.is_multiple + '"]').iCheck('check');
                                    // structure question item option html
                                    $.each(questionItemVal.question_item_options, function (index, questionItemOptionVal) {
                                        var questionItemOptionContainer = questionItem.find('.question-item-option-container');
                                        var questionItemOption = $(html.questionItemOption());
                                        // edit config
                                        questionItemOption.data('edit-question-item-option-id', questionItemOptionVal.question_item_option_id);

                                        questionItemOption.find('.curr-question-item-option-counter').text(questionItemOptionContainer.find('.question-item-option').length + 1);
                                        // set val
                                        questionItemOption.find('input[name="question-item-option"]').val(questionItemOptionVal.value);
                                        questionItemOptionContainer.append(questionItemOption);
                                    });
                                });
                            } else {
                                tool.showToast(data.errMsg);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                },
                saveQuestion: function () {
                    // 表单验证
                    check.questionForm();
                    if (!elem.questionForm.valid()) return false;

                    var question = {};
                    question.dataActionType = flag.dataActionType;
                    question.questionTitle = cVal.getVal('text', 'question-title');
                    question.startTime = new Date(cVal.getVal('text', 'start-time')).getTime() / 1000;
                    question.endTime = new Date(cVal.getVal('text', 'end-time')).getTime() / 1000;
                    question.status = cVal.getVal('radio', 'status');

                    if (question.startTime > question.endTime) {
                        tool.showToast('结束时间不得小于开始时间');
                        return false;
                    }

                    var questionItems = [];
                    // 获取投票问题
                    $.each(elem.questionItemContainer.find('.question-item'), function () {
                        var _this = $(this);
                        var questionItem = {};

                        // editQuestionItemId
                        var editQuestionItemId = _this.data('edit-question-item-id');
                        if (editQuestionItemId != undefined) {
                            questionItem.editQuestionItemId = editQuestionItemId;
                        }

                        questionItem.questionItemTitle = $.trim(_this.find('input[name="question-item-title"]').val());
                        questionItem.isMultiple = $.trim(_this.find('.is-multiple:checked').val());

                        var questionItemOptions = [];
                        $.each(_this.find('.question-item-option'), function () {
                            var _this = $(this);
                            var questionItemOption = {};

                            // editItemOptionId
                            var editQuestionItemOptionId = _this.data('edit-question-item-option-id');
                            if (editQuestionItemOptionId != undefined) {
                                questionItemOption.editQuestionItemOptionId = editQuestionItemOptionId;
                            }

                            questionItemOption.value = $.trim(_this.find('input[name="question-item-option"]').val());
                            questionItemOptions.push(questionItemOption);
                        });

                        questionItem.questionItemOptions = questionItemOptions;
                        questionItems.push(questionItem);
                    });

                    question.questionItems = questionItems;

                    question.delQuestionItemIds = buf.delQuestionItemId.join(',');
                    question.delQuestionItemOptionIds = buf.delQuestionItemOptionId.join(',');

                    switch (flag.dataActionType) {
                        case 'add':

                            break;
                        case 'edit':
                            question.questionId = buf.questionId;
                            break;
                    }

                    $.ajax({
                        url: request.saveQuestion,
                        data: {
                            question: question
                        },
                        dataType: 'json',
                        type: 'POST',
                        beforeSend: function () {
                            tool.showLoad();
                        },
                        success: function (data) {
                            // 关闭加载
                            tool.closeLoad();
                            if (data.errCode == 0) {
                                event.dataActionSuccess();
                            } else {
                                tool.showError(data.errMsg);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                },
                toDelQuestion: function () {
                    var _this = $(this);
                    var question = elem.dtb.row(_this.parents('tr')).data().DT_RowData;
                    layer.confirm('<?php echo $this->lang->line('action_delete_tooltip'); ?>', {
                        btn: [
                            '<?php echo $this->lang->line('confirm'); ?>',
                            '<?php echo $this->lang->line('cancel'); ?>'
                        ] //按钮
                    }, function (index) {
                        layer.close(index);
                        flag.dataActionType = 'del';
                        var questionData = {
                            dataActionType: flag.dataActionType,
                            questionId: question.question_id
                        };
                        $.ajax({
                            url: request.saveQuestion,
                            type: 'post',
                            data: {
                                question: questionData
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                tool.showLoad();
                            },
                            success: function (data) {
                                // 关闭加载
                                tool.closeLoad();
                                if (data.errCode == 0) {
                                    event.dataActionSuccess();
                                } else {
                                    tool.showError(data.errMsg);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log(xhr);
                                console.log(status);
                                console.log(error);
                            }
                        });
                    }, function () {

                    });
                },
                questionItemContentToggle: function () {
                    var _this = $(this);
                    _this.next().slideToggle();
                },
                addQuestionItem: function () {
                    var questionItem = $(html.questionItem());
                    // 问题后面的数字
                    var questionItemCounter = $('.question-item').length + 1;
                    questionItem.find('.curr-question-item-counter').text(questionItemCounter);

                    // 设置选择字段默认为1
                    questionItem.find('.is-multiple').attr('name', 'is-multiple-' + questionItemCounter);
                    questionItem.find('.is-multiple[value="1"]').iCheck('check');

                    var questionItemOptionContainer = questionItem.find('.question-item-option-container');
                    for (var i = 0; i < buf.initQuestionItemSize; i++) {
                        var questionItemOption = $(html.questionItemOption());
                        // 问题选项后面的数字
                        questionItemOption.find('.curr-question-item-option-counter').text(questionItemOptionContainer.find('.question-item-option').length + 1);
                        questionItemOptionContainer.append(questionItemOption);
                    }

                    elem.questionItemContainer.append(questionItem);

                    // iCheck
                    cEvent.initICheckStyle();
                },
                delQuestionItem: function () {
                    var _this = $(this);
                    // 不得小于1个问题
                    var questionItemContainer = _this.parents('.question-item-container');
                    if (questionItemContainer.find('.question-item').length <= 1) {
                        tool.showToast('问题最少1个');
                        return;
                    }

                    // 当前删除的问题
                    var currQuestionItemDel = _this.parents('.question-item');

                    // 数据库是否已存在 已存在添加被删除的id
                    var editQuestionItemId = currQuestionItemDel.data('edit-question-item-id');
                    if (editQuestionItemId != undefined) {
                        buf.delQuestionItemId.push(editQuestionItemId);
                    }

                    currQuestionItemDel.remove();

                    // 标识数字刷新
                    var questionItems = questionItemContainer.find('.question-item');
                    $.each(questionItems, function (index, value) {
                        var _this = $(this);
                        _this.find('.curr-question-item-counter').text(index + 1);
                    });
                },
                addQuestionItemOption: function () {
                    var _this = $(this);
                    var questionItemOptionContainer = _this.parent().prev();
                    var questionItemOption = $(html.questionItemOption());
                    questionItemOption.find('.curr-question-item-option-counter').text(questionItemOptionContainer.find('.question-item-option').length + 1);
                    questionItemOptionContainer.append(questionItemOption);
                },
                delQuestionItemOption: function () {
                    var _this = $(this);
                    // 不得小于2个选项
                    var questionItemOptionContainer = _this.parents('.question-item-option-container');
                    if (questionItemOptionContainer.find('.question-item-option').length <= 2) {
                        tool.showToast('问题最少2个选项');
                        return;
                    }

                    // 当前删除的问题选项
                    var questionItemOption = _this.parents('.question-item-option');
                    // 数据库是否存在 已存在添加被删除的id
                    var editQuestionItemOptionId = questionItemOption.data('edit-question-item-option-id');
                    if (editQuestionItemOptionId != undefined) {
                        buf.delQuestionItemOptionId.push(editQuestionItemOptionId);
                    }

                    questionItemOption.remove();

                    // 刷新选项后面的数字
                    var questionItemOptions = questionItemOptionContainer.find('.question-item-option');
                    // 遍历标签
                    $.each(questionItemOptions, function (index, value) {
                        var _this = $(this);
                        _this.find('.curr-question-item-option-counter').text(index + 1);
                    });
                },
                dataActionSuccess: function () {
                    // 关闭添加的容器
                    cEvent.closeDataActionContainer();
                    // 重新加载数据
                    elem.dtb.draw(false);
                },
                closeDataActionContainer: function () {
                    elem.dataActionContainer.hide();
                    elem.dataContainer.show();
                },
                toQuestionDetail: function () {
                    var _this = $(this);
                    var question = elem.dtb.row(_this.parents('tr')).data().DT_RowData;
                    $.ajax({
                        url: request.getQuestionDetail,
                        type: 'POST',
                        data: {
                            questionId: question.question_id
                        },
                        dataType: 'JSON',
                        beforeSend: function () {
                            tool.showLoad();
                        },
                        success: function (data) {
                            tool.closeLoad();
                            if (data.errCode == 0) {
                                var question = data.question;
                                // 投票详情
                                var questionDetail = $(html.questionDetail());
                                // set
                                questionDetail.find('.question-detail-title').text(question.question_title);
                                questionDetail.find('.question-detail-start-time').text(question.start_time);
                                questionDetail.find('.question-detail-end-time').text(question.end_time);
                                questionDetail.find('.question-detail-counter').text(question.question_collect_counter);

                                // 投票问题
                                var questionDetailItemAutoIncrement = 0;
                                var questionDetailItemContainer = questionDetail.find('.question-detail-item-container');
                                $.each(question.question_items, function (index, questionItem) {
                                    var questionDetailItem = $(html.questionDetailItem());

                                    // set
                                    var questionSelectType = '';
                                    if (questionItem.is_multiple == 1) {
                                        questionSelectType = '(多选)';
                                    } else {
                                        questionSelectType = '(单选)';
                                    }
                                    questionDetailItem.find('.question-detail-item-title').text(++questionDetailItemAutoIncrement + '. ' + questionItem.question_item_title + ' ' + questionSelectType);

                                    // 投票选项
                                    var questionDetailItemOptionContainer = questionDetailItem.find('.question-detail-item-option-container');
                                    $.each(questionItem.question_item_options, function (index2, questionItemOption) {
                                        var questionDetailItemOption = $(html.questionDetailItemOption());

                                        // set
                                        questionDetailItemOption.find('.question-detail-item-option-value').text(questionItemOption.value);
                                        questionDetailItemOption.find('.question-detail-item-option-content-bar-inner').animate({
                                            'width': questionItemOption.question_item_option_percentage + '%'
                                        });
                                        questionDetailItemOption.find('.question-detail-item-option-counter').text(questionItemOption.question_item_option_counter);
                                        questionDetailItemOption.find('.question-detail-item-option-percentage').text(questionItemOption.question_item_option_percentage);

                                        // append itemOption
                                        questionDetailItemOptionContainer.append(questionDetailItemOption);
                                    });

                                    // append item
                                    questionDetailItemContainer.append(questionDetailItem);
                                });

                                elem.questionDetailContainer.empty().append(questionDetail);

                                elem.questionDetailModel.modal('show');
                            } else {
                                tool.showToast(data.errMsg);
                            }
                        },
                        error: function (xhr, status ,error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                }
            };

            // 页面初始化
            event.initPage();

            // 刷新事件
            event.initEvent();
        });

    </script>
</section>
