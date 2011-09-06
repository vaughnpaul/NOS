<?php
/*
  $Id: database.php,v 1.1.1.1 2004/03/04 23:40:48 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link = mysql_pconnect($server, $username, $password);
    } else {
      $$link = mysql_connect($server, $username, $password);
    }

    if ($$link) mysql_select_db($database);

    return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
  }

  function tep_db_error($query, $errno, $error) {
    global $PHP_SELF;
    $msg = 'Query Error reported on page ' . $PHP_SELF . "\n" . 'MySQL error: ' . $errno . ' - ' . $error . "\n\n" . $query . "\n\n";
    $email_address = '';
    if (defined('STORE_OWNER_EMAIL_ADDRESS')) $email_address = STORE_OWNER_EMAIL_ADDRESS;
    if (defined('TEP_STOP_NOTIFY')) $email_address = TEP_STOP_NOTIFY;
    if ($email_address != '') {
      tep_mail('Stop Notification', $email_address, 'Critical Store Error', $msg, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
    // log the error message
    _error_handler($msg);
    die(INTERNAL_ERROR);
  }

  function tep_db_query($query, $link = 'db_link') {
    global $$link;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      $page_name = $_SERVER['SCRIPT_FILENAME'];
      $page_name = str_replace('\\', '/', $page_name);
      $page_name = str_replace('//', '/', $page_name);
      $i = strrpos($page_name, '/');
      $page_name = substr($page_name, $i+1);
      error_log('QUERY - ' . $page_name . ':'. "\n" . $query . "\n", 3, DIR_FS_CATALOG . STORE_PAGE_PARSE_TIME_LOG);
      $sql_start = microtime(true);
    }

    $result = mysql_query($query, $$link) or tep_db_error($query, mysql_errno(), mysql_error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      $sql_end = microtime(true);
      $sql_exec_time = $sql_end - $sql_start;
      $row_count = mysql_num_rows($result);
      error_log('RESULT - time: ' . $sql_exec_time . ' rows: ' . $row_count . "\n\n", 3, DIR_FS_CATALOG . STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }
  
  function tep_db_insert_delayed($table, $data, $parameters = '', $link = 'db_link') {
    reset($data);
    $query = 'INSERT DELAYED INTO ' . $table . ' (';
    while (list($columns, ) = each($data)) {
      $query .= $columns . ', ';
    }
    $query = substr($query, 0, -2) . ') values (';
    reset($data);
    while (list(, $value) = each($data)) {
      switch ((string)$value) {
        case 'now()':
          $query .= 'now(), ';
          break;
        case 'null':
          $query .= 'null, ';
          break;
        default:
          $query .= '\'' . tep_db_input($value) . '\', ';
          break;
      }
    }
    $query = substr($query, 0, -2) . ')';
      
    return tep_db_query($query, $link);
  }  

  function tep_db_fetch_array($db_query) {
    return mysql_fetch_array($db_query, MYSQL_ASSOC);
  }

  function tep_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id() {
    return mysql_insert_id();
  }

  function tep_db_free_result($db_query) {
    return mysql_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return mysql_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string) {
    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(tep_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }

  //Eversun mod for sppc and qty price breaks
 function tep_db_table_exists($table, $link = 'db_link') {
    $result = tep_db_query("show table status from `" . DB_DATABASE . "`");
    while ($list_tables = tep_db_fetch_array($result)) {
    if ($list_tables['Name'] == $table) {
      return true;
    }
    }
    return false;
  }

  function tep_db_decoder($string) {
    $string = str_replace('&#39;', "'", $string);
    $string = str_replace('&#39', "'", $string); //backword compatabiliy
    return $string;
  }
?>