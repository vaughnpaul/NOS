<?php
/*
  $Id: validate_new.php,v 1.2 2009/03/09 19:56:29 wa4u Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_VALIDATE_NEW);
  
  $verifycodesent="";
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
      $email_address = strtolower(tep_db_prepare_input($_POST['email_address']));
      $check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($email_address) . "'");
      
      if (tep_db_num_rows($check_customer_query)) {
          $check_customer = tep_db_fetch_array($check_customer_query);
          $pw="ABCDEFGHJKMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz0123456789";
          srand((double)microtime()*1000000);
          if (!isset($Pass)) {
              $Pass = '';
          }
          for ($i=1;$i<=5;$i++) { 
              $Pass .= $pw{rand(0,strlen($pw)-1)};
          }
          $pw1="ABCDEFGHJKMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz0123456789";
          srand((double)microtime()*1000000);
          if (!isset($Pass_neu)) {
              $Pass_neu = '';
          }
          for ($i=1;$i<=5;$i++){ 
              $Pass_neu .= $pw1{rand(0,strlen($pw1)-1)};
          }
          
          tep_db_query('update customers set customers_validation_code = "' . $Pass . $Pass_neu . '" where customers_id = "' .  (int)$check_customer['customers_id'] . '"');
          
          $email_body = sprintf(EMAIL_PASSWORD_REMINDER_BODY, $Pass . $Pass_neu) . sprintf(EMAIL_PASSWORD_REMINDER_BODY2, '<a href="' . tep_href_link('pw.php', 'action=reg&pass=' . $Pass . $Pass_neu . '&id=' . (int)$check_customer['customers_id'], 'SSL', false) . '">' . tep_href_link('pw.php', 'action=reg&pass=' . $Pass . $Pass_neu . '&verifyid=' . (int)$check_customer['customers_id'], 'SSL', false) . '</a>');
          tep_mail($check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname'], $email_address, EMAIL_PASSWORD_REMINDER_SUBJECT, $email_body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);  
          
          $verifycodesent="success";
      } else {
       $messageStack->add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
  }
  
  }
    $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
    $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_VALIDATE_NEW, '', 'SSL')); 
 


    $content = CONTENT_VALIDATE_NEW;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
