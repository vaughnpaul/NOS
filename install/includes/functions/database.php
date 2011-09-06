<?php
/*
  $Id: database.php,v 1.1.1.1 2004/03/04 23:41:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function osc_db_connect($server, $username, $password, $link = 'db_link') {
    global $$link, $db_error;

    $db_error = 'false';

    if (!$server) {
      $db_error = DB_ERROR_06;
      return 'true';
    }

    $$link = @mysql_connect($server, $username, $password) or $db_error = mysql_error();

    return $$link;
  }
 function osc_db_connect1($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;
      $$link = mysql_connect($server, $username, $password);

    if ($$link) mysql_select_db($database);

    return $$link;
  }

  function osc_db_select_db($database) {
    return mysql_select_db($database);
  }

  function osc_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
  }

function osc_db_error($query, $errno, $error) {

    $error_a = '<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[OSC STOP]</font></small><br><br></b></font>';
    return $error_a;
  }

  function osc_db_query($query, $link = 'db_link') {
    global $$link;
  $result = mysql_query($query, $$link) or osc_db_error($query, mysql_errno(), mysql_error());

   return $result;
  }

  function osc_db_fetch_array($db_query) {
    return mysql_fetch_array($db_query);
  }

  function osc_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

  function osc_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

  function osc_db_insert_id() {
    return mysql_insert_id();
  }


function osc_db_input($string) {
    return addslashes($string);
  }

  function osc_db_free_result($db_query) {
    return mysql_free_result($db_query);
  }


  function osc_db_test_db_empty($database) {
    global $db_error, $data_error;

    $db_error = 'false';

    if ( $table_list = @mysql_list_tables($database) ) {
      if ( osc_db_num_rows($table_list) > 0 ) {
        $db_error = DB_ERROR_09;
        return 'false';
      } else {
        return 'true';
      }
    } else {
      $db_error = DB_ERROR_01;
      $data_error = mysql_error();
      return 'true';
    }
  }


  function osc_db_test_create_db_permission($database) {
    global $db_error, $data_error;

    $db_created = 'false';
    $db_error = 'false';

    if (!$database) {
      $db_error = DB_ERROR_06;
      return 'true';
    }

    if ($db_error != 'false') {
      if (osc_db_select_db($database)) {
        $db_created = 'true';
        $db_error = DB_ERROR_01;
        $data_error = mysql_error();
        if (!@osc_db_query('create database ' . $database)) {
          $db_error = DB_ERROR_07 ;
          $data_error = mysql_error();
        }
      } else {

      }
      if ($db_error == 'false') {
        if (@osc_db_select_db($database)) {
          if (@osc_db_query('create table temp ( temp_id int(5) )')) {
            if (@osc_db_query('drop table temp')) {
              if ($db_created) {
                if (@osc_db_query('drop database ' . $database)) {
                } else {
                  $db_error = DB_ERROR_05;
                  $data_error = mysql_error();
                }
              }
            } else {
               $db_error = DB_ERROR_02;
               $data_error = mysql_error();
            }
          } else {
             $db_error = DB_ERROR_04;
             $data_error = mysql_error();
          }
        } else {
            $db_error = DB_ERROR_03;
            $data_error = mysql_error();
        }
      }
    }

    if ($db_error == 'false') {
      return 'false';
    } else {
      return 'true';
      return $db_error;
      return $data_error;
    }
  }

  function osc_db_test_connection($database) {
    global $db_error, $data_error;

//initalize variables
    $db_error = 'false';
    $data_error = '';

    if ($db_error == 'false') {
      if (!@osc_db_select_db($database)) {
      //no database found
         return 'false';
      } else {
       //databse found, check configuration table for data
        if (!osc_db_query('select count(*) from configuration')) {
        //db exists but has no configuration table
        $db_error = 'true';
        $data_error = DB_ERROR_10 . $database;
        $db_error = mysql_error();
        return $data_error;
        return $db_error;
        } else {
        //db exists but has configuration table
         $db_error = 'true';
         $data_error = DB_ERROR_11 . $database;
         $db_error = mysql_error();
         return $data_error;
         return $db_error;
        }
      }
    }
  }

  function osc_db_install($database, $sql_file) {
    global $db_error, $data_error;

    $db_error = 'false';
    $data_error = '';

    if (!osc_db_select_db($database)) {
      // check to see if database exists, if it does not create it

      if (osc_db_query('create database ' . $database)) {
          osc_db_select_db($database);
        } else {
        return 'true';
        $data_error = DB_ERROR_12 . $database;
        $db_error = mysql_error();
        return $data_error;
        return $db_error;
     //  }
      }
    }

    if ($db_error == 'false') {
      if (file_exists($sql_file)) {
        $fd = fopen($sql_file, 'rb');
        $restore_query = fread($fd, filesize($sql_file));
        fclose($fd);
      } else {
        $db_error = DB_ERROR_13 . $sql_file;
        $data_error = mysql_error();

        return 'true';
        return $data_error;
        return $db_error;
      }

      $sql_array = array();
      $sql_length = strlen($restore_query);
      $pos = strpos($restore_query, ';');
      for ($i=$pos; $i<$sql_length; $i++) {
        if (($restore_query[0] == '#') or ($restore_query[0] == '--')){
          $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
          $sql_length = strlen($restore_query);
          $i = strpos($restore_query, ';')-1;
          continue;
        }
        if ($restore_query[($i+1)] == "\n") {
          for ($j=($i+2); $j<$sql_length; $j++) {
            if (trim($restore_query[$j]) != '') {
              $next = substr($restore_query, $j, 6);
              if (($next[0] == '#') or ($next[0] == '--')){
// find out where the break position is so we can remove this line (#comment line)
                for ($k=$j; $k<$sql_length; $k++) {
                  if ($restore_query[$k] == "\n") break;
                }
                $query = substr($restore_query, 0, $i+1);
                $restore_query = substr($restore_query, $k);
// join the query before the comment appeared, with the rest of the dump
                $restore_query = $query . $restore_query;
                $sql_length = strlen($restore_query);
                $i = strpos($restore_query, ';')-1;
                continue 2;
              }
              break;
            }
          }
          if ($next == '') { // get the last insert query
            $next = 'insert';
          }
          if ( (eregi('create', $next)) || (eregi('insert', $next)) || (eregi('update', $next)) || (eregi('drop t', $next)) ) {
            $next = '';
            $sql_array[] = substr($restore_query, 0, $i);
            $restore_query = ltrim(substr($restore_query, $i+1));
            $sql_length = strlen($restore_query);
            $i = strpos($restore_query, ';')-1;
          }
        }
      }

      for ($i=0; $i<sizeof($sql_array); $i++) {
        osc_db_query($sql_array[$i]);
      }
      return $data_error;
      return $db_error;
    } else {
      return 'false';
    }
  }



  function osc_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
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
              $query .= '\'' . osc_db_input($value) . '\', ';
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
              $query .= $columns . ' = \'' . osc_db_input($value) . '\', ';
              break;
          }
        }
        $query = substr($query, 0, -2) . ' where ' . $parameters;
      }

      return osc_db_query($query, $link);
    }

?>
