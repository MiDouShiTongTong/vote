<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Account
 *
 */
class Account extends Init_Controller
{
    /**
     * SignIn constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 登录视图
     *
     * @return void
     */
    public function sign_in()
    {
        $data = [
            'page' => [
                'title' => 'Admin | Sign In'
            ]
        ];
        $this->load->view('admin/account/sign_in', $data);
    }

    /**
     * 登录验证
     *
     * @return string
     */
    public function sign_in_check()
    {
        $sys_user = $this->input->post();
        $user_name = $sys_user['userName'];
        $password = sha1('sysUser' . $sys_user['password']);

        // 获取状态不为0的管理员
        $condition = [
            'user_name' => $user_name,
            'status' => 1
        ];
        $sys_user = $this->common_model->get($this->sys_user_table, '', $condition);
        if (count($sys_user) > 0 && $sys_user[0]->password == $password) {
            // 管理员
            $sys_user = $sys_user[0];
            // 管理员权限
            $sys_permission = $this->common_model->get($this->sys_role_table, '', [
                'sys_role_id' => $sys_user->sys_role_id
            ])[0];
            $sys_user->sys_permission_ids = $sys_permission->sys_permission_ids;
            $data = [
                'sys_user' => $sys_user,
                'sys_permissions' => json_encode($this->common_model->get($this->sys_permission_table))
            ];
            //创建session
            $this->session->set_userdata($data);

            // 创建COOKIE
            $this->result->toJson([
                'errCode' => '0',
                'errMsg' => $this->lang->line('sign_in') . $this->lang->line('success')
            ]);
        } else {
            $this->result->toJson([
                'errCode' => '400501',
                'errMsg' => $this->lang->line('sign_in') . $this->lang->line('fail')
            ]);
        }
    }

    /**
     * 注销登录
     *
     * @return void
     */
    public function sign_out()
    {
        // 销毁 sys_user session
        $data = [
            'sys_user',
            'permission'
        ];
        $this->session->unset_userdata($data);
        
        //创建COOKIE
        $this->load->helper('cookie');
        delete_cookie('merchant_id');
        
        // 重定向登录页面
        redirect(site_url('admin/account/sign_in'));
    }
}