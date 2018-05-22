<section class="content-header">
    <h5 id="page-title" class="sr-only"><?php echo $page['title']; ?></h5>
    <ol class="breadcrumb" data-page-src="<?php echo $page['page_src']; ?>">
        <?php
        foreach ($breadcrumb['breadcrumb'] as $_key => $breadcrumb_data) {
        ?>
        <li>
            <a href="<?php echo $breadcrumb_data['href']; ?>" class="pjax <?php echo $breadcrumb_data['class']; ?>">
                <!-- 只有一个导航有icon -->
                <?php
                if (!empty($breadcrumb_data['icon'])) {
                ?>
                <i class="fa <?php echo $breadcrumb_data['icon']; ?>"></i>
                <?php
                }
                ?>
                <?php echo $breadcrumb_data['name']; ?>
            </a>
        </li>
        <?php
        }
        ?>
    </ol>
</section>