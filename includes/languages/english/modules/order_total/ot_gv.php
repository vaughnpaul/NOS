<?php
/*
  $Id: ot_gv.php,v 1.3 2004/03/09 18:56:37 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_ORDER_TOTAL_GV_TITLE', 'Gift Vouchers');
  define('MODULE_ORDER_TOTAL_GV_HEADER', 'Gift Vouchers/Discount Coupons');
  define('MODULE_ORDER_TOTAL_GV_DESCRIPTION', 'Gift Vouchers');
  if (!defined('SHIPPING_NOT_INCLUDED')) {
    define('SHIPPING_NOT_INCLUDED', ' [Shipping not included]');
  }
  if (!defined('TAX_NOT_INCLUDED')) {
    define('TAX_NOT_INCLUDED', ' [Tax not included]');
  }
  define('MODULE_ORDER_TOTAL_GV_USER_PROMPT', 'Check to use gift voucher account balance ->&nbsp;');
  define('TEXT_ENTER_GV_CODE', 'Enter Redeem Code&nbsp;&nbsp;');
  if (!defined('IMAGE_REDEEM_VOUCHER')) {
    define('IMAGE_REDEEM_VOUCHER', 'Redeem Code Voucher');
  }
  define('MODULE_ORDER_TOTAL_GV_TEXT_ERROR', 'Gift Voucher/Discount coupon');
?>
