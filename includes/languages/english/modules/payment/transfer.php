<?php
/*
  $Id: transfer.php,v 2.1 2008/08/20 00:36:41 wa4u Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  define('MODULE_PAYMENT_TRANSFER_TEXT_TITLE','Bank Transfer');
  define('MODULE_PAYMENT_TRANSFER_TEXT_DESCRIPTION', 'Please use the following details to transfer your total order value:<br>Account Name: ' . (defined('MODULE_PAYMENT_TRANSFER_PAYTO')? MODULE_PAYMENT_TRANSFER_PAYTO : '') . '<br>Account Number: ' . (defined('MODULE_PAYMENT_TRANSFER_ACCOUNT')? MODULE_PAYMENT_TRANSFER_ACCOUNT : '') . '<br>Bank Name: ' . (defined('MODULE_PAYMENT_TRANSFER_BANK')? MODULE_PAYMENT_TRANSFER_BANK : '') . '<br>We will not ship your order until we receive payment in the above account.');
  define('MODULE_PAYMENT_TRANSFER_TEXT_EMAIL_FOOTER', 'Please use the following details to transfer your total order value:' . "\n\n" . 'Account Name: ' . (defined('MODULE_PAYMENT_TRANSFER_PAYTO')? MODULE_PAYMENT_TRANSFER_PAYTO : '') . "\n" . 'Account Number:  ' . (defined('MODULE_PAYMENT_TRANSFER_ACCOUNT')? MODULE_PAYMENT_TRANSFER_ACCOUNT : '') . "\n" . 'Bank Name: ' . (defined('MODULE_PAYMENT_TRANSFER_BANK')? MODULE_PAYMENT_TRANSFER_BANK : '') . "\n\n" . 'Your order will not ship until we receive payments in the above account.');

?>