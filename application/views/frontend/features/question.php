<div class="page-init">
    <span id="page-title"><?php echo $page['title']; ?></span>
</div>

<main class="question-wrapper row">
    <div class="col s11 m9 l7 xl5 question-body z-depth-2">
        <div class="top-line blue accent-1"></div>
        <div class="question-container"></div>
    </div>
</main>

<script>
    $(function () {
        var elem = {
            questionContainer: $('.question-container'),
            questionBody: $('.question-body'),
            topLine: $('.top-line')
        };

        var buf = {
            questionId: undefined
        };

        var flag = {
            checkQuestion: undefined,
            questionCollect: undefined
        };

        var html = {
            questionContentContainer: function () {
                return '<div class="question-content-container">\
                                <div class="question-title"></div>\
                                <div class="question-item-container"></div>\
                                <div class="question-action-container">\
                                    <button class="btn waves-effect btn blue darken-1 question-collect">提交</button>\
                                    <div class="to-question-list">\
                                        <a href="<?php echo site_url('question/list'); ?>" class="waves-effect pjax">←返回投票列表</a>\
                                    </div>\
                                </div>\
                            </div>';
            },
            questionItem: function () {
                return '<div class="question-item">\
                                <div class="question-item-title"></div>\
                                <div class="question-item-option-container"></div>\
                            </div>';
            },
            questionItemOption: function () {
                return '<div class="question-item-option">\
                                <label>\
                                    <input type="" name="" value="" class="" data-question-id="" data-question-item-id="" data-item-option-id="">\
                                    <span class="question-item-option-title"></span>\
                                </label>\
                            </div>';
            },
            questionDetailContentContainer: function () {
                return '<div class="question-detail-content-container">\
                                <div class="question-detail-title"></div>\
                                <div class="question-detail-item-container"></div>\
                                <div class="question-detail-action-container">\
                                    <button class="btn waves-effect btn blue darken-1 disabled question-collect">您已参此投票</button>\
                                    <div class="to-question-list">\
                                        <a href="<?php echo site_url('question/list'); ?>" class="waves-effect pjax">←填写其他投票</a>\
                                    </div>\
                                </div>\
                            </div>';
            },
            questionDetailItem: function () {
                return '<div class="question-detail-item">\
                                <div class="question-detail-item-title">\
                                    \
                                </div>\
                                <div class="question-detail-item-option-container">\
                                    \
                                </div>\
                            </div>';
            },
            questionDetailItemOption: function () {
                return '<div class="question-detail-item-option">\
                                <div class="question-detail-item-option-value"></div>\
                                <div class="question-detail-item-option-content">\
                                    <div class="question-detail-item-option-content-bar col s8 z-depth-1">\
                                        <div class="question-detail-item-option-content-bar-inner"></div>\
                                    </div>\
                                    <div class="question-detail-item-option-content-counter-container col s4">\
                                        <div class="col s6">\
                                        <span class="question-detail-item-option-counter">\
                                            \
                                        </span>票\
                                        </div>\
                                        <div class="col s6">\
                                            <span class="question-detail-item-option-percentage">\
                                                \
                                            </span>%\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>';
            }
        };

        var request = {
            questionCollect: site_url + 'question/collect',
            getQuestionDetail: site_url + 'question/get_detail'
        };

        var check = {
            checkQuestion: function () {
                flag.checkQuestion = true;
                var errScrollTop = undefined;
                $.each($('.question-item'), function (index, value) {
                    var _this = $(this);
                    if (_this.find('input:checked').length <= 0) {
                        // 问题中一个选项都没有选择
                        flag.checkQuestion = false;
                        // 记录当前错误的 问题 ScrollTop 高度 body滚动条要滚到此处
                        if (errScrollTop == undefined) errScrollTop = _this.offset().top - 30;
                        // 显示错误 没有就新增 有 直接显示
                        var errorElem = _this.find('.error-no-select');
                        if (errorElem.length <= 0) {
                            var errorHTML = $('<span class="error-no-select"/>').text('　-　暂未选择');
                            _this.find('.question-item-title').append(errorHTML);
                        } else {
                            errorElem.show();
                        }
                    }
                });

                if (errScrollTop != undefined) {
                    $('body,html').animate({
                        scrollTop: errScrollTop
                    });
                }

                return flag.checkQuestion;
            }
        };

        var event = {
            eventInit: function () {
                buf.questionId = '<?php echo $question_info['question_id']; ?>';
                if ('<?php echo $question_info['question_join']; ?>' == 'false') {
                    event.toQuestionInit();
                } else {
                    event.toQuestionDetailInit();
                }
            },
            questionWrapperStyleInit: function (type) {
                elem.questionBody.show();
            },
            toQuestionInit: function () {
                $.ajax({
                    type: 'POST',
                    url: request.getQuestionDetail,
                    data: {
                        questionId: buf.questionId
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        tool.showLoad();
                    },
                    success: function (data) {
                        tool.closeLoad();
                        var question = data.question;

                        if (question == '') {
                            tool.showToast('投票不存在');
                            return false;
                        }

                        // question
                        var questionContentContainerHTML = $(html.questionContentContainer());
                        questionContentContainerHTML.find('.question-title').html(question.question_title);
                        var questionItemContainerHTML = questionContentContainerHTML.find('.question-item-container');

                        // question item
                        $.each(question.question_items, function (index, questionItem) {
                            var questionItemHTML = $(html.questionItem());
                            questionItemHTML.find('.question-item-title').html(questionItem.question_item_title);

                            // question item option
                            var questionItemOptionContainer = questionItemHTML.find('.question-item-option-container');
                            $.each(questionItem.question_item_options, function (index2, questionItemOption) {
                                var selectType = questionItem.is_multiple == 1 ? 'checkbox' : 'radio';
                                var questionItemOptionHTML = $(html.questionItemOption());
                                questionItemOptionHTML.find('input').attr('type', selectType).attr('name', 'question-item-option-' + questionItem.question_item_id).data('question-id', question.question_id).data('question-item-id', questionItem.question_item_id).data('question-item-option-id', questionItemOption.question_item_option_id).val(questionItemOption.item_option_id);
                                questionItemOptionHTML.find('.question-item-option-title').html(questionItemOption.value);
                                if (selectType == 'checkbox') {
                                    questionItemOptionHTML.find('input').addClass('filled-in')
                                } else {
                                    questionItemOptionHTML.find('input').addClass('with-gap')
                                }

                                questionItemOptionContainer.append(questionItemOptionHTML);
                            });

                            questionItemContainerHTML.append(questionItemHTML);
                        });

                        elem.questionContainer.empty().html(questionContentContainerHTML);

                        $('.question-collect').click(event.questionCollect);
                        $('.question-content-container').find('input').on('change', event.questionItemOptionChange);

                        event.questionWrapperStyleInit('toQuestionInit');

                        cVal.setVal('checkbox', 'question-item-option-1');
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            },
            questionItemOptionChange: function () {
                var _this = $(this);
                _this.parents('.question-item').find('.error-no-select').hide();
            },
            questionCollect: function () {
                var _this = $(this);
                // 验证问题是否已经选择 每个都要
                if (!check.checkQuestion()) return false;

                var questionCollect = {
                    questionId: buf.questionId
                };

                var questionCollectDetailArr = [];
                // 获取选中的选项
                $('.question-container input:checked').each(function (index, value) {
                    var _this = $(this);
                    var questionCollect = {
                        questionId: _this.data('question-id'),
                        questionItemId: _this.data('question-item-id'),
                        questionItemOptionId: _this.data('question-item-option-id')
                    };
                    questionCollectDetailArr.push(questionCollect);
                });

                _this.addClass('disabled');
                $.ajax({
                    url: request.questionCollect,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        questionCollect: questionCollect,
                        questionCollectDetail: questionCollectDetailArr
                    },
                    beforeSend: function () {
                        tool.showLoad();
                    },
                    success: function (data) {
                        tool.closeLoad();
                        if (data.errCode == 0) {
                            event.toQuestionDetailInit();
                            $('body,html').animate({
                                scrollTop: 0
                            });
                            tool.showToast(data.errMsg, 4000);
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
            toQuestionDetailInit: function () {
                $.ajax({
                    url: request.getQuestionDetail,
                    type: 'POST',
                    data: {
                        questionId: buf.questionId
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        tool.showLoad();
                    },
                    success: function (data) {
                        tool.closeLoad();
                        var question = data.question;

                        var questionDetailContentContainerHTML = $(html.questionDetailContentContainer());
                        questionDetailContentContainerHTML.find('.question-detail-title').html(question.question_title);

                        // detail question item
                        var questionDetailItemContainerHTML = questionDetailContentContainerHTML.find('.question-detail-item-container');
                        $.each(question.question_items, function (index, questionItem) {
                            var questionDetailItem = $(html.questionDetailItem());
                            var questionSelectType = '';
                            if (questionItem.is_multiple == 1) {
                                questionSelectType = '(多选)';
                            } else {
                                questionSelectType = '(单选)';
                            }
                            questionDetailItem.find('.question-detail-item-title').text(questionItem.question_item_title + ' ' + questionSelectType);

                            // detail question item option
                            var questionDetailItemOptionContainer = questionDetailItem.find('.question-detail-item-option-container');
                            $.each(questionItem.question_item_options, function (index, questionItemOption) {
                                var questionDetailItemOption = $(html.questionDetailItemOption());

                                questionDetailItemOption.find('.question-detail-item-option-value').text(questionItemOption.value);

                                questionDetailItemOption.find('.question-detail-item-option-content-bar-inner').animate({
                                    'width': questionItemOption.question_item_option_percentage + '%'
                                }, 233);

                                questionDetailItemOption.find('.question-detail-item-option-counter').text(questionItemOption.question_item_option_counter);
                                questionDetailItemOption.find('.question-detail-item-option-percentage').text(questionItemOption.question_item_option_percentage);

                                questionDetailItemOptionContainer.append(questionDetailItemOption);
                            });

                            questionDetailItemContainerHTML.append(questionDetailItem);
                        });

                        elem.questionContainer.empty().html(questionDetailContentContainerHTML);
                        event.questionWrapperStyleInit('toQuestionDetailInit');
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            }
        };

        event.eventInit();

    });
</script>