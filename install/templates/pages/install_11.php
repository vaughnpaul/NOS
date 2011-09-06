<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

  Released under the GNU General Public License
*/
require('includes/languages/' . $language . '/install_11.php');

  $dir_fs_document_root = $_POST['DIR_FS_DOCUMENT_ROOT'];
  if ((substr($dir_fs_document_root, -1) != '/') && (substr($dir_fs_document_root, -1) != '/')) {
    $where = strrpos($dir_fs_document_root, '\\');
    if (is_string($where) && !$where) {
      $dir_fs_document_root .= '/';
    } else {
      $dir_fs_document_root .= '\\';
    }
  }

  $admin_user = trim($_POST['adminuser']);
  $admin_pass = trim($_POST['adminpass']);
  $admin_first = trim($_POST['adminfirst']);
  $admin_last = trim($_POST['adminlast']);

  unset($error_msg) ;
  unset($_POST['$error_msg']) ;
  
  if ( (empty($_POST['adminuser'])) || (empty($_POST['adminpass'])) ) {
    $error_msg = '3';
  } elseif (osc_validate_email($admin_user) == 'false') {
    $error_msg = '1';
  } elseif ($admin_user == 'admin@localhost.com') {
    $error_msg = '2';
  } elseif ((!preg_match('/[0-9]/', $admin_pass) || !preg_match('/[A-Z]/', $admin_pass) || !preg_match('/[a-z]/', $admin_pass)) || (strlen($admin_pass) < 8)) {
    $error_msg = '8';
  }
  
  $db = array();
  $db['DB_SERVER'] = trim(stripslashes($_POST['DB_SERVER']));
  $db['DB_SERVER_USERNAME'] = trim(stripslashes($_POST['DB_SERVER_USERNAME']));
  $db['DB_SERVER_PASSWORD'] = trim(stripslashes($_POST['DB_SERVER_PASSWORD']));
  $db['DB_DATABASE'] = trim(stripslashes($_POST['DB_DATABASE']));

// test connection to db
  $db_error = false;

  osc_db_connect1($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD'], $db['DB_DATABASE']);

if ($db_error = false) {
    osc_db_test_connection($db['DB_DATABASE']);

//----------------check for db conntection -------------------
if ($db_error != false) {
  $error_msg = '6';
}
}
//-------------------------------add admn user to db ----------------------
if (empty($error_msg)) {
$db_error = false;
 $admin_check = osc_db_query ("select admin_email_address from admin where admin_email_address = '" . $admin_user . "'");

if (osc_db_num_rows($admin_check) == 0) {

   $sql_data_array = array('admin_groups_id' => '1',
                           'admin_firstname' => $admin_first,
                           'admin_lastname' => $admin_last,
                           'admin_email_address' => $admin_user,
                           'admin_password' => osc_encrypt_password($admin_pass),
                           'admin_created' => 'now()',
                           'admin_modified' => 'now()');

    osc_db_perform('admin', $sql_data_array, 'insert' );
 }else{
 $error_msg = '7';
 }

  // update session directory configuration value
  $session_dir = isset($_POST['DIR_FS_DOCUMENT_ROOT']) ? $_POST['DIR_FS_DOCUMENT_ROOT'] . 'tmp' : '/tmp';
  $log_destination = $session_dir . '/page_parse_time.log';
  osc_db_query("UPDATE `configuration` SET `configuration_value` = '" . $session_dir . "' WHERE `configuration_key` = 'SESSION_WRITE_DIRECTORY'");
  osc_db_query("UPDATE `configuration` SET `configuration_value` = '" . $log_destination . "' WHERE `configuration_key` = 'STORE_PAGE_PARSE_TIME_LOG'");

  // update MODULE_PAYMENT_PAYPAL_ID, MODULE_PAYMENT_PAYPAL_BUSINESS_ID values from configuration table, if paypal module is installed/exists
  $paypal_module_check = osc_db_query ("select * from `configuration` where `configuration_key` = 'MODULE_PAYMENT_PAYPAL_STATUS'");
  if (osc_db_num_rows($paypal_module_check) > 0) {
    osc_db_query("UPDATE `configuration` SET `configuration_value` = '" . $admin_user . "' WHERE `configuration_key` = 'MODULE_PAYMENT_PAYPAL_BUSINESS_ID'");
    osc_db_query("UPDATE `configuration` SET `configuration_value` = '" . $admin_user . "' WHERE `configuration_key` = 'MODULE_PAYMENT_PAYPAL_ID'");
  }

//-------------------if error set errorto error 4-------------------

if ($db_error != false){
$error_msg = '4';
 }
}

//----------------- check for configure write --------------------

if ( ( (file_exists($dir_fs_document_root . 'includes/configure.php')) && (!is_writeable($dir_fs_document_root . 'includes/configure.php')) ) || ( (file_exists($dir_fs_document_root . '/admin/includes/configure.php')) && (!is_writeable($dir_fs_document_root . '/admin/includes/configure.php')) ) ) {

$error_msg = '5' ;

 }


if ( ($db_error == false) && empty($error_msg) ){

    if (substr($_POST['HTTP_WWW_ADDRESS'], -1) != '/') $_POST['HTTP_WWW_ADDRESS'] .= '/';
    if (substr($_POST['HTTPS_WWW_ADDRESS'], -1) != '/') $_POST['HTTPS_WWW_ADDRESS'] .= '/';
    
    $http_url = parse_url($_POST['HTTP_WWW_ADDRESS']);
    $http_server = $http_url['scheme'] . '://' . $http_url['host'];
    $http_catalog = $http_url['path'];
    if (isset($http_url['port']) && !empty($http_url['port'])) {
      $http_server .= ':' . $http_url['port'];
    }

    if (substr($http_catalog, -1) != '/') {
      $http_catalog .= '/';
    }

    $https_server = '';
    $https_catalog = '';
    if (isset($_POST['HTTPS_WWW_ADDRESS']) && !empty($_POST['HTTPS_WWW_ADDRESS'])) {
      $https_url = parse_url($_POST['HTTPS_WWW_ADDRESS']);
      $https_server = $https_url['scheme'] . '://' . $https_url['host'];
      $https_catalog = $https_url['path'];

      if (isset($https_url['port']) && !empty($https_url['port'])) {
        $https_server .= ':' . $https_url['port'];
      }

      if (substr($https_catalog, -1) != '/') {
        $https_catalog .= '/';
      }
    }

    $enable_ssl = (isset($_POST['ENABLE_SSL']) && ($_POST['ENABLE_SSL'] == 'true') ? 'true' : 'false');
    $http_cookie_domain = $_POST['HTTP_COOKIE_DOMAIN'];
    $https_cookie_domain = (isset($_POST['HTTPS_COOKIE_DOMAIN']) ? $_POST['HTTPS_COOKIE_DOMAIN'] : '');
    $http_cookie_path = $_POST['HTTP_COOKIE_PATH'];
    $https_cookie_path = (isset($_POST['HTTPS_COOKIE_PATH']) ? $_POST['HTTPS_COOKIE_PATH'] : '');

    $file_contents = '<?php' . "\n" .
                     '/*' . "\n" .
                     '  osCommerce, Open Source E-Commerce Solutions' . "\n" .
                     '  http://www.oscommerce.com' . "\n" .
                     '' . "\n" .
                     '  Copyright (c) 2003 osCommerce' . "\n" .
                     '' . "\n" .
                     '  Released under the GNU General Public License' . "\n" .
                     '*/' . "\n" .
                     '' . "\n" .
                     '// Define the webserver and path parameters' . "\n" .
                     '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
                     '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
                     '  define(\'HTTP_SERVER\', \'' . $http_server . '\'); // eg, http://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'HTTPS_SERVER\', \'' . $https_server . '\'); // eg, https://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'ENABLE_SSL\', ' . $enable_ssl . '); // secure webserver for checkout procedure?' . "\n" .
                     '  define(\'HTTP_COOKIE_DOMAIN\', \'' . $http_cookie_domain . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_DOMAIN\', \'' . $https_cookie_domain . '\');' . "\n" .
                     '  define(\'HTTP_COOKIE_PATH\', \'' . $http_cookie_path . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_PATH\', \'' . $https_cookie_path . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $https_catalog . '\');' . "\n" .
                     '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                     '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
                     '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
                     '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
                     '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                     '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                     '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                     '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
                     '' . "\n" .
                     '//Added for BTS1.0' . "\n" .
                     '  define(\'DIR_WS_TEMPLATES\', \'templates/\');' . "\n" .
                     '  define(\'DIR_WS_CONTENT\', DIR_WS_TEMPLATES . \'content/\');' . "\n" .
                     '  define(\'DIR_WS_JAVASCRIPT\', DIR_WS_INCLUDES . \'javascript/\');' . "\n" .
                     '//End BTS1.0' . "\n" .
                     '  define(\'DIR_WS_DOWNLOAD_PUBLIC\', \'pub/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\');' . "\n" .
                     '  define(\'DIR_FS_DOWNLOAD\', DIR_FS_CATALOG . \'download/\');' . "\n" .
                     '  define(\'DIR_FS_DOWNLOAD_PUBLIC\', DIR_FS_CATALOG . \'pub/\');' . "\n" .
                     '' . "\n" .
                     '// define our database connection' . "\n" .
                     '  define(\'DB_SERVER\', \'' . $_POST['DB_SERVER'] . '\'); // eg, localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'DB_SERVER_USERNAME\', \'' . $_POST['DB_SERVER_USERNAME'] . '\');' . "\n" .
                     '  define(\'DB_SERVER_PASSWORD\', \'' . $_POST['DB_SERVER_PASSWORD']. '\');' . "\n" .
                     '  define(\'DB_DATABASE\', \'' . $_POST['DB_DATABASE']. '\');' . "\n" .
                     '  define(\'USE_PCONNECT\', \'' . (($_POST['USE_PCONNECT'] == 'true') ? 'true' : 'false') . '\'); // use persistent connections?' . "\n" .
                     '  define(\'STORE_SESSIONS\', \'' . (($_POST['STORE_SESSIONS'] == 'files') ? '' : 'mysql') . '\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
                     '?>';

    $fp = fopen($dir_fs_document_root . 'includes/configure.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);

    if ($enable_ssl == 'true') {
       $my_http_server = $https_server;
    } else {
       $my_http_server = $http_server;
    }

    $file_contents = '<?php' . "\n" .
                     '/*' . "\n" .
                     '  osCommerce, Open Source E-Commerce Solutions' . "\n" .
                     '  http://www.oscommerce.com' . "\n" .
                     '' . "\n" .
                     '  Copyright (c) 2003 osCommerce' . "\n" .
                     '' . "\n" .
                     '  Released under the GNU General Public License' . "\n" .
                     '*/' . "\n" .
                     '' . "\n" .
                     '// Define the webserver and path parameters' . "\n" .
                     '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
                     '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
                     '  define(\'HTTP_SERVER\', \'' . $my_http_server . '\'); // eg, http://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'HTTP_CATALOG_SERVER\', \'' . $http_server . '\');' . "\n" .
                     '  define(\'HTTPS_CATALOG_SERVER\', \'' . $https_server . '\');' . "\n" .
                     '  define(\'HTTPS_SERVER\', \'' . $https_server . '\'); // eg, https://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'HTTPS_ADMIN_SERVER\', \'' . $https_server . '\');' . "\n" .
                     '  define(\'HTTP_COOKIE_DOMAIN\', \'' . $http_cookie_domain . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_DOMAIN\', \'' . $https_cookie_domain . '\');' . "\n" .
                     '  define(\'HTTP_COOKIE_PATH\', \'' . $http_cookie_path . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_PATH\', \'' . $https_cookie_path . '\');' . "\n" .
                     '  define(\'ENABLE_SSL\',  \'' . $enable_ssl . '\'); // secure webserver for checkout procedure?' . "\n" .
                     '  define(\'ENABLE_SSL_CATALOG\', \'' . $enable_ssl . '\'); // secure webserver for catalog module' . "\n" .
                     '  define(\'DIR_WS_HTTP_ADMIN\',  \'' . $http_catalog . 'admin/\');' . "\n" .
                     '  define(\'DIR_WS_HTTPS_ADMIN\',  \'' . $https_catalog . 'admin/\');' . "\n" .
                     '  define(\'DIR_FS_DOCUMENT_ROOT\', \'' . $dir_fs_document_root . '\'); // where the pages are located on the server' . "\n" .
                     '  define(\'DIR_FS_ADMIN\', \'' . $dir_fs_document_root . 'admin/\'); // absolute path required' . "\n" .
                     '  define(\'DIR_WS_CATALOG\', \'' . $http_catalog . '\'); // absolute path required' . "\n" .
                     '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $https_catalog . '\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\'); // absolute path required' . "\n" .
                     '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                     '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
                     '  define(\'DIR_WS_CATALOG_IMAGES\', DIR_WS_CATALOG . \'images/\');' . "\n" .
                     '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
                     '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
                     '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                     '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                     '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                     '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
                     '  define(\'DIR_WS_CATALOG_LANGUAGES\', DIR_WS_CATALOG . \'includes/languages/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG_LANGUAGES\', DIR_FS_CATALOG . \'includes/languages/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG_IMAGES\', DIR_FS_CATALOG . \'images/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG_MODULES\', DIR_FS_CATALOG . \'includes/modules/\');' . "\n" .
                     '  define(\'DIR_FS_BACKUP\', DIR_FS_ADMIN . \'backups/\');' . "\n" .
                     '' . "\n" .

                     '// Added for Templating' . "\n" .
                     '    define(\'DIR_FS_CATALOG_MAINPAGE_MODULES\', DIR_FS_CATALOG_MODULES . \'mainpage_modules/\');' . "\n" .
                     '    define(\'DIR_WS_TEMPLATES\', DIR_WS_CATALOG . \'templates/\');' . "\n" .
                     '    define(\'DIR_FS_TEMPLATES\', DIR_FS_CATALOG . \'templates/\');' . "\n" .
                     '' . "\n" .


                     '// define our database connection' . "\n" .
                     '  define(\'DB_SERVER\', \'' . $_POST['DB_SERVER'] . '\'); // eg, localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'DB_SERVER_USERNAME\', \'' . $_POST['DB_SERVER_USERNAME'] . '\');' . "\n" .
                     '  define(\'DB_SERVER_PASSWORD\', \'' . $_POST['DB_SERVER_PASSWORD']. '\');' . "\n" .
                     '  define(\'DB_DATABASE\', \'' . $_POST['DB_DATABASE']. '\');' . "\n" .
                     '  define(\'USE_PCONNECT\', \'' . (($_POST['USE_PCONNECT'] == 'true') ? 'true' : 'false') . '\'); // use persisstent connections?' . "\n" .
                     '  define(\'STORE_SESSIONS\', \'' . (($_POST['STORE_SESSIONS'] == 'files') ? '' : 'mysql') . '\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
                     '?>';





    $fp = fopen($dir_fs_document_root . 'admin/includes/configure.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);
  
  $http_url = parse_url($_POST['HTTP_WWW_ADDRESS']);
  $http_server = $http_url['scheme'] . '://' . $http_url['host'];
  $http_catalog = $http_url['path'];
  if (isset($http_url['port']) && !empty($http_url['port'])) {
    $http_server .= ':' . $http_url['port'];
  }

require('includes/languages/' . $language . '/install_9.php');

if ($_SERVER["HTTP_HOST"] != 'localhost'){ 
  $message = 'Hello ' . $admin_first . ' ' . $admin_last . ', ' . "\n\n" .
             'Your login to ' . $http_server . $http_catalog . 'admin/index.php' . "\n\n" .
             'Login: ' . $admin_user . "\n" .
             'Password: ' . $admin_pass . "\n\n";

  $headers = 'From: ' . $admin_user . "\r\n" .
             'Reply-To: ' . $admin_user . "\r\n" .
             'X-Mailer: PHP/' . phpversion();
  @mail($admin_user, 'Your store login', $message, $headers);
}
?>

<h1><?php echo TEXT_INSTALL_1; ?></h1>
<?php echo TEXT_INSTALL_3; ?>
<table width="300" align="center">
  <tr>
    <td align="center"><a href="<?php echo $http_server . $http_catalog . 'index.php'; ?>" target="_blank"><?php echo TEXT_INSTALL_5 ;?></a></td>
    <td align="center"><a href="<?php echo $http_server . $http_catalog . 'admin/index.php'; ?>" target="_blank"><?php echo TEXT_INSTALL_6 ;?></a>
  </tr>
</table>

<?php
  } else {
    switch ($error_msg) {
      case '1':
        $error_code =  TEXT_ERROR_11 ;
        $error_fix =  TEXT_ERROR_1_S ;
        break;
      case '2':
        $error_code =  TEXT_ERROR_12 ;
        $error_fix =  TEXT_ERROR_2_S ;
        break;
      case '3':
        $error_code =  TEXT_ERROR_13 ;
        $error_fix =  TEXT_ERROR_3_S ;
        break;
      case '4':
        $error_code =  TEXT_ERROR_14 ;
        $error_fix =  TEXT_ERROR_4_S ;
        break;
      case '5':
        $error_code =  TEXT_ERROR_15 ;
        $error_fix =  (sprintf(TEXT_ERROR_5_S , $dir_fs_document_root)) ;
        break;
      case '6':
        $error_code =  TEXT_ERROR_16 ;
        $error_fix =  TEXT_ERROR_6_S ;
        break;
      case '7':
        $error_code =  TEXT_ERROR_17 ;
        $error_fix =  TEXT_ERROR_7_S ;
        break;
      case '8':
        $error_code =  TEXT_ERROR_18 ;
        $error_fix =  TEXT_ERROR_8_S ;
        break;
    }
?>
<!-- install error -->
<form name="install_9b" action="install.php?step=8" method="post">

  <p><?php echo TEXT_INSTALL_8 . $error_code ;?></p>
  <p><?php echo TEXT_INSTALL_9 . $error_fix ;?> </p>
  <?php
      echo osc_draw_hidden_field('error_msg', $error_msg);
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if (($key != 'step') && $key != 'x' && $key != 'y') {
          if (is_array($value)) {
            for ($i=0; $i<sizeof($value); $i++) {
              echo osc_draw_hidden_field($key . '[]', $value[$i]);
            }
          } else {
            echo osc_draw_hidden_field($key, $value);
          }
        }
    }
    echo osc_draw_hidden_field(error_msg, $error_msg);
  ?>

  <p style="text-align: center;">
    <a href="javascript:void(0)" onclick="document.install_9b.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_BACK ;?></span><span class="installation-button-right">&nbsp;</span></a>
  </p>
  
</form>
<?php
  }
?>