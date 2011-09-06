<?php
/*
  $Id: footer.php,v 1.1.1.1 2004/03/04 23:39:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Chain Reaction Works, Inc
  
  Copyright &copy; 2003-2006
  
  Last Modified By : $Author$
  Last Modified On : $LastChangeDate$
  Latest Revision :  $Revision: 3529 $
  
*/
// RCI top
echo $cre_RCI->get('footer', 'top');
?>
<div id="foot">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="foot-content">
  <tr>
    <td align="left" valign="top"><a href="http://www.creloaded.com/" target="_blank"><?php echo tep_image(DIR_WS_IMAGES . 'chain-reaction-ecommerce.png', 'Chain Reaction Ecommerce', '250', '45') ?></a></td>
    <td align="center"><!--iframe src="messages.php?s=footer" frameborder="0" width="468" height="60" scrolling="no"  allowtransparency="true"></iframe--></td>
    <td align="right">
    <?php
      $has_cds = (file_exists(DIR_FS_ADMIN . 'cds_configuration.php')) ? true : false;
      $has_fdms = (file_exists(DIR_FS_ADMIN . 'fdm_configuration.php')) ? true : false;
      if ($has_fdms && $has_cds) {
        echo '<a href="http://www.creloaded.com/main/products/addons/" target="_blank">' . tep_image(DIR_WS_IMAGES . 'cre-logo-cd-fdm.png', 'CRE Loaded Add-Ons', '', '65') . '</a>';
      } else if ($has_fdms && !$has_cds) {
        echo '<a href="http://www.creloaded.com/main/products/addons/fdm/" target="_blank">' . tep_image(DIR_WS_IMAGES . 'cre-logo-fdm.png', 'CRE File Distribution System', '', '65') . '</a>'; 
      } else if (!$has_fdms && $has_cds) {
        echo '<a href="http://www.creloaded.com/main/products/addons/contentdirector/" target="_blank">' . tep_image(DIR_WS_IMAGES . 'cre-logo-cd.png', 'CRE Content Director System', '', '65') . '</a>'; 
      } else {
        echo '<a href="http://www.creloaded.com/main/products/" target="_blank">' . tep_image(DIR_WS_IMAGES . 'cre-logo.png', 'CRE Loaded', '', '65') . '</a>';
      }
    ?>
    </td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="foot-content">
  <tr>
    <td align="center" class="smallText"><?php echo FOOTER_TEXT_BODY;?>
    </td>
  </tr>
</table>
</div>
<?php
// RCI bottom
echo $cre_RCI->get('footer', 'bottom');
?>
<script type="text/javascript">
if (Prototype && Prototype.Browser.Gecko) {
  $$('.cssButtonSubmit').invoke('addClassName', 'cssButtonSubmitMoz');
  $$('.cssButton').each(function (button) {
    button.addClassName('cssButtonMoz');
    button.up('a').setStyle({marginRight: '4px', textDecoration: 'none'});
  });
}
</script>