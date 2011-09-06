<?php
  //---------------------------------------------------------------------------//
  //
  //  Code: categories_description
  //  Author: Brian Lowe <blowe@wpcusrgrp.org>
  //  Date: June 2002
  //
  //  Contains code snippets for the categories_description contribution to
  //  osCommerce.
  //---------------------------------------------------------------------------//
  //  Code: categories_description MS2 1.5
  //  Editor: Lord Illicious <shaolin-venoms@illicious.net>
  //  Date: July 2003
  //
  //---------------------------------------------------------------------------//

  //---------------------------------------------------------------------------//
  //  Get a category heading_title or description
  // These should probably be in admin/includes/functions/general.php, but since
  // this is a contribution and not part of the base code, they are here instead
  //---------------------------------------------------------------------------//
  function tep_get_category_heading_title($category_id, $language_id) {
    $category_query = tep_db_query("select categories_heading_title from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
    return $category['categories_heading_title'];
  }

  function tep_get_category_description($category_id, $language_id) {
    $category_query = tep_db_query("select categories_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
    return $category['categories_description'];
  }
 function tep_get_category_head_title_tag($category_id, $language_id) {
    $category_query = tep_db_query("select categories_head_title_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
    return $category['categories_head_title_tag'];
  }
   function tep_get_category_head_desc_tag($category_id, $language_id) {
      $category_query = tep_db_query("select categories_head_desc_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
      $category = tep_db_fetch_array($category_query);
      return $category['categories_head_desc_tag'];
  }
   function tep_get_category_head_keywords_tag($category_id, $language_id) {
      $category_query = tep_db_query("select categories_head_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
      $category = tep_db_fetch_array($category_query);
      return $category['categories_head_keywords_tag'];
  }
  function tep_update_sub_name($parent_id, $old_products_name, $new_products_name, $language_id) {
    if ($parent_id > 0) {
      $sub_query = tep_db_query("SELECT p.products_id, pd.products_name
                                 FROM " . TABLE_PRODUCTS . " p,
                                      " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                 WHERE p.products_parent_id = " . (int)$parent_id . "
                                   and pd.products_id = p.products_id
                                   and pd.language_id = " . (int)$language_id);
      while ($sub = tep_db_fetch_array($sub_query)) {
        $subname = substr( $sub['products_name'], strlen( $old_products_name . ' - ' ));
        $new_subname = $new_products_name . ' - ' . $subname;
        if ($new_subname != $sub['product_name']) {
          $sql_data_array = array('products_name' => tep_db_encoder($new_subname));
          tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', 'products_id = ' . (int)$sub['products_id'] . ' and language_id = ' . $language_id);
        }
      }
    }
  }
  function tep_get_pricing() {
    $pricing[1]['qty'] = $_POST['products_price1_qty'];
//    $pricing[1]['qty'] = 2;
    
    for ($i = 2; $i <= 11; ++$i) {
      $pricing[$i]['qty'] = $_POST['products_price' . ($i) . '_qty'];
      if ($pricing[$i]['qty'] < $pricing[$i-1]['qty']) $pricing[$i]['qty'] = $pricing[$i-1]['qty'];
    }
    
    $pricing[1]['price'] = (float)$_POST['products_price1'];
    if ($pricing[1]['price'] == 0 || $pricing[1]['price'] > $_POST['products_price']) $pricing[1]['price'] = $_POST['products_price'];
    
    for ($i = 2; $i <= 11; ++$i) {
      $pricing[$i]['price'] = (float)$_POST['products_price'.$i];
      if ($pricing[$i]['price'] == 0 || $pricing[$i]['price'] > $pricing[$i-1]['price']) $pricing[$i]['price'] = $pricing[$i-1]['price'];
    }
    
    return $pricing;
  }
?>