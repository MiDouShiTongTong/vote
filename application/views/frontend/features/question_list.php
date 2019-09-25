<div class="page-init">
    <span id="page-title"><?php echo $page['title']; ?></span>
</div>

<main class="question-wrapper row">
    <div class="col s11 m9 l7 xl5 question-list-body z-depth-2">
        <div class="top-line blue accent-1"></div>
        <div class="question-list-container">
            <ul class="collection with-header">
                <li class="question-list-title">问卷列表</li>
                <?php
                if (!empty($questions)) {
                    foreach ($questions as $_key => $question) {
                ?>
                <a href="<?php echo site_url('question/' . $question->question_id); ?>" class="collection-item waves-effect pjax">
                    <span><?php echo $question->question_title ?></span>
                    <i class="fa fa-arrow-right secondary-content"></i>
                </a>
                </li>
                <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</main>

<script>
    $(function () {

        'use strict';
        // elem
        var elem = {};

        // event
        var event = {
            pageInit: function () {

            },
            eventInit: function () {

            }
        };

        // 初始化页面
        event.pageInit();
        // 初始化事件
        event.eventInit();
    });
</script>
