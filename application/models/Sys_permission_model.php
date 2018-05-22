<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Sys_permission_model
 *
 */
class Sys_permission_model extends CI_Model
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
     *
     * @return array
     */
    public function get_all_sys_permission()
    {
        // 用户的菜单
        $this->db->select('*, sys_permission_id as id, permission_name as value');
        $this->db->order_by('sys_permission_id', 'ASC');
        $query = $this->db->get('sys_permission');
        $sys_permission = $query->result();

        // 构造系统菜单 [层级关系]
        $sys_permission_structure = $this->common_model->get_tree_data($sys_permission, 0);
        $sys_permission = [];
        // 获取子菜单不为空的 菜单
        foreach ($sys_permission_structure as $_key => $_sys_permission_structure) {
            if (isset($_sys_permission_structure->child) && !empty($_sys_permission_structure->child)) {
                $sys_permission[] = $_sys_permission_structure;
            }
        }
        return $sys_permission;
    }
}
