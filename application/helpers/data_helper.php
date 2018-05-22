<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * DataTable 专用 - 根据id获取值
 * @param int $id
 * @param array $row
 * @param array $data
 *
 * @return string
 */
function get_value_by_id($id, $row, $data)
{
    foreach ($data as $_data) {
        if ($_data->id == $id) {
            return $_data->value;
            break;
        }
    }
    return '';
}

/**
 * DataTable 专用 - 格式化时间
 * @param mixed $value
 * @param array $row
 * @param array $data
 *
 * @return string
 */
function date_formatter($value, $row, $data)
{
    $formatter_date = date('Y-m-d H:i:s', $value);
    return $formatter_date;
}
