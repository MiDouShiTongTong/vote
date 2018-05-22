<?php
// 公共 head
$this->load->view('frontend/layouts/head.php');
?>

<div class="row">
    <div class="col s12 m6" style="float: none; margin: 0 auto;">
        <div class="card blue">
            <div class="card-content white-text">
                <span class="card-title"><?php echo $tooltip_title; ?></span>
                <p><?php echo $tooltip_content; ?></p>
            </div>
        </div>
    </div>
</div>

<script>
    document.title = '<?php echo $page_title; ?>';
</script>