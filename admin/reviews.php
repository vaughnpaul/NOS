<?php
/*
  $Id: reviews.php,v 1.2 2008/05/30 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$action = (isset($_GET['action']) ? $_GET['action'] : '');
$reviews_rating = (isset($_POST['reviews_rating']) ? $_POST['reviews_rating'] : '');
if (tep_not_null($action)) {
  switch ($action) {
    case 'update':
      $reviews_id = tep_db_prepare_input($_GET['rID']);
      $reviews_rating = tep_db_prepare_input($reviews_rating);
      $reviews_text = tep_db_prepare_input($_POST['reviews_text']);
      tep_db_query("update " . TABLE_REVIEWS . " set reviews_rating = '" . tep_db_input($reviews_rating) . "', last_modified = now() where reviews_id = '" . (int)$reviews_id . "'");
      tep_db_query("update " . TABLE_REVIEWS_DESCRIPTION . " set reviews_text = '" . tep_db_input($reviews_text) . "' where reviews_id = '" . (int)$reviews_id . "'");
      tep_redirect(tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews_id));
      break;
    case 'deleteconfirm':
      $reviews_id = tep_db_prepare_input($_GET['rID']);
      tep_db_query("delete from " . TABLE_REVIEWS . " where reviews_id = '" . (int)$reviews_id . "'");
      tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$reviews_id . "'");
      tep_redirect(tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page']));
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
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <?php
      if ($action == 'edit') {
        $rID = tep_db_prepare_input($_GET['rID']);
        $reviews_query = tep_db_query("SELECT r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating 
                                         from " . TABLE_REVIEWS . " r, 
                                              " . TABLE_REVIEWS_DESCRIPTION . " rd 
                                       WHERE r.reviews_id = '" . (int)$rID . "' 
                                         and r.reviews_id = rd.reviews_id");
        $reviews = tep_db_fetch_array($reviews_query);
        $products_query = tep_db_query("SELECT products_image 
                                          from " . TABLE_PRODUCTS . " 
                                        WHERE products_id = '" . (int)$reviews['products_id'] . "'");
        $products = tep_db_fetch_array($products_query);
        $products_name_query = tep_db_query("SELECT products_name 
                                               from " . TABLE_PRODUCTS_DESCRIPTION . " 
                                             WHERE products_id = '" . (int)$reviews['products_id'] . "' 
                                               and language_id = '" . (int)$languages_id . "'");
        $products_name = tep_db_fetch_array($products_name_query);
        $rInfo_array = array_merge((array)$reviews, (array)$products, (array)$products_name);
        $rInfo = new objectInfo($rInfo_array);
        ?>
        <tr><?php echo tep_draw_form('review', FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID'] . '&action=preview'); ?>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="main" valign="top"><b><?php echo ENTRY_PRODUCT; ?></b> <?php echo $rInfo->products_name; ?><br><b><?php echo ENTRY_FROM; ?></b> <?php echo $rInfo->customers_name; ?><br><br><b><?php echo ENTRY_DATE; ?></b> <?php echo tep_date_short($rInfo->date_added); ?></td>
              <td class="main" align="right" valign="top"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="main" valign="top"><b><?php echo ENTRY_REVIEW; ?></b><br><br><?php echo tep_draw_textarea_field('reviews_text', 'soft', '60', '15', $rInfo->reviews_text); ?></td>
            </tr>
            <tr>
              <td class="smallText" align="right"><?php echo ENTRY_REVIEW_TEXT; ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="main"><b><?php echo ENTRY_RATING; ?></b>&nbsp;<?php echo TEXT_BAD; ?>&nbsp;<?php for ($i=1; $i<=5; $i++) echo tep_draw_radio_field('reviews_rating', $i, '', $rInfo->reviews_rating) . '&nbsp;'; echo TEXT_GOOD; ?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td align="right" class="main"><?php echo tep_draw_hidden_field('reviews_id', $rInfo->reviews_id) . tep_draw_hidden_field('products_id', $rInfo->products_id) . tep_draw_hidden_field('customers_name', $rInfo->customers_name) . tep_draw_hidden_field('products_name', $rInfo->products_name) . tep_draw_hidden_field('products_image', $rInfo->products_image) . tep_draw_hidden_field('date_added', $rInfo->date_added) . '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_magnifier.png', IMAGE_PREVIEW); ?></td>
        </form></tr>
        <?php
      } elseif ($action == 'preview') {
        if (tep_not_null($_POST)) {
          $rInfo = new objectInfo($_POST);
        } else {
          $rID = tep_db_prepare_input($_GET['rID']);
          $reviews_query = tep_db_query("SELECT r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating 
                                           from " . TABLE_REVIEWS . " r, 
                                                " . TABLE_REVIEWS_DESCRIPTION . " rd 
                                         WHERE r.reviews_id = '" . (int)$rID . "' 
                                           and r.reviews_id = rd.reviews_id");
          $reviews = tep_db_fetch_array($reviews_query);
          $products_query = tep_db_query("SELECT products_image 
                                            from " . TABLE_PRODUCTS . " 
                                          WHERE products_id = '" . (int)$reviews['products_id'] . "'");
          $products = tep_db_fetch_array($products_query);
          $products_name_query = tep_db_query("SELECT products_name 
                                                 from " . TABLE_PRODUCTS_DESCRIPTION . " 
                                               WHERE products_id = '" . (int)$reviews['products_id'] . "' 
                                                 and language_id = '" . (int)$languages_id . "'");
          $products_name = tep_db_fetch_array($products_name_query);
          $rInfo_array = array_merge((array)$reviews, (array)$products, (array)$products_name);
          $rInfo = new objectInfo($rInfo_array);
        }
        ?>      
        <tr><?php echo tep_draw_form('update', FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID'] . '&action=update', 'post', 'enctype="multipart/form-data"'); ?>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="main" valign="top"><b><?php echo ENTRY_PRODUCT; ?></b> <?php echo $rInfo->products_name; ?><br><b><?php echo ENTRY_FROM; ?></b> <?php echo $rInfo->customers_name; ?><br><br><b><?php echo ENTRY_DATE; ?></b> <?php echo tep_date_short($rInfo->date_added); ?></td>
              <td class="main" align="right" valign="top"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
            </tr>
          </table>
        </tr>
        <tr>
          <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" class="main"><b><?php echo ENTRY_REVIEW; ?></b><br><br><?php echo nl2br(tep_db_output(tep_break_string($rInfo->reviews_text, 15))); ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="main"><b><?php echo ENTRY_RATING; ?></b>&nbsp;<?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif', sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating)); ?>&nbsp;<small>[<?php echo sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating); ?>]</small></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <?php
        if (tep_not_null($_POST)) {
          /* Re-Post all POST'ed variables */
          reset($_POST);
          while(list($key, $value) = each($_POST)) echo tep_draw_hidden_field($key, $value);
          ?>
          <tr>
            <td align="right" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
            </form>
          </tr>
          <?php
        } else {
          if (isset($_GET['origin'])) {
            $back_url = $_GET['origin'];
            $back_url_params = '';
          } else {
            $back_url = FILENAME_REVIEWS;
            $back_url_params = 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id;
          }
          ?>
          <tr>
            <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
          <?php
        }
      } else {
        ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_RATING; ?></td>
                  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
                  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                </tr>
                <?php   
                $reviews_query_raw = "SELECT reviews_id, products_id, date_added, last_modified, reviews_rating 
                                        from " . TABLE_REVIEWS . " 
                                      ORDER BY date_added DESC";
                $reviews_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $reviews_query_raw, $reviews_query_numrows);
                $reviews_query = tep_db_query($reviews_query_raw);
                while ($reviews = tep_db_fetch_array($reviews_query)) {
                  if ((!isset($_GET['rID']) || (isset($_GET['rID']) && ($_GET['rID'] == $reviews['reviews_id']))) && !isset($rInfo)) {
                    $reviews_text_query = tep_db_query("SELECT r.reviews_read, r.customers_name, length(rd.reviews_text) as reviews_text_size 
                                                          from " . TABLE_REVIEWS . " r, 
                                                               " . TABLE_REVIEWS_DESCRIPTION . " rd 
                                                        WHERE r.reviews_id = '" . (int)$reviews['reviews_id'] . "' 
                                                          and r.reviews_id = rd.reviews_id");
                    $reviews_text = tep_db_fetch_array($reviews_text_query);
                    $products_image_query = tep_db_query("SELECT products_image 
                                                            from " . TABLE_PRODUCTS . " 
                                                          WHERE products_id = '" . (int)$reviews['products_id'] . "'");
                    $products_image = tep_db_fetch_array($products_image_query);
                    $products_name_query = tep_db_query("SELECT products_name 
                                                           from " . TABLE_PRODUCTS_DESCRIPTION . " 
                                                         WHERE products_id = '" . (int)$reviews['products_id'] . "' 
                                                         and language_id = '" . (int)$languages_id . "'");
                    $products_name = tep_db_fetch_array($products_name_query);
                    $reviews_average_query = tep_db_query("SELECT (avg(reviews_rating) / 5 * 100) as average_rating 
                                                             from " . TABLE_REVIEWS . " 
                                                           WHERE products_id = '" . (int)$reviews['products_id'] . "'");
                    $reviews_average = tep_db_fetch_array($reviews_average_query);
                    $review_info = array_merge((array)$reviews_text, (array)$reviews_average, (array)$products_name);
                    $rInfo_array = array_merge((array)$reviews, (array)$review_info, (array)$products_image);
                    $rInfo = new objectInfo($rInfo_array);
                  }
                  if (isset($rInfo) && is_object($rInfo) && ($reviews['reviews_id'] == $rInfo->reviews_id) ) {
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=preview') . '\'">' . "\n";
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews['reviews_id']) . '\'">' . "\n";
                  }
                  ?>
                  <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews['reviews_id'] . '&action=preview') . '">' . tep_image(DIR_WS_ICONS . 'magnifier.png', ICON_PREVIEW) . '</a>&nbsp;' . tep_get_products_name($reviews['products_id']); ?></td>
                  <td class="dataTableContent" align="right"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif'); ?></td>
                  <td class="dataTableContent" align="right"><?php echo tep_date_short($reviews['date_added']); ?></td>
                  <td class="dataTableContent" align="right"><?php if ( (is_object($rInfo)) && ($reviews['reviews_id'] == $rInfo->reviews_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png'); } else { echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                  </tr>
                  <?php
                }
                ?>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
                <tr>
                  <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText" valign="top"><?php echo $reviews_split->display_count($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></td>
                      <td class="smallText" align="right"><?php echo $reviews_split->display_links($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
              <?php
              $heading = array();
              $contents = array();
              switch ($action) {
                case 'delete':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_REVIEW . '</b>');
                  $contents = array('form' => tep_draw_form('reviews', FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=deleteconfirm'));
                  $contents[] = array('text' => TEXT_INFO_DELETE_REVIEW_INTRO);
                  $contents[] = array('text' => '<br><b>' . $rInfo->products_name . '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
                  break;
                default:
                  if (isset($rInfo) && is_object($rInfo)) {
                    $heading[] = array('text' => '<b>' . $rInfo->products_name . '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' <b>' . tep_date_short($rInfo->date_added) . '</b>');
                    if (tep_not_null($rInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' <b>' . tep_date_short($rInfo->last_modified) . '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_AUTHOR . ' <b>' . $rInfo->customers_name . '</b>');
                    $contents[] = array('text' => TEXT_INFO_REVIEW_RATING . ' ' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif'));
                    $contents[] = array('text' => TEXT_INFO_REVIEW_READ . ' <b>' . $rInfo->reviews_read . '</b>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_SIZE . ' <b>' . $rInfo->reviews_text_size . ' bytes</b>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_PRODUCTS_AVERAGE_RATING . ' <b>' . number_format($rInfo->average_rating, 2) . '%</b>');
                  }
                  break;
              }
              if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                echo '<td width="25%" valign="top">' . "\n";
                $box = new box;
                echo $box->infoBox($heading, $contents);
                echo '</td>' . "\n";
              }
              ?>
            </tr>
          </table></td>
        </tr>
       <?php
      }
      ?>
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