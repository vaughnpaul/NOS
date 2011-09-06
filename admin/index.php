<?php
/*
  $Id: index.php,v 1.0.0.0 2007/07/24 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require_once('includes/application_top.php');
include_once('includes/functions/rss2html.php');
// RCI top
echo $cre_RCI->get('index', 'top', false); 
// Get admin name 
$my_account_query = tep_db_query ("select admin_id, admin_firstname, admin_lastname from " . TABLE_ADMIN . " where admin_id= " . $_SESSION['login_id']);
$myAccount = tep_db_fetch_array($my_account_query);
$store_admin_name = $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<!-- code related to index.php only -->
<link type="text/css" rel="StyleSheet" href="includes/index.css" />
<link type="text/css" rel="StyleSheet" href="includes/helptip.css" /> 
<script type="text/javascript" src="includes/javascript/helptip.js"></script>
<!-- code related to index.php EOF -->
<!-- Tabs code -->
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/luna/tab.css">
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<!-- End Tabs -->
</head>
<body>    
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<div id="body">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>                   
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
    <td valign="top" class="page-container">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2" style="padding-bottom: 1em;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <?php echo sprintf(TEXT_WELCOME,$store_admin_name); ?>
                </td>
                <td align="right" style="padding-right: 12px;">
                  Version: <?php echo PROJECT_VERSION;?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td valign="top">
            
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td width="50%" valign="top"><?php 
                  // RCI include left admin blocks
                  echo $cre_RCI->get('index', 'blockleft');
                  ?>
                </td>
                <td width="50%" valign="top"><?php 
                  // RCI include right admin blocks
                  echo $cre_RCI->get('index', 'blockright');
                  ?>
                </td>
              </tr>
            </table>
          
          </td>
          <td width="180" valign="top">
            <!-- CRE Forge & CRE Loaded News -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td class="box-top-left">&nbsp;</td><td class="box-top">&nbsp;</td><td class="box-top-right">&nbsp;</td>
              </tr>
              <tr>
                <td class="box-left">&nbsp;</td><td class="box-content">
                  <div style="font-weight: bold; margin-bottom: .5em; font-size: 12px;">
                  <?php echo  BLOCK_TITLE_CRE_LOADED_ORG?>
                  </div>
                  <a class="adminLink" href="http://www.creloaded.org/index.php?option=com_jmrphpbb&Itemid=56" target="_blank"><?php echo  BLOCK_CONTENT_CRE_ORG_FORUMS;?></a> <br>
                  <a class="adminLink" href="http://www.creloaded.org/index.php?option=com_mtree&Itemid=55" target="_blank"><?php echo  BLOCK_CONTENT_CRE_ORG_EXTENSIONS?></a> <br>
                  
                  <a class="adminLink" href="http://creforge.com/tracker/?atid=543&group_id=119" target="_blank"><?php echo  BLOCK_CONTENT_CRE_FORGE_BUG_TRACKER;?></a><br>
                  
                  <a class="adminLink" href="http://creloaded.com/support" target="_blank"><?php echo  TEXT_PURCHASE_SUPPORT;?></a><br>
                  
                  <a class="adminLink" href="http://cresecure.com/from_admin" target="_blank"><?php echo  TEXT_CRE_SECURE;?></a><br>
                 
                </td><td class="box-right">&nbsp;</td>
              </tr>
              <tr>
                <td class="box-bottom-left">&nbsp;</td><td class="box-bottom">&nbsp;</td><td class="box-bottom-right">&nbsp;</td>
              </tr>
            </table>
            <?php 
            // RCO override index newsfeed
            if ($cre_RCO->get('index', 'newsfeed') !== true) {  
              ?>
              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 1em;">
              <tr>
                <td class="box-top-left">&nbsp;</td><td class="box-top">&nbsp;</td><td class="box-top-right">&nbsp;</td>
              </tr>
              <tr>
                <td class="box-left">&nbsp;</td><td class="box-content">
                  <div style="font-weight: bold; margin-bottom: .5em; font-size: 12px;"><?php echo  BLOCK_TITLE_CRE_NEWS?></div>
                  <?php
                  include_once('includes/functions/rss2html.php');
                  parseRDF("http://www.creloaded.com/rss/", 4);
                  ?>
                  <a href="http://www.creloaded.com/articles_new.php" target="_blank">more...</a>
                </td><td class="box-right">&nbsp;</td>
              </tr>
              <tr>
                <td class="box-bottom-left">&nbsp;</td><td class="box-bottom">&nbsp;</td><td class="box-bottom-right">&nbsp;</td>
              </tr>
            </table>
              </div>
              <?php
            }
            // RCO eof
            ?>
          </td>
        </tr>
      </table>
    </td>    
  </tr>
</table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="Footer Banner Table">
  <tr>
    <td align="center"><?php require(DIR_WS_INCLUDES . 'footer.php'); ?></td>
  </tr>
</table>
<?php
// RCI top
echo $cre_RCI->get('index', 'bottom'); 
?>
</body>
</html>