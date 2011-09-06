<?php
/*
  $Id: login.php,v 1.1.1.1 2004/03/04 23:38:00 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
if ($session_started == false) {
  tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
}
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
$error = false;
// RCI logic top
echo $cre_RCI->get('login', 'logictop'); 
if (isset($_GET['login']) && $_GET['login'] == 'fail') {
  $fail_reason = (!empty($_GET['reason'])) ? urldecode($_GET['reason']): TEXT_LOGIN_ERROR;
  $messageStack->add('login', $fail_reason);
}
if (isset($_GET['email'])) {
  $email_address = $_GET['email'];
} 
if ( (isset($_POST['action']) && ($_POST['action'] == 'process')) ||
     (isset($_POST['password']) && isset($_POST['email_address'])) ) { 
  $email_address = strtolower(tep_db_prepare_input($_POST['email_address']));
  $password = tep_db_prepare_input($_POST['password']);
  if(ACCOUNT_EMAIL_CONFIRMATION=='true') {
    if (isset($_POST['pass'])) {
      $check_customer_query_val = tep_db_query("select customers_id, customers_email_address, customers_default_address_id,customers_validation_code from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($email_address) . "'");
      $new_query_for_val = tep_db_fetch_array($check_customer_query_val);   
      if ($new_query_for_val['customers_validation_code'] == $_POST['pass']) {
        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_validation = '1', customers_email_registered = '" . tep_db_input($email_address) . "' where customers_id = '" . $new_query_for_val['customers_id']  . "'");
      } else {
        tep_redirect(tep_href_link('pw.php', 'verifyid=' . $new_query_for_val['customers_id'] . '&pass=' . $_POST['pass'], SSL));
      }
    }
  }
  $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_validation, customers_default_address_id from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($email_address) . "'");
  if (!tep_db_num_rows($check_customer_query)) {
    $error = true;
  } else {
    $check_customer = tep_db_fetch_array($check_customer_query);
    // Check that password is good
    if(ACCOUNT_EMAIL_CONFIRMATION=='true') {
      $customers_validation=$check_customer['customers_validation'];}else{$customers_validation=1;
    }
    if ((!tep_validate_password($password, $check_customer['customers_password'])) ||  $customers_validation== '0') {
      $error = true;
      // RCI login invalid
      echo $cre_RCI->get('login', 'invalid');
      if ($customers_validation == '0') $setme = true;
      // added code to check for top administrator password, if using top admin password, allow login as customer
      $admin_check_query = tep_db_query("SELECT admin_password from `admin` WHERE admin_groups_id = '1'");
      // allow all admins      
      if (tep_db_num_rows($admin_check_query) > 0) {
        while($admin_check = tep_db_fetch_array($admin_check_query)) {
          if (tep_validate_password($password, $admin_check['admin_password'])) {
            $error = false;
            $setme = false;   
            $_SESSION['admin_login'] = true;
            if (SESSION_RECREATE == 'True') {
              tep_session_recreate();
            }
            // RCI login valid
            echo $cre_RCI->get('login', 'valid'); 
            $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
            $check_country = tep_db_fetch_array($check_country_query);
            $_SESSION['customer_id'] = $check_customer['customers_id'];
            $_SESSION['customer_default_address_id'] = $check_customer['customers_default_address_id'];
            $_SESSION['customer_first_name'] = $check_customer['customers_firstname'];
            $_SESSION['customer_country_id'] = $check_country['entry_country_id'];
            $_SESSION['customer_zone_id'] = $check_country['entry_zone_id'];
            tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
            // restore cart contents
            $cart->restore_contents();
            if (sizeof($navigation->snapshot) > 0) {
              $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
              $navigation->clear_snapshot();
              tep_redirect($origin_href);
            } else {
              tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));
            }               
          }
        }
      }
    } else {
      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }
      // RCI login valid
      echo $cre_RCI->get('login', 'valid'); 
      $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
      $check_country = tep_db_fetch_array($check_country_query);
      $_SESSION['customer_id'] = $check_customer['customers_id'];
      $_SESSION['customer_default_address_id'] = $check_customer['customers_default_address_id'];
      $_SESSION['customer_first_name'] = $check_customer['customers_firstname'];
      $_SESSION['customer_country_id'] = $check_country['entry_country_id'];
      $_SESSION['customer_zone_id'] = $check_country['entry_zone_id'];
      tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
      // restore cart contents
      $cart->restore_contents();
      if (sizeof($navigation->snapshot) > 0) {
        $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
        $navigation->clear_snapshot();
        tep_redirect($origin_href);
      } else {
        tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));
      }
    }
  }
}
if(!isset($setme)) {
  $setme = false;
}
if ($error == true) {
  if ($setme != ''){
    $messageStack->add('login', TEXT_LOGIN_ERROR_VALIDATION);
  } else {
    $messageStack->add('login', TEXT_LOGIN_ERROR);
  }
}
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
$content = CONTENT_LOGIN;
$javascript = $content . '.js';
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>