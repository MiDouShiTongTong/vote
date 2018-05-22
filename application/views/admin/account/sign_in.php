<?php
// pjax session 过期
if (!isset($manager_session_express)) {
?>

<?php
// head
$this->load->view('admin/layouts/head');
?>
<body class="hold-transition login-page">

<?php
} else {
?>
    <style>
        .login-box, .register-box {
            margin-top: 9%;
            margin-bottom: 0;
        }
    </style>
<?php
}
?>

<div class="login-box">
    <div class="login-logo">
    </div>
    <div class="login-box-body">
        <h3 class="login-box-msg">Login</h3>
        <form action="" method="post" id="sign-in">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="User Name" name="user-name">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12" style="margin-top: 10px;">
                    <button type="button" class="btn btn-primary btn-block btn-flat sign-in">Login</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
// pjax session 过期
if (!isset($manager_session_express)) {
?>

<?php
// foot
$this->load->view('admin/layouts/foot');
?>

<?php
}
?>

<script>
    $(function () {
        var elem = {
            signIn: $('.sign-in'),
            signInForm: $('#sign-in')
        };

        var flag = {
            sysUserFormCheck: undefined
        };

        var check = {
            signInForm: function () {
                flag.sysUserFormCheck = undefined;

                elem.signInForm.validate({
                    rules: {
                        'user-name': {
                            required: true,
                            maxlength: 20
                        },
                        'password': {
                            required: true,
                            maxlength: 20
                        }
                    },
                    messages: {
                        'user-name': {
                            required: '<?php echo $this->lang->line('sys_user_name') . $this->lang->line('form_validate_required'); ?>',
                            maxlength: '<?php echo $this->lang->line('sys_user_name') . $this->lang->line('form_validate_max_length'); ?>{0}'
                        },
                        'password': {
                            required: '<?php echo $this->lang->line('password') . $this->lang->line('form_validate_required'); ?>',
                            maxlength: '<?php echo $this->lang->line('password') . $this->lang->line('form_validate_max_length'); ?>{0}'
                        }
                    }
                });

                if (!elem.signInForm.valid()) {
                    flag.signInFormCheck = 1;
                    return false;
                } else {
                    return true;
                }
            }
        };

        var event = {
            pageInit: function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            },
            eventInit: function () {
                elem.signIn.on('click', event.signIn)
            },
            signIn: function () {
                if (!check.signInForm()) return false;

                var userName = cVal.getVal('text', 'user-name');
                var password = $.trim($('input[name="password"]').val());

                // loading层
                var index = layer.load(1, {
                    shade: [0.1, '#fff'] //0.1透明度的白色背景
                });

                $.ajax({
                    url: "<?php echo site_url('admin/account/sign_in_check'); ?>",
                    type: "POST",
                    data: {
                        userName: userName,
                        password: password
                    },
                    dataType: 'JSON',
                    beforeSend: function () {
                        tool.showLoad();
                    },
                    success: function (data) {
                        layer.closeAll("loading");  //取消loading
                        if (data.errCode == 0) {
                            window.location.href = "<?php echo site_url('admin'); ?>";
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
            }
        };
        event.pageInit();
        event.eventInit();
    });
</script>
