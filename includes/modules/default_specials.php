<?php
/*
  $Id: default_specials.php,v 2.1 2006/09/07 23:42:27 datazen Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- default_specials mainpage //-->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_DEFAULT_SPECIALS, strftime('%B')));
  
  new contentBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SPECIALS));

  $random_specials = array();
  $specials_array = array();
  $specials_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and s.status = '1' order by s.specials_date_added DESC");
  while ($specials = tep_db_fetch_array($specials_query)) {
    $random_specials[] .= $specials['products_id'];
  }

  $row = 0;
  $col = 0;

  if (sizeof($random_specials)!=0){
    $max_displayed = (MAX_DISPLAY_SPECIAL_PRODUCTS > sizeof($random_specials)) ? sizeof($random_specials) : MAX_DISPLAY_SPECIAL_PRODUCTS;
    $special_products = cre_random_array($random_specials, $max_displayed);
    for($i=0; $i < $max_displayed; $i++) {
      $product_array = array();
      $product_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price as special_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where  p.products_id = $special_products[$i] and  s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "'");
      $product = tep_db_fetch_array($product_query);

      $pf->loadProduct($product['products_id'],$languages_id);
      $products_price_s = $pf->getPriceStringShort();
     
        $buyitnow='<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $product['products_id'] . '&cPath=' . tep_get_product_path($product['products_id'])) . '">' . tep_template_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
      
 
      $info_box_contents[$row][$col] = array('align' => 'center',
                                                             'params' => 'class="smallText" width="33%" valign="top"',
                                                             'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $product['products_image'], $product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['products_id']) . '">' . $product['products_name'] . '</a><br>' . $products_price_s . '<br>'. $buyitnow);
      $col ++;
      if ($col > 2) {
        $col = 0;
        $row ++;
      }
    }
  }else{
      $info_box_contents[$row][$col] = array('align' => 'left',
                                             'params' => 'class="smallText" width="33%" valign="top"',
                                             'text' => TEXT_NO_SPECIALS);
  }
  
  new contentBox($info_box_contents, true, true);

 if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){
   $info_box_contents = array();
   $info_box_contents[] = array('align' => 'left',
                                 'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                               );
  new contentBoxFooter($info_box_contents);
}
?><!-- default_specials_eof //-->