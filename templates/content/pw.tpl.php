<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('pw', 'top');
// RCI code eof
?>    
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
<td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
</tr>
<tr>
<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
<?php
if (($_GET['pass'] != '') && ($_GET['verifyid'] != '')){
$select = tep_db_query("SELECT * from " . TABLE_CUSTOMERS . " where customers_id =  " . (int)$_GET['verifyid'] . "");
$start = tep_db_fetch_array($select);
if ($start['customers_validation_code'] == $_GET['pass']) {
if ($start['customers_validation'] == '1'){
?>            <td class="main"><?php echo TEXT_YOUR_ACCOUNT_ALREADY_EXIST . '<br>'; ?></td>
<?php
}else{
tep_db_query("update " . TABLE_CUSTOMERS . " set customers_validation = '1', customers_email_registered = '" . $start['customers_email_address'] . "' where customers_id = '" . (int)$_GET['verifyid'] . "'");

if (defined('LOGIN_AFTER_VALIDATE') && LOGIN_AFTER_VALIDATE == 'true'){
    $_SESSION['customer_id'] = $start['customers_id'];
    $_SESSION['customer_default_address_id'] = $start['customers_default_address_id'];
    $_SESSION['customer_first_name'] = $start['customers_firstname'];
    $_SESSION['customer_country_id'] = $start['entry_country_id'];
    $_SESSION['customer_zone_id'] = $start['entry_zone_id'];

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
        $cart->restore_contents();
    }
?>
<td class="main"><?php echo TEXT_ACCOUNT_CREATED . '<br>'; ?></td>
         </tr>
          <tr>
            <td><?php 
    }
  } else {
  ?>
  <td class="main"><?php echo TEXT_ACCOUNT_CREATED_FAIL . '<br>'; ?></td>
         </tr>
          <tr>
            <td>
      <?php
      }
  } else {
  ?>
  <td class="main"><?php echo TEXT_ACCOUNT_CREATED_FAIL2 . '<br>'; ?></td>
         </tr>
          <tr>
            <td>
  <?php 
  
  }
  echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php 
      // RCI code start
      echo $cre_RCI->get('pw', 'menu');
      // RCI code eof
      ?>    
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>

          </tr>
        </table>
<?php 
// RCI code start
echo $cre_RCI->get('global', 'bottom');
echo $cre_RCI->get('pw', 'bottom');
// RCI code eof
?>