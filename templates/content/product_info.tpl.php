<?php
/*
  $Id: product_info.tpl.php,v 1.2.0.0 2008/01/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('productinfo', 'top');
// RCI code eof
echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action', 'products_id', 'id')) . 'action=add_product' . '&' . $params), 'post', 'enctype="multipart/form-data"'); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">

<?php
  if ($messageStack->size('cart_quantity') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('cart_quantity'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }

  // added for CDS CDpath support
  $params = (isset($_SESSION['CDpath'])) ? 'CDpath=' . $_SESSION['CDpath'] : ''; 
  if ($product_check['total'] < 1) {
    ?>
    <tr>
      <td><?php  new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, $params) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);
    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$product_info['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
    if (tep_not_null($product_info['products_model'])) {
      $products_name = '<h1>' . $product_info['products_name'] . '</h1>&nbsp;<span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = '<h1>' . $product_info['products_name'] . '</h1>';
    }
    if ($product_has_sub > '0'){ // if product has sub products
      $products_price ='';// if you like to show some thing in place of price add here
    } else {
      $pf->loadProduct($product_info['products_id'],$languages_id);
      $products_price = $pf->getPriceStringShort();
    } // end sub product check
    if (SHOW_HEADING_TITLE_ORIGINAL=='yes') {
      $header_text = '';
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><h1 class="pageHeading"><?php echo $products_name; ?></h1></td>
            <td class="pageHeading" align="right"><?php echo $products_price; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    } else {
      $header_text =  $products_name .'</td><td class="productlisting-headingPrice">' . $products_price;
    }
    // RCI code start
    echo $cre_RCI->get('productinfo', 'underpriceheading');
    // RCI code eof
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_top(false, false, $header_text);
    }
    ?>
    <tr>
      <td class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main" valign="top">
            <?php if (tep_not_null($product_info['products_image']) || tep_not_null($product_info['products_image_med'])) { ?>
            <table border="0" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td>
                  <?php
                  if ($product_info['products_image_med']!='') {
                    $new_image = $product_info['products_image_med'];
                    $image_width = MEDIUM_IMAGE_WIDTH;
                    $image_height = MEDIUM_IMAGE_HEIGHT;
                  } else {
                    $new_image = $product_info['products_image'];
                    $image_width = SMALL_IMAGE_WIDTH;
                    $image_height = SMALL_IMAGE_HEIGHT;
                  }
                  $popup_avail = tep_not_null($product_info['products_image_lrg']) ? true : false;
                  echo tep_javascript_image(DIR_WS_IMAGES . $new_image, 'product' . $product_info['products_id'], addslashes($product_info['products_name']), $image_width, $image_height, 'hspace="5" vspace="5"', $popup_avail);
                  if (isset($_SESSION['affiliate_id'])) {
                    echo '<br><br><a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD, 'individual_banner_id=' . $product_info['products_id'] . '&' . $params) .'" target="_self">' . tep_template_image_button('button_affiliate_build_a_link.gif', LINK_ALT) . ' </a>';       
                  }
                  ?>
                </td>
              </tr>
            </table>
            <?php    }  // end if image
            echo '<p>' .  cre_clean_product_description($product_info['products_description']) . '</p>';
            echo tep_draw_separator('pixel_trans.gif', '100%', '10');
            $products_id_tmp = $product_info['products_id'];
            if(tep_subproducts_parent($products_id_tmp)){
              $products_id_query = tep_subproducts_parent($products_id_tmp);
            } else {
              $products_id_query = $products_id_tmp;
            }
            if ((defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES != 'True') || $product_has_sub == '0'){
              $load_attributes_for = $products_id_query;
              include(DIR_WS_MODULES . 'product_info/product_attributes.php');
            }
            ?>
      </table></td>
    </tr>
    <?php
    if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') { 
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . 'additional_images.php')) {
            require(TEMPLATE_FS_CUSTOM_MODULES . 'additional_images.php');
        } else {
            require(DIR_WS_MODULES . 'additional_images.php');
        }
    }
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_info['products_id'] . "'");
    $reviews = tep_db_fetch_array($reviews_query);
    if ($reviews['count'] > 0) {
      ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>
      </tr>
      <?php
    }
    
    include(DIR_WS_MODULES . 'product_info/extra_products_fields.php');
    
    if (tep_not_null($product_info['products_url'])) {
      ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&amp;goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    }
    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
      ?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></td>
      </tr>
      <?php
    } else {
      ?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])); ?></td>
      </tr>
      <?php
    }
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_bottom();
    }
    // sub product start
    if (STOCK_ALLOW_CHECKOUT =='false') {
      $allowcriteria = "";
    }
    
    $select_order_by = '';
    switch (defined('SUB_PRODUCTS_SORT_ORDER')? strtoupper(SUB_PRODUCTS_SORT_ORDER) : '') {
      case 'MODEL':
        $select_order_by .= 'sp.products_model';
        break;
      case 'NAME':
        $select_order_by .= 'spd.products_name';
        break;
      case 'PRICE':
        $select_order_by .= 'sp.products_price';
        break;
      case 'QUANTITY':
        $select_order_by .= 'sp.products_quantity';
        break;
      case 'WEIGHT':
        $select_order_by .= 'sp.products_weight';
        break;
      case 'SORT ORDER':
        $select_order_by .= 'sp.sort_order';
        break;
      case 'LAST ADDED':
        $select_order_by .= 'sp.products_date_added';
        break;
      default:
        $select_order_by .= 'sp.products_model';
        break;
    }
    $sub_products_query = tep_db_query("select sp.products_id, sp.products_quantity, sp.products_price, sp.products_tax_class_id, sp.products_image, spd.products_name, spd.products_blurb, sp.products_model from " . TABLE_PRODUCTS . " sp, " . TABLE_PRODUCTS_DESCRIPTION . " spd where sp.products_parent_id = " . (int)$product_info['products_id'] . " and spd.products_id = sp.products_id and spd.language_id = " . (int)$languages_id . " order by " . $select_order_by);
    if ( tep_db_num_rows($sub_products_query) > 0 ) {
        if(defined('PRODUCT_INFO_SUB_PRODUCT_DISPLAY') && PRODUCT_INFO_SUB_PRODUCT_DISPLAY == 'In Listing'){
            include(DIR_WS_MODULES . 'product_info/sub_products_listing.php');
        } else if ( PRODUCT_INFO_SUB_PRODUCT_DISPLAY == 'Drop Down'){
            include(DIR_WS_MODULES . 'product_info/sub_products_dropdown.php');
        }
    }
    // sub product_eof
    if ($product_check['total'] > 0) {
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" valign="middle"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params() . $params) . '">' . tep_template_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS,'align="middle"') . '</a>'; ?></td>
                <?php
                if (DESIGN_BUTTON_WISHLIST == 'true') {
                  echo '<td align="right" class="main" valign="middle"><!-- Begin Wishlist Code -->' . "\n";
                  echo '<script type="text/javascript"><!--' . "\n";
                  echo 'function addwishlist() {' . "\n";
                  echo 'document.cart_quantity.action=\'' . str_replace('&amp;', '&', tep_href_link(FILENAME_PRODUCT_INFO, 'action=add_wishlist' . '&' . $params)) . '\';' . "\n";
                  echo 'document.cart_quantity.submit();' . "\n";
                  echo '}' . "\n";
                  echo '--></script>' . "\n";
                  echo '<a href="javascript:addwishlist()">' . tep_template_image_button('button_add_wishlist.gif', IMAGE_BUTTON_ADD_WISHLIST,'align="middle"') . '</a>' ;
                  echo '</td><!-- End Wishlist Code -->';
                }
              } // if products_check
              ?>
              <td class="main" align="right" valign="middle"><table border="0" cellspacing="0" cellpadding="0" align="right">
                <tr>
                  <?php 
                  
                  if (tep_db_num_rows($sub_products_query) == 0) {
                  ?>
                    <td class="main"><?php echo TEXT_ENTER_QUANTITY . ':&nbsp;&nbsp;';?></td>
                    <td class="main"><?php echo tep_draw_input_field('cart_quantity', '1', 'size="4" maxlength="4"');?>&nbsp;&nbsp;</td>
                  <?php 
                  }
                  ?>
                  <td class="main"><?php
                    
                    echo tep_draw_hidden_field('products_id', $product_info['products_id']) . ((PRODUCT_INFO_SUB_PRODUCT_PURCHASE == 'Multi' || (PRODUCT_INFO_SUB_PRODUCT_PURCHASE == 'Single' && PRODUCT_INFO_SUB_PRODUCT_DISPLAY == 'Drop Down') || tep_db_num_rows($sub_products_query) == 0 ) ? tep_template_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART,'align="middle"') : ''); 
                  ?></td>
                </tr>
              </table></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php 
    if (SHOW_PRICE_BREAK_TABLE == 'true') {
      //include(DIR_WS_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE);
      if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE)) {
        require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE);
      } else {
        require(DIR_WS_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE);
      }
    }  
    if ( (USE_CACHE == 'true') && !SID) {    
      echo tep_cache_also_purchased(3600);
      // include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);
      if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS)) {
          require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS);
      } else {
          require(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);
      }
    } else {
      //include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
      if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW)) {
         require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
      } else {
         require(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
      }
      //include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
      if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS)) {
        require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
      } else {
        require(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
      }
    }          
  }
  // product info page bottom
  echo $cre_RCI->get('productinfo', 'bottom');
 ?>   
</table>
<?php
    if(PRODUCT_INFO_SUB_PRODUCT_DISPLAY != 'Single Purchase'){
?>
</form>
<?php
    }
// RCI code start
echo $cre_RCI->get('global', 'bottom');
?>
