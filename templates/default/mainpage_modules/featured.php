<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Featured Products V1.1
  Displays a list of featured products, selected from admin
  For use as an Infobox instead of the "New Products" Infobox
*/
?>
<!-- D featured_products_mainpage //-->
<?php
 
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => TABLE_HEADING_FEATURED_PRODUCTS);

  $featured_products_2bb_query = tep_db_query("SELECT DISTINCT
                                                      p.products_image, p.products_id,
                                                      pd.products_name, pd.products_blurb
                                               FROM (" . TABLE_PRODUCTS . " p 
                                               LEFT JOIN " . TABLE_SPECIALS . " s using(products_id)),
                                                     " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                                     " . TABLE_FEATURED . " f
                                               WHERE p.products_status = 1
                                                 and f.status = 1
                                                 and p.products_id = f.products_id
                                                 and pd.products_id = p.products_id
                                                 and pd.language_id = " . $languages_id . "
                                               ORDER BY rand()
                                               LIMIT " . MAX_DISPLAY_FEATURED_PRODUCTS);
   
  $featured_products_2bb_check = tep_db_num_rows($featured_products_2bb_query);
  if ($featured_products_2bb_check > 0){
    $row = 0;
    $col = 0;
    $num = 0;
    while ($featured_products_2bb = tep_db_fetch_array($featured_products_2bb_query)) {
      $num++;
      if ($num == 1) {
        new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_FEATURED_PRODUCTS));
      }    
      $pf->loadProduct($featured_products_2bb['products_id'],$languages_id);
      $products_price_2bb = $pf->getPriceStringShort();
      $info_box_contents[$row][$col] = array('align' => 'center',
                                             'params' => 'class="smallText" width="33%" valign="top"',
                                             'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_2bb['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products_2bb['products_image'], $featured_products_2bb['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_2bb['products_id']) . '">' . $featured_products_2bb['products_name'] . '</a><br>' . (tep_not_null($featured_products_2bb['products_blurb'] && PRODUCT_BLURB == 'true') ? $featured_products_2bb['products_blurb'] . '<br>' : '') . $products_price_2bb);
       
      $col ++;
      if ($col > 2) {
        $col = 0;
        $row ++;
      }
    }

    new contentBox($info_box_contents, true, true);
    if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){ 
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                  );
      new contentBoxFooter($info_box_contents);
    }
  } 
?>
<!-- featured_products_eof //-->