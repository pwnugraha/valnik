<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Base_model extends CI_Model
{

    //isset for non array and empty for array

    public function get_item($search_type, $table, $select = NULL, $where = NULL, $groupby = NULL, $order = NULL, $limit = NULL, $offset = NULL)
    {
        if (isset($select)) {
            $this->db->select($select);
        }
        if (isset($order)) {
            $this->db->order_by($order);
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($groupby)) {
            $this->db->group_by($groupby);
        }

        $query = $this->db->get($table, $limit, $offset);
        if ($query->num_rows() > 0) {
            if ($search_type == 'result') {
                $result = $query->result_array();
            } else {
                $result = $query->row_array();
            }
            $query->free_result();
            return $result;
        } else {
            return FALSE;
        }
    }

    //relation example table1.id = table2.table1_id. option example is inner, outer, etc
    public function get_join_item($search_type, $select, $order = NULL, $table1, $table_join, $relation, $option = NULL, $where = NULL, $groupby = NULL, $limit = NULL, $offset = NULL)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($groupby)) {
            $this->db->group_by($groupby);
        }
        if (isset($order)) {
            $this->db->order_by($order);
        }

        $this->db->select($select);
        for ($i = 0; $i < count($table_join); $i++) {
            $this->db->join($table_join[$i], $relation[$i], $option[$i]);
        }

        $query = $this->db->get($table1, $limit, $offset);

        if ($query->num_rows() > 0) {
            if ($search_type == 'result') {
                $result = $query->result_array();
            } else {
                $result = $query->row_array();
            }
            $query->free_result();
            return $result;
        } else {
            return FALSE;
        }
    }

    function insert_item($table, $data, $return = NULL)
    {
        $query = $this->db->insert($table, $data);
        if ($return == 'id' && $query) {
            return $this->db->insert_id();
        }
        return $query;
    }

    //added 14 juli 2018
    function insert_batch_item($table, $data)
    {
        $query = $this->db->insert_batch($table, $data);
        return $query;
    }

    function update_item($table, $set = array(), $where = array())
    {
        $query = $this->db->update($table, $set, $where);
        return $this->db->affected_rows();
    }

    //added 21 November 2019
    function update_batch($table, $data, $column_key)
    {
        $query = $this->db->update_batch($table, $data, $column_key);
        return $this->db->affected_rows();
    }

    function delete_item($table, $where = array())
    {
        $query = $this->db->delete($table, $where);
        return $query;
    }

    //added 03 desember 2019
    function replace_item($table, $data = array())
    {
        $query = $this->db->replace($table, $data);
        return $query;
    }


    //This function used to check if there is data in current table
    function count_result_item($table, $where = NULL)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->count_all_results($table);
        return $query;
    }

    //If you want to delete all data from a table or empty_table().

    function empty_table($table)
    {
        return $this->db->empty_table($table);
    }
}
