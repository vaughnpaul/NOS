<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

   Released under the GNU General Public License
*/

  require('includes/languages/' . $language . '/upgrade_1.php');
  
  $error = '';
  $pci = (isset($_GET['pci']) && $_GET['pci'] == '1') ? true : false;
  $form_params = ($pci == true) ? '&pci=1' : '';
  $script_filename = $_SERVER['SCRIPT_FILENAME'];
  $script_filename = str_replace('\\', '/', $script_filename);
  $fs_array = explode('/', dirname($script_filename));
  
  $fs_root_array = array();
  for ($i=0, $n=sizeof($fs_array)-1; $i<$n; $i++) {
    $fs_root_array[] = $fs_array[$i];
  }
  $fs_www_root = implode('/', $fs_root_array) . '/';
  
  $www_location = 'http://' . $_SERVER["HTTP_HOST"] . '/';
  
  if (isset($_POST['cre_path']) && $_POST['cre_path'] != '') {
    if (substr($_POST['cre_path'], -1) != '/') $_POST['cre_path'] .= '/';
    if (substr($_POST['cre_path'], 0, 1) == '/') $_POST['cre_path'] = substr($_POST['cre_path'], 1);
    if (substr($_POST['cre_path'], 0, 1) != '/') $_POST['cre_path'] = '/' . $_POST['cre_path'];
    $cre_path = $_POST['cre_path'];  
  } else {
    $cre_path = '';
  }
  
  $default_selected = $fs_www_root; 

  if (isset($_POST['cre_path'])) {
    if ($cre_path == $fs_www_root) {
        $error = TEXT_ERROR_3;
    } elseif (!file_exists($cre_path . 'admin/includes/configure.php') ||
              !is_readable($cre_path . 'admin/includes/configure.php')
             ) {
        $error = TEXT_ERROR_1;
    } else {
      
      $config = file_get_contents($cre_path . 'admin/includes/configure.php');
      $pattern = "/'DB_SERVER',\s*'(.*?)'/";
      $match = array();
      if (preg_match($pattern, $config, $match)) {
        $db_server = $match[1];
      } else {
        $error .= ($error != '' ? "<br>" :'').TEXT_DB_SERVER_ERROR_MSG;
      }
      
      $pattern = "/'DB_SERVER_USERNAME',\s*'(.*?)'/";
      $match = array();
      if(preg_match($pattern, $config, $match)) {
        $db_username = $match[1];
      } else {
        $error .= ($error != '' ? "<br>" :'').TEXT_DB_SERVER_USERNAME_ERROR_MSG;
      }
      
      $pattern = "/'DB_SERVER_PASSWORD',\s*'(.*?)'/";
      $match = array();
      if(preg_match($pattern, $config, $match)) {
        $db_password = $match[1];
      } else {
        $error .= ($error != '' ? "<br>" :'').TEXT_DB_SERVER_PASSWORD_ERROR_MSG;
      }
      
      $pattern = "/'DB_DATABASE',\s*'(.*?)'/";
      $match = array();
      if(preg_match($pattern, $config, $match)) {
        $db_database = $match[1];
      } else {
        $error .= ($error != '' ? "<br>" :'').TEXT_DB_DATABASE_ERROR_MSG;
      }
      unset($config);
      
      $db_link = '';
      if ($error == '') {
        osc_db_connect($db_server, $db_username, $db_password, 'db_link');
        if (mysql_select_db($db_database,  $db_link) === false) {
          $error = mysql_errno($db_link) . ' - ' . mysql_error($db_link);
        } else {
          $sql = "SELECT admin_firstname, admin_lastname FROM admin WHERE admin_groups_id = 1 ORDER BY admin_id";
          $admin_query = mysql_query($sql, $db_link);
          if ($admin_query === false) {
            $error = mysql_errno($db_link) . ' - ' . mysql_error($db_link);
          } else {
            $admin = mysql_fetch_assoc($admin_query);
        
            $sql = "SELECT configuration_value FROM configuration WHERE configuration_key = 'STORE_NAME' ";
            $config_query = mysql_query($sql, $db_link);
            if ($config_query === false) {
              $error = mysql_errno($db_link) . ' - ' . mysql_error($db_link);
            } else {
              $config = mysql_fetch_assoc($config_query);
            }
          }
        }
      }
    }
  } else {
    if (isset($_POST['cre_path'])) $error =  TEXT_ERROR_6;
  }
  
  
?>
<h1><?php echo TEXT_UPGRADE_1; ?></h1>
<form name="installer" action="" method="post">
<?php
  if (!isset($_POST['cre_path']) || $error != '') {
?>
<p><?php echo TEXT_UPGRADE_2; ?></p>
<div class="installation-form-body-wide">
  <table align="center">
    <tr>
      <td class="installation-form-label"><?php echo TEXT_UPGRADE_3; ?></td>
      <td class="installation-form-value"><div style="float: left; border: solid 1px #666; border-color: #fff; background-color: #fff; color: #666; line-height:23px; border-right: none; margin: 4px 0; padding-left: 0px;"><?php // echo $www_location; ?></div><input name="cre_path" type="text" class="string long" style="padding-left: 0;" value="<?php echo $default_selected; ?>" /></td>
    </tr>
    <tr>
      <td></td>
      <td class="info" style="color: #333;"><?php echo TEXT_UPGRADE_4; ?><div style="margin-top: 4px;"><?php echo TEXT_UPGRADE_5; ?>&nbsp;<span style="font-style: italic;">/home/user/www/catalog/</span></div></td>
    </tr>
<?php
  if ($error != '') {
?>
    <tr>
      <td></td>
      <td class="error"><?php echo $error; ?></td>
    </tr>
<?php
  }
?>
  </table>
  <p style="text-align: center;">
    <a href="javascript:void(0)" onclick="document.installer.action = 'index.php'; document.installer.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle">Back</span><span class="installation-button-right">&nbsp;</span></a>
    <a href="javascript:void(0)" onclick="document.installer.action = 'upgrade.php?step=1<?php echo $form_params; ?>'; document.installer.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle">Check</span><span class="installation-button-right">&nbsp;</span></a>
  </p>
</div>
<div class="installation-form-foot-wide">&nbsp;</div>
<?php
    reset($_POST);
    while (list($key, $value) = each($_POST)) {
      if ($key != 'cre_path') {
        if (is_array($value)) {
          for ($i=0; $i < sizeof($value); ++$i) {
            echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
          }
        } else {
          echo osc_draw_hidden_field($key, $value) . "\n";
        }
      }
    }
  
  } else {
?>
<p><?php echo TEXT_UPGRADE_10; ?></p>
<div class="installation-form-body-wide">
  <table align="center">
    <tr>
      <td class="installation-form-label"><?php echo TEXT_UPGRADE_3; ?></td>
      <td class="installation-form-value" style="font-weight: bold;"><?php echo $cre_path; ?></td>
    </tr>
    <tr>
      <td class="installation-form-label" style="padding-top: 4px;"><?php echo TEXT_UPGRADE_11; ?></td>
      <td class="installation-form-value" style="padding-top: 4px; font-weight: bold;"><?php echo $config['configuration_value']; ?></td>
    </tr>
    <tr>
      <td class="installation-form-label" style="padding-top: 4px;"><?php echo TEXT_UPGRADE_12; ?></td>
      <td class="installation-form-value" style="padding-top: 4px; font-weight: bold;"><?php echo $admin['admin_firstname'] . ' ' . $admin['admin_lastname']; ?></td>
    </tr>
    <tr>
      <td class="installation-form-label" style="padding-top: 4px;"><?php echo TEXT_UPGRADE_13; ?></td>
      <td class="installation-form-value" style="padding-top: 4px; font-weight: bold;"><?php echo $db_database; ?></td>
    </tr>
  </table>
  <p style="text-align: center;">
    <a href="javascript:void(0)" onclick="document.installer.cre_path.value = ''; document.installer.action = 'upgrade.php'; document.installer.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle">Back</span><span class="installation-button-right">&nbsp;</span></a>
    <a href="javascript:void(0)" onclick="document.installer.action = 'upgrade.php?step=2<?php echo $form_params; ?>'; document.installer.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle">Continue</span><span class="installation-button-right">&nbsp;</span></a>
  </p>
</div>
<div class="installation-form-foot-wide">&nbsp;</div>
<?php
    reset($_POST);
    while (list($key, $value) = each($_POST)) {
      if ($key != 'cre_existing_server' && $key != 'cre_existing_username' && $key != 'cre_existing_password' && $key != 'cre_existing_database') {
        if (is_array($value)) {
          for ($i=0; $i < sizeof($value); ++$i) {
            echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
          }
        } else {
          echo osc_draw_hidden_field($key, $value) . "\n";
        }
      }
    }
    echo osc_draw_hidden_field('cre_existing_server', $db_server) . "\n";
    echo osc_draw_hidden_field('cre_existing_username', $db_username) . "\n";
    echo osc_draw_hidden_field('cre_existing_password', $db_password) . "\n";
    echo osc_draw_hidden_field('cre_existing_database', $db_database) . "\n";
  } // end of else
?>
</form>