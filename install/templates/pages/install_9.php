<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.


   Released under the GNU General Public License
*/

require('includes/languages/' . $language . '/install_9.php');

  $dir_fs_document_root = $_POST['DIR_FS_DOCUMENT_ROOT'];
  if ((substr($dir_fs_document_root, -1) != '/') && (substr($dir_fs_document_root, -1) != '/')) {
    $where = strrpos($dir_fs_document_root, '\\');
    if (is_string($where) && !$where) {
      $dir_fs_document_root .= '/';
    } else {
      $dir_fs_document_root .= '\\';
    }
  }

      $admin_user = ($_POST['adminuser']);
      $admin_pass = ($_POST['adminpass']);
      $admin_first = ($_POST['adminfirst']);
      $admin_last = ($_POST['adminlast']);

       unset($error_msg) ;
       unset($_POST['$error_msg']) ;
//user name as a valid validate email adress

// echo 'validate:' . $admin_user . osc_validate_email($admin_user);

//check user email and if old default

if ( (empty($_POST['adminuser'])) || (empty($_POST['adminpass'])) ) {
  $error_msg = '3';
  } else if (osc_validate_email($admin_user) == 'false'){
  $error_msg = '1';
  } else if ($admin_user == 'admin@localhost.com'){
  $error_msg = '2';
  }

// echo 'test' . $_POST['adminpass'] . $_POST['DIR_FS_DOCUMENT_ROOT'] . $_POST['DB_SERVER'] . $_POST['DB_SERVER_USERNAME'] . $_POST['DB_SERVER_PASSWORD'] . $_POST['DB_DATABASE'] . $_POST['ENABLE_SSL'];

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

//-------------------if error set errorto error 4-------------------

if ($db_error != false){
$error_msg = '4';
 }
}

//----------------- check for configure write --------------------

if ( ( (file_exists($dir_fs_document_root . 'includes/configure.php')) && (!is_writeable($dir_fs_document_root . 'includes/configure.php')) ) || ( (file_exists($dir_fs_document_root . '/admin/includes/configure.php')) && (!is_writeable($dir_fs_document_root . '/admin/includes/configure.php')) ) ) {

$error_msg = '5' ;

 }


if ( ($db_error == false) || (empty($error_msg)) ){

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
?>
<!-- no error -->
<script language="JavaScript" type="text/javascript">


/***********************************************
* Required field(s) validation v1.10- By NavSurf
* Visit Nav Surf at http://navsurf.com
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

function formCheck(formobj){
  // Enter name of mandatory fields
  var fieldRequired = Array("full_name", "email_address","telephone","website","type_of_website","usbusiness","appx_sale_amount","appx_sale_volume");
  // Enter field description to appear in the dialog box
  var fieldDescription = Array("<?php echo TEXT_FULL_NAME;?>", "<?php echo TEXT_EMAIL_ADDRESS;?>","<?php echo TEXT_TELEPHONE;?>","<?php echo TEXT_WEBSITE;?>","<?php echo TEXT_TYPE_OF_WEBSITE;?>","<?php echo TEXT_US_BUSINESS;?>","<?php echo TEXT_AMOUNT_OF_SALE;?>","<?php echo TEXT_APPX_SALE_VOLUME;?>");
  // dialog message
  var alertMsg = "Please complete the following fields:\n";
  
  var l_Msg = alertMsg.length;
  
  for (var i = 0; i < fieldRequired.length; i++){
    var obj = formobj.elements[fieldRequired[i]];
    if (obj){
      switch(obj.type){
        case "select-one":
          if (obj.selectedIndex == -1 || obj.options[obj.selectedIndex].text == ""){
            alertMsg += " - " + fieldDescription[i] + "\n";
          }
          break;
        case "select-multiple":
          if (obj.selectedIndex == -1){
            alertMsg += " - " + fieldDescription[i] + "\n";
          }
          break;
        case "text":
        case "textarea":
          if (obj.value == "" || obj.value == null){
            alertMsg += " - " + fieldDescription[i] + "\n";
          }
          break;
        default:
      }
      
      if (obj.type == undefined){
        var blnchecked = false;
        for (var j = 0; j < obj.length; j++){
          if (obj[j].checked){
            blnchecked = true;
          }
        }
        if (!blnchecked){
          alertMsg += " - " + fieldDescription[i] + "\n";
        }
      }
    }
  }

  if (alertMsg.length == l_Msg){
    return true;
  } else {
    alert(alertMsg);
    return false;
  }
}
// -->
</script>
<?php
//form skip
/*
echo osc_draw_form('skipCrem', basename($_SERVER['PHP_SELF']), 'step=10', 'post');
    while (list($key, $value) = each($_POST)) {
      if ($key != 'x' && $key != 'y') {
        if (is_array($value)) {
          for ($i=0; $i<sizeof($value); $i++) {
            echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
          }
        } else {
          echo osc_draw_hidden_field($key, $value) . "\n";
        }
      }
    }
echo '</form>';
*/
?>


<h1>Credit Card Processing</h1>
<p>
<script language='JavaScript' type='text/javascript' src='https://adserver.authsecure.com/adx.js'></script>
<script language='JavaScript' type='text/javascript'>
  if (!document.phpAds_used) document.phpAds_used = ',';
  phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
  
  document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
  document.write ("https://adserver.authsecure.com/adjs.php?n=" + phpAds_random);
  document.write ("&amp;what=zone:40");
  document.write ("&amp;exclude=" + document.phpAds_used);
  if (document.referrer)
    document.write ("&amp;referer=" + escape(document.referrer));
  document.write ("'><" + "/script>");
</script>
<noscript>
  <a href='https://adserver.authsecure.com/adclick.php?n=a3011f16' target='_blank'><img src='https://adserver.authsecure.com/adview.php?what=zone:40&amp;n=a3011f16' border='0' alt=''></a>
</noscript>
</p>

<div class="installation-form-body-wide" style="position: relative;">

  <form name="install_9a" action="install.php?step=11" method="post" onsubmit="return formCheck(this);">
  <?php
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if ($key != 'x' && $key != 'y') {
          if (is_array($value)) {
            for ($i=0; $i<sizeof($value); $i++) {
              echo osc_draw_hidden_field($key . '[]', $value[$i]);
            }
          } else {
            echo osc_draw_hidden_field($key, $value);
          }
        }
      }
  
      $website_type = array();
      $website_type = array(
                            array(id => '', text => ''),
                            array(id => 'Retail Site', text => 'Retail Site'),
                            array(id =>'Wholesale Site', text => 'Wholesale Site'),
                            array(id => 'Adult Site', text => 'Adult Site'),
                            array(id => 'Non Profit Site', text => 'Non Profit Site')
                           );
  
      $yes_no_select = array();
      $yes_no_select = array(array(id => '', text => ''), array(id => 'Yes', text => 'Yes'),array(id => 'No', text => 'No'));
  ?>

  <table align="center">
    <tr><td class="installation-form-label"><?php echo TEXT_COMPANY_NAME;?></td><td><?php echo osc_draw_input_field('company', '', 'text', 'class="string"');?></td></tr>
    <tr><td class="installation-form-label"><?php echo TEXT_FULL_NAME;?></td><td><?php echo osc_draw_input_field('full_name',$admin_first . ' ' . $admin_last, 'text', 'class="string"');?></td></tr>
    <tr><td class="installation-form-label"><?php echo TEXT_TELEPHONE;?></td><td><?php echo osc_draw_input_field('telephone', '', 'text', 'class="string"');?></td></tr>
    <tr><td class="installation-form-label"><?php echo TEXT_NIGHT_PHONE;?></td><td><?php echo osc_draw_input_field('nightphone', '', 'text', 'class="string"');?></td></tr>
    <tr><td class="installation-form-label"><?php echo TEXT_COUNTRY;?></td><td><?php echo osc_draw_input_field('country', '', 'text', 'class="string"');?></td></tr>
    <tr><td class="installation-form-label"><?php echo TEXT_EMAIL_ADDRESS;?></td><td><?php echo osc_draw_input_field('email_address',$admin_user, 'text', 'class="string"');?></td></tr>
    <tr><td class="installation-form-label"><?php echo TEXT_YEARS_IN_BUSINESS;?></td><td><?php echo osc_draw_input_field('businessyears', '', 'text', 'class="string"');?></td></tr>
    <tr><td class="installation-form-label"><?php echo TEXT_WEBSITE;?></td><td><?php echo osc_draw_input_field('website_url',$http_server . $http_catalog, 'text', 'class="string"')?></td></tr>
    <tr>
      <td class="installation-form-label" valign="top"><?php echo TEXT_PROCESSING;?></td>
      <td>
        <input name="processing" type="radio" /> I am currently accepting credit cards <br />
        <input name="processing" type="radio" /> I have not processed credit cards before. <br />
      </td>
    </tr>
    <tr>
      <td class="installation-form-label" valign="top"><?php echo TEXT_START_PROCESSING;?></td>
      <td>
        <input name="start_processing" value="Now" type="radio" /> Now<br />
        <input name="start_processing" value="2 Weeks" type="radio" /> 2 Weeks<br />
        <input name="start_processing" value="1 Month" type="radio" /> 1 Month<br />
        <input name="start_processing" value="Longer" type="radio" /> Longer
      </td>
    </tr>
    <tr>
      <td class="installation-form-label" valign="top" style="line-height: 28px;"><?php echo TEXT_COMMENTS;?></td>
      <td><textarea name="comments" id="comments" class="string brief"></textarea></td>
    </tr>
  
  </table>
    <p style="text-align: center;"><a href="javascript:void(0)" onclick="document.install_9a.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CONTINUE ;?></span><span class="installation-button-right">&nbsp;</span></a></p>
    <p style="text-align: center;" id="skip-href"><a href="javascript:void(0)" onclick="document.getElementById('skip-message').style.display = '';"><?php echo TEXT_CRE_MERCHANT_SKIP; ?></a></p>
  </form>

  
  <div style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%; display: none;" id="skip-message">
    <div style="opacity: 0.8; filter: alpha(opacity=80); background-color: white; width: 100%; height: 100%; position: absolute; left: 0px; top: 0px;"></div>
    <div style="position: relative; background-color: #eee; border: solid 2px #ccc;  background: #FFF6BF; color: #514721; border-color: #FFD324; margin: 40px; padding: 0 10px;">
      <p>
      <script language='JavaScript' type='text/javascript' src='https://adserver.authsecure.com/adx.js'></script>
      <script language='JavaScript' type='text/javascript'>
        if (!document.phpAds_used) document.phpAds_used = ',';
        phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
        
        document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
        document.write ("https://adserver.authsecure.com/adjs.php?n=" + phpAds_random);
        document.write ("&amp;what=zone:41");
        document.write ("&amp;exclude=" + document.phpAds_used);
        if (document.referrer)
          document.write ("&amp;referer=" + escape(document.referrer));
        document.write ("'><" + "/script>");
      </script>
      
      <?php
      echo osc_draw_form('skipCrem', basename($_SERVER['PHP_SELF']), 'step=11', 'post');
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if ($key != 'x' && $key != 'y') {
          if (is_array($value)) {
            for ($i=0; $i<sizeof($value); $i++) {
              echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
            }
          } else {
            echo osc_draw_hidden_field($key, $value) . "\n";
          }
        }
      }
      echo osc_draw_hidden_field('skip','true') . "\n";
      ?>
      </p>
      <p><a href="javascript:void(0)" onclick="document.getElementById('skip-message').style.display = 'none';"><?php echo TEXT_CRE_MERCHANT_SKIP_CONFIRM_NO; ?></a></p>
      <p><a href="javascript:void(0)" onclick="document.skipCrem.submit(); return false;"><?php echo TEXT_CRE_MERCHANT_SKIP_CONFIRM_YES; ?></a></p>
      </form>
  
    </div>
  </div>

</div>
<div class="installation-form-foot-wide">&nbsp;</div>

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
    }
?>
<!-- install error -->
<form name="install_9b" action="install.php?step=8" method="post">

  <p><?php echo TEXT_INSTALL_8 . $error_code ;?></p>
  <p><?php echo TEXT_INSTALL_9 . $error_fix ;?> </p>
  <?php
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