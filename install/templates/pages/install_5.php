<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

   Released under the GNU General Public License
*/
require('includes/languages/' . $language . '/install_5.php');

  $cookie_path = substr(dirname(getenv('SCRIPT_NAME')), 0, -7);

  $www_location = 'http://' . getenv('HTTP_HOST') . getenv('SCRIPT_NAME');
  $www_location = substr($www_location, 0, strpos($www_location, 'install'));

  $script_filename = getenv('SCRIPT_FILENAME');

  $script_filename = str_replace('\\', '/', $script_filename);
  $script_filename = str_replace('//', '/', $script_filename);

  $dir_fs_www_root_array = explode('/', dirname($script_filename));
  $dir_fs_www_root = array();
  for ($i=0, $n=sizeof($dir_fs_www_root_array)-1; $i<$n; $i++) {
    $dir_fs_www_root[] = $dir_fs_www_root_array[$i];
  }
  $dir_fs_www_root = implode('/', $dir_fs_www_root) . '/';
  $https_www_address = str_replace('http://', 'https://', $www_location);
?>

<script type="text/javascript">
function changeSSL (checked) {
  if (checked) {
  document.getElementById('ssl-address').disabled = false;
  document.getElementById('ssl-domain').disabled = false;
  document.getElementById('ssl-path').disabled = false;
  } else {
  document.getElementById('ssl-address').disabled = true;
  document.getElementById('ssl-domain').disabled = true;
  document.getElementById('ssl-path').disabled = true;
}
}
</script>

<h1><?php echo TEXT_INSTALL_1 ;?></h1>
<form name="install" action="install.php" method="post">
  <input type="hidden" name="step" value="8" />
<p><?php echo TEXT_INSTALL_2 ;?></p>

<div class="installation-form-body-wide">
<table align="center">
  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_3 ;?></td><td><?php echo osc_draw_input_field('HTTP_WWW_ADDRESS', $www_location, 'text', 'class="string long"'); ?></td>
  </tr>
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_4 ;?></td>
  </tr>

  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_6 ;?></td><td><?php echo osc_draw_input_field('DIR_FS_DOCUMENT_ROOT', $dir_fs_www_root, 'text', 'class="string long"'); ?></td>
  </tr>
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_7 ;?></td>
  </tr>

  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_9 ;?></td><td><?php echo osc_draw_input_field('HTTP_COOKIE_DOMAIN', getenv('HTTP_HOST'), 'text', 'class="string"'); ?></td>
  </tr>
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_14 ;?></td>
  </tr>

  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_16 ;?></td><td><?php echo osc_draw_input_field('HTTP_COOKIE_PATH', $cookie_path, 'text', 'class="string"'); ?></td>
  </tr>
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_10 ;?></td>
  </tr>

  <tr>
    <td></td><td><?php echo osc_draw_checkbox_field('ENABLE_SSL', 'true', true, 'onclick="changeSSL(this.checked)"'); ?> <?php echo TEXT_INSTALL_12 ;?></td>
  </tr>
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_17 ;?></td>
  </tr>
</table>

<h3 style="margin: 10px;">SSL Settings</h3>
<div style="border-bottom: solid 1px #aaa; margin: 10px;"></div>

<table align="center">

  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_2s ;?></td><td><?php echo osc_draw_input_field('HTTPS_WWW_ADDRESS', $https_www_address, 'text', 'id="ssl-address" class="string"'); ?></td>
  </tr>
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_3s ;?></td>
  </tr>

  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_5s ;?></td><td><?php echo osc_draw_input_field('HTTPS_COOKIE_DOMAIN', getenv('HTTP_HOST'), 'text', 'id="ssl-domain" class="string"'); ?></td>
  </tr>
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_6s ;?></td>
  </tr>

  <tr>
    <td class="installation-form-label"><?php echo TEXT_INSTALL_8s ;?></td><td><?php echo osc_draw_input_field('HTTPS_COOKIE_PATH', $cookie_path, 'text', 'id="ssl-path" class="string"'); ?></td>
  </tr>
  <tr>
    <td></td><td class="info"><?php echo TEXT_INSTALL_9s ;?></td>
  </tr>

<?php
  reset($_POST);
  while (list($key, $value) = each($_POST)) {
    if (($key != 'step') && ($key != 'x') && ($key != 'y')) {
      if (is_array($value)) {
        for ($i=0; $i<sizeof($value); $i++) {
          echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
        }
      } else {
        echo osc_draw_hidden_field($key, $value) . "\n";
      }
    }
  }
  
?>


</table>
  <p style="text-align: center;">
    <a href="javascript:void(0)" onclick="document.install.step.value = 3; document.install.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_BACK ;?></span><span class="installation-button-right">&nbsp;</span></a>
    <a href="javascript:void(0)" onclick="document.install.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CONTINUE ;?></span><span class="installation-button-right">&nbsp;</span></a></p>

</div>
<div class="installation-form-foot-wide">&nbsp;</div>
</form>
