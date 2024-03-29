<?php
/*
  $Id: currencies.php,v 1.1.1.1 2004/03/04 23:38:22 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($_GET['cID'])) $currency_id = tep_db_prepare_input($_GET['cID']);
        $title = tep_db_prepare_input($_POST['title']);
        $code = tep_db_prepare_input($_POST['code']);
        $symbol_left = tep_db_prepare_input($_POST['symbol_left']);
        $symbol_right = tep_db_prepare_input($_POST['symbol_right']);
        $decimal_point = tep_db_prepare_input($_POST['decimal_point']);
        $thousands_point = tep_db_prepare_input($_POST['thousands_point']);
        $decimal_places = tep_db_prepare_input($_POST['decimal_places']);
        $value = tep_db_prepare_input($_POST['value']);

        if(trim($symbol_left) == '�'){
          $symbol_left = '&#163;';
        }
        if(trim($symbol_right) == '�'){          
          $symbol_right = '&#163;';
        }

        $sql_data_array = array('title' => $title,
                                'code' => $code,
                                'symbol_left' => $symbol_left,
                                'symbol_right' => $symbol_right,
                                'decimal_point' => $decimal_point,
                                'thousands_point' => $thousands_point,
                                'decimal_places' => $decimal_places,
                                'value' => $value);

        if ($action == 'insert') {
          tep_db_perform(TABLE_CURRENCIES, $sql_data_array);
          $currency_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          tep_db_perform(TABLE_CURRENCIES, $sql_data_array, 'update', "currencies_id = '" . (int)$currency_id . "'");
        }

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_CURRENCY'");
        }

        tep_redirect(tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $currency_id));
        break;
      case 'deleteconfirm':
        $currencies_id = tep_db_prepare_input($_GET['cID']);

        $currency_query = tep_db_query("select currencies_id from " . TABLE_CURRENCIES . " where code = '" . DEFAULT_CURRENCY . "'");
        $currency = tep_db_fetch_array($currency_query);

        if ($currency['currencies_id'] == $currencies_id) {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'DEFAULT_CURRENCY'");
        }

        tep_db_query("delete from " . TABLE_CURRENCIES . " where currencies_id = '" . (int)$currencies_id . "'");

        tep_redirect(tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page']));
        break;
      case 'update':
        $server_used = CURRENCY_SERVER_PRIMARY;

        $currency_query = tep_db_query("select currencies_id, code, title from " . TABLE_CURRENCIES);
        while ($currency = tep_db_fetch_array($currency_query)) {
          $quote_function = 'quote_' . CURRENCY_SERVER_PRIMARY . '_currency';
          $rate = $quote_function($currency['code']);

          if (empty($rate) && (tep_not_null(CURRENCY_SERVER_BACKUP))) {
            $messageStack->add_session('search', sprintf(WARNING_PRIMARY_SERVER_FAILED, CURRENCY_SERVER_PRIMARY, $currency['title'], $currency['code']), 'warning');

            $quote_function = 'quote_' . CURRENCY_SERVER_BACKUP . '_currency';
            $rate = $quote_function($currency['code']);

            $server_used = CURRENCY_SERVER_BACKUP;
          }

          if (tep_not_null($rate)) {
            tep_db_query("update " . TABLE_CURRENCIES . " set value = '" . $rate . "', last_updated = now() where currencies_id = '" . (int)$currency['currencies_id'] . "'");

            $messageStack->add_session('search', sprintf(TEXT_INFO_CURRENCY_UPDATED, $currency['title'], $currency['code'], $server_used), 'success');
          } else {
            $messageStack->add_session('search', sprintf(ERROR_CURRENCY_INVALID, $currency['title'], $currency['code'], $server_used), 'error');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $_GET['cID']));
        break;
      case 'delete':
        $currencies_id = tep_db_prepare_input($_GET['cID']);

        $currency_query = tep_db_query("select code from " . TABLE_CURRENCIES . " where currencies_id = '" . (int)$currencies_id . "'");
        $currency = tep_db_fetch_array($currency_query);

        $remove_currency = true;
        if ($currency['code'] == DEFAULT_CURRENCY) {
          $remove_currency = false;
          $messageStack->add('search', ERROR_REMOVE_DEFAULT_CURRENCY, 'error');
        }
        break;
    }
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CURRENCY_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CURRENCY_CODES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_CURRENCY_VALUE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $currency_query_raw = "select currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, last_updated, value from " . TABLE_CURRENCIES . " order by title";
  $currency_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $currency_query_raw, $currency_query_numrows);
  $currency_query = tep_db_query($currency_query_raw);
  while ($currency = tep_db_fetch_array($currency_query)) {
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $currency['currencies_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cInfo = new objectInfo($currency);
    }

    if (isset($cInfo) && is_object($cInfo) && ($currency['currencies_id'] == $cInfo->currencies_id) ) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->currencies_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $currency['currencies_id']) . '\'">' . "\n";
    }

    if (DEFAULT_CURRENCY == $currency['code']) {
      echo '                <td class="dataTableContent"><b>' . $currency['title'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $currency['title'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $currency['code']; ?></td>
                <td class="dataTableContent" align="right"><?php echo number_format($currency['value'], 8); ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($currency['currencies_id'] == $cInfo->currencies_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png'); } else { echo '<a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $currency['currencies_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>          </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $currency_split->display_count($currency_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CURRENCIES); ?></td>
                    <td class="smallText" align="right"><?php echo $currency_split->display_links($currency_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td><?php if (CURRENCY_SERVER_PRIMARY) { echo '<a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->currencies_id . '&action=update') . '">' . tep_image_button('button_update_currencies.gif', IMAGE_UPDATE_CURRENCIES) . '</a>'; } ?></td>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->currencies_id . '&action=new') . '">' . tep_image_button('button_new_currency.gif', IMAGE_NEW_CURRENCY) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => TEXT_INFO_HEADING_NEW_CURRENCY);

      $contents = array('form' => tep_draw_form('currencies', FILENAME_CURRENCIES, 'page=' . $_GET['page'] . (isset($cInfo) ? '&cID=' . $cInfo->currencies_id : '') . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_TITLE . '<br>' . tep_draw_input_field('title'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_CODE . '<br>' . tep_draw_input_field('code'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_SYMBOL_LEFT . '<br>' . tep_draw_input_field('symbol_left'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_SYMBOL_RIGHT . '<br>' . tep_draw_input_field('symbol_right'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_DECIMAL_POINT . '<br>' . tep_draw_input_field('decimal_point'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_THOUSANDS_POINT . '<br>' . tep_draw_input_field('thousands_point'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_DECIMAL_PLACES . '<br>' . tep_draw_input_field('decimal_places'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_VALUE . '<br>' . tep_draw_input_field('value'));
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_INFO_SET_AS_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $_GET['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'. tep_image_submit('button_insert.gif', IMAGE_INSERT));
      break;
    case 'edit':
      $heading[] = array('text' => TEXT_INFO_HEADING_EDIT_CURRENCY);

      $contents = array('form' => tep_draw_form('currencies', FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->currencies_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_TITLE . '<br>' . tep_draw_input_field('title', $cInfo->title));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_CODE . '<br>' . tep_draw_input_field('code', $cInfo->code));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_SYMBOL_LEFT . '<br>' . tep_draw_input_field('symbol_left', $cInfo->symbol_left));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_SYMBOL_RIGHT . '<br>' . tep_draw_input_field('symbol_right', $cInfo->symbol_right));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_DECIMAL_POINT . '<br>' . tep_draw_input_field('decimal_point', $cInfo->decimal_point));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_THOUSANDS_POINT . '<br>' . tep_draw_input_field('thousands_point', $cInfo->thousands_point));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_DECIMAL_PLACES . '<br>' . tep_draw_input_field('decimal_places', $cInfo->decimal_places));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_VALUE . '<br>' . tep_draw_input_field('value', $cInfo->value));
      if (DEFAULT_CURRENCY != $cInfo->code) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_INFO_SET_AS_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->currencies_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
      break;
    case 'delete':
      $heading[] = array('text' => TEXT_INFO_HEADING_DELETE_CURRENCY);

      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->title . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->currencies_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . (($remove_currency) ? '<a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->currencies_id . '&action=deleteconfirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>' : ''));
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => $cInfo->title);

        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->currencies_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->currencies_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_TITLE . ' <b>' . $cInfo->title . '</b>');
        $contents[] = array('text' => TEXT_INFO_CURRENCY_CODE . ' <b>' . $cInfo->code . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_SYMBOL_LEFT . ' <b>' . $cInfo->symbol_left . '</b>');
        $contents[] = array('text' => TEXT_INFO_CURRENCY_SYMBOL_RIGHT . ' <b>' . $cInfo->symbol_right . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_DECIMAL_POINT . ' <b>' . $cInfo->decimal_point . '</b>');
        $contents[] = array('text' => TEXT_INFO_CURRENCY_THOUSANDS_POINT . ' <b>' . $cInfo->thousands_point . '</b>');
        $contents[] = array('text' => TEXT_INFO_CURRENCY_DECIMAL_PLACES . ' <b>' . $cInfo->decimal_places . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_LAST_UPDATED . ' <b>' . tep_date_short($cInfo->last_updated) . '</b>');
        $contents[] = array('text' => TEXT_INFO_CURRENCY_VALUE . ' <b>' . number_format($cInfo->value, 8) . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_EXAMPLE . '<br><b>' . $currencies->format('30', false, DEFAULT_CURRENCY) . ' = ' . $currencies->format('30', true, $cInfo->code) . '</b>');
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
