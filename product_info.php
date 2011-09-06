<?php
/*
  $Id: product_info.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

//check if product is really a subproduct
  $product_sub_product_query = tep_db_query("select products_parent_id from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$_GET['products_id'] . "'");
  while ($product_sub_product = tep_db_fetch_array($product_sub_product_query)){
  $product_sub_check = $product_sub_product['products_parent_id'];
  }

  if ($product_sub_check > 0){
   tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_sub_check));
  }
//check to see if products have sub_products
$product_has_sub = '0';
$sub_products_sql1 = tep_db_query("select p.products_id, p.products_price, p.products_image, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_parent_id = " . (int)$_GET['products_id'] . " and p.products_quantity > '0' and p.products_id = pd.products_id and pd.language_id = " . (int)$languages_id);
if (tep_db_num_rows($sub_products_sql1) > 0) {
$product_has_sub = '1';
}else{
$product_has_sub = '0';
}
if (isset($_GET['werror']) && $_GET['werror'] == 1) {
  $error = true;
  if (PRODUCT_INFO_SUB_PRODUCT_ADDCART_TYPE == 'Checkbox') {
    $messageStack->add('cart_quantity', WISHLIST_SUB_PRODUCT_CHECKBOX_ERROR);
  } else {
    $messageStack->add('cart_quantity', WISHLIST_SUB_PRODUCT_INPUT_ERROR);
  }
}
      if(defined('PRODUCT_INFO_TAB_ENABLE') && PRODUCT_INFO_TAB_ENABLE == 'True' && (is_dir(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/tabs/'))){
          $content = CONTENT_PRODUCT_INFO_TABS;
      } else {
          $content = CONTENT_PRODUCT_INFO;
      }
  $javascript = 'popup_window.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
