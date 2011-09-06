<?php
/*
  $Id: featured.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- featured_products //-->
<?php
if(file_exists(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/modules/featured.php')) { 
  include_once(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/modules/featured.php');  
} else {
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => TABLE_HEADING_FEATURED_PRODUCTS);
  $featured_products_2bb_query = tep_db_query("SELECT DISTINCT p.products_image, p.products_id, pd.products_name, p.products_image 
                                                 from (" . TABLE_PRODUCTS . " p 
                                               LEFT JOIN " . TABLE_SPECIALS . " s using(products_id)),
                                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                                         " . TABLE_FEATURED . " f
                                               WHERE p.products_status = '1'
                                                 and f.status ='1'
                                                 and p.products_id = f.products_id
                                                 and pd.products_id = p.products_id
                                                 and pd.language_id = '" . $languages_id . "'
                                               ORDER BY rand(), p.products_date_added DESC, pd.products_name");
  $row = 0;
  $col = 0;
  $num = 0;
  while ($featured_products = tep_db_fetch_array($featured_products_query)) {
    $num ++;
    if ($num == 1) {
      new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_FEATURED_PRODUCTS));
    }    
    $pf->loadProduct($featured_products['products_id'],$languages_id);
    $products_price_2bb = $pf->getPriceStringShort();
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a><br>' . $products_price_2bb);
    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }
  if($num) {
  new contentBox($info_box_contents, true, true);

 if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){
   $info_box_contents = array();
   $info_box_contents[] = array('align' => 'left',
                                 'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                               );
  new contentBoxFooter($info_box_contents);
}
  }
}
?>
<!-- featured_products_eof //-->