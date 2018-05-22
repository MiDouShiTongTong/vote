<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo base_url('public/admin/images/user.png'); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $this->session->userdata('sys_user')->user_name; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form hidden">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <?php
            if (!empty($sys_menus)) {
                foreach ($sys_menus as $_key => $sys_menu) {
            ?>
            <!-- 一级菜单 -->
            <li class="treeview <?php if ($sys_menu->active == '1') echo 'active'; ?>">
                <a href="<?php echo !empty($sys_menu->src) ? site_url($sys_menu->src) : 'javascript:void(0)'; ?>" class="pjax" data-page-src="<?php echo !empty($sys_menu->src) ? site_url($sys_menu->src) : 'javascript:void(0)'; ?>">
                    <i class="fa <?php echo $sys_menu->icon; ?>"></i> <span><?php echo $sys_menu->menu_name; ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <?php
                if (!empty($sys_menu->child)) {
                ?>
                <!-- 二级菜单 -->
                <ul class="treeview-menu">
                    <?php
                    foreach ($sys_menu->child as $_key_child => $sys_menu_child) {
                    ?>
                    <li class="<?php if ($sys_menu_child->active == '1') echo 'active treeview-child'; ?>">
                        <a href="<?php echo !empty($sys_menu_child->src) ? site_url($sys_menu_child->src) : 'javascript:void(0)'; ?>" class="pjax" data-page-src="<?php echo !empty($sys_menu_child->src) ? site_url($sys_menu_child->src) : 'javascript:void(0)'; ?>">
                            <i class="fa fa-angle-double-right"></i> <span><?php echo $sys_menu_child->menu_name; ?></span>
                            <?php
                            if (!empty($sys_menu_child->child)) {
                            ?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            <?php
                            }
                            ?>
                        </a>

                        <?php
                        if (!empty($sys_menu_child->child)) {
                        ?>
                        <!-- 三级菜单 -->
                        <ul class="treeview-menu">
                            <?php
                            foreach ($sys_menu_child->child as $_key_child_child => $sys_menu_child_child) {
                            ?>
                            <li class="<?php if ($sys_menu_child_child->active == '1') echo 'active treeview-child'; ?>">
                                <a href="<?php echo !empty($sys_menu_child_child->src) ? site_url($sys_menu_child_child->src) : 'javascript:void(0)'; ?>" class="pjax" data-page-src="<?php echo !empty($sys_menu_child_child->src) ? site_url($sys_menu_child_child->src) : 'javascript:void(0)'; ?>">
                                    <i class="fa fa-angle-double-right"></i> <span><?php echo $sys_menu_child_child->menu_name; ?></span>
                                    <?php
                                    if (!empty($sys_menu_child_child->child)) {
                                    ?>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                    <?php
                                    }
                                    ?>
                                </a>

                                <?php
                                if (!empty($sys_menu_child_child->child)) {
                                ?>
                                <!-- 四级菜单 -->
                                <ul class="treeview-menu">
                                    <?php
                                    foreach ($sys_menu_child_child->child as $_key_child_child_child => $sys_menu_child_child_child) {
                                    ?>
                                    <li>
                                        <a href="<?php echo !empty($sys_menu_child_child_child->src) ? site_url($sys_menu_child_child_child->src) : 'javascript:void(0)'; ?>" class="pjax" data-page-src="<?php echo !empty($sys_menu_child_child_child->src) ? site_url($sys_menu_child_child_child->src) : 'javascript:void(0)'; ?>">
                                            <i class="fa fa-angle-double-right"></i> <span><?php echo $sys_menu_child_child_child->menu_name; ?></span>
                                        </a>
                                    </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                                <?php
                                }
                                ?>

                            </li>
                            <?php
                            }
                            ?>
                        </ul>
                        <?php
                        }
                        ?>

                    </li>
                    <?php
                    }
                    ?>
                </ul>
                <?php
                }
                ?>

            </li>
            <?php
                }
            }
            ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>