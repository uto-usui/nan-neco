<?php
/**
 * Data processing class
 *
 * @copyright  Copyright (c) Yoshika
 * @author     Yoshika (@rnsk)
 * @link       http://rnsk.net/
 * @license    MIT License
 */
class PluginModel
{

/**
 * Database table name
 *
 * @var string
 */
    public $dbTable;

/**
 * Construct
 *
 * @return void
 */
    public function __construct($dbTable = null)
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        if (!empty($dbTable)) {
            $this->dbTable = $dbTable;
        }
    }

/**
 * Get single data from a database
 *
 * @param integer $id Data ID
 * @param string $fields Name of the columns
 * @return array
 */
    public function getFirst($id = null, $fields = '*', $where = array())
    {
        if ((empty($id)) || (empty($this->dbTable))) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . $this->dbTable;

        $where_str = '';
        if ((is_array($where)) && (!empty($where))) {
            $_where = array();
            foreach ($where as $key => $value) {
                $_where[] = $key . ' ' . $value;
            }
            $where_str = implode(' AND ', $_where);
        }

        $sql = "SELECT {$fields} FROM {$table_name} WHERE id = {$id}{$where_str}";

        $result = $wpdb->get_row($sql, ARRAY_A);
        return $result;
    }

/**
 * Get multiple data from the database
 *
 * @param string $fields Name of the columns
 * @return array
 */
    public function getAll($fields = '*', $where = array())
    {
        if (empty($this->dbTable)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . $this->dbTable;

        $where_str = '';
        if ((is_array($where)) && (!empty($where))) {
            $_where = array();
            foreach ($where as $key => $value) {
                $_where[] = $key . ' ' . $value;
            }
            $where_str = implode(' AND ', $_where);
            $where_str = ' WHERE ' . $where_str;
        }

        $sql = "SELECT {$fields} FROM {$table_name}{$where_str}";

        $results = $wpdb->get_results($sql, ARRAY_A);
        return $results;
    }

/**
 * Get single field from a database
 *
 * @param string $field Name of the columns
 * @return string field contents, or false if not found
 */
    public function getField($field = null, $where = array())
    {
        if ((empty($field)) || (empty($this->dbTable))) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . $this->dbTable;

        $where_str = '';
        if ((is_array($where)) && (!empty($where))) {
            $_where = array();
            foreach ($where as $key => $value) {
                $_where[] = $key . ' ' . $value;
            }
            $where_str = implode(' AND ', $_where);
            $where_str = ' WHERE ' . $where_str;
        }

        $sql = "SELECT {$field} FROM {$table_name}{$where_str}";

        $result = $wpdb->get_var($sql);
        return $result;
    }

/**
 * Remove data from the database
 *
 * @param integer $id Data ID
 * @return boolean
 */
    public function delete($id = null)
    {
        if ((empty($id)) || (empty($this->dbTable))) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . $this->dbTable;

        $sql = "DELETE FROM {$table_name} WHERE id = {$id}";
        return $wpdb->query($sql);
    }
}
