<?php
/*
  $Id: header.php,v 1.3.0.0 2008/06/09 23:39:42 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$languages = tep_get_languages();
$languages_array = array();
$languages_selected = DEFAULT_LANGUAGE;
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
  $languages_array[] = array('id' => $languages[$i]['code'],
                             'text' => $languages[$i]['name']);
  if ($languages[$i]['directory'] == $language) {
    $languages_selected = $languages[$i]['code'];
  }
}
// RCI top
echo $cre_RCI->get('header', 'top');
?>
<!-- warnings //-->
<?php require(DIR_WS_INCLUDES . 'warnings.php'); ?>
<!-- warning_eof //-->
<script type="text/javascript">
/* preload images */
var imgs = ['images/button.png', 'images/button-over.png', 'images/button-active.png', 'images/button-submit.png', 'images/button-submit-over.png', 'images/button-submit-active.png'];
for (var i = 0; i < imgs.length; i++) {
  var img = new Image();
  img.src = imgs[i];
}
/* toolbar function */
function showSearch (section) {
  $$('.toolbar-search-link').invoke('show');
  $$('.toolbar-search-label').invoke('hide');
  $$('.toolbar-search-input').invoke('hide');
  $(section + '-search-label').show();
  $(section + '-search-input').show().down('input').focus();
  $(section + '-search-link').hide();
}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0" id="head">
  <tr>
    <td align="left" valign="middle" class="head-content" rowspan="2">
      <a href="<?php echo tep_catalog_href_link();?>" target="_blank"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . '/' . STORE_LOGO ,STORE_NAME);?></a>
    </td>
    <td align="right"  valign="top" class="HeaderMessage"><!--Banner Script mods to fix TEXT ONLY ad area for Admin Header  This ad area reserved for Security Alert System - Please DO NOT change! -->
      <!--Banner Script Start-->
      <iframe src="messages.php?s=header" frameborder="0"   width="468" height="52" scrolling="No"  allowtransparency="true"></iframe>
      <!--Banner Script End-->
     </td>
    <td rowspan="2" align="right" width="76"  valign="middle" class="HeaderMessage"> <?php echo tep_image(DIR_WS_IMAGES . 'pro_badge.png'); ?></td>
  </tr>
  <!--<tr>
    <td align="right" valign="bottom" class="head-content">  <a href="http://www.creloaded.com/support/" class="admin_header" target="_blank"><?php echo TEXT_PURCHASE_SUPPORT;?></a> | <a href="http://www.cresecure.com/from_admin" target="_blank" class="admin_header"><?php echo TEXT_CRE_SECURE;?></a></td>
  </tr>-->
</table>

<?php
// hide search bar when not logged in
if (basename($PHP_SELF) != FILENAME_LOGIN && basename($PHP_SELF) != FILENAME_PASSWORD_FORGOTTEN && basename($PHP_SELF) != FILENAME_LOGOFF) {
?>
<div id="toolbar">
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="toolbar-table">
  <tr>
  <td align="left" valign="top">
    <table border="0" cellpadding="0" cellspacing="0" height="36" id="toolbar-left">
      <tr>
        <td class="toolbar-label"><?php echo TEXT_SEARCH; ?></td>
        <td id="products-search-link" class="toolbar-search-link" style="display: none;"><a href="javascript:void(0)" onclick="showSearch('products')"><?php echo TEXT_HEADING_CATALOG; ?></a></td>
        <td id="products-search-label" class="toolbar-search-label"><?php echo TEXT_HEADING_CATALOG; ?></td><td id="products-search-input" class="toolbar-search-input">
          <?php
          echo tep_draw_form('frmprodsearch', FILENAME_CATEGORIES, '', 'post');
          $prodparams="class=\"text\" onChange=\"this.form.submit();\" onblur=\"javascript:document.frmprodsearch.search.value='';\" onfocus=\"javascript:document.frmprodsearch.search.value='';\"";
          echo tep_draw_input_field('search','', $prodparams, false, '', false);
          if (isset($_GET[tep_session_name()])) {
            echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
          }
          ?>  
          </form>
        </td>
        <td id="customers-search-link" class="toolbar-search-link"><a href="javascript:void(0)" onclick="showSearch('customers')"><?php echo TEXT_HEADING_CUSTOMERS; ?></a></td>
        <td id="customers-search-label" class="toolbar-search-label" style="display: none;"><?php echo TEXT_HEADING_CUSTOMERS; ?></td><td id="customers-search-input" class="toolbar-search-input" style="display: none;">
          <?php 
          echo tep_draw_form('frmcustsearch', FILENAME_CUSTOMERS, '', 'post');
          $custparams="class=\"text\" onChange=\"this.form.submit();\" onblur=\"javascript:document.frmcustsearch.search.value='';\" onclick=\"javascript:document.frmcustsearch.search.value='';\"";
          echo tep_draw_input_field('search','', $custparams, false, '', false); 
          if (isset($_GET[tep_session_name()])) {
            echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
          }
          ?>
          </form>        
        </td>
        <td id="orders-search-link" class="toolbar-search-link"><a href="javascript:void(0)" onclick="showSearch('orders')"><?php echo TEXT_HEADING_ORDERS; ?></a></td>
        <td id="orders-search-label" class="toolbar-search-label" style="display: none;"><?php echo TEXT_HEADING_ORDERS; ?></td><td id="orders-search-input" class="toolbar-search-input" style="display: none;">
          <?php 
          $orderparams="class=\"text\" onChange=\"this.form.submit();\" onblur=\"javascript:document.frmordersearch.oID.value='';\" onfocus=\"javascript:document.frmordersearch.oID.value='';\"";?>
          <?php echo tep_draw_form('frmordersearch', FILENAME_ORDERS, '', 'get') . tep_draw_input_field('SoID', '', $orderparams, false, '', false) . tep_draw_input_field('action', 'edit', '', false, 'hidden', false);
          if (isset($_GET[tep_session_name()])) {
            echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
          }
          ?>
          </form>
        </td>
        <td id="pages-search-link" class="toolbar-search-link"><a href="javascript:void(0)" onclick="showSearch('pages')"><?php echo BOX_PAGES; ?></a></td>
        <td id="pages-search-label" class="toolbar-search-label" style="display: none;"><?php echo BOX_PAGES; ?></td><td id="pages-search-input" class="toolbar-search-input" style="display: none;">
          <?php 
          echo tep_draw_form('frmpagesearch', FILENAME_PAGES, '', 'get');
          $articlesparams="class=\"text\" onblur=\"javascript:document.frmpagesearch.search.value='';\" onfocus=\"javascript:document.frmpagesearch.search.value='';\"";
          echo tep_draw_input_field('search','',$articlesparams,false,'',false);
          if (isset($_GET[tep_session_name()])) {
            echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
          }
          ?>
          </form>
        </td>
        <td class="toolbar-separator">|</td>
        <td class="toolbar-label">Create</td>
        <td class="toolbar-link"><a href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'); ?>"><?php echo CUSTOMER; ?></a></td>
        <td class="toolbar-link"><a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, '', 'SSL'); ?>"><?php echo TEXT_ORDER; ?></a></td>
        <td class="toolbar-separator">|</td>
         <td class="toolbar-link"><a href="<?php echo tep_href_link(FILENAME_DEFAULT);?>" class="admin_header"><?php echo TEXT_ADMIN_HOME;?></a></td>
          <td class="toolbar-link"><a target="_blank" href="<?php echo tep_catalog_href_link();?>" class="admin_header"><?php echo TEXT_VIEW_CATALOG;?></a></td>
      </tr>
    </table>
  </td>
  <td align="right" valign="top">
    <table border="0" cellpadding="0" cellspacing="0" height="36" id="toolbar-right">
      <tr>
        <td class="toolbar-label"><?php echo TEXT_ADMIN_LANG; ?></td>
        <td class="toolbar-input">
          <?php
          echo tep_draw_form('languages', 'index.php', '', 'get');
          echo tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"') . "\n";
          if (isset($_GET[tep_session_name()])) {
            echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]) . "\n";
          }
          ?>
          </form>
        </td>
        <td class="toolbar-separator">|</td>
        <td class="toolbar-link"><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><?php echo TEXT_LOGOUT; ?></a></td>
      </tr>
    </table>
  </td>
  </tr>
</table>
<div id="toolbar-shadow">&nbsp;</div>
</div>
<?php 
if (MENU_DHTML == 'True') require(DIR_WS_INCLUDES . 'header_navigation.php');
  if ($messageStack->size('search') > 0) {
    echo $messageStack->output('search');  
  }
} // hide search bar eof
// RCI bottom
echo $cre_RCI->get('header', 'bottom');
?>