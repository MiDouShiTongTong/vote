<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Sys_menu_model
 *
 */
class Sys_menu_model extends CI_Model
{
    /**
     * Sys_menu_model constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取系统菜单
     * @param string $get_condition
     *
     * @return array
     */
    public function get_all_sys_menu($get_condition = '')
    {
        // 获得用户的菜单
        if ($get_condition != 'get_all') {
            $role_menu_ids = $this->common_model->get('sys_role', '', ['sys_role_id' => $this->session->userdata('sys_user')->sys_role_id])[0]->sys_menu_ids;
            $this->db->where_in('sys_menu_id', explode(',', $role_menu_ids));
        }

        // 用户的菜单
        $this->db->select('sys_menu_id, menu_name, icon, src, parent_id, active, status, sys_menu_id as id, menu_name as value');
        $this->db->where('status', 1);
        $this->db->order_by('menu_seq', 'ASC');
        $query = $this->db->get('sys_menu');
        $sys_user_menu = $query->result();

        // 构造系统菜单 [层级关系]
        $sys_menus_structure = $this->common_model->get_tree_data($sys_user_menu, 0, 'admin_index');
        $sys_menu = [];
        // 获取子菜单不为空的 菜单
        foreach ($sys_menus_structure as $_key => $_sys_menus_structure) {
            if (isset($_sys_menus_structure->child) && !empty($_sys_menus_structure->child)) {
                $sys_menu[] = $_sys_menus_structure;
            }
        }
        return $sys_menu;
    }
}
