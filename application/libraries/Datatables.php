<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Helper functions for building a DataTables server-side processing SQL query
 *
 * The static functions in this class are just helper functions to help build
 * the SQL used in the DataTables demo server-side processing scripts. These
 * functions obviously do not represent all that can be done with server-side
 * processing, they are intentionally simple to show how it works. More complex
 * server-side processing operations will likely require a custom script.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

class Datatables
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     *
     * @param array $columns Column information array
     * @param array $data Data from the SQL get
     * @param int sql start
     * @return array Formatted data in a row based format
     */
    public function data_output($columns, $data, $offset)
    {
        $this->CI->load->helper('data');
        $out = array();

        $row_num = $offset + 1;

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();
            $row_data = array();
            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];
                if (isset($column['isRowData']) && $column['isRowData']) {
                    if (isset($column['formatter'])) {
                        $row_data[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i], $column['formatter_data']);
                    } else {
                        $row_data[$column['dt']] = $data[$i][$columns[$j]['db']];
                    }
                } else {
                    // Is there a formatter?
                    if (isset($column['formatter'])) {
                        $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i], $column['formatter_data']);
                        $row_data[$column['dt']] = $data[$i][$columns[$j]['db']];
                    } else {
                        $row[$column['dt']] = $data[$i][$columns[$j]['db']];
                    }
                }
            }
            if (count($row_data) > 0) {
                $row['DT_RowData'] = $row_data;
            }
            ///每一行的序列号
            $row['RowId'] = $row_num;
            $row_num++;
            $out[] = $row;
        }

        return $out;
    }

    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     * @param  array $request Data sent to server by DataTables
     * @param  array $columns Column information array
     * @return string SQL limit clause
     */
    static function limit($request, $columns)
    {
        $limit = '';

        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
        }

        return $limit;
    }


    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     * @param  array $request Data sent to server by DataTables
     * @param  array $columns Column information array
     * @return string SQL order by clause
     */
    static function order($request, $columns)
    {
        $order = '';

        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');

            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';

                    $orderBy[] = '`' . $column['db'] . '` ' . $dir;
                }
            }

            $order = 'ORDER BY ' . implode(', ', $orderBy);
        }

        return $order;
    }

    /**
     *
     * @param string $table SQL table to query
     * @param array $columns Column information array
     * @param string $cus_filter filter by user
     * @param string $default_filter default filter str
     * @param string $primaryKey Primary key of the table
     * @return array
     */
    function get_datatables_data($table, $columns, $cus_filter, $default_filter = '', $primaryKey = '1')
    {
        $this->CI->load->model('common/common_model');

        $request = $this->CI->input->post();

        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);

        // Main query to actually get the data
        $db_columns = "`" . implode("`, `", self::pluck($columns, 'db')) . "`";
        $data = $this->CI->common_model->get_data_by_datatable($table, $db_columns, $cus_filter, $order, $limit);

        // Data set length after filtering
        $recordsFiltered = $this->CI->common_model->get_all_data_count_by_datatable($table, $cus_filter, $primaryKey);

        // Total data set length
        $recordsTotal = $this->CI->common_model->get_all_data_count_by_datatable($table, $default_filter, $primaryKey);
        /*
         * Output
         */
        return array(
            "draw" => isset ($request['draw']) ? intval($request['draw']) : 0,
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $this->data_output($columns, $data, intval($request['start']))
        );
    }

    /**
     * Pull a particular property from each assoc. array in a numeric array,
     * returning and array of the property values from each item.
     *
     * @param  array $a Array to get data from
     * @param  string $prop Property to read
     * @return array        Array of property values
     */
    static function pluck($a, $prop)
    {
        $out = array();

        for ($i = 0, $len = count($a); $i < $len; $i++) {
            if ($a[$i][$prop] != 'null') {
                $out[] = $a[$i][$prop];
            }

        }

        return $out;
    }

    /**
     * Parse filter array.
     * @param array $filter_arr
     *
     * @return string
     */
    function parse_filter_array($filter_arr)
    {
        $filter_str = "";
        foreach ($filter_arr as $filter):
            $filter_field = $filter['FieldName'];
            $filter_type = $filter['FilterType'];
            $filter_value = $filter['FilterValue'];
            $in_parenthese = isset($filter['InParenthese']) ? $filter['InParenthese'] : 'NA';

            $type = "AND";
            if ($in_parenthese == 'AndStart') {
                $filter_str = $filter_str . " And (";
                $type = " ";
            }
            if ($in_parenthese == 'OrStart') {
                $filter_str = $filter_str . " OR (";
                $type = " ";
            }
            if ($in_parenthese == 'OrIn' || $in_parenthese == 'OrEnd') {
                $type = " OR ";
            }
            if ($in_parenthese == 'AndIn' || $in_parenthese == 'AndEnd') {
                $type = " AND ";
            }
            switch ($filter_type) {
                case 'eq':
                    $filter_str = $this->get_compare_str($filter_str, $filter_field, '=', $filter_value, $type);
                    break;
                case 'neq':
                    $filter_str = $this->get_compare_str($filter_str, $filter_field, '<>', $filter_value, $type);
                    break;
                case 'lt':
                    $filter_str = $this->get_compare_str($filter_str, $filter_field, '<', $filter_value, $type);
                    break;
                case 'lteq':
                    $filter_str = $this->get_compare_str($filter_str, $filter_field, '<=', $filter_value, $type);
                    break;
                case 'gt':
                    $filter_str = $this->get_compare_str($filter_str, $filter_field, '>', $filter_value, $type);
                    break;
                case 'gteq':
                    $filter_str = $this->get_compare_str($filter_str, $filter_field, '>=', $filter_value, $type);
                    break;
                case 'llk':
                    $filter_str = $this->get_like_str($filter_str, $filter_field, 'LIKE', $filter_value, 'left', $type);
                    break;
                case 'rlk':
                    $filter_str = $this->get_like_str($filter_str, $filter_field, 'LIKE', $filter_value, 'right', $type);
                    break;
                case 'blk':
                    $filter_str = $this->get_like_str($filter_str, $filter_field, 'LIKE', $filter_value, 'both', $type);
                    break;
                case 'nllk':
                    $filter_str = $this->get_like_str($filter_str, $filter_field, 'NOT LIKE', $filter_value, 'left', $type);
                    break;
                case 'nrlk':
                    $filter_str = $this->get_like_str($filter_str, $filter_field, 'NOT LIKE', $filter_value, 'right', $type);
                    break;
                case 'nblk':
                    $filter_str = $this->get_like_str($filter_str, $filter_field, 'NOT LIKE', $filter_value, 'both', $type);
                    break;
                case 'in':
                    $filter_str = $this->get_in_str($filter_str, $filter_field, 'IN', $filter_value, $type);
                    break;
                case 'nin':
                    $filter_str = $this->get_in_str($filter_str, $filter_field, 'NOT IN', $filter_value, $type);
                    break;
                case 'isn':
                    $filter_str = $this->get_null_str($filter_str, $filter_field, 'IS', $filter_value, $type);
                    break;
                case 'isnn':
                    $filter_str = $this->get_null_str($filter_str, $filter_field, 'IS NOT', $filter_value, $type);
                    break;
                case 'fdeq':
                    $filter_str = $this->get_fields_compare_str($filter_str, $filter_field, '=', $filter_value, $type);
                    break;
                case 'fdneq':
                    $filter_str = $this->get_fields_compare_str($filter_str, $filter_field, '<>', $filter_value, $type);
                    break;
            }
            if ($in_parenthese == 'AndEnd' || $in_parenthese == 'OrEnd') {
                $filter_str = $filter_str . ")";
            }
            //	echo $filter_str;
        endforeach;

        if ($filter_str != "") {
            $filter_str = " WHERE " . $filter_str;
        }

        return $filter_str;
    }

    /**
     * Get fields compare string.
     * @param $filter_str
     * @param $field1
     * @param string $operator
     * @param $field2
     * @param string $type
     *
     * @return string
     */
    function get_fields_compare_str($filter_str, $field1, $operator = '=', $field2, $type = 'And')
    {
        $return_str = $filter_str;
        if ($return_str != "") {
            $return_str = $return_str . " " . $type . " " . " " . $field1 . " " . $operator . " " . $field2;
        } else {
            $return_str = $field1 . " " . $operator . " " . $field2;
        }
        return $return_str;
    }

    /**
     * Get compare string.
     * eq(=), neq(<>), lt(<), lteq(<=), gt(>), gteq(>=)
     *
     * @param string $filter_str
     * @param string $field
     * @param string $operator
     * @param string $value
     * @param string $type
     * @return string $return_str
     * return query string
     */
    function get_compare_str($filter_str, $field, $operator = '=', $value, $type = 'And')
    {
        //	echo $value;
        $return_str = $filter_str;
        if ($return_str != "") {
            if ($value === 'NULLVAL') {
                $return_str = $return_str . " " . $type . " " . $field . " IS NULL ";
            } else {
                if ($operator == '=' || $operator == '<>') {
                    $return_str = $return_str . " " . $type . " " . $field . " " . $operator . " '" . $value . "'";
                } else {
                    $return_str = $return_str . " " . $type . " " . $field . " " . $operator . " '" . $value . "'";
                }
            }
        } else {
            if ($value === 'NULLVAL') {
                $return_str = $field . " IS NULL ";
            } else {
                if ($operator == '=' || $operator == '<>') {
                    $return_str = $field . " " . $operator . " '" . $value . "'";
                } else {
                    $return_str = $field . " " . $operator . " '" . $value . "'";
                }
            }
        }

        return $return_str;
    }

    /**
     * Get like query string.
     * @param $filter_str
     * @param $field
     * @param string $operator
     * @param $value
     * @param string $side
     * @param string $type
     *
     * @return string
     */
    function get_like_str($filter_str, $field, $operator = 'LIKE', $value, $side = 'both', $type = 'And')
    {
        $return_str = $filter_str;
        $f_str = "";

        if ($side == 'both') {
            $f_str = $field . " " . $operator . " '%" . $value . "%'";
        } elseif ($side == 'left') {
            $f_str = $field . " " . $operator . " '" . $value . "%'";
        } elseif ($side == 'right') {
            $f_str = $field . " " . $operator . " '%" . $value . "'";
        }
        if ($return_str != "") {
            $return_str = $return_str . " and " . $f_str;
        } else {
            $return_str = $f_str;
        }

        return $return_str;
    }

    /**
     * Get like query string.
     * @param $filter_str
     * @param $field
     * @param string $operator
     * @param $value
     * @param string $type
     *
     * @return string
     */
    function get_in_str($filter_str, $field, $operator = 'IN', $value, $type = 'And')
    {
        $return_str = $filter_str;

        if ($value != "") {
            if ($return_str != "") {
                $return_str = $return_str . " " . $type . " " . $field . " " . $operator . " (" . $value . ")";
            } else {
                $return_str = $field . " " . $operator . " (" . $value . ")";
            }
        }
        return $return_str;
    }

    /**
     * Get null query string.
     * @param $filter_str
     * @param $field
     * @param string $operator
     * @param $value
     * @param string $type
     *
     * @return string
     */
    function get_null_str($filter_str, $field, $operator = 'is', $value, $type = 'And')
    {
        $return_str = $filter_str;

        if ($value = 'NULL') {
            if ($return_str != "") {
                $return_str = $return_str . " " . $type . " " . $field . " " . $operator . " NULL ";
            } else {
                $return_str = $field . " " . $operator . " NULL";
            }
        }
        return $return_str;
    }


    /**
     * Get customize filter data.
     * @param $default_right
     * @param $filter_arr
     *
     * @return string
     */
    function get_cus_filter_data($default_right, $filter_arr)
    {
        $filter_data = array();
        $filter_str = "";
        foreach ($filter_arr as $filter):
            // Initial filter value is FALSE
            $filter_value = FALSE;
            $filter_field = $filter['FieldName'];
            $filter_type = $filter['FilterType'];
            // Get filter value from DOM element
            $dom_value = $this->CI->input->get_post($filter['DomName'], true);
            if ($dom_value != "") {
                if ($dom_value == 'SHOWALL')
                    continue;
                $filter_value = $dom_value;
            } else {
                if (isset($filter['DefaultValue'])) {
                    $filter_value = $filter['DefaultValue'];
                }
            }
            // If DOM element is not empty or set defalut filter value, then get filter string
            if ($filter_value !== FALSE) {
                $filter_data['DomValue'][] = array($filter['DomName'] => $filter_value);
                $filter_data['FilterArr'][] = array('FieldName' => $filter_field, 'FilterType' => $filter_type, 'FilterValue' => $filter_value);
                switch ($filter_type) {
                    case 'eq':
                        $filter_str = $this->get_compare_str($filter_str, $filter_field, '=', $filter_value, 'And');
                        break;
                    case 'neq':
                        $filter_str = $this->get_compare_str($filter_str, $filter_field, '<>', $filter_value, 'And');
                        break;
                    case 'lt':
                        $filter_str = $this->get_compare_str($filter_str, $filter_field, '<', $filter_value, 'And');
                        break;
                    case 'lteq':
                        $filter_str = $this->get_compare_str($filter_str, $filter_field, '<=', $filter_value, 'And');
                        break;
                    case 'gt':
                        $filter_str = $this->get_compare_str($filter_str, $filter_field, '>', $filter_value, 'And');
                        break;
                    case 'gteq':
                        $filter_str = $this->get_compare_str($filter_str, $filter_field, '>=', $filter_value, 'And');
                        break;
                    case 'llk':
                        $filter_str = $this->get_like_str($filter_str, $filter_field, 'LIKE', $filter_value, 'left', 'And');
                        break;
                    case 'rlk':
                        $filter_str = $this->get_like_str($filter_str, $filter_field, 'LIKE', $filter_value, 'right', 'And');
                        break;
                    case 'blk':
                        $filter_str = $this->get_like_str($filter_str, $filter_field, 'LIKE', $filter_value, 'both', 'And');
                        break;
                    case 'nllk':
                        $filter_str = $this->get_like_str($filter_str, $filter_field, 'NOT LIKE', $filter_value, 'left', 'And');
                        break;
                    case 'nrlk':
                        $filter_str = $this->get_like_str($filter_str, $filter_field, 'NOT LIKE', $filter_value, 'right', 'And');
                        break;
                    case 'nblk':
                        $filter_str = $this->get_like_str($filter_str, $filter_field, 'NOT LIKE', $filter_value, 'both', 'And');
                        break;
                    case 'in':
                        $filter_str = $this->get_in_str($filter_str, $filter_field, 'IN', $filter_value, 'And');
                        break;
                    case 'nin':
                        $filter_str = $this->get_in_str($filter_str, $filter_field, 'NOT IN', $filter_value, 'And');
                        break;
                    case 'isn':
                        $filter_str = $this->get_null_str($filter_str, $filter_field, 'IS', $filter_value, 'And');
                        break;
                    case 'isnn':
                        $filter_str = $this->get_null_str($filter_str, $filter_field, 'IS NOT', $filter_value, 'And');
                        break;
                    case 'fdeq':
                        $filter_str = $this->get_fields_compare_str($filter_str, $filter_field, '=', $filter_value, 'And');
                        break;
                    case 'fdneq':
                        $filter_str = $this->get_fields_compare_str($filter_str, $filter_field, '<>', $filter_value, 'And');
                        break;
                }
            }
        endforeach;
        // If not set any filter conditions, initial $filter_data['FilterArr'], $filter_data['DomValue']
        if (!isset($filter_data['FilterArr'])) {
            $filter_data['FilterArr'] = array();
            $filter_data['DomValue'] = array();
        }
        //If $default_right and $filter_str are not empty, merge default right with $filter_str
        if ($default_right != "") {
            if ($filter_str == "") {
                $filter_str = $default_right;
            } else {
                $filter_str = $default_right . " and " . $filter_str;
            }
        } else {
            if ($filter_str == "") {
                $filter_str = "";
            } else {
                $filter_str = " WHERE " . $filter_str;
            }
        }

        $filter_data['FilterStr'] = $filter_str;
        return $filter_str;
    }
}
