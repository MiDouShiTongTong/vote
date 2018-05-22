<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Common_model
 *
 */
class Common_model extends CI_Model
{

    /**
     * Common_model constructor.
     *
     */
    public function __construct()
    {

    }

    /**
     * Get data
     *
     * @param $table
     * @param string $field
     * @param $where
     * @param $order_by
     * @param $limit
     * @param string $return_type
     *
     * @return mixed
     */
    public function get($table, $field = '*', $where = '', $order_by = '', $limit = '', $return_type = 'object')
    {
        $this->db->select($field);
        if ($where != '') {
            $this->db->where($where);
        }
        if ($order_by != '') {
            $this->db->order_by($order_by);
        }
        if ($limit != '') {
            $this->db->limit($limit);
        }
        $query = $this->db->get($table);
        return $return_type != 'object' ? $query->result_array() : $query->result();
    }

    /**
     * 返回新增的索引id
     *
     * @return int
     */
    public function get_insert_id()
    {
        return $this->db->insert_id();
    }

    /**
     * Insert data to table
     *
     * @param  string $table_name 表名
     * @param  array $data 数据
     * @return int
     */
    public function insert($table_name, $data)
    {
        $this->db->insert($table_name, $data);
        return $this->db->affected_rows();
    }

    /**
     * Insert Batch to table
     *
     * @param  string $table_name 表名
     * @param  array $data 数据
     * @return int
     */
    public function insert_batch($table_name, $data)
    {
        $this->db->insert_batch($table_name, $data);
        return $this->db->affected_rows();
    }

    /**
     * Update data to table
     *
     * @param  string $table_name 表名
     * @param  array $data 数据
     * @param  array $condition 获取条件
     * @return boolean
     */
    public function update($table_name, $data, $condition = [])
    {
        $this->db->update($table_name, $data, $condition);
        return $this->db->affected_rows();
    }

    /**
     * Delete data to table
     *
     * @param  string $table_name 表名
     * @param  array $condition 获取条件
     * @return boolean
     */
    public function delete($table_name, $condition = [])
    {
        $this->db->delete($table_name, $condition);
        return $this->db->affected_rows();
    }

    /**
     * Get record count
     * @param $table
     * @param string $where
     * @param string $primaryKey
     *
     * @return int
     */
    public function count_all($table, $where = '', $primaryKey = '')
    {
        $this->db->select("COUNT(" . $primaryKey . ") AS count");
        if ($where != '') {
            $this->db->where($where);
        }
        $query = $this->db->get($table);
        return $query->result()[0]->count;
    }

    /**
     * ge table data
     *
     * @param string $table
     * @param string $field
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return array
     */
    public function get_data_by_datatable($table, $field, $where, $order, $limit)
    {
        $sql = "SELECT " . $field . " FROM `$table`  $where $order $limit";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * Get record count
     * @param string $table
     * @param string $where_str
     * @param string $primaryKey
     *
     * @return int
     */
    public function get_all_data_count_by_datatable($table, $where_str = '', $primaryKey = '1')
    {
        $sql = "SELECT COUNT(" . $primaryKey . ") AS cnt FROM " . $table . " " . $where_str;
        $query = $this->db->query($sql)->row_array();
        return $query['cnt'];
    }

    /**
     * 构造系统菜单
     *
     * @param  object $data 对象
     * @param  int $parent_id 父导航 id
     * @return object
     */
    public function get_tree_data($data, $parent_id)
    {
        $tree_data_by_parent_id = $this->get_tree_data_by_parent_id($data, $parent_id);
        if (empty($tree_data_by_parent_id)) return [];
        foreach ($tree_data_by_parent_id as $_key => $_tree_data_by_parent_id) {
            $tree_data = $this->get_tree_data($data, $_tree_data_by_parent_id->id);
            if (!empty($tree_data)) $_tree_data_by_parent_id->child = $tree_data;
        }
        return $tree_data_by_parent_id;
    }

    /**
     * 根据parent_id 返回子菜单
     *
     * @param  object $data 对象
     * @param  int $parent_id 父导航 id
     * @return array
     */
    public function get_tree_data_by_parent_id($data, $parent_id)
    {
        $tree_data_by_parent_id = [];
        foreach ($data as $_key => $_data) {
            if ($_data->parent_id == $parent_id) $tree_data_by_parent_id[] = $_data;
        }
        return $tree_data_by_parent_id;
    }
}
