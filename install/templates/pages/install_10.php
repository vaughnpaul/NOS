<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce
     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

  Released under the GNU General Public License
*/
require('includes/languages/' . $language . '/install_10.php');

?>
<p>&nbsp;</p>
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="formPage">
  <tr>
    <td align="center"><script language='JavaScript' type='text/javascript' src='https://adserver.authsecure.com/adx.js'></script>
      <script language='JavaScript' type='text/javascript'>
<!--
   if (!document.phpAds_used) document.phpAds_used = ',';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
   
   document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
   document.write ("https://adserver.authsecure.com/adjs.php?n=" + phpAds_random);
   document.write ("&amp;what=zone:41");
   document.write ("&amp;exclude=" + document.phpAds_used);
   if (document.referrer)
      document.write ("&amp;referer=" + escape(document.referrer));
   document.write ("'><" + "/script>");
//-->
      </script>
      <noscript>
        <a href='https://adserver.authsecure.com/adclick.php?n=afffd05a' target='_blank'><img src='https://adserver.authsecure.com/adview.php?what=zone:41&amp;n=afffd05a' border='0' alt=''></a>
      </noscript>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<?php
echo osc_draw_form('skipCrem', basename($_SERVER['PHP_SELF']), 'step=11', 'post');
    while (list($key, $value) = each($_POST)) {
      if ($key != 'x' && $key != 'y') {
        if (is_array($value)) {
          for ($i=0; $i<sizeof($value); $i++) {
            echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
          }
        } else {
          echo osc_draw_hidden_field($key, $value) . "\n";
        }
      }
    }
echo osc_draw_hidden_field('skip','true') . "\n";
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="main" align="center"><input name="Submit" type="button" class="button" value="   <?php echo TEXT_GET_SUPPORT;?>   " onclick="javascript:void(history.go(-1))">
      <br>
      <br>
      <a href="javascript:void(document.skipCrem.submit())"><?php echo NO_THANKS;?></a></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>