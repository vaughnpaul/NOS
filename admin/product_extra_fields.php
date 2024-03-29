<?php
/*
  $Id: product_extra_field.php,v 2.0 2004/11/09 22:50:52 ChBu Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  * 
  * v2.0: added languages support
*/
require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
// Has "Remove" button been pressed?
if (isset($_POST['remove_x']) || isset($_POST['remove_y'])) $action='remove';

if (tep_not_null($action)) {
  switch ($action) {
    case 'setflag':
      $sql_data_array = array('products_extra_fields_status' => tep_db_prepare_input($_GET['flag']));
    tep_db_perform(TABLE_PRODUCTS_EXTRA_FIELDS, $sql_data_array, 'update', 'products_extra_fields_id=' . $_GET['id']);
      tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));  
    break;
    case 'add':
      $sql_data_array = array('products_extra_fields_name' => tep_db_prepare_input($_POST['field']['name']),
                            'languages_id' => tep_db_prepare_input ($_POST['field']['language']),
                'products_extra_fields_order' => tep_db_prepare_input($_POST['field']['order']));
      tep_db_perform(TABLE_PRODUCTS_EXTRA_FIELDS, $sql_data_array, 'insert');

      tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));
      break;
    case 'update':
      foreach ($_POST['field'] as $key=>$val) {
        $sql_data_array = array('products_extra_fields_name' => tep_db_prepare_input($val['name']),
                            'languages_id' =>  tep_db_prepare_input($val['language']),
                  'products_extra_fields_order' => tep_db_prepare_input($val['order']));
        tep_db_perform(TABLE_PRODUCTS_EXTRA_FIELDS, $sql_data_array, 'update', 'products_extra_fields_id=' . $key);
      }
      tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));

      break;
    case 'remove':
      //print_r($_POST['mark']);
      if ($_POST['mark']) {
        foreach ($_POST['mark'] as $key=>$val) {
          tep_db_query("DELETE FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " WHERE products_extra_fields_id=" . tep_db_input($key));
          tep_db_query("DELETE FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_extra_fields_id=" . tep_db_input($key));
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS));
      }

      break;
  }
}

// Put languages information into an array for drop-down boxes
  $languages=tep_get_languages();
  $values[0]=array ('id' =>'0', 'text' => TEXT_ALL_LANGUAGES);
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
  $values[$i+1]=array ('id' =>$languages[$i]['id'], 'text' =>$languages[$i]['name']);
  }
     
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">  
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
     <td width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
       <tr>
        <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
       </tr>
      </table>
     </td>
    </tr>

    <tr>
     <td width="100%">
      <!--
      <div style="font-family: verdana; font-weight: bold; font-size: 17px; margin-bottom: 8px; color: #727272;">
       <?php echo SUBHEADING_TITLE; ?>
      </div>
      -->
      <br>
      <?php echo tep_draw_form("add_field", FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=add', 'post', '', 'SSL'); ?>
      <table border="0" width="400" cellspacing="0" cellpadding="2">
       <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIELDS; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER; ?></td>
    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LANGUAGE; ?></td>
       </tr>

       <tr>
        <td class="dataTableContent">
         <?php echo tep_draw_input_field('field[name]', (isset($field['name']) ? $field['name'] : ''), 'size=30', false, 'text', true);?>
        </td>
    <td class="dataTableContent" align="center">
         <?php echo tep_draw_input_field('field[order]', (isset($field['order']) ? $field['order'] : ''), 'size=5', false, 'text', true);?>
        </td>
    <td class="dataTableContent" align="center">
         <?php 
     echo tep_draw_pull_down_menu('field[language]', $values, '0', '');?>
        </td>   
        <td class="dataTableContent" align="right"><?php echo tep_image_submit('button_add_field.gif',IMAGE_ADD_FIELD)?></td>
       </tr>
       </form>
      </table>
      <hr />
      <br>
      <?php 
       echo tep_draw_form('extra_fields', FILENAME_PRODUCTS_EXTRA_FIELDS,'action=update','post', '', 'SSL');
      ?>
      <?php echo (isset($action_message) ? $action_message : ''); ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
       <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" width="20">&nbsp;</td>
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIELDS; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER; ?></td>
    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LANGUAGE; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
       </tr>
<?php 
$products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " ORDER BY products_extra_fields_order");
while ($extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
?>
       <tr>
        <td width="20">
         <?php echo tep_draw_checkbox_field('mark['.$extra_fields['products_extra_fields_id'].']', 1) ?>
        </td>
        <td class="dataTableContent">
         <?php echo tep_draw_input_field('field['.$extra_fields['products_extra_fields_id'].'][name]', $extra_fields['products_extra_fields_name'], 'size=30', false, 'text', true);?>
        </td>
    <td class="dataTableContent" align="center">
         <?php echo tep_draw_input_field('field['.$extra_fields['products_extra_fields_id'].'][order]', $extra_fields['products_extra_fields_order'], 'size=5', false, 'text', true);?>
        </td>
    <td class="dataTableContent" align="center">
     <?php echo tep_draw_pull_down_menu('field['.$extra_fields['products_extra_fields_id'].'][language]', $values, $extra_fields['languages_id'], ''); ?>
        </td> 
        <td  class="dataTableContent" align="center">
         <?php
          if ($extra_fields['products_extra_fields_status'] == '1') {
            echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=setflag&flag=0&id=' . $extra_fields['products_extra_fields_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
          }
          else {
            echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=setflag&flag=1&id=' . $extra_fields['products_extra_fields_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
          }
         ?>
        </td>
       </tr>
<?php } ?>
       <tr>
        <td colspan="4">
         <?php echo tep_image_submit('button_update_fields.gif',IMAGE_UPDATE_FIELDS,'name="update" onclick="document.extra_fields.action=\'' . str_replace('&amp;', '&', tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=update', 'SSL')) . '\';"')?> 
         &nbsp;&nbsp;
   <?php echo tep_image_submit('button_remove_fields.gif',IMAGE_REMOVE_FIELDS,'name="remove" onclick="document.extra_fields.action=\'' . str_replace('&amp;', '&', tep_href_link(FILENAME_PRODUCTS_EXTRA_FIELDS, 'action=remove', 'SSL')) . '\';"')?> 
        </td>
       </tr>
       </form>
      </table>
     </td>
    </tr>
   </table>
  </td>
 <!-- body_text_eof //-->
 </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
