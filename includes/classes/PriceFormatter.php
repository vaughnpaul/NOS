<?php
/*
  $Id: PriceFormatter.php,v 1.6 2003/06/25 08:29:26 petri Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/*
    PriceFormatter.php - module to support quantity pricing

    Created 2003, Beezle Software based on some code mods by WasaLab Oy (Thanks!)
*/

class PriceFormatter {
  var $hiPrice;
  var $lowPrice;
  var $quantity;
  var $hasQuantityPrice;
  
  
  function PriceFormatter($prices=NULL) {
    $this->productsID = -1;
    $this->hasQuantityPrice = false;
    $this->hasSpecialPrice = false;    
    $this->hiPrice = -1;
    $this->lowPrice = -1;
    
    for ($i=1; $i<=11; $i++) {
      $this->quantity[$i] = -1;
      $this->prices[$i] = -1;
    }
    $this->thePrice = -1;
    $this->specialPrice = -1;
    $this->qtyBlocks = 1;
    
    if($prices) $this->parse($prices);
  }

  function encode() {
  $str = $this->productsID . ":"
         . (($this->hasQuantityPrice == true) ? "1" : "0") . ":"
         . (($this->hasSpecialPrice == true) ? "1" : "0") . ":"
         . $this->quantity[1] . ":"
         . $this->quantity[2] . ":"
         . $this->quantity[3] . ":"
         . $this->quantity[4] . ":"
         . $this->quantity[5] . ":"
         . $this->quantity[6] . ":"
         . $this->quantity[7] . ":"
         . $this->quantity[8] . ":"
         . $this->quantity[9] . ":"
         . $this->quantity[10] . ":"
         . $this->quantity[11] . ":"
         . $this->price[1] . ":"
         . $this->price[2] . ":"
         . $this->price[3] . ":"
         . $this->price[4] . ":"
         . $this->price[5] . ":"
         . $this->price[6] . ":"
         . $this->price[7] . ":"
         . $this->price[8] . ":"
         . $this->price[9] . ":"
         . $this->price[10] . ":"
         . $this->price[11] . ":"
         . $this->thePrice . ":"
         . $this->specialPrice . ":"
         . $this->qtyBlocks . ":"
         . $this->taxClass;
  return $str;
  }

  function decode($str) {
  list($this->productsID,
       $this->hasQuantityPrice,
       $this->hasSpecialPrice,
       $this->quantity[1],
       $this->quantity[2],
       $this->quantity[3],
       $this->quantity[4],
       $this->quantity[5],
       $this->quantity[6],
       $this->quantity[7],
       $this->quantity[8],
       $this->quantity[9],
       $this->quantity[10],
       $this->quantity[11],
       $this->price[1],
       $this->price[2],
       $this->price[3],
       $this->price[4],
       $this->price[5],
       $this->price[6],
       $this->price[7],
       $this->price[8],
       $this->price[9],
       $this->price[10],
       $this->price[11],
       $this->thePrice,
       $this->specialPrice,
       $this->qtyBlocks,
       $this->taxClass) = explode(":", $str);
     $this->hasQuantityPrice = (($this->hasQuantityPrice == 1) ? true : false);
     $this->hasSpecialPrice = (($this->hasSpecialPrice == 1) ? true : false);
  }

  function parse($prices) {
    $this->productsID = isset($prices['products_id']) ? (int)$prices['products_id'] : 0;
    $this->hasQuantityPrice = false;
    $this->hasSpecialPrice = false;
    
    $this->quantity[1] = isset($prices['products_price1_qty']) ? (int)$prices['products_price1_qty'] : 0;
    $this->quantity[2] = isset($prices['products_price2_qty']) ? (int)$prices['products_price2_qty'] : 0;
    $this->quantity[3] = isset($prices['products_price3_qty']) ? (int)$prices['products_price3_qty'] : 0;
    $this->quantity[4] = isset($prices['products_price4_qty']) ? (int)$prices['products_price4_qty'] : 0;
    $this->quantity[5] = isset($prices['products_price5_qty']) ? (int)$prices['products_price5_qty'] : 0;
    $this->quantity[6] = isset($prices['products_price6_qty']) ? (int)$prices['products_price6_qty'] : 0;
    $this->quantity[7] = isset($prices['products_price7_qty']) ? (int)$prices['products_price7_qty'] : 0;
    $this->quantity[8] = isset($prices['products_price8_qty']) ? (int)$prices['products_price8_qty'] : 0;
    $this->quantity[9] = isset($prices['products_price9_qty']) ? (int)$prices['products_price9_qty'] : 0;
    $this->quantity[10] = isset($prices['products_price10_qty']) ? (int)$prices['products_price10_qty'] : 0;
    $this->quantity[11] = isset($prices['products_price11_qty']) ? (int)$prices['products_price11_qty'] : 0;
    $this->products_qty_days = isset($prices['products_qty_days']) ? (int)$prices['products_qty_days'] : 0;
    $this->products_qty_years = isset($prices['products_qty_years']) ? (int)$prices['products_qty_years'] : 0;
    
    $this->thePrice = isset($prices['products_price']) ? $prices['products_price'] : 0;
    $this->specialPrice = $prices['specials_new_products_price'];
    $this->hasSpecialPrice = tep_not_null($this->specialPrice);
    
    $this->price[1] = isset($prices['products_price1']) ? $prices['products_price1'] : 0;
    $this->price[2] = isset($prices['products_price2']) ? $prices['products_price2'] : 0;
    $this->price[3] = isset($prices['products_price3']) ? $prices['products_price3'] : 0;
    $this->price[4] = isset($prices['products_price4']) ? $prices['products_price4'] : 0;
    $this->price[5] = isset($prices['products_price5']) ? $prices['products_price5'] : 0;
    $this->price[6] = isset($prices['products_price6']) ? $prices['products_price6'] : 0;
    $this->price[7] = isset($prices['products_price7']) ? $prices['products_price7'] : 0;
    $this->price[8] = isset($prices['products_price8']) ? $prices['products_price8'] : 0;
    $this->price[9] = isset($prices['products_price9']) ? $prices['products_price9'] : 0;
    $this->price[10] = isset($prices['products_price10']) ? $prices['products_price10'] : 0;
    $this->price[11] = isset($prices['products_price11']) ? $prices['products_price11'] : 0;
    /*
       Change support special prices
     If any price level has a price greater than the special
     price lower it to the special price
    */
    if ($this->hasSpecialPrice == true) {
      for($i=1; $i<=11; $i++) {
        if ($this->price[$i] > $this->specialPrice)
          $this->price[$i] = $this->specialPrice;
      }
    }
    //end changes to support special prices

    $this->qtyBlocks = (isset($prices['products_qty_blocks']) ? $prices['products_qty_blocks'] : 0 );
    $this->taxClass = (isset($prices['products_tax_class_id']) ? $prices['products_tax_class_id'] : 0 );

    if ($this->quantity[1] > 0) {
      $this->hasQuantityPrice = true;
      $this->hiPrice = $this->thePrice;
      $this->lowPrice = $this->thePrice;
      
      for($i=1; $i<=11; $i++) {
        if($this->quantity[$i] > 0) {
          if ($this->price[$i] > $this->hiPrice) {
            $this->hiPrice = $this->price[$i];
          }
          if ($this->price[$i] < $this->lowPrice) {
            $this->lowPrice = $this->price[$i];
          }
        }
      }
    }
  }

  function loadProduct($product_id, $language_id=1) {
  
    $sql="select pd.products_name, p.products_model, p.products_image, p.products_id," .
        " p.manufacturers_id, p.products_price, p.products_weight," .
        " p.products_price1,p.products_price2,p.products_price3,p.products_price4, p.products_price5,p.products_price6,p.products_price7,p.products_price8,p.products_price9,p.products_price10,p.products_price11," .
        " p.products_price1_qty,p.products_price2_qty,p.products_price3_qty,p.products_price4_qty, p.products_price5_qty,p.products_price6_qty,p.products_price7_qty,p.products_price8_qty,p.products_price9_qty,p.products_price10_qty,p.products_price11_qty," .
        " p.products_qty_blocks, " .
        " p.products_tax_class_id, p.products_qty_days, p.products_qty_years, " .
        " IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price " .
        " from " . TABLE_PRODUCTS . " p " .
        " left join " . TABLE_SPECIALS . " s using(products_id), " .
        "      " . TABLE_PRODUCTS_DESCRIPTION . " pd " .
        " where  p.products_id = '" . (int)$product_id . "'" .
        "   and pd.products_id = '" . (int)$product_id . "'" .
        "   and pd.language_id = '". (int)$language_id . "'";
    
    $product_info_query = tep_db_query($sql);
    $product_info = tep_db_fetch_array($product_info_query);
    
    $this->parse($product_info);
    return $product_info;
  }

  function computePrice($qty) {
    $qty = $this->adjustQty($qty);
    // Compute base price, taking into account the possibility of a special
    $price = ($this->hasSpecialPrice === true) ? $this->specialPrice : $this->thePrice;
    
    $parent_id = tep_db_fetch_array(tep_db_query("select products_parent_id from " . TABLE_PRODUCTS . " where products_id = '" . $this->productsID . "'"));
    if ($parent_id['products_parent_id'] != '0' && $this->hasQuantityPrice != true) {
      return $price;
    }
    
    for ($i=1; $i<=11; $i++) {
      if (($this->price[$i] > 0) && ($this->quantity[$i] > 0) && ((($qty > $this->quantity[$i]) && ($i != 1)) || (($qty >= $this->quantity[$i]) && ($i == 1)))) {
        $price = $this->price[$i];
      }
    }
    return $price;
  }

  function adjustQty($qty) {
    // Force QTY_BLOCKS granularity
    $qb = $this->getQtyBlocks();
    if ($qty < 1) $qty = 1;

    if ($qb >= 1) {
      if ($qty < $qb) $qty = $qb;

      if (($qty % $qb) != 0) $qty += ($qb - ($qty % $qb));
    }
    return $qty + $this->getHistoryQty();
  }
  
  function getHistoryQty() {
    $end_date = date('Y-m-d H:i:s');
    if (!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == 0) {
      return 0;
    } elseif ($this->products_qty_days > 0) {
      $start_date = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - (int)$this->products_qty_days, date('Y')));
    } elseif ($this->products_qty_years > 0) {
      $start_date = date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, date('Y') - (int)$this->products_qty_years + 1));
    } else {
      return 0;
    }
    $qty = 0;
    $qty_query = tep_db_query("SELECT op.products_quantity FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op WHERE o.orders_id = op.orders_id AND o.customers_id = '" . $_SESSION['customer_id'] . "' AND op.products_id = '" . $this->productsID . "' AND o.date_purchased BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
    while ($qty_data = tep_db_fetch_array($qty_query)) {
      $qty += $qty_data['products_quantity'];
    }
    
    return $qty;
  }
  
  function getQtyBlocks() {
    return $this->qtyBlocks;
  }

  function getPrice() {
    return $this->thePrice;
  }

  function getLowPrice() {
    return $this->lowPrice;
  }

  function getHiPrice() {
    return $this->hiPrice;
  }

  function hasSpecialPrice() {
    return $this->hasSpecialPrice;
  }

  function hasQuantityPrice() {
    return $this->hasQuantityPrice;
  }

  function getPriceString($style='productPriceInBox') {
    global $currencies;

    if ($this->hasSpecialPrice == true) {
      $lc_text = '<table align="top" border="1" cellspacing="0" cellpadding="0">';
      $lc_text .= '<tr><td align="center" class=' . $style. ' colspan="2">';
      $lc_text .= '&nbsp;<s>'
      . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
      . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'
      . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
      . '</span>&nbsp;'
      .'</td></tr>';
    } else {
        $lc_text = '<table align="top" border="1" cellspacing="0" cellpadding="0">';
        $lc_text .= '<tr><td align="center" class=' . $style. ' colspan="2">' . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))  . '</td></tr>';
    }
    // If you want to change the format of the price/quantity table
    // displayed on the product information page, here is where you do it.
    
    if ($this->hasQuantityPrice == true) {
      for ($i=1; $i<=11; $i++) {
        if ($this->quantity[$i] > 0) {
          $lc_text .= '<tr><td class='.$style.'>'
                   . $this->quantity[$i]
                   .'+&nbsp;</td><td class='.$style.'>'
                   . $currencies->display_price($this->price[$i],  tep_get_tax_rate($this->taxClass)) 
                   .'</td></tr>';
        }
      }
      $lc_text .= '</table>';
    } else {
      if ($this->hasSpecialPrice == true) {
        $lc_text = '&nbsp;<s>'
                 . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
                 . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'
                 . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
                 . '</span>&nbsp;';
      } 
    }
    return $lc_text;
  }

  function getPriceStringShort($price_selection='') {
    // the price selection default to the normal style of proce return
    // A selection of 'r' returns the formated regular price only
    // A selection of 's' returns the formated special price only, or null
    global $currencies;

    if (strtolower($price_selection) == 'r') {
      $lc_text = $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass));
    } elseif (strtolower($price_selection) == 's') {
      if ($this->hasSpecialPrice == true) {
        $lc_text = $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass));
      } else {
        $lc_text = '';
      }
    } else {
      if ($this->hasSpecialPrice == true) {
        $lc_text = '&nbsp;<s>' . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'  . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass)) . '</span>&nbsp;';
      } else {
        if ($this->hasQuantityPrice == true) {
          if ($this->lowPrice != $this->hiPrice && $this->lowPrice != 0) {
            $lc_text = '&nbsp;' . $currencies->display_price($this->lowPrice, tep_get_tax_rate($this->taxClass)) . ' - '. $currencies->display_price($this->hiPrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
          } else {
            $lc_text = '&nbsp;' . $currencies->display_price($this->hiPrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
          }
        } else {
          $lc_text = '&nbsp;' . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
        }
      }
    }
    return $lc_text;
  }


}
?>