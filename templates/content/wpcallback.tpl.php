<?php
/*
  $Id: wpcallback.php,v 2.0 2008/07/17 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('wpcallback', 'top');
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
if (isset($_POST['transStatus']) && $_POST['transStatus'] == "Y") {  // Success 
  $url = tep_href_link(FILENAME_CHECKOUT_PROCESS, 'order_id=' . $_POST['cartId'], 'SSL', false);
  echo "<meta http-equiv='Refresh' content='10; Url=\"$url\"'>";
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;';
    ?>
    <tr>
      <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
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
  ?>
  <tr>
    <td class="pageHeading" width="100%" colspan="2" align="center"><?php echo WP_TEXT_SUCCESS; ?></td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main" align="center">
            <p><?php echo WP_TEXT_HEADING; ?></p>
            <p><?php echo '<WPDISPLAY ITEM=banner>'; ?></p>
            <p><a href=http://www.worldpay.com/index.php?CMP=BA2713><img src=https://www.worldpay.com/cgenerator/logos/poweredByWorldPay.gif border=0 alt="Powered By WorldPay"></a></p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
  </tr>
  <tr>
    <td class="pageHeading" width="100%" colspan="2" align="center"><h3><?php echo WP_TEXT_SUCCESS_WAIT; ?></h3></td>
  </tr>
  <tr align="right">
    <td><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PROCESS, 'order_id=' . $_POST['cartId'], 'SSL', false) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '50'); ?></td>
  </tr>
  <?php // Failure
} else {
  $url = tep_href_link(FILENAME_CHECKOUT_PAYMENT, $$_POST['cartId'], 'SSL');
  echo "<meta http-equiv='Refresh' content='10; Url=\"$url\"'>";
  ?>
  <tr>
    <td class="pageHeading" width="100%" colspan="2" align="center"><?php echo WP_TEXT_FAILURE; ?></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '50'); ?></td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <table border="2" bordercolor="#FF0000" width="80%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main" align="center"><p><?php echo WP_TEXT_HEADING; ?></p><p><?php echo '<WPDISPLAY ITEM=banner>'; ?></p></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td>
      <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><b><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', false) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="25%">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table>
          </td>
          <td width="25%">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table>
          </td>
          <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
          <td width="25%">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
          <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
          <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
          <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
        </tr>
      </table>
    </td>
  </tr>
  <?php
}
?>
</table>
<?php
// RCI bottom
echo $cre_RCI->get('wpcallback', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>