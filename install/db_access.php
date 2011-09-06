<?php
/*
  Released under the GNU General Public License
*/
  // Set the level of error reporting
  error_reporting(E_ALL ^ E_NOTICE);
  
  // find the server file path
  $fs_array = explode('/', dirname($_SERVER['SCRIPT_FILENAME']));
  $fs_path_array = array();
  for ($i=0, $n=sizeof($fs_array)-1; $i<$n; $i++) {
    $fs_path_array[] = $fs_array[$i];
  }
  $fs_path = implode('/', $fs_path_array) . '/';
  unset($fs_array, $fs_path_array, $i, $n);
  
  // set new error logging in case this module fails to run successfully
  ini_set('error_log', $fs_path . 'debug/db_access.log');
  ini_set('log_errors', 1);
  error_reporting(E_ALL);
  
  
  require('includes/classes/tableProcessor.php');
  require('includes/functions/database.php');
  require('includes/functions/general.php');
  require('includes/languages/language_list.php');
  // Load JsHttpRequest backend.
  require_once "includes/classes/JsHttpRequest.php";
  // Create main library object. You MUST specify page encoding!
  $JsHttpRequest =& new JsHttpRequest("windows-1251");
  // Store resulting data in $_RESULT array (will appear in req.responseJs).
  
  if (!isset($_POST['language_code'])) $_POST['language_code'] = 'en';
  require('includes/languages/'. $languages_list[$_POST['language_code']]['directory'] . '/db_access.php');
  
  // check to see if the DB access variables are available
  if (!isset($_POST['cre_existing_server']) || !isset($_POST['cre_existing_username']) || !isset($_POST['cre_existing_password']) || !isset($_POST['cre_existing_database']) || 
      !isset($_POST['DB_SERVER']) || !isset($_POST['DB_SERVER_USERNAME']) || !isset($_POST['DB_SERVER_PASSWORD']) || !isset($_POST['DB_DATABASE'])) {
    $GLOBALS['_RESULT'] = array('code' => 'error',
                                'action' => $_POST['function_call'],
                                'msg' => DB_ERROR_00
                               );
    return;
  }
  // decrypt logic for cc_nuumbers
  function cc_decrypt($enc) {
    // get key 
    if (!file_exists($_POST['cre_path'] . 'includes/key/cc_key.php')) return false;
    include_once($_POST['cre_path'] . 'includes/key/cc_key.php');
    $key = CC_KEY;
    $enc = base64_decode($enc);
    $key = md5($key);
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $enc, MCRYPT_MODE_ECB, $iv);
    $decrypttext1 = trim($decrypttext);
  
    return ($decrypttext1);
  }   
  // connect to the database
  $db_link_old = '';
  $db_link_new = '';
  
  $error_msg = '<font color="#000000"><b>%s - %s<br><br>%s<br><br><small><font color="#ff0000">[OSC STOP]</font></small><br><br></b></font>';
  
  // take action based on the function being called
  switch ($_POST['function_call']) {
    case '_copy':
      osc_db_connect($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD'], 'db_link_new');
      osc_db_connect($_POST['cre_existing_server'], $_POST['cre_existing_username'], $_POST['cre_existing_password'], 'db_link_old');
      
      $new_tables = array();
      $sql = "SHOW TABLES FROM " . $_POST['DB_DATABASE'];
      $tables_query = mysql_query($sql, $db_link_new);
      if ($tables_query === false) {
        $GLOBALS['_RESULT'] = array('code' => 'error',
                                    'action' => $_POST['function_call'],
                                    'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
        return;
      }
      while ($row = mysql_fetch_row($tables_query)) {
        $new_tables[] = $row[0];
      }
      mysql_free_result($tables_query);
      
      // find out which tables are to be moved over
      $table_names = array();
      $sql = "SHOW TABLES FROM " . $_POST['cre_existing_database'];
      $tables_query = mysql_query($sql, $db_link_old);
      if ($tables_query === false) {
        $GLOBALS['_RESULT'] = array('code' => 'error',
                                    'action' => $_POST['function_call'],
                                    'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
        return;
      }
      while ($row = mysql_fetch_row($tables_query)) {
        $table_names[] = $row[0];
      }
      mysql_free_result($tables_query);
      
      // Set the database that is in use
      if (mysql_select_db($_POST['cre_existing_database'], $db_link_old) === false) {
        $GLOBALS['_RESULT'] = array('code' => 'error',
                                    'action' => $_POST['function_call'],
                                    'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
        return;
      }
      
      // process the tables
      $exclude_tables = array('configuration', 'configuration_group');
      foreach ($table_names as $table) {
        if (in_array($table, $exclude_tables)) continue;
        
        // drop tables in new db
        if (in_array($table, $new_tables)) {
          $sql = "DROP TABLE " . $_POST['DB_DATABASE'] . "." . $table;
          if (mysql_query($sql, $db_link_new) === false) {
            $GLOBALS['_RESULT'] = array('code' => 'error',
                                        'action' => $_POST['function_call'],
                                        'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
            return;
          }
        }
        
        // get the status information for the table
        $sql = "SHOW TABLE STATUS LIKE '$table'";
        $status_query = mysql_query($sql, $db_link_old);
        if ($status_query === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
          return;
        }
        $status = mysql_fetch_assoc($status_query);
        
        // start building the output
        $str = 'CREATE TABLE ' . $_POST['DB_DATABASE'] . "." . $table . ' (' . "\n";
    
        // loop thru the columns and output them
        $sql = "SHOW COLUMNS FROM " . $table;
        $table_query = mysql_query($sql, $db_link_old);
        if ($table_query === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
          return;
        }
        while ($row = mysql_fetch_assoc($table_query)) {
          $nullValue = '';
          if ($row['Null'] != 'YES') $nullValue = ' NOT NULL'; // some version return 'NO', some an empty string
          // because of PHP NULL handling, our normal test  $row['Default'] == 'NULL' does not work
          // the $row['Default'] may be NULL, a null string or have a value
          if (is_null($row['Default'])) {
            if ($row['Null'] == 'YES') { // if nulls are allowed, default it to NULL
              $defaultValue = " DEFAULT NULL";
            } else {  // if nulls are not allowed, then there is no default value
              $defaultValue = '';
            }
          } elseif ($row['Default'] == '') {  // a null string needs to be adjusted to the type
            if ($row['Null'] == 'YES') { // if nulls are allowed, default it to NULL
              $defaultValue = " DEFAULT NULL";
            } elseif (substr($row['Type'], 0, 3) == 'int' ||
                      substr($row['Type'], 0, 7) == 'tinyint' ||
                      substr($row['Type'], 0, 8) == 'smallint' ||
                      substr($row['Type'], 0, 6) == 'bigint' ||
                      substr($row['Type'], 0, 9) == 'mediumint' ) {
              $defaultValue = " DEFAULT 0";
            } elseif ($row['Type'] == 'timestamp' || $row['Type'] == 'datetime') {
              $defaultValue = " DEFAULT '0000-00-00 00:00:00'";
            } elseif ($row['Type'] == 'date') {
              $defaultValue = " DEFAULT '0000-00-00'";
            } elseif ($row['Type'] == 'time') {
              $defaultValue = " DEFAULT '00:00:00";
            } elseif ($row['Type'] == 'year') {
              $defaultValue = " DEFAULT 0000";
            } else {
              $defaultValue = " DEFAULT ''";
            }
          } else {  // there is a default value, use it
            if ($row['Type'] == 'timestamp' && $row['Default'] == 'CURRENT_TIMESTAMP') {
              $defaultValue = " DEFAULT CURRENT_TIMESTAMP";
            } else {
              $defaultValue = " DEFAULT '" . $row['Default'] . "'";
            }
          }
          $extraValue = '';
          if ($row['Extra'] != '') $extraValue =  ' ' . $row['Extra'];
          $str .= '  ' . $row['Field'] . ' ' . $row['Type'] . $nullValue . $defaultValue . $extraValue . ',' . "\n";
        }
            
        // loop thru the indexes and output them
        $tableUniqueIndexes = array();
        $tableNonUniqueIndexes = array();
        $primaryKey = '';
        $sql = "SHOW INDEX FROM " . $table;
        $table_query = mysql_query($sql, $db_link_old);
        if ($table_query === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
          return;
        }
        while ($row = mysql_fetch_assoc($table_query)) {
          if ( $row['Key_name'] == 'PRIMARY' ) {
            $primaryKey .= $row['Column_name'] . ',';
          } elseif ( $row['Non_unique'] == 1 ) {
            if ( isset($tableNonUniqueIndexes[$row['Key_name']]) ) {
              $tableNonUniqueIndexes[$row['Key_name']] .= $row['Column_name'] . ',';
            } else {
              $tableNonUniqueIndexes[$row['Key_name']] = $row['Column_name'] . ',';
            }
          } else {
            if ( isset($tableUniqueIndexes[$row['Key_name']]) ) {
              $tableUniqueIndexes[$row['Key_name']] .= $row['Column_name'] . ',';
            } else {
              $tableUniqueIndexes[$row['Key_name']] = $row['Column_name'] . ',';
            }
          }
        }
        
        // if there is no key of any kind, the str needs to be modified
        if ($primaryKey == '' && count($tableUniqueIndexes) == 0 && count($tableNonUniqueIndexes) == 0 ) {
          $str = substr($str, 0 ,-2);  // remove the new line and the comma
          $str .= "\n";  // add the new line back
        }
    
        if ($primaryKey != '') {
          if ( count($tableUniqueIndexes) > 0 || count($tableNonUniqueIndexes) > 0 ) {
            $str .= '  PRIMARY KEY (' . substr($primaryKey, 0, -1) . '),' . "\n";
          } else {
            $str .= '  PRIMARY KEY (' . substr($primaryKey, 0, -1) . ')' . "\n";
          }
        }
        $i = 1;
        $last = count($tableUniqueIndexes);
        foreach ($tableUniqueIndexes as $key => $value) {
          if ($i < $last) {
            $str .= '  UNIQUE KEY ' . $key . ' (' . substr($value, 0 ,-1) . '),' . "\n";
          } else {
            if (count($tableNonUniqueIndexes) > 0) {
              $str .= '  UNIQUE KEY ' . $key . ' (' . substr($value, 0 ,-1) . '),' . "\n";
            } else {
              $str .= '  UNIQUE KEY ' . $key . ' (' . substr($value, 0 ,-1) . ')' . "\n";
            }
          }
          ++$i;
        }
        $i = 1;
        $last = count($tableNonUniqueIndexes);
        foreach ($tableNonUniqueIndexes as $key => $value) {
          if ($i < $last) {
            $str .= '  KEY ' . $key . ' (' . substr($value, 0 ,-1) . '),' . "\n";
          } else {
            $str .= '  KEY ' . $key . ' (' . substr($value, 0 ,-1) . ')' . "\n";
          }
          ++$i;
        }
    
        // finish up building the output
        $auto = '';
        if ($status['Auto_increment'] != '') $auto = ' AUTO_INCREMENT=' . $status['Auto_increment'];
        $str .= ') ENGINE=' . $status['Engine'] . ' DEFAULT COLLATE=' . $status['Collation'] . $auto . ';' . "\n";
        
    
        $sql = $str;
        if (mysql_query($sql, $db_link_new) === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
          return;
        }
        
        // proceed with moving any data over
        $sql = "SELECT * FROM " . $table;
        $data_query = mysql_query($sql, $db_link_old);
        if ($data_query === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
          return;
        }
        while ($row = mysql_fetch_assoc($data_query)) {
          $sql = "INSERT INTO " . $_POST['DB_DATABASE'] . "." . $table . " SET ";
          foreach ($row as $column => $value) {
            $sql .= $column . "=" . "'" . addslashes($value) . "', ";
          }
          $sql = substr($sql, 0, -2);
          if (mysql_query($sql, $db_link_new) === false) {
            $GLOBALS['_RESULT'] = array('code' => 'error',
                                        'action' => $_POST['function_call'],
                                        'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
            return;
          }
        }
      }
      
      // special logic is needded to move the orders_pay_methods and orders_ship_methods tables
      // becuase of the change in the primary key.  This affect 6.15 not 6.2, but works for both.
      // After the restructure, the new tables will be created, so then a more standard copy can occur.
      
      $GLOBALS['_RESULT'] = array('code' => 'success',
                                  'action' => $_POST['function_call'],
                                  'msg' => ''
                                 );
      return;
      break;
      
    case '_restruct':
      $tblProcessor = new tableProcessor();
      
      // call the method to encode the current table
      $currTable = $tblProcessor->getTableStructure($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD'], $_POST['DB_DATABASE']);
      
      // call the method to encode the reference table
      $refTable = file_get_contents('sql/reference-CRELoaded_Pro_6_4.xml');
      
      // now what is needed to conver the current table into the same structure as the reference table
      $diffTable = $tblProcessor->diffTableStructure($currTable, $refTable, false, false);
      
      unset($currTable);
      unset($refTable);
      
      $action_results = $tblProcessor->applyTableChanges($diffTable, $_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD'], $_POST['DB_DATABASE']);
      foreach ($action_results as $result) {
        if ($result['success'] == 'FALSE') {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => $result['sql'] . '<br>' . $result['msg']
                                     );
          return;
        }
      }
      
      $GLOBALS['_RESULT'] = array('code' => 'success',
                                  'action' => $_POST['function_call'],
                                  'msg' => ''
                                 );
      return;
      break;
    case '_dbupgrade':

      $error = '';
      $ver_type = '';
      $ver_major = '';
      $ver_minor = '';

      if (!file_exists($_POST['cre_path']."admin/includes/version.php") ||
          !is_readable($_POST['cre_path']."admin/includes/version.php")
         ) {
          $error = TEXT_VERSION_ERROR_MSG;
      } else {
        
        $config = file_get_contents($_POST['cre_path']."admin/includes/version.php");

        $pattern = "/'INSTALLED_VERSION_TYPE',\s*'(.*?)'/";
        $match = array();
        if (preg_match($pattern, $config, $match)) {
          $ver_type = trim($match[1]);
        } else {
          $error .= ($error != '' ? "<br>" :'').TEXT_INSTALLED_VERSION_TYPE_MSG;
        }

        $pattern = "/'INSTALLED_VERSION_MAJOR',\s*'(.*?)'/";
        $match = array();
        if (preg_match($pattern, $config, $match)) {
          $ver_major = trim($match[1]);
        } else {
          $error .= ($error != '' ? "<br>" :'').TEXT_INSTALLED_VERSION_MAJOR_MSG;
        }

        $pattern = "/'INSTALLED_VERSION_MINOR',\s*'(.*?)'/";
        $match = array();
        if (preg_match($pattern, $config, $match)) {
          $ver_minor = trim($match[1]);
        } else {
          $error .= ($error != '' ? "<br>" :'').TEXT_INSTALLED_VERSION_MINOR_MSG;
        }
      }  
      
      if ($error != '') {
        $GLOBALS['_RESULT'] = array('code' => 'error',
                                        'action' => $_POST['function_call'],
                                        'msg' => $error);
        return;
      }
                       
      if($ver_type == 'Standard') {
        $upgrade_sql_file = "upgradeto_64pro_from_".$ver_major.$ver_minor."std.sql";
      } else if($ver_type == 'Pro') {
        $upgrade_sql_file = "upgradeto_64pro_from_".$ver_major.$ver_minor."pro.sql";
      } else if($ver_type == 'B2B' || $ver_type == 'proB2B') {
        $upgrade_sql_file = "upgradeto_64pro_from_".$ver_major.$ver_minor."b2b.sql";
      } else {
        $upgrade_sql_file = "upgradeto_64pro_from_".$ver_major.$ver_minor.$ver_type.".sql";
      }
      
      // load in the standard configuration data
      osc_db_connect($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD']);
      osc_db_install($_POST['DB_DATABASE'], 'sql/'.$upgrade_sql_file);
      osc_db_close();

      /******************************/
      //Logic to check to see what modules were installed in the existing site and if these exist in the new site. Coping over module settings for modules that are part of the new site, then the settings are preserved.

      osc_db_connect($_POST['cre_existing_server'], $_POST['cre_existing_username'], $_POST['cre_existing_password'], 'db_link_old');
      mysql_select_db($_POST['cre_existing_database'], $db_link_old);
      osc_db_connect($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD'], 'db_link_new');
      mysql_select_db($_POST['DB_DATABASE'], $db_link_new);
      
      $tableColumns = array();
      $columnNames = '';
        
      $sql = "SHOW COLUMNS FROM configuration";
      $res3 = mysql_query($sql, $db_link_old);
      if ($res3 === false) {
        $GLOBALS['_RESULT'] = array('code' => 'error',
                                    'action' => $_POST['function_call'],
                                    'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
        return;
      }
      while ($data3 = mysql_fetch_assoc($res3)) {
        if ($data3['Field'] == 'configuration_id') {
          continue;
        }
        $tableColumns[] = array('name' => $data3['Field'], 'type' => $data3['Type']); 
        $columnNames .= " " . $data3['Field'] . ", ";
      }
      mysql_free_result($res3);
      $columnNames = substr($columnNames, 0, -2); 
      
      $configuration_key[] = 'MODULE_PAYMENT_INSTALLED';
      $configuration_key[] = 'MODULE_ORDER_TOTAL_INSTALLED';
      $configuration_key[] = 'MODULE_SHIPPING_INSTALLED';
      $configuration_key[] = 'MODULE_CHECKOUT_SUCCESS_INSTALLED';
      $configuration_key[] = 'MODULE_ADDONS_INSTALLED';
      $arr_modules_query = array();

      foreach($configuration_key as $x_configuration_key) {
        $new_installed_value = '';
        
        $main_module_name = str_replace('MODULE_','',$x_configuration_key);
        $main_module_name = str_replace('_INSTALLED','',$main_module_name);
        $main_module_name = strtolower($main_module_name);

        $sql = "select * from configuration where configuration_key = '".$x_configuration_key."'";
        $res1 = mysql_query($sql, $db_link_old);
        if ($res1 === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
          return;
        }
        $data1 = mysql_fetch_assoc($res1);
        mysql_free_result($res1);
        
        $s_sub_modules_string = '';
        if ($data1 !== false) {
          $s_sub_modules_string = trim($data1['configuration_value']);
        }
        if ($s_sub_modules_string == '') {
          continue;
        }

        $arr_sub_modules = explode(";", $s_sub_modules_string);
        $s_module_dir_path = "includes/modules/".$main_module_name."/";

        foreach($arr_sub_modules as $sub_module) { 
          if (file_exists($fs_path.$s_module_dir_path.$sub_module)) {
            $new_installed_value .= $sub_module.';';

            $sub_module_name = substr($sub_module,0,-4);
            if ($main_module_name == 'order_total') {
              $sub_module_name = substr($sub_module_name,3);
            }
            
            $sql = "SELECT * FROM configuration WHERE configuration_key LIKE 'MODULE_".strtoupper($main_module_name)."_".strtoupper($sub_module_name)."_%'";
            $res2 = mysql_query($sql, $db_link_old);
            if ($res2 === false) {
              $GLOBALS['_RESULT'] = array('code' => 'error',
                                          'action' => $_POST['function_call'],
                                          'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
              return;
            } 
            while($data2 = mysql_fetch_assoc($res2)) {
              // loop thru the column names
              $columnValues = '';        
              foreach ( $tableColumns as $columnInfo ) {           
                if (substr($columnInfo['type'], 0, 3) == 'int' ||
                    substr($columnInfo['type'], 0, 7) == 'tinyint' ||
                    substr($columnInfo['type'], 0, 8) == 'smallint' ||
                    substr($columnInfo['type'], 0, 6) == 'bigint' ||
                    substr($columnInfo['type'], 0, 9) == 'mediumint'
                   ) {
                     $columnValues .= " " . (int)$data2[$columnInfo['name']] . ", ";
                   } else {            
                     $columnValues .= "'" . addslashes($data2[$columnInfo['name']]) . "', ";
                   }
              }        
              $columnValues = substr($columnValues, 0, -2);
              $sql = 'REPLACE INTO configuration ( ' .$columnNames.  ') VALUES(' . $columnValues . ');';
              if (mysql_query($sql, $db_link_new) === false) {
                $GLOBALS['_RESULT'] = array('code' => 'error',
                                            'action' => $_POST['function_call'],
                                            'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
                return;
              }     
            } // end of while
            mysql_free_result($res2);
          }
        }
        
        // special check for payment modules installed
        if ($main_module_name == 'payment') {
          $sub_check = explode(';', $new_installed_value);
          if ( ! in_array('paypal.php', $sub_check)) $new_installed_value .= 'paypal.php;';
          if ( ! in_array('cresecure.php', $sub_check)) $new_installed_value .= 'cresecure.php;';
        }
        
        $new_installed_value = substr($new_installed_value, 0, -1);
        $sql = "UPDATE configuration SET configuration_value = '".$new_installed_value."' WHERE configuration_key = '".$x_configuration_key."'";
        if (mysql_query($sql, $db_link_new) === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
          return;
        }      
      }

      
      // check and update the order statsu setting for the admin block
      $tmp_query = mysql_query("SELECT configuration_value FROM configuration WHERE configuration_key = 'ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP' ",$db_link_new);
      if ($tmp_query === false) {
        $GLOBALS['_RESULT'] = array('code' => 'error',
                                    'action' => $_POST['function_call'],
                                    'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
        return;
      }
      $tmp_data = mysql_fetch_assoc($tmp_query);
      $s_order_status_map = $tmp_data['configuration_value'];
      $s_order_status_map_arr = explode(",",$s_order_status_map);
      $orders_status_id = '';
      foreach($s_order_status_map_arr as $tmp_order_status) {
        $tmp_order_status_query = mysql_query("SELECT orders_status_id FROM orders_status WHERE orders_status_name = '" . trim($tmp_order_status) . "' ",$db_link_new);
        if ($tmp_order_status_query === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
          return;
        }
        if (mysql_num_rows($tmp_order_status_query) > 0) {
          $tmp_order_status_data = mysql_fetch_assoc($tmp_order_status_query);
          $orders_status_id .= $tmp_order_status_data['orders_status_id'].',';
        }
      }

      if ($orders_status_id != '') {
        $orders_status_id = substr($orders_status_id, 0, -1);
        $sql = "UPDATE configuration SET configuration_value = '".$orders_status_id."' WHERE configuration_key = 'ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP' ";
        if (mysql_query($sql, $db_link_new) === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
          return;
        }
      }
//===================================================================
// Update MODULE_PAYMENT_PAYPAL_ID and MODULE_PAYMENT_PAYPAL_BUSINESS_ID - Start
      $tmp_admin_query = mysql_query("SELECT admin_email_address FROM admin WHERE admin_groups_id = '1' limit 1 ",$db_link_new);
      if (mysql_num_rows($tmp_admin_query) > 0) {
          
          $tmp_admin_emailid_data = mysql_fetch_assoc($tmp_admin_query);
          $tmp_admin_emailid = $tmp_admin_emailid_data['admin_email_address'];


          
          $tmp_query_payment_paypal_id = mysql_query("SELECT configuration_value FROM configuration WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_ID' ",$db_link_new);
          if (mysql_num_rows($tmp_query_payment_paypal_id) > 0) {

             $tmp_payment_paypal_id_data = mysql_fetch_assoc($tmp_query_payment_paypal_id);
             $tmp_payment_paypal_id = $tmp_payment_paypal_id_data['configuration_value'];

            if ($tmp_payment_paypal_id == '') {
              mysql_query("update configuration set configuration_value = '".$tmp_admin_emailid."' WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_ID' ",$db_link_new);
            }
          }

          $tmp_query_payment_paypal_business_id = mysql_query("SELECT configuration_value FROM configuration WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_BUSINESS_ID' ",$db_link_new);
          if (mysql_num_rows($tmp_query_payment_paypal_business_id) > 0) {

             $tmp_payment_paypal_business_id_data = mysql_fetch_assoc($tmp_query_payment_paypal_business_id);
             $tmp_payment_paypal_business_id = $tmp_payment_paypal_business_id_data['configuration_value'];

            if ($tmp_payment_paypal_id == '') {
              mysql_query("update configuration set configuration_value = '".$tmp_admin_emailid."' WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_BUSINESS_ID' ",$db_link_new);
            }
          }

        }
// Update MODULE_PAYMENT_PAYPAL_ID and MODULE_PAYMENT_PAYPAL_BUSINESS_ID - End
//===================================================================


      /******************************/

      $GLOBALS['_RESULT'] = array('code' => 'success',
                            'action' => $_POST['function_call'],
                            'msg' => ''
                           );
      
      return;
      break;     
    case '_cust':
      osc_db_connect($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD'], 'db_link_new');
      osc_db_connect($_POST['cre_existing_server'], $_POST['cre_existing_username'], $_POST['cre_existing_password'], 'db_link_old');
      
      // find out if the customers is in the old format
      $sql = "SHOW COLUMNS FROM " . $_POST['cre_existing_database'] . ".customers";
      $table_query = mysql_query($sql, $db_link_old);
      if ($table_query === false) {
        $GLOBALS['_RESULT'] = array('code' => 'error',
                                    'action' => $_POST['function_call'],
                                    'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
        return;
      }
      $customers_column = array();
      while ($row = mysql_fetch_assoc($table_query)) {
        $customers_column[$row['Field']] = 1;
      }
      // If the removed columns exist, then the tables must be processed
      if (isset($customers_column['customers_telephone']) && isset($customers_column['customers_fax'])) {
        
        $sql = "SELECT customers_id, customers_default_address_id FROM " . $_POST['DB_DATABASE'] . ".customers";
        $loop_query = mysql_query($sql, $db_link_new);
        if ($loop_query === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
          return;
        }
        while ($new = osc_db_fetch_array($loop_query)) {
          // read in the existing table data
          $sql = "SELECT customers_email_address, customers_telephone, customers_fax FROM " . $_POST['cre_existing_database'] . ".customers WHERE customers_id = " . $new['customers_id'];
          $old_query = mysql_query($sql, $db_link_old);
          if ($old_query === false) {
            $GLOBALS['_RESULT'] = array('code' => 'error',
                                        'action' => $_POST['function_call'],
                                        'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
            return;
          }
          $old = osc_db_fetch_array($old_query);
          $sql = "UPDATE " . $_POST['DB_DATABASE'] . ".address_book SET entry_telephone = '" . addslashes($old['customers_telephone']) . "', entry_fax = '" . addslashes($old['customers_telephone']) . "', entry_email_address = '" . addslashes($old['customers_email_address']) . "' WHERE customers_id = " . $new['customers_id'];
          if (mysql_query($sql, $db_link_new) === false) {
            $GLOBALS['_RESULT'] = array('code' => 'error',
                                        'action' => $_POST['function_call'],
                                        'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
            return;
          }
        }
      }  
      $GLOBALS['_RESULT'] = array('code' => 'success',
                                  'action' => $_POST['function_call'],
                                  'msg' => ''
                                 );
      return;
      break;
      
    case '_config':
      // load in the standard configuration data
      osc_db_connect($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD']);
      osc_db_install($_POST['DB_DATABASE'], 'sql/creloaded_configdata.sql');
      osc_db_close();
      
      // now process the keys from the existing database
      osc_db_connect($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD'], 'db_link_new');
      osc_db_connect($_POST['cre_existing_server'], $_POST['cre_existing_username'], $_POST['cre_existing_password'], 'db_link_old');
      
      // include the list of configuration keys that have been removed from the system
      require 'sql/reference-CRELoaded_Pro_Removed_Keys.php';
      // need to capiture which keys have been processed, so they are not reporcessed
      $processed_configuration_keys = array();
      
      // Process all the pre-loaded configuration kyes, get their existing values
      $sql = "SELECT configuration_key FROM " . $_POST['DB_DATABASE'] . ".configuration ";
      $loop_query = mysql_query($sql, $db_link_new);
      if ($loop_query === false) {
        $GLOBALS['_RESULT'] = array('code' => 'error',
                                    'action' => $_POST['function_call'],
                                    'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
        return;
      }
      while ($new = osc_db_fetch_array($loop_query)) {
        $processed_configuration_keys[] = $new['configuration_key'];  // record this key as processed
        if ($new['configuration_key'] == 'DEFAULT_TEMPLATE') continue;  // do not update this value
        if ($new['configuration_key'] == 'DEFAULT_LANGUAGE') continue;  // do not update this value
        
        // read in the existing table data
        $sql = "SELECT configuration_value FROM " . $_POST['cre_existing_database'] . ".configuration WHERE configuration_key = '" . $new['configuration_key'] . "'";
        $old_query = mysql_query($sql, $db_link_old);
        if ($old_query === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                    'action' => $_POST['function_call'],
                                    'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
          return;
        }
        if (osc_db_num_rows($old_query) > 0) {
          $old = osc_db_fetch_array($old_query);
          $sql = "UPDATE " . $_POST['DB_DATABASE'] . ".configuration SET configuration_value = '" . addslashes($old['configuration_value']) . "' WHERE configuration_key = '" . $new['configuration_key'] . "'";
          if (mysql_query($sql, $db_link_new) === false) {
            $GLOBALS['_RESULT'] = array('code' => 'error',
                                        'action' => $_POST['function_call'],
                                        'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
            return;
          }
        }
      }
      
      /*
      Loading the various old configurration values create numerious other issues.
      Therefore, non current values will not be loaded.
      // copy all the remaining exiting configuration keys over
      $sql = "SELECT * FROM " . $_POST['cre_existing_database'] . ".configuration ";
      $loop_query = mysql_query($sql, $db_link_old);
      if ($loop_query === false) {
        $GLOBALS['_RESULT'] = array('code' => 'error',
                                    'action' => $_POST['function_call'],
                                    'msg' => sprintf($error_msg, mysql_errno($db_link_old), mysql_error($db_link_old), $sql));
        return;
      }
      while ($old = mysql_fetch_assoc($loop_query)) {
        if (in_array($old['configuration_key'], $processed_configuration_keys)) continue;
        if (in_array($old['configuration_key'], $deleted_configuration_keys)) continue;
        // special case to exclude all FDMS keys
        if (substr($old['configuration_key'],0, 4) == 'FDM_') continue;
        if (substr($old['configuration_key'],0, 8) == 'LIBRARY_') continue;
        if (substr($old['configuration_key'],0, 10) == 'FILE_LIST_') continue;
        // add it tot he new configuration table
        $sql = "INSERT INTO " . $_POST['DB_DATABASE'] . ".configuration SET ";
        foreach ($old as $column => $value) {
          if ($column == 'configuration_id') continue;
          $sql .= $column . " = " . "'" . addslashes($value) . "', ";
        }
        $sql = substr($sql, 0, -2);
        if (mysql_query($sql, $db_link_new) === false) {
          $GLOBALS['_RESULT'] = array('code' => 'error',
                                      'action' => $_POST['function_call'],
                                      'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
          return;
        }
      }
      */
      
      $GLOBALS['_RESULT'] = array('code' => 'success',
                                  'action' => $_POST['function_call'],
                                  'msg' => ''
                                 );
      return;
      break;
      
    case '_pci':
      osc_db_connect($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD'], 'db_link_new');
      
      $purge_method = (isset($_POST['cc_purge']) && $_POST['cc_purge'] != '') ? $_POST['cc_purge'] : '1';
      switch ($purge_method) {
        case '2': // mask middle 6
        case '3': // mask first 12
          // loop through orders table
          $orders_query = mysql_query("SELECT * FROM " . $_POST['DB_DATABASE'] . ".orders", $db_link_new);
          if ($orders_query === false) {
            $GLOBALS['_RESULT'] = array('code' => 'error',
                                        'action' => $_POST['function_call'],
                                        'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
            return;
          }          
          while ($orders = mysql_fetch_assoc($orders_query)) {
            $oID = $orders['orders_id'];
            if ($orders['cc_number'] != '' && $orders['cc_number'] != '0000000000000000') {
              // cc number is not null
              if ((int)strlen($orders['cc_number']) > 16) {
                // data is encrypted
                $cc_number = cc_decrypt($orders['cc_number']);
              } else {
                // data is not encrypted
                $cc_number = $orders['cc_number'];
              }
              $len = strlen($cc_number);
              if ($purge_method == '2') {  // mask middle 6
                $masked_cc_number = substr($cc_number, 0, 5) . str_repeat('X', ($len - 10)) . substr($cc_number, -5);
              } else {  // mask all but last 4
                $masked_cc_number = str_repeat('X', ($len - 4)) . substr($cc_number, -4);                  
              }
              $sql = "UPDATE " . $_POST['DB_DATABASE'] . ".orders SET cc_number = '" . $masked_cc_number . "' WHERE orders_id = '" . $oID . "'";
              if (mysql_query($sql, $db_link_new) === false) {
                $GLOBALS['_RESULT'] = array('code' => 'error',
                                            'action' => $_POST['function_call'],
                                            'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
                return;
              }
            }
          }
          break; 
        default:
          // purge all CC info
          $sql = "UPDATE " . $_POST['DB_DATABASE'] . ".orders SET cc_number = NULL";
          if (mysql_query($sql, $db_link_new) === false) {
            $GLOBALS['_RESULT'] = array('code' => 'error',
                                        'action' => $_POST['function_call'],
                                        'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
            return;
          }
          $sql = "UPDATE " . $_POST['DB_DATABASE'] . ".orders SET cc_expires = NULL";
          if (mysql_query($sql, $db_link_new) === false) {
            $GLOBALS['_RESULT'] = array('code' => 'error',
                                        'action' => $_POST['function_call'],
                                        'msg' => sprintf($error_msg, mysql_errno($db_link_new), mysql_error($db_link_new), $sql));
            return;
          }   
          break;    
      }
      $GLOBALS['_RESULT'] = array('code' => 'success',
                                  'action' => $_POST['function_call'],
                                  'msg' => ''
                                 );
      return;
      break;      
      
    default:
      $GLOBALS['_RESULT'] = array('code' => 'error',
                                  'action' => $_POST['function_call'],
                                  'msg' => DB_ERROR_01
                                 );
      return;
  }

  $GLOBALS['_RESULT'] = array('result' => 'error',
                              'msg' => 'We should not be here.'
                             );
?>