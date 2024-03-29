<?php
/*
  $Id: cache.php,v 1.1.1.1 2004/03/04 23:38:12 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/   
require('includes/application_top.php');
$dir_cache = DIR_FS_CATALOG . DIR_FS_CACHE ;
$action = (isset($_GET['action']) ? $_GET['action'] : '');
if (tep_not_null($action)) {
  if ($action == 'reset') {
    tep_reset_cache_block($_GET['block']);
  }
  tep_redirect(tep_href_link(FILENAME_CACHE));
}
// check if the cache directory exists
if (is_dir($dir_cache)) {
  if (!is_writeable($dir_cache)) $messageStack->add('search', ERROR_CACHE_DIRECTORY_NOT_WRITEABLE, 'error');
} else {
  $messageStack->add('search', ERROR_CACHE_DIRECTORY_DOES_NOT_EXIST, 'error');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CACHE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_CREATED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              if (isset($messageStack) && is_object($messageStack)) {
                $languages = tep_get_languages();
                // get default template
                if (isset($cptemplate1) && is_array($cptemplate1) && tep_not_null($cptemplate1['template_selected'])) {
                  define('TEMPLATE_NAME', $cptemplate['template_selected']);
                } else if  (tep_not_null(DEFAULT_TEMPLATE)) {
                  define('TEMPLATE_NAME', DEFAULT_TEMPLATE);  
                } else {
                  define('TEMPLATE_NAME', 'default');
                }
                $template_query = tep_db_query("select tp.template_name from " . TABLE_TEMPLATE . " tp order by tp.template_name");
                while ($template = tep_db_fetch_array($template_query)) {
                  $template_array[] = array('template' => $template['template_name']);
                }
                for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                  if ($languages[$i]['code'] == DEFAULT_LANGUAGE) {
                    $language = $languages[$i]['directory'];
                  }
                }
                for ($i=0, $n=sizeof($cache_blocks); $i<$n; $i++) {
                  $cached_file = ereg_replace('.language', '.' . $language, $cache_blocks[$i]['file']);
                  for ($j=0, $k=sizeof($template_array); $j<$k; $j++) {
                    $cached_file_unlink = ereg_replace('-TEMPLATE_NAME', '-' . $template_array[$j]['template'] , $cached_file);
                    if (file_exists($dir_cache . $cached_file_unlink)) {
                      $cache_mtime = strftime(DATE_TIME_FORMAT, filemtime($dir_cache . $cached_file_unlink));
                    } else {
                      $cache_mtime = TEXT_FILE_DOES_NOT_EXIST;
                      $dir = dir($dir_cache);
                      $cache_file = (isset($cache_file) ? $cache_file : '' );
                      while ($cached_file_unlink = $dir->read()) {
                        if (isset($cache_file) && ereg('^' . $cached_file, $cache_file)) {
                          $cache_mtime = strftime(DATE_TIME_FORMAT, filemtime($dir_cache . $cached_file_unlink));
                          break;
                        }
                      }
                    }
                    $dir->close();
                  }
                  ?>
                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                    <td class="dataTableContent"><?php echo $cache_blocks[$i]['title']; ?></td>
                    <td class="dataTableContent" align="right"><?php echo $cache_mtime; ?></td>
                    <td class="dataTableContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CACHE, 'action=reset&block=' . $cache_blocks[$i]['code'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_reset.gif', 'Reset', 13, 13) . '</a>'; ?>&nbsp;</td>
                  </tr>
                  <?php
                }
              }
              ?>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_CACHE_DIRECTORY . ' ' . $dir_cache; ?></td>
              </tr>
            </table></td>
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