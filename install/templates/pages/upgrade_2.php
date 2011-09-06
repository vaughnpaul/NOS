<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.


   Released under the GNU General Public License
*/
require('includes/languages/' . $language . '/upgrade_2.php');
?>
<h1><?php echo TEXT_INSTALL_2A ;?></h1>
<?php
$pci = (isset($_GET['pci']) && $_GET['pci'] == '1') ? true : false;
$form_params = ($pci == true) ? '?pci=1' : '';

$db_server = (isset($_POST['DB_SERVER']) ? $_POST['DB_SERVER'] : 'localhost');
$db_server_username = (isset($_POST['DB_SERVER_USERNAME']) ? $_POST['DB_SERVER_USERNAME'] : '');
$db_server_password = (isset($_POST['DB_SERVER_PASSWORD']) ? $_POST['DB_SERVER_PASSWORD'] : '');
$db_database  = (isset($_POST['DB_DATABASE']) ? $_POST['DB_DATABASE'] : '');
$use_pconnect = 'false';
$store_sessions = 'mysql';

if (!empty($db_server) && !empty($db_server_username) && !empty($db_server_password) && !empty($db_database) &&
    isset($_POST['DB_TEST_CONNECTION']) && ($_POST['DB_TEST_CONNECTION'] == 'true')) {
  $db = array();
  $db['DB_SERVER'] = trim(stripslashes($db_server));
  $db['DB_SERVER_USERNAME'] = trim(stripslashes($db_server_username));
  $db['DB_SERVER_PASSWORD'] = trim(stripslashes($db_server_password));
  $db['DB_DATABASE'] = trim(stripslashes($db_database));
  $db_error = 'false';
  osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD']);

  if ( osc_db_select_db($db['DB_DATABASE']) ) {
    $db_exist = 'true';
    osc_db_test_db_empty($db['DB_DATABASE']);
  }
  $db_no_name = 'false';
  if (empty($db['DB_DATABASE'])){
    $db_error = 'true';
    $db_no_name = 'true';
  }
  if ($db_error != 'false') {
    ?>
    <!-- install_3_error -->    
    <form name="install_3a" action="upgrade.php<?php echo $form_params; ?>" method="post">
      <input type="hidden" name="step" value="2" />
      <p>
      <?php
      if ( !$db_exist ){
        echo TEXT_ERROR_1 . $db_error . TEXT_ERROR_2 . $data_error . TEXT_ERROR_3;
      } else {
        echo TEXT_ERROR_1E . $db_error . TEXT_ERROR_3;
      }
      ?>
      </p>
      <?php
      if ( $db_no_name == 'true') {
        echo '<p>' . TEXT_ERROR_6 . '</p>';
      }
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if (($key != 'step') && ($key != 'DB_TEST_CONNECTION')) {
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
      <p style="text-align: center;">
        <a href="javascript:void(0)" onclick="document.install_3a.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo TEXT_BUTTON_RECHECK ;?></span><span class="installation-button-right">&nbsp;</span></a>
      </p>
    </form>
    <?php
  } else {
    ?>
    <!-- install3_connect_ok -->
    <form name="install_3b" action="upgrade.php<?php echo $form_params; ?>" method="post">
      <input type="hidden" name="step" value="3" />
      <p><?php echo TEXT_INSTALL_2 ;?></p>
      <p>
      <?php
      $test_data = 0 ;
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if (($key != 'step') && ($key != 'DB_TEST_CONNECTION')) {
          if (is_array($value)) {
            for ($i=0; $i<sizeof($value); $i++) {
              echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
            }
          } else {
            echo osc_draw_hidden_field($key, $value) . "\n";
          }
        }
      }
      if ($test_data == 1){
        echo TEXT_INSTALL_2B ;
        echo $dir_fs_www_root . SQL_DEMO_DATA;
      }
      ?>
      </p>
      <?php 
      if ($pci == true) { 
        ?>
        <table width="100%" border="0">
          <tr><td>&nbsp;</td></tr>
          <tr><td colspan="3"><h1><?php echo TEXT_INSTALL_PCI_T1; ?></h1></td></tr>
          <tr><td colspan="3" class="installation-form-label"><?php echo TEXT_INSTALL_PCI_T2; ?></td></tr>
          <tr><td>&nbsp;</td></tr>
          <tr><td colspan="3" class="installation-form-label"><b><?php echo TEXT_INSTALL_PCI1; ?></b></td></tr>
          <tr><td class="installation-form-label"><?php echo TEXT_INSTALL_PCI2; ?></td><td><?php echo osc_draw_radio_field('cc_purge', '1', true) . '&nbsp;' . TEXT_INSTALL_PCI3; ?></td></tr>
          <tr><td class="installation-form-label"></td><td><?php echo osc_draw_radio_field('cc_purge', '2', false) . '&nbsp;' . TEXT_INSTALL_PCI4; ?></td></tr>
          <tr><td class="installation-form-label"></td><td><?php echo osc_draw_radio_field('cc_purge', '3', false) . '&nbsp;' . TEXT_INSTALL_PCI5; ?></td></tr>      
        </table>
        <?php
      }
      ?>
      <p style="text-align: center;">
        <a href="index.php"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CANCEL ;?></span><span class="installation-button-right">&nbsp;</span></a>
        <a href="javascript:void(0)" onclick="document.install_3b.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CONTINUE ;?></span><span class="installation-button-right">&nbsp;</span></a>
      </p>
    </form>
    <?php
  }
} else {
  $error = false;
  $error_ind = '&nbsp;<span style="color:red">*</span>';
  if (isset($_POST['DB_TEST_CONNECTION']) && (empty($db_server) || empty($db_server_username) || empty($db_server_password) || empty($db_database)) ) $error = true;
  ?>
  <!--install 3 entery -->
  <form name="install_3c" action="upgrade.php<?php echo $form_params; ?>" method="post">
    <input type="hidden" name="step" value="2" />
    <p><?php echo TEXT_INSTALL_4 ;?></p>
    <div class="installation-form-body-wide">
      <table align="center">
        <tr><td class="installation-form-label"><?php echo TEXT_INSTALL_6; ?></td><td><?php echo osc_draw_input_field('DB_SERVER',$db_server, 'text', 'class="string"'); echo ($error && empty($db_server)) ? $error_ind : ''; ?></td></tr>
        <tr><td></td><td class="info"><?php echo TEXT_INSTALL_7 ;?></td></tr>
        <tr><td class="installation-form-label"><?php echo TEXT_INSTALL_14; ?></td><td><?php echo osc_draw_input_field('DB_DATABASE', $db_database, 'text', 'class="string"', 'true'); echo ($error && empty($db_database)) ? $error_ind : ''; ?></td></tr>
        <!--<tr><td></td><td><?php echo TEXT_INSTALL_15 ;?></td></tr>-->
        <tr><td class="installation-form-label"><?php echo TEXT_INSTALL_22; ?></td><td><?php echo osc_draw_input_field('DB_SERVER_USERNAME', $db_server_username, 'text', 'class="string"'); echo ($error && empty($db_server_username)) ? $error_ind : ''; ?></td></tr>
        <!--<tr><td></td><td><?php echo TEXT_INSTALL_9 ;?></td></tr>-->
        <tr><td class="installation-form-label"><?php echo TEXT_INSTALL_11; ?></td><td><?php echo osc_draw_input_field('DB_SERVER_PASSWORD', $db_server_password, 'password', 'class="string"'); echo ($error && empty($db_server_password)) ? $error_ind : ''; ?></td></tr>
        <!--<tr><td></td><td><?php echo TEXT_INSTALL_12 ;?></td></tr>-->
        <?php
        if ($error) {
          ?>
          <tr><td colspan="2" class="error" align="center"><?php echo TEXT_ERROR_MISSING; ?></td></tr>
          <?php
        }
        ?>
      </table>
      <p style="text-align: center;">
        <a href="javascript:void(0)" onclick="document.install_3c.step.value = 1; document.install_3c.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_BACK ;?></span><span class="installation-button-right">&nbsp;</span></a>
        <a href="javascript:void(0)" onclick="document.install_3c.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CONTINUE ;?></span><span class="installation-button-right">&nbsp;</span></a>
      </p>
    </div>
    <div class="installation-form-foot-wide">&nbsp;</div>
    <?php
    reset($_POST);
    while (list($key, $value) = each($_POST)) {
      if (($key != 'step') && ($key != 'DB_SERVER') && ($key != 'DB_SERVER_USERNAME') && ($key != 'DB_SERVER_PASSWORD') && ($key != 'DB_DATABASE') && ($key != 'USE_PCONNECT') && ($key != 'STORE_SESSIONS') && ($key != 'DB_TEST_CONNECTION')) {
        if (is_array($value)) {
          for ($i=0; $i<sizeof($value); $i++) {
            echo osc_draw_hidden_field($key . '[]', $value[$i]). "\n";
          }
        } else {
          echo osc_draw_hidden_field($key, $value). "\n";
        }
      }
    }
    echo osc_draw_hidden_field('DB_TEST_CONNECTION', 'true');
  ?>
  </form>
  <?php
}
?>