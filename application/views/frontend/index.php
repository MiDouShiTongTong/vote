<?php
// 公共 head
$this->load->view('frontend/layouts/head.php');

?>

<link rel="stylesheet" href="<?php echo base_url('public/frontend/css/question.css'); ?>">

<header class="question-jumbotron slide-to-bottom-opacity-show"></header>
<section id="pjax-container" class="slide-to-top-opacity-show">
    <?php $this->load->view($page['view']); ?>
</section>
<?php
// 公共 foot
$this->load->view('frontend/layouts/foot.php');
?>

<script>
    $(function () {
        'use strict';
        // elem
        var elem = {};

        // event
        var event = {
            pageInit: function () {
                // 改变title
                document.title = '<?php echo $page['title']; ?>';
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
