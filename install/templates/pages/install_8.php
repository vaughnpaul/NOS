<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

   Released under the GNU General Public License
*/

require('includes/languages/' . $language . '/install_8.php');

$error_msg = (isset($_POST['error_msg']) ? $_POST['error_msg'] : '');
$db_error = (isset($_POST['db_error']) ? $_POST['db_error'] : '');

$https_www_address = str_replace('http://', 'https://', $_POST['HTTP_WWW_ADDRESS']);
$db_server = (isset($_POST['DB_SERVER']) ? $_POST['DB_SERVER'] : 'localhost');
$db_server_username = (isset($_POST['DB_SERVER_USERNAME']) ? $_POST['DB_SERVER_USERNAME'] : '');
$db_server_password = (isset($_POST['DB_SERVER_PASSWORD']) ? $_POST['DB_SERVER_PASSWORD'] : '');
$db_database = (isset($_POST['DB_DATABASE']) ? $_POST['DB_DATABASE'] : '');
$use_pconnect = (isset($_POST['USE_PCONNECT']) ? $_POST['USE_PCONNECT'] : 'false');
$store_sessions = (isset($_POST['STORE_SESSIONS']) ? $_POST['STORE_SESSIONS'] : 'mysql');

  if (isset($db_server) && !empty($db_server) && isset($_POST['DB_TEST_CONNECTION']) && ($_POST['DB_TEST_CONNECTION'] == 'true')) {
    $db = array();
    $db['DB_SERVER'] = trim(stripslashes($db_server));
    $db['DB_SERVER_USERNAME'] = trim(stripslashes($db_server_username));
    $db['DB_SERVER_PASSWORD'] = trim(stripslashes($db_server_password));
    $db['DB_DATABASE'] = trim(stripslashes($db_database));
  }
?>
<!-- start //-->
<h1><?php echo TEXT_INSTALL_1 ;?></h1>
<form name="install" action="install.php" method="post">
  <input type="hidden" name="step" value="9" />
<p><?php echo TEXT_INSTALL_2 ;?></p>
<?php
  if ($error_msg == 1 ){
?>
<p><?php echo TEXT_ERROR_10 . ' <b>' . $_POST['adminuser'] . ' </b>' . TEXT_ERROR_10a ; ?></p>
<?php
    unset ($_POST['adminpass']) ;
  }
?>
<?php
  if ($error_msg == '2' ){
?>
<p><?php echo TEXT_ERROR_20 . $_POST['adminuser'] . TEXT_ERROR_20a; ?></p>
<?php
    unset ($_POST['adminpass']) ;
  }
  if ($error_msg == '3' ){
?>
<p><?php echo  TEXT_ERROR_30 ;?></p>
<?php
    unset ($_POST['adminpass']) ;
  }
  if ($error_msg == '8' ){
?>
<p><?php echo  TEXT_ERROR_80 ;?></p>
<?php
    unset ($_POST['adminpass']);
  }
  if ($db_error == true){
?>
<p><?php echo  TEXT_ERROR_40 ;?></p>
<?php
  }
  
  $error_msg = 0;
?>






<div class="installation-form-body-wide">
<table align="center">

  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_9 ;?></td><td><?php echo osc_draw_input_field('adminfirst', '', 'text', 'class="string"'); ?></td>
  </tr>
  <!--
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_10 ; ?></td>
  </tr>
  -->

  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_12 ;?></td><td><?php echo osc_draw_input_field('adminlast', '', 'text', 'class="string"'); ?></td>
  </tr>
  <!--
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_13 ; ?></td>
  </tr>
  -->
  
  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_3 ;?></td><td><?php echo osc_draw_input_field('adminuser', '', 'text', 'class="string"'); ?></td>
  </tr>
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_4 ; ?></td>
  </tr>

  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_6 ;?></td><td><?php echo osc_draw_input_field('adminpass', '', 'password', 'class="string"', 'false'); ?></td>
  </tr>
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_15 ; ?></td>
  </tr>
  <!--
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_7 ; ?></td>
  </tr>
  -->

<?php
  echo osc_draw_hidden_field('error_msg', '') . "\n";
  echo osc_draw_hidden_field('USE_PCONNECT', $use_pconnect) . "\n";

  reset($_POST);
  while (list($key, $value) = each($_POST)) {
    if ( ($key != 'step') && ($key != 'x') && ($key != 'y') && ($key != 'adminfirst') && ($key != 'adminlast') && ($key != 'adminuser') && ($key != 'adminpass') ){
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
  

</table>
    <p style="text-align: center;">
    <a href="javascript:void(0)" onclick="document.install.step.value = 5; document.install.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_BACK ;?></span><span class="installation-button-right">&nbsp;</span></a>
    <a href="javascript:void(0)" onclick="document.install.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CONTINUE ;?></span><span class="installation-button-right">&nbsp;</span></a></p>

</div>
<div class="installation-form-foot-wide">&nbsp;</div>
</form>