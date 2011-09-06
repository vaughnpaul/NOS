<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
  Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.


   Released under the GNU General Public License
*/
require('includes/languages/' . $language . '/index.php');
?>
<h1><?php echo VERSION; ?></h1>
<?php echo TEXT1; ?>
<form name="installer" action="" method="post">
<?php
    echo osc_draw_hidden_field('language_code', $language_code);

    reset($_POST);
    while (list($key, $value) = each($_POST)) {
      if (($key != 'x') && ($key != 'y')) {
        if (is_array($value)) {
          for ($i=0; $i<sizeof($value); $i++) {
            echo osc_draw_hidden_field($key . '[]', $value[$i]);
          }
        } else {
          echo osc_draw_hidden_field($key, $value);
        }
      }
    }
?>
<table align="center" cellpadding="0" cellspacing="0" style="padding: 10px;">
  <tr>
    <td  valign="top" width="50%">
      <p style="text-align: center; font-size: 1.2em; line-height: 1.5em;"><?php echo TEXT2; ?></p>
    </td>
    <td rowspan="2" align="center" valign="middle"><img src="images/separator-short.png" /></td>
    <td  valign="top" width="50%">
      <p style="text-align: center; font-size: 1.2em; line-height: 1.5em;"><?php echo TEXT3; ?></p>
      <p style="text-align: center; font-size: .9em; margin-bottom: 0; font-style: italic;"><?php echo TEXT3A; ?></p>
    </td>
  </tr>
  <tr>
    <td valign="top"  align="center">
      <a href="javascript:void(0)" onclick="document.installer.action = 'install.php'; document.installer.submit(); return false;" class="installation-button-large">
        <div class="installation-button-large-shadow"><?php echo INSTALL_BUTTON; ?></div>
      </a>
    </td>
    <td  align="center">
      <a href="javascript:void(0)" onclick="document.installer.action = 'upgrade.php?pci=1'; document.installer.submit(); return false;" class="installation-button-large">
        <div class="installation-button-large-shadow"><?php echo UPGRADE_BUTTON_PCI; ?></div>
      </a>&nbsp;
      <a href="javascript:void(0)" onclick="document.installer.action = 'upgrade.php'; document.installer.submit(); return false;" class="installation-button-large">
        <div class="installation-button-large-shadow"><?php echo UPGRADE_BUTTON; ?></div>
      </a>      
      <p style="text-align: center; font-size: 1.2em; line-height: 1.5em;"><?php echo TEXT4; ?></p>
    </td>
  </tr>
</table>
</form>