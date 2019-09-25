<section class="content">
    <!-- search bar container -->
    <?php
    $data = [
        'conditions_info' => [
            [
                'field' => 'ip_address',
                'type' => 'text',
                'field_placeholder' => $this->lang->line('ip_address')
            ]
        ],
    ];
    $this->load->view('admin/components/search_bar', $data);
    ?>

    <!-- data list -->
    <div class="data-container">
        <table class="table table-hover table-striped table-sort dataTable dtb question-collect-dtb">
            <thead>
            <tr>
                <th><?php echo $this->lang->line('seq'); ?></th>
                <th><?php echo $this->lang->line('commit_address'); ?></th>
                <th><?php echo $this->lang->line('question_title'); ?></th>
                <th><?php echo $this->lang->line('created_at'); ?></th>
                <th><?php echo $this->lang->line('operate'); ?></th>
            </tr>
            </thead>
            <tbody class="data">

            </tbody>
        </table>
    </div>

    <!-- question collect detail modal -->
    <div class="modal question-collect-detail-modal fade" data-backdrop="false" tabindex="-1">
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
                                <div class="question-collect-detail-container">
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
    <section class="my-js">
        <script type="text/javascript">
            $(function () {
                'use strict';

                // elem
                var elem = {
                    questionCollectDtb: $('.question-collect-dtb'),
                    questionCollectDetailModal: $('.question-collect-detail-modal'),
                    questionCollectDetailContainer: $('.question-collect-detail-container')
                };

                var flag = {
                    dataActionType: undefined
                };

                // html
                var html = {
                    questionDetail: function () {
                        return '<div class="question-detail">\
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
                                    <div class="question-detail-item-option-content-bar col-xs-12 px-0">\
                                        <div class="question-detail-item-option-content-bar-inner" style="width: 0%;"></div>\
                                    </div>\
                                </div>\
                            </div>';
                    }
                };

                // request
                var request = {
                    getQuestionCollect: siteUrl + 'admin/features/get_question_collect',
                    getQuestionDetail: siteUrl + 'admin/features/get_question_detail',
                    getQuestionDetailAndQuestionCollectDetail: siteUrl + 'admin/features/get_question_detail_and_question_collect_detail',
                    saveQuestionCollect: siteUrl + 'admin/features/save_question_collect'
                };

                // event
                var event = {
                    initPage: function () {
                        elem.questionCollectDtb = elem.questionCollectDtb.DataTable({
                            ajax: {
                                url: request.getQuestionCollect,
                                type: 'POST'
                            },
                            columns: [
                                {
                                    data: 'question_collect_id'
                                },
                                {
                                    data: 'ip_address'
                                },
                                {
                                    data: 'question_title'
                                },
                                {
                                    data: 'created_at'
                                },
                                {
                                    data: 'DT_RowData',
                                    orderable: false,
                                    render: function (data, type, row, meta) {
                                        return '<button class="btn btn-primary btn-sm to-question-collect-detail">\
                                                <i class="fa fa-eye"></i>\
                                            </button>\
                                            <button class="btn btn-primary btn-sm to-del-question-collect">\
                                                <i class="fa fa-trash"></i>\
                                            </button>';
                                    }
                                }
                            ]
                        }).on('preXhr.dt', function () {

                        }).on('xhr.dt', function () {

                        });
                    },
                    initEvent: function () {
                        // 操作删除
                        elem.questionCollectDtb.on('click', '.to-question-collect-detail', event.toQuestionCollectDetail);
                        elem.questionCollectDtb.on('click', '.to-del-question-collect', event.toDelQuestionCollect);
                    },
                    toQuestionCollectDetail: function () {
                        var _this = $(this);
                        // 当前行数据
                        var questionCollect = elem.questionCollectDtb.row(_this.parents('tr')).data().DT_RowData;

                        $.ajax({
                            url: request.getQuestionDetailAndQuestionCollectDetail,
                            data: {
                                questionId: questionCollect.question_id,
                                questionCollectId: questionCollect.question_collect_id
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

                                    var joinQuestionItemOptionId = [];
                                    $.each(data.questionCollectDetail, function (index, value) {
                                        joinQuestionItemOptionId.push(parseInt(value.question_item_option_id));
                                    });

                                    var question = data.questionDetail;
                                    // 问卷详情
                                    var questionDetail = $(html.questionDetail());

                                    // 问卷问题
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

                                        // 问卷选项
                                        var questionDetailItemOptionContainer = questionDetailItem.find('.question-detail-item-option-container');
                                        $.each(questionItem.question_item_options, function (index2, questionItemOption) {
                                            var questionDetailItemOption = $(html.questionDetailItemOption());

                                            // set
                                            questionDetailItemOption.find('.question-detail-item-option-value').text(questionItemOption.value);
                                            if ($.inArray(parseInt(questionItemOption.question_item_option_id), joinQuestionItemOptionId) > -1) {
                                                questionDetailItemOption.find('.question-detail-item-option-content-bar-inner').animate({
                                                    'width': '100%'
                                                });
                                            }

                                            // append itemOption
                                            questionDetailItemOptionContainer.append(questionDetailItemOption);
                                        });

                                        // append item
                                        questionDetailItemContainer.append(questionDetailItem);
                                    });


                                    elem.questionCollectDetailContainer.empty().append(questionDetail);

                                    elem.questionCollectDetailModal.modal('show');
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
                    toDelQuestionCollect: function () {
                        var _this = $(this);
                        // 当前行数据
                        var questionCollect = elem.questionCollectDtb.row(_this.parents('tr')).data().DT_RowData;
                        layer.confirm('<?php echo $this->lang->line('action_delete_tooltip'); ?>', {
                            btn: [
                                '<?php echo $this->lang->line('confirm'); ?>',
                                '<?php echo $this->lang->line('cancel'); ?>'
                            ] //按钮
                        }, function (index) {
                            layer.close(index);
                            flag.dataActionType = 'del';
                            var questionCollectData = {
                                dataActionType: flag.dataActionType,
                                questionCollectId: questionCollect.question_collect_id
                            };
                            $.ajax({
                                url: request.saveQuestionCollect,
                                type: 'post',
                                data: {
                                    questionCollect: questionCollectData
                                },
                                dataType: 'JSON',
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
                    dataActionSuccess: function () {
                        // 重新加载数据
                        elem.questionCollectDtb.draw(false);
                    }
                };

                // 页面初始化
                event.initPage();

                // 刷新事件
                event.initEvent();
            });
        </script>
    </section>
</section>
