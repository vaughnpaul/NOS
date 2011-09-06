<?php
/*
  $Id: account_history_info.tpl.php,v 1.0 20090/04/06 23:38:03 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('accounthistoryinfo', 'top');
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
    ?>
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_history.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <?php
  } else {
    $header_text = HEADING_TITLE;
  }  
  if (MAIN_TABLE_BORDER == 'yes') {
    table_image_border_top(false, false, $header_text);
  }
  ?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main" colspan="2"><b><?php echo sprintf(HEADING_ORDER_NUMBER, $_GET['order_id']) . ' <small>(' . $order->info['orders_status'] . ')</small>'; ?></b></td>
      </tr>
      <tr>
        <td class="smallText"><?php echo HEADING_ORDER_DATE . ' ' . tep_date_long($order->info['date_purchased']); ?></td>
        <td class="smallText" align="right"><?php echo HEADING_ORDER_TOTAL . ' ' . $order->info['total']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <?php
        if ($order->delivery != false) {
          ?>
          <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main"><b><?php echo HEADING_DELIVERY_ADDRESS; ?></b></td>
            </tr>
            <tr>
              <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?></td>
            </tr>
            <?php
            if (tep_not_null($order->info['shipping_method'])) {
              ?>
              <tr>
                <td class="main"><b><?php echo HEADING_SHIPPING_METHOD; ?></b></td>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['shipping_method']; ?></td>
              </tr>
              <?php
            }
            ?>
          </table></td>
          <?php
        }
        ?>
        <td width="<?php echo (($order->delivery != false) ? '70%' : '100%'); ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
              if (sizeof($order->info['tax_groups']) > 1) {
                ?>
                <tr>
                  <td class="main" colspan="2"><b><?php echo HEADING_PRODUCTS; ?></b></td>
                  <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
                  <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                </tr>
                <?php
              } else {
                ?>
                <tr>
                  <td class="main" colspan="2"><b><?php echo HEADING_PRODUCTS; ?></b></td>
                  <td class="main" align="right"><b><?php echo HEADING_PRODUCTS_BASE_PRICE; ?></b></td>
                  <td class="main" align="right"><b><?php echo HEADING_PRODUCTS_FINAL_PRICE; ?></b></td>
                </tr>
                <?php
              }
              for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
                echo '          <tr>' . "\n" .
                     '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
                     '            <td class="main" valign="top">' . $order->products[$i]['name'] ; 
                echo '<br><b>' . HEADING_OPTIONS  . '</b></small>';
                //check for attibutes:
                $attributes_check_query = tep_db_query("SELECT *
                                                          from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "
                                                        WHERE orders_id = '" .(int)$_GET['order_id'] . "' 
                                                          and orders_products_id = '" . $order->products[$i]['orders_products_id'] . "' ");
                if (tep_db_num_rows($attributes_check_query)) {
                  while ($attributes = tep_db_fetch_array($attributes_check_query)) {
                    echo '<br><small><i> *' . $attributes['products_options'] . ' : ' . $attributes['products_options_values'] . '</i></small>';
                    echo '<br><small> ' . $attributes['price_prefix'] . ' ' . $currencies->display_price($attributes['options_values_price'], tep_get_tax_rate($order->products[$i]['tax_class_id']), 1) . '</small>';
                  }
                }
                // Begin RMA Returns System
                $return_link = '';
                if ($order->products[$i]['return'] == '1') {
                  $rma_query_one = tep_db_query("SELECT returns_id FROM " . TABLE_RETURNS_PRODUCTS_DATA . " where products_id = '" . $order->products[$i]['id'] . "' and order_id = '" . $_GET['order_id'] . "'");
                  while($rma_query = tep_db_fetch_array($rma_query_one)) {
                    $rma_number_query = tep_db_query("SELECT rma_value FROM " . TABLE_RETURNS . " where returns_id = '" . $rma_query['returns_id'] . "'");
                    $rma_result = tep_db_fetch_array($rma_number_query);
                    $return_link .= '<b>' . TEXT_RMA . ' #&nbsp;<u><a href="' . tep_href_link(FILENAME_RETURNS_TRACK, 'action=returns_show&rma=' . $rma_result['rma_value'], 'NONSSL') . '">' . $rma_result['rma_value'] . '</a></u></b>';
                  }
                }
                if (defined('DISPLAY_RMA_LINK') && DISPLAY_RMA_LINK == 'true') {
                  $return_link .= '<a href="' . tep_href_link(FILENAME_RETURN, 'order_id=' . $_GET['order_id'] . '&product_id=' . ($order->products[$i]['id']), 'NONSSL') . '"><b><u>' . TEXT_RETURN_PRODUCT .'</a></u></b>';
                }
                // Don't show Return link if order is still pending or processing
                // You can change this or comment it out as best fits your store configuration
                if (($order->info['orders_status'] == 'Pending') OR ($order->info['orders_status'] == 'Processing')) {
                  $return_link = '';
                }
                echo $return_link  . "\n";
                // End RMA Returns System                 
                echo '</td>' . "\n";
                echo '</td><td class="main" valign="top" align="right">' .  $currencies->display_price($order->products[$i]['price'], (isset($products[$i]['tax_class_id']) ? tep_get_tax_rate($products[$i]['tax_class_id']) : 0), 1) . '</td>' . "\n";
                if (sizeof($order->info['tax_groups']) > 1) {
                  echo '<td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
                }
                echo '<td class="main" align="right" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
                     '</tr>' . "\n";
              }
              ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td class="main"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo HEADING_BILLING_ADDRESS; ?></b></td>
          </tr>
          <tr>
            <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo HEADING_PAYMENT_METHOD; ?></b></td>
          </tr>
          <tr>
            <td class="main"><?php echo $order->info['payment_method']; ?></td>
          </tr>
        </table></td>
        <td width="70%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <?php
          for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
            echo '<tr>' . "\n" .
                 '<td class="main" align="right" width="100%">' . $order->totals[$i]['title'] . '</td>' . "\n" .
                 '<td class="main" align="right">' . $order->totals[$i]['text'] . '</td>' . "\n" .
                 '</tr>' . "\n";
          }
          ?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td class="main"><b><?php echo HEADING_ORDER_HISTORY; ?></b></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td colspan="2"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <?php
          $statuses_query = tep_db_query("select os.orders_status_name, osh.date_added, osh.comments from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '" . (int)$_GET['order_id'] . "' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' order by osh.date_added");
          while ($statuses = tep_db_fetch_array($statuses_query)) {
            echo '<tr>' . "\n" .
                 '<td class="main" valign="top" width="70">' . tep_date_short($statuses['date_added']) . '</td>' . "\n" .
                 '<td class="main" valign="top" width="170">' . $statuses['orders_status_name'] . '</td>' . "\n" .
                 '<td class="main" valign="top">' . (empty($statuses['comments']) ? '&nbsp;' : nl2br(tep_output_string_protected($statuses['comments']))) . '</td>' . "\n" .
                 '</tr>' . "\n";
          }
          ?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <?php
  // RCI menu
  echo $cre_RCI->get('accounthistoryinfo', 'bottominsidetable');
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
  if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . FILENAME_DOWNLOADS);
  ?>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="left" width="30%" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, tep_get_all_get_params(array('order_id')), 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <?php
            // RCI menu buttons
            echo $cre_RCI->get('accounthistoryinfo', 'menubuttons');
            ?> 
            <td align="right" class="main"><?php echo '<a href="javascript:popupWindow(\'' .  tep_href_link(FILENAME_ORDERS_PRINTABLE, tep_get_all_get_params(array('order_id')) . 'order_id=' . (int)$_GET['order_id'].'&customer_id='.$_SESSION['customer_id'], 'NONSSL') . '\')">' . tep_template_image_button('button_printorder.gif', IMAGE_BUTTON_PRINT_ORDER) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php
// RCI bottom
echo $cre_RCI->get('accounthistoryinfo', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>