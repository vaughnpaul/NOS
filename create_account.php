<?php
/*
  $Id: create_account.php,v 2.0.0.0 2008/06/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
// needs to be included earlier to set the success message in the messageStack
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);
$process = false;  // used by the state routine
if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
  $process = true;
  if (ACCOUNT_GENDER == 'true') {
    if (isset($_POST['gender'])) {
      $gender = tep_db_prepare_input($_POST['gender']);
    } else {
      $gender = false;
    }
  }
  $firstname = tep_db_prepare_input($_POST['firstname']);
  $lastname = tep_db_prepare_input($_POST['lastname']);
  if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($_POST['dob']);
  $email_address = strtolower(tep_db_prepare_input($_POST['email_address']));
  if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);
  $street_address = tep_db_prepare_input($_POST['street_address']);
  if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);
  $postcode = tep_db_prepare_input($_POST['postcode']);
  $city = tep_db_prepare_input($_POST['city']);
  if (ACCOUNT_STATE == 'true') {
    $state = tep_db_prepare_input($_POST['state']);
    if (isset($_POST['zone_id'])) {
      $zone_id = tep_db_prepare_input($_POST['zone_id']);
    } else {
      $zone_id = false;
    }
  }
  $country = tep_db_prepare_input($_POST['country']);
  $telephone = tep_db_prepare_input($_POST['telephone']);
  $fax = tep_db_prepare_input($_POST['fax']);
  if (isset($_POST['newsletter'])) {
    $newsletter = tep_db_prepare_input($_POST['newsletter']);
  } else {
    $newsletter = false;
  }
  $password = tep_db_prepare_input($_POST['password']);
  $confirmation = tep_db_prepare_input($_POST['confirmation']);
  $error = false;
  if (ACCOUNT_GENDER == 'true') {
    if ( ($gender != 'm') && ($gender != 'f') ) {
      $error = true;
      $messageStack->add('create_account', ENTRY_GENDER_ERROR);
    }
  }
  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;
    $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
  }
  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;
    $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
  }
  if (ACCOUNT_DOB == 'true') {
    if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
      $error = true;
      $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
    }
  }
  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;
    $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
  } elseif (tep_validate_email($email_address) == false) {
    $error = true;
    $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
  } else {
    $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($email_address) . "'");
    $check_email = tep_db_fetch_array($check_email_query);
    if ($check_email['total'] > 0) {  //PWA delete account
      $get_customer_info = tep_db_query("select customers_id, customers_email_address, purchased_without_account from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($email_address) . "'");
      $customer_info = tep_db_fetch_array($get_customer_info);
      $customer_email_address = strtolower($customer_info['customers_email_address']);
      if ($customer_info['purchased_without_account'] !='1') {
        $error = true;
        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
      } else {
        tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_info['customers_id'] . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_info['customers_id'] . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customer_info['customers_id'] . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $customer_info['customers_id'] . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $customer_info['customers_id'] . "'");
        tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . $customer_info['customers_id'] . "'");
      }
    }
  }
  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;
    $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
  }
  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;
    $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
  }
  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;
    $messageStack->add('create_account', ENTRY_CITY_ERROR);
  }
  if (is_numeric($country) == false) {
    $error = true;
    $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
  }
  if (ACCOUNT_STATE == 'true') {
    $zone_id = 0;
    $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
    $check = tep_db_fetch_array($check_query);
    $entry_state_has_zones = ($check['total'] > 0);
    if ($entry_state_has_zones == true) {
      $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' OR zone_code = '" . tep_db_input($state) . "')");
      if (tep_db_num_rows($zone_query) == 1) {
        $zone = tep_db_fetch_array($zone_query);
        $zone_id = $zone['zone_id'];
      } else {
        $error = true;
        $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
      }
    } else {
      if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
        $error = true;
        $messageStack->add('create_account', ENTRY_STATE_ERROR);
      }
    }
  }
  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;
    $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
  }
  if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;
    $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
  } elseif ($password != $confirmation) {
    $error = true;
    $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
  }    
  if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
    if (defined('VVC_CREATE_ACCOUNT_ON_OFF') && VVC_CREATE_ACCOUNT_ON_OFF == 'On') {
    $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . " where oscsid = '" . tep_session_id() . "'");
    $code_array = tep_db_fetch_array($code_query);
    tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'"); //remove the visual verify code associated with this session to clean database and ensure new results
    if ( isset($_POST['visual_verify_code']) && tep_not_null($_POST['visual_verify_code']) && 
         isset($code_array['code']) &&  tep_not_null($code_array['code']) && 
         strcmp($_POST['visual_verify_code'], $code_array['code']) == 0) {   //make the check case sensitive
         //match is good, no message or error.
         } else {
        $error = true;
        $messageStack->add('create_account', VISUAL_VERIFY_CODE_ENTRY_ERROR);
      }
    }
  }
  // RCI to include error checks
  echo $cre_RCI->get('createaccount', 'check', false);
  if ($error == false) {
    // RCI to include error data 

    $sql_data_array = array('customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $email_address, 
                            'customers_newsletter' => $newsletter,
                            'customers_password' => tep_encrypt_password($password));
    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);
    if (ACCOUNT_EMAIL_CONFIRMATION == 'false' ) $sql_data_array['customers_validation'] = '1';
    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
    $_SESSION['customer_id'] = tep_db_insert_id();
    $customer_id = $_SESSION['customer_id'];

    echo $cre_RCI->get('createaccount', 'submit', false);

    $sql_data_array = array('customers_id' => $customer_id,
                            'entry_firstname' => $firstname,
                            'entry_lastname' => $lastname,
                            'entry_street_address' => $street_address,
                            'entry_postcode' => $postcode,
                            'entry_city' => $city,
                            'entry_telephone' => $telephone,
                            'entry_fax' => $fax,
                            'entry_email_address' => $email_address,
                            'entry_country_id' => $country);
    if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
    if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
    if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
    if (ACCOUNT_STATE == 'true') {
      if ($zone_id > 0) {
        $sql_data_array['entry_zone_id'] = $zone_id;
        $sql_data_array['entry_state'] = '';
      } else {
        $sql_data_array['entry_zone_id'] = '0';
        $sql_data_array['entry_state'] = $state;
      }
    }
    tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
    $address_id = tep_db_insert_id();
    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");
    tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");
    if (SESSION_RECREATE == 'True') {
      tep_session_recreate();
    }                        
    // If we are not doing the email confirmation, then log the customer in
    if ( ACCOUNT_EMAIL_CONFIRMATION == 'false' ) {
      $_SESSION['customer_first_name'] = $firstname;
      $_SESSION['customer_default_address_id'] = $address_id;
      $_SESSION['customer_country_id'] = $country;
      $_SESSION['customer_zone_id'] = $zone_id;
    } else {  // we need to build the data to do the verification
      $Pass = '';
      $Pass_neu = '';
      $pw="ABCDEFGHJKMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz0123456789";
      srand((double)microtime()*1000000);
      for ($i=1;$i<=5;$i++){
        $Pass .= $pw{rand(0,strlen($pw)-1)};
       }
      $pw1="ABCDEFGHJKMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz0123456789";
      srand((double)microtime()*1000000);
      for ($i=1;$i<=5;$i++){
        $Pass_neu .= $pw1{rand(0,strlen($pw1)-1)};
       }
      tep_db_query('update customers set customers_validation_code = "' . $Pass . $Pass_neu . '" where customers_id = "' . $customer_id . '"');
    }
    // restore cart contents
    $cart->restore_contents();
    $name = $firstname . ' ' . $lastname;
    if (ACCOUNT_GENDER == 'true') {
      if ($gender == 'm') {
        $email_text = sprintf(EMAIL_GREET_MR, $lastname);
      } else {
        $email_text = sprintf(EMAIL_GREET_MS, $lastname);
      }
    } else {
      $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
    }
    if (EMAIL_USE_HTML == 'true') {
      $formated_store_owner_email = '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">' . STORE_OWNER . ': ' . STORE_OWNER_EMAIL_ADDRESS . '</a>';
    } else {
      $formated_store_owner_email = STORE_OWNER . ': ' . STORE_OWNER_EMAIL_ADDRESS;
    }
    $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . $formated_store_owner_email . "\n\n" . EMAIL_WARNING . $formated_store_owner_email . "\n\n";
    if ( ACCOUNT_EMAIL_CONFIRMATION == 'true' ) {
      $email_text .=  "\n" . MAIL_VALIDATION . "\n" . '<a href="' . str_replace('&amp;', '&', tep_href_link('pw.php', 'action=reg&pass=' . $Pass . $Pass_neu . '&verifyid=' . $_SESSION['customer_id'], 'SSL', false)) . '">' . VALIDATE_YOUR_MAILADRESS . '</a>' . "\n" . "\n" . '(' . SECOND_LINK . ' ' . str_replace('&amp;', '&', tep_href_link('pw.php', 'action=reg&pass=' . $Pass . $Pass_neu . '&verifyid=' . $_SESSION['customer_id'], 'SSL', false)) . ' )' . "\n" . "\n". OR_VALIDATION_CODE . $Pass . $Pass_neu . "\n" . "\n";
    }
    if (NEW_SIGNUP_GIFT_VOUCHER_AMOUNT > 0) {
      $coupon_code = create_coupon_code();
      $insert_query = tep_db_query("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $coupon_code . "', 'G', '" . NEW_SIGNUP_GIFT_VOUCHER_AMOUNT . "', now())");
      $insert_id = tep_db_insert_id();
      $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '0', 'Admin', '" . tep_db_input($email_address) . "', now() )");
      $email_text .= sprintf(EMAIL_GV_INCENTIVE_HEADER, $currencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) . "\n\n" .
                     sprintf(EMAIL_GV_REDEEM, $coupon_code) . "\n\n" .
                     EMAIL_GV_LINK . tep_href_link(FILENAME_GV_REDEEM, 'gv_no=' . $coupon_code,'NONSSL', false) .
                     "\n\n";
    }
    if (NEW_SIGNUP_DISCOUNT_COUPON != '') {
      $coupon_code = NEW_SIGNUP_DISCOUNT_COUPON;
      $coupon_query = tep_db_query("select * from " . TABLE_COUPONS . " where coupon_code = '" . $coupon_code . "'");
      $coupon = tep_db_fetch_array($coupon_query);
      $coupon_id = $coupon['coupon_id'];
      $coupon_desc_query = tep_db_query("select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $coupon_id . "' and language_id = '" . (int)$languages_id . "'");
      $coupon_desc = tep_db_fetch_array($coupon_desc_query);
      $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $coupon_id ."', '0', 'Admin', '" . tep_db_input($email_address) . "', now() )");
      $email_text .= EMAIL_COUPON_INCENTIVE_HEADER .  "\n" .
                     sprintf("%s", $coupon_desc['coupon_description']) ."\n\n" .
                     sprintf(EMAIL_COUPON_REDEEM, $coupon['coupon_code']) . "\n\n" .
                     "\n\n";
    }
    if (isset($_SESSION['is_std'])) {
      if (defined('EMAIL_USE_HTML') && EMAIL_USE_HTML == 'true') {
        $email_text .= '<a href="http://www.creloaded.com" target="_blank">' . TEXT_POWERED_BY_CRE . '</a>' . "\n\n";
      } else {
        $email_text .= TEXT_POWERED_BY_CRE . "\n\n";
      }
    }
    tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    if ( ACCOUNT_EMAIL_CONFIRMATION == 'true' ) {
    // force a log off
    unset($_SESSION['customer_id']);
    $customer_id = 0;
    unset($_SESSION['customer_default_address_id']);
    unset($_SESSION['customer_first_name']);
    unset($_SESSION['customer_country_id']);
    unset($_SESSION['customer_zone_id']);
    unset($_SESSION['comments']);
    unset($_SESSION['gv_id']);
    unset($_SESSION['cc_id']);
    $cart->reset();
  }
    tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
  }
} else {
  // check to see if someone is already logged in
  if ( isset($_SESSION['customer_id']) ) {
    // force a log off
    unset($_SESSION['customer_id']);
    $customer_id = 0;
    unset($_SESSION['customer_default_address_id']);
    unset($_SESSION['customer_first_name']);
    unset($_SESSION['customer_country_id']);
    unset($_SESSION['customer_zone_id']);
    unset($_SESSION['comments']);
    unset($_SESSION['gv_id']);
    unset($_SESSION['cc_id']);
    $cart->reset();
  }
}      
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
$content = CONTENT_CREATE_ACCOUNT;
$javascript = 'form_check.js.php';
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>