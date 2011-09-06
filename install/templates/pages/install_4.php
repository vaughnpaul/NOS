<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

   Released under the GNU General Public License
*/
require('includes/languages/' . $language . '/install_4.php');

  $script_filename = getenv('SCRIPT_FILENAME');

  $script_filename = str_replace('\\', '/', $script_filename);
  $script_filename = str_replace('//', '/', $script_filename);

  $dir_fs_www_root_array = explode('/', dirname($script_filename));
  $dir_fs_www_root = array();
  for ($i=0, $n=sizeof($dir_fs_www_root_array)-1; $i<$n; $i++) {
    $dir_fs_www_root[] = $dir_fs_www_root_array[$i];
  }
  $dir_fs_www_root = implode('/', $dir_fs_www_root) . '/';
?>

<h1>Database Import</h1>

<?php
  if ((osc_in_array('database', $_POST['install'])) || (osc_in_array('database_1', $_POST['install'])) ) {
    $db = array();
    $db['DB_SERVER'] = trim(stripslashes($_POST['DB_SERVER']));
    $db['DB_SERVER_USERNAME'] = trim(stripslashes($_POST['DB_SERVER_USERNAME']));
    $db['DB_SERVER_PASSWORD'] = trim(stripslashes($_POST['DB_SERVER_PASSWORD']));
    $db['DB_DATABASE'] = trim(stripslashes($_POST['DB_DATABASE']));

$use_pconnect = (isset($_POST['USE_PCONNECT']) ? $_POST['USE_PCONNECT'] : 'false');
$store_sessions = (isset($_POST['STORE_SESSIONS']) ? $_POST['STORE_SESSIONS'] : 'mysql');

    osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD']);

    //$db_error = 'false';
    $sql_file = $dir_fs_www_root . SQL_SCHEMA;
    $sql_file_1= $dir_fs_www_root . SQL_BASEDATA;
    $sql_file_2= $dir_fs_www_root . SQL_CONFIGDATA;
    $sql_file_3= $dir_fs_www_root . SQL_DEMO_DATA;


    osc_set_time_limit(0);
    if (osc_in_array('database_1', $_POST['install'])) {
      osc_db_install($db['DB_DATABASE'], $sql_file);
      osc_db_install($db['DB_DATABASE'], $sql_file_1);
      osc_db_install($db['DB_DATABASE'], $sql_file_2);
    }
    if (osc_in_array('database_2', $_POST['install'])){
      osc_db_install($db['DB_DATABASE'], $sql_file_3);
    }
    
    if ( (isset($db_error)) && ($db_error != 'false') ) {
?>
<!-- istall 4 load db error -->
<form name="install" action="install.php?step=5" method="post">

<p><?php echo TEXT_INSTALL_1 ;?></p>
<p><?php echo $db_error; ?></p>
<p><?php echo $data_error; ?></p>

<?php
      reset($_POST);

      while (list($key, $value) = each($_POST)) {
        if (($key != 'x') && ($key != 'y') && ($key != 'DB_TEST_CONNECTION')) {
          if (is_array($value)) {
            for ($i=0; $i<sizeof($value); $i++) {
              echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
            }
          } else {
            echo osc_draw_hidden_field($key, $value) . "\n";
          }
        }
      }
?>

<div style="text-align: center;">
  <p>
    <a href="index.php"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CANCEL ;?></span><span class="installation-button-right">&nbsp;</span></a>

    <a href="javascript:void(0)" onclick="document.install.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CONTINUE ;?></span><span class="installation-button-right">&nbsp;</span></a>
  </p>
</div>

</form>

<?php
    } else {
?>
<!-- db import -->
<form name="install" action="install.php?step=5" method="post">

<p><?php echo TEXT_INSTALL_3 ;?></p>

<?php
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if (($key != 'x') && ($key != 'y') && ($key != 'DB_TEST_CONNECTION')) {
          if (is_array($value)) {
            for ($i=0; $i<sizeof($value); $i++) {
              echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
            }
          } else {
            echo osc_draw_hidden_field($key, $value) . "\n";
          }
        }
      }
?>

<?php
      if (osc_in_array('configure', $_POST['install'])) {
?>
<p style="text-align: center;">
  <a href="javascript:void(0)" onclick="document.install.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CONTINUE ;?></span><span class="installation-button-right">&nbsp;</span></a>
</p>
<?php
      } else {
?>
    <form name="install" action="install.php" method="post">
      <p style="text-align: center;">
        <a href="javascript:void(0)" onclick="document.install.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CONTINUE ;?></span><span class="installation-button-right">&nbsp;</span></a>
      </p>
    </form>
<?php
      }
?>

</form>

<?php
    }
  }
?>
