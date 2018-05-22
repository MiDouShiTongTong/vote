<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Init_Controller
 *
 */
class Init_Controller extends CI_Controller
{
    /**
     * 所有表名称和主键
     *
     * @var string
     */

    protected $question_table = 'q_question';
    protected $question_table_primaryKey = 'question_id';

    protected $question_item_table = 'q_question_item';
    protected $question_item_table_primaryKey = 'question_item_id';

    protected $question_item_option_table = 'q_question_item_option';
    protected $question_item_option_table_primaryKey = 'question_item_option_id';

    protected $question_collect_table = 'q_question_collect';
    protected $question_collect_table_primaryKey = 'question_collect_id';

    protected $question_collect_detail_table = 'q_question_collect_detail';
    protected $question_collect_detail_table_primaryKey = 'question_collect_detail_id';

    protected $sys_permission_table = 'q_sys_permission';
    protected $sys_permission_table_primaryKey = 'sys_permission_id';

    protected $sys_role_table = 'q_sys_role';
    protected $sys_role_table_primaryKey = 'sys_role_id';

    protected $sys_user_table = 'q_sys_user';
    protected $sys_user_table_primaryKey = 'sys_user_id';

    protected $status_table = 'q_status';
    protected $status_table_primaryKey = 'status_id';

    /**
     * Init_Controller constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->library('Tool');
        $this->load->library('Result');

        // 加载语言资源
        $this->load_language_package();
    }

    /**
     * 加载语言资源
     *
     * @return void
     */
    public function load_language_package()
    {
        $this->lang->load([
            'admin'
        ], 'zh-CN');
    }
}

/**
 * Class Admin_Controller
 *
 */
class Admin_Controller extends Init_Controller
{
    /**
     * 后台管理员 缓存
     *
     * @var
     */
    public $sign_in_sys_user;

    /**
     * Admin_Controller constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sys_menu_model');
        $this->load->model('sys_permission_model');
        $this->load->library('datatables');

        // 判断是否登录
        $sys_user = $this->session->userdata('sys_user');
        if (!isset($sys_user) || empty($sys_user)) {
            if (!Tool::is_pjax()) {
                redirect(site_url('admin/account/sign_in'));
            } else {
                $data = [
                    'page' => [
                        'title' => 'Admin | Sign In'
                    ],
                    'manager_session_express' => true
                ];
                $this->load->view('sign_in', $data);
                $this->output->_display();
                exit;
            }
        }
        // 保存登陆信息
        $this->sign_in_sys_user = $sys_user;

        $permission_flag = $this->backend_permission_check();

        if (!$permission_flag) {
            if (Tool::is_pjax()) {
                redirect(site_url('admin/system/no_permission'));
            } else {
                $this->result->toJson([
                    'errCode' => '400901',
                    'errMsg' => $this->lang->line('not_sys_permission')
                ]);
            }
        }
    }

    /**
     * 页面初始化
     *
     * @param array $data 数据
     */
    public function load_page($data)
    {
        $data['page']['page_src'] = current_url();
        $view = $data['page']['view'];
        if (empty($view)) {
            // 不得为空
            $this->result->toJson([
                'errCode' => '400301',
                'errMsg' => $this->lang->line('line') . $this->lang->line('get_fail') . '-' . $view
            ]);
        }
        if (!Tool::is_pjax()) {
            // 没有pjax头请求 刷新加载整个框架
            // 系统菜单
            $data['sys_menus'] = $this->sys_menu_model->get_all_sys_menu();

            $this->load->view('admin/index', $data);
        } else {
            // 有pjax有请求 只加载html内容
            $this->load->view('admin/layouts/breadcrumb', $data);
            $this->load->view($view);
        }
    }

    /**
     * 后台权限验证
     *
     * @return boolean
     */
    public function backend_permission_check()
    {
        // $sys_user_sys_permission_ids_arr = explode(',', $this->session->userdata('sys_user')->sys_permission_ids);

        $sys_permission = $this->common_model->get($this->sys_role_table, '', [
            'sys_role_id' => $this->sign_in_sys_user->sys_role_id
        ])[0];
        $sys_user_sys_permission_ids_arr = explode(',', $sys_permission->sys_permission_ids);

        // 获取URL参数
        $controller = $this->router->fetch_class();
        $function = $this->router->fetch_method();

        // 此控制器权限
        $sys_permission = $this->sys_permission_get($controller, 'controller');
        // 只要是空的跳过
        if (!empty($sys_permission)) {
            // 是否需要验证
            if ((integer) $sys_permission->permission_value == 1) {
                // 方法权限
                $sys_permission = $this->sys_permission_get($function, 'function');
                if (!empty($sys_permission)) {
                    // 是否需要验证
                    if ((integer) $sys_permission->permission_value == 1) {

                        // 是否是增删改查----------------------------------------------------------------------------------------------------
                        $post_data = $this->input->post();
                        // 获取操作类型
                        $data_action_type = '';
                        if (!empty($post_data) && is_array($post_data)) {
                            foreach ($post_data as $_key => $data) {
                                if (is_array($data)) {
                                    if (array_key_exists('dataActionType', $data)) {
                                        $data_action_type = strtolower($data['dataActionType']);
                                        break;
                                    }
                                }
                            }
                        }

                        if (!empty($data_action_type)) {
                            $sys_permission = $this->sys_permission_get($data_action_type, 'action', $sys_permission->sys_permission_id);
                            if (!empty($sys_permission)) {
                                if ($sys_permission->permission_value != 1) {
                                    return true;
                                }
                            } else {
                                return true;
                            }
                        }
                        // 是否是增删改查----------------------------------------------------------------------------------------------------

                        // check permission
                        if (in_array($sys_permission->sys_permission_id, $sys_user_sys_permission_ids_arr)) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * 权限获取
     *
     * @param  string $permission_code
     * @param  string $permission_type
     * @param  string $parent_id
     * @return mixed
     */
    public function sys_permission_get($permission_code, $permission_type, $parent_id = '')
    {
        $sys_permission = null;
        $sys_permissions = json_decode($this->session->userdata('sys_permissions'));

        // 获取权限
        foreach ($sys_permissions as $_key => $_sys_permission) {
            if ($_sys_permission->permission_code == $permission_code && $_sys_permission->permission_type == $permission_type) {
                if (!empty($parent_id)) {
                    if ($_sys_permission->parent_id == $parent_id) {
                        $sys_permission = $_sys_permission;
                        break;
                    }
                } else {
                    $sys_permission = $_sys_permission;
                    break;
                }
            }
        }
        return $sys_permission;
    }
}

class Frontend_Controller extends Init_Controller
{
    /**
     * 页面初始化
     *
     * @param array $data 数据
     */
    public function load_page($data)
    {
        $view = $data['page']['view'];
        if (empty($view)) {
            // 不得为空
            $this->result->toJson([
                'errCode' => '400301',
                'errMsg' => $this->lang->line('line') . $this->lang->line('get_fail') . '-' . $view
            ]);
        }
        if (!Tool::is_pjax()) {
            // 没有pjax头请求 刷新加载整个框架
            $this->load->view('frontend/index', $data);
        } else {
            // 有pjax有请求 只加载html内容
            $this->load->view($view, $data);
        }
    }
}
