<!-- common head -->
<?php
$this->load->view('admin/layouts/head');
?>

<body class="hold-transition skin-blue sidebar-mini">
<!--<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">-->

<!-- page wrapper -->
<div class="wrapper">
    <!-- common input -->
    <section class="hidden">
        <input type="hidden" name="site-url" value="<?php echo site_url(); ?>">
        <input type="hidden" name="base-url" value="<?php echo base_url(); ?>">
    </section>

    <!-- head -->
    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo site_url('admin'); ?>" class="logo pjax">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>Web</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Web</b> Question</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav d-flex align-items-center">
                    <!-- Messages: style can be found in dropdown.less-->
                    <li class="dropdown messages-menu hidden">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">1</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 1 messages</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <!-- start message -->
                                    <li>
                                        <a href="#">
                                            <div class="pull-left">
                                                <img src="<?php echo base_url('public/admin/images/user.png'); ?>" class="img-circle" alt="User Image">
                                            </div>
                                            <h4>
                                                Support Team
                                                <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                            </h4>
                                            <p>Why not buy a new awesome theme?</p>
                                        </a>
                                    </li>
                                    <!-- end message -->
                                </ul>
                            </li>
                            <li class="footer"><a href="#">See All Messages</a></li>
                        </ul>
                    </li>
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu hidden">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">1</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 1 notifications</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>
                    <!-- Tasks: style can be found in dropdown.less -->
                    <li class="dropdown tasks-menu hidden">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger">9</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 9 tasks</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <h3>
                                                Design some buttons
                                                <small class="pull-right">20%</small>
                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="sr-only">20% Complete</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#">View all tasks</a>
                            </li>
                        </ul>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo base_url('public/admin/images/user.png'); ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo $this->session->userdata('sys_user')->user_name; ?></span>
                        </a>
                        <ul class="dropdown-menu user-body-container">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?php echo base_url('public/admin/images/user.png'); ?>" class="img-circle" alt="User Image">
                                <p>
                                    <?php echo $this->session->userdata('sys_user')->user_name; ?> - Web Developer
                                    <small>Member since Nov. 2012</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body p-3">
                                <div class="row">
                                    <div class="col-xs-4 text-center hidden">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center hidden">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center hidden">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo site_url('admin/account/sign_out'); ?>" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-cogs"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Left side column. contains the logo and sidebar -->
    <?php
    $this->load->view('admin/layouts/aside_sys_menu');
    ?>

    <!-- content Wrapper. Contains page content -->
    <div class="content-wrapper" id="pjax-container">
        <!-- breadcrumb -->
        <?php
        $this->load->view('admin/layouts/breadcrumb');
        ?>

        <!-- Main content -->
        <?php
        $this->load->view($page['view']);
        ?>

        <!-- toast -->
        <div id="toast-container">
            <div class="toast"></div>
        </div>
    </div>

    <!-- foot -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            webStore
        </div>
        <strong>Copyright &copy; 2014 - <script>document.write(new Date().getFullYear().toString());</script> .</strong>
    </footer>

    <!-- control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li class="active"><a href='#control-sidebar-options-tab' data-toggle='tab'><i class='fa fa-cog'></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">
                <h4 class="control-sidebar-heading">System Setting</h4>
                <ul class="control-sidebar-menu">

                </ul>
            </div>
            <div class="tab-pane active" id="control-sidebar-options-tab">
                <h4 class='control-sidebar-heading'>Layout Options</h4>
                <!-- Fixed layout -->
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <input type="checkbox" data-layout="fixed" class="control-sidebar-option-checkbox">
                        Fixed layout
                    </label>
                    <p>Activate the fixed layout. You can"t use fixed and boxed layouts together</p>
                 </div>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <input type="checkbox" data-layout="sidebar-collapse" class="control-sidebar-option-checkbox">
                        Toggle Sidebar
                    </label>
                    <p>Toggle the left sidebar"s state (open or collapse)</p>
                </div>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <input type="checkbox" data-sidebarskin="toggle" class="control-sidebar-option-checkbox">
                        Toggle Right Sidebar Skin
                    </label>
                    <p>Toggle between dark and light skins for the right sidebar</p>
                </div>
                <h4 class="control-sidebar-heading">Skins</h4>
                <ul class="list-unstyled clearfix">
                    <li class="skin-item">
                        <a href="javascript:void(0);" data-skin="skin-blue" class="clearfix full-opacity-hover">
                            <div>
                                <span class="skin-top-left" style="background: #367fa9;"></span>
                                <span class="bg-light-blue skin-top-right" style=""></span>
                            </div>
                            <div>
                                <span class="skin-bottom-left" style="background: #222d32;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin">Blue</p></li>
                    <li class="skin-item">
                        <a href="javascript:void(0);" data-skin="skin-black" class="clearfix full-opacity-hover">
                            <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix">
                                <span class="skin-top-left" style="background: #fefefe;"></span>
                                <span class="skin-top-right" style="background: #fefefe;"></span>
                            </div>
                            <div>
                                <span class="skin-bottom-left" style="background: #222;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin">Black</p></li>
                    <li class="skin-item"><a href="javascript:void(0);" data-skin="skin-purple" class="clearfix full-opacity-hover">
                            <div>
                                <span class="skin-top-left bg-purple-active"></span>
                                <span class="skin-top-right bg-purple" style=""></span>
                            </div>
                            <div>
                                <span class="skin-bottom-left" style="background: #222d32;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin">Purple</p></li>
                    <li class="skin-item">
                        <a href="javascript:void(0);" data-skin="skin-green" class="clearfix full-opacity-hover">
                            <div>
                                <span class="skin-top-left bg-green-active"></span>
                                <span class="skin-top-right bg-green" style=""></span>
                            </div>
                            <div>
                                <span class="skin-bottom-left" style="background: #222d32;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin">Green</p></li>
                    <li class="skin-item">
                        <a href="javascript:void(0);" data-skin="skin-red" class="clearfix full-opacity-hover">
                            <div>
                                <span class="skin-top-left bg-red-active"></span>
                                <span class="skin-top-right bg-red" style=""></span>
                            </div>
                            <div>
                                <span class="skin-bottom-left" style="background: #222d32;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin">Red</p>
                    </li>
                    <li class="skin-item"><a href="javascript:void(0);" data-skin="skin-yellow" class="clearfix full-opacity-hover">
                            <div>
                                <span class="skin-top-left bg-yellow bg-yellow-active"></span>
                                <span class="skin-top-right bg-yellow" style=""></span></div>
                            <div>
                                <span class="skin-bottom-left" style="background: #222d32;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin">Yellow</p></li>
                    <li class="skin-item"><a href="javascript:void(0);" data-skin="skin-blue-light" class="clearfix full-opacity-hover">
                            <div>
                                <span class="skin-top-left" style="background: #367fa9;"></span>
                                <span class="skin-top-right bg-light-blue" style=""></span></div>
                            <div>
                                <span class="skin-bottom-left" style="background: #f9fafc;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px">Blue Light</p></li>
                    <li class="skin-item">
                        <a href="javascript:void(0);" data-skin="skin-black-light" class="clearfix full-opacity-hover">
                            <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix">
                                <span class="skin-top-left" style="background: #fefefe;"></span>
                                <span class="skin-top-right" style="background: #fefefe;"></span>
                            </div>
                            <div>
                                <span class="skin-bottom-left" style="background: #f9fafc;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px">Black Light</p></li>
                    <li class="skin-item">
                        <a href="javascript:void(0);" data-skin="skin-purple-light" class="clearfix full-opacity-hover">
                            <div>
                                <span class="skin-top-left bg-purple-active"></span>
                                <span class="skin-top-right bg-purple" style=""></span>
                            </div>
                            <div>
                                <span class="skin-bottom-left" style="background: #f9fafc;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px">Purple Light</p></li>
                    <li class="skin-item"><a href="javascript:void(0);" data-skin="skin-green-light" class="clearfix full-opacity-hover">
                            <div>
                                <span style="" class="skin-top-left bg-green-active"></span>
                                <span class="skin-top-right bg-green" style=""></span>
                            </div>
                            <div>
                                <span class="skin-bottom-left" style="background: #f9fafc;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px">Green Light</p></li>
                    <li class="skin-item">
                        <a href="javascript:void(0);" data-skin="skin-red-light" class="clearfix full-opacity-hover">
                            <div>
                                <span class="skin-top-left bg-red-active"></span>
                                <span class="skin-top-right bg-red" style=""></span>
                            </div>
                            <div>
                                <span class="skin-bottom-left" style="background: #f9fafc;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px">Red Light</p></li>
                    <li class="skin-item">
                        <a href="javascript:void(0);" data-skin="skin-yellow-light" class="clearfix full-opacity-hover">
                            <div>
                                <span style="" class="skin-top-left bg-yellow-active"></span>
                                <span  class="skin-top-right bg-yellow" style=""></span></div>
                            <div>
                                <span class="skin-bottom-left" style="background: #f9fafc;"></span>
                                <span class="skin-bottom-right" style="background: #f4f5f7;"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px;">Yellow Light</p>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>

</body>

<!-- common foot -->
<?php
$this->load->view('admin/layouts/foot');
?>