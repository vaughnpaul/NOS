<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

   Released under the GNU General Public License
*/
require('includes/languages/' . $language . '/upgrade_3.php');
$pci = (isset($_GET['pci']) && $_GET['pci'] == '1') ? true : false;
$form_params = ($pci == true) ? '?pci=1' : '';

if ($pci == true) {
  ?>
  <script type="text/javascript">
    var processes = [             
                     ['_copy', '<?php echo TEXT_UPGRADE_6M; ?>', 0],
                     ['_restruct', '<?php echo TEXT_UPGRADE_7M; ?>', 0],
                     ['_cust', '<?php echo TEXT_UPGRADE_8M; ?>', 0],
                     ['_pci', '<?php echo TEXT_UPGRADE_AM; ?>', 0],
                     ['_config', '<?php echo TEXT_UPGRADE_9M; ?>', 0],
                     ['_dbupgrade', '<?php echo TEXT_UPGRADE_10M; ?>', 0]  
                    ];
  </script>
  <?php
} else {
  ?>
  <script type="text/javascript">
    var processes = [             
                     ['_copy', '<?php echo TEXT_UPGRADE_6M; ?>', 0],
                     ['_restruct', '<?php echo TEXT_UPGRADE_7M; ?>', 0],
                     ['_cust', '<?php echo TEXT_UPGRADE_8M; ?>', 0],
                     ['_config', '<?php echo TEXT_UPGRADE_9M; ?>', 0],
                     ['_dbupgrade', '<?php echo TEXT_UPGRADE_10M; ?>', 0]  
                    ];
  </script>
  <?php
}
?>
<h1><?php echo TEXT_UPGRADE_1; ?></h1>
<form name="installer" action="upgrade.php" method="post" enctype="multipart/form-data" target="_self">
<p><?php echo TEXT_UPGRADE_2 . ' ' . TEXT_UPGRADE_3; ?></p>
<div class="installation-form-body">
  <div class="convert-label"><?php echo TEXT_UPGRADE_4; ?></div>
  <!-- div id="div_address" class="convert-pending">
    <img id="img_address_tick" class="tick" src="images/tick.png" align="right" />
    <img id="img_address_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    <img id="img_address_cross" class="cross" src="images/cross.png" align="right" />
    <?php echo TEXT_UPGRADE_5; ?>
  </div -->
  <div id="div_copy" class="convert-pending">
    <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    <?php echo TEXT_UPGRADE_6; ?>
  </div>
  <div id="div_restruct" class="convert-pending">
    <img id="img_restruct_tick" class="tick" src="images/tick.png" align="right" />
    <img id="img_restruct_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    <img id="img_restruct_cross" class="cross" src="images/cross.png" align="right" />
    <?php echo TEXT_UPGRADE_7; ?>
  </div>
  <div id="div_cust" class="convert-pending">
    <img id="img_cust_tick" class="tick" src="images/tick.png" align="right" />
    <img id="img_cust_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    <img id="img_cust_cross" class="cross" src="images/cross.png" align="right" />
    <?php echo TEXT_UPGRADE_8; ?>
  </div>
  <?php
  if ($pci == true) {
    ?>  
    <div id="div_pci" class="convert-pending">
      <img id="img_pci_tick" class="tick" src="images/tick.png" align="right" />
      <img id="img_pci_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
      <img id="img_pci_cross" class="cross" src="images/cross.png" align="right" />
      <?php echo TEXT_UPGRADE_A; ?>
    </div>
    <?php
  }
  ?>
  <div id="div_config" class="convert-pending">
    <img id="img_config_tick" class="tick" src="images/tick.png" align="right" />
    <img id="img_config_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    <img id="img_config_cross" class="cross" src="images/cross.png" align="right" />
    <?php echo TEXT_UPGRADE_9; ?>
  </div>
  <div id="div_dbupgrade" class="convert-pending">
    <img id="img_restruct_tick" class="tick" src="images/tick.png" align="right" />
    <img id="img_restruct_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    <img id="img_restruct_cross" class="cross" src="images/cross.png" align="right" />
    <?php echo TEXT_UPGRADE_10; ?>
  </div>
</div>
<div class="installation-form-foot">&nbsp;</div>
<p id="process_msg" style="margin: 0 auto; margin-bottom: 10px; width: 380px; text-align: center; display: none;"><?php echo TEXT_UPGRADE_PROCESS_COMPLETE; ?></p>
<div style="text-align: center; height: 30px;">
  <a href="javascript:void(0)" id="button-back" class="installation-button-disabled" onclick="if (this.className == 'installation-button-disabled') return false; document.installer.action = 'upgrade.php?step=2'; document.installer.submit(); return false;"><span id="button_left_back" class="installation-button-left installation-button-left-disabled">&nbsp;</span><span id="button_middle_back" class="installation-button-middle installation-button-middle-disabled"><?php echo BUTTON_NEW_BACK; ?></span><span id="button_right_back" class="installation-button-right installation-button-right-disabled">&nbsp;</span></a>
  <a href="javascript:void(0)" id="button-continue" class="installation-button-disabled" onclick="if (this.className == 'installation-button-disabled') return false; document.installer.action = 'upgrade.php?step=4'; document.installer.submit(); return false;"><span id="button_left_continue" class="installation-button-left installation-button-left-disabled">&nbsp;</span><span id="button_middle_continue" class="installation-button-middle installation-button-middle-disabled"><?php echo BUTTON_NEW_CONTINUE; ?></span><span id="button_right_continue" class="installation-button-right installation-button-right-disabled">&nbsp;</span></a>
</div>
<?php
  if ($pci == true) {
    $ccpurge = (isset($_POST['cc_purge'])) ? $_POST['cc_purge'] : '1';
    echo osc_draw_hidden_field('cc_purge', $ccpurge); 
  }
  reset($_POST);
  while (list($key, $value) = each($_POST)) {
    if ($key != 'function_call') {
      if (is_array($value)) {
        for ($i=0; $i < sizeof($value); ++$i) {
          echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
        }
      } else {
        echo osc_draw_hidden_field($key, $value) . "\n";
      }
    }
  }
  echo osc_draw_hidden_field('function_call', '');
?>
</form>