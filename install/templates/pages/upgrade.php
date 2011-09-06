<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

   Released under the GNU General Public License
*/

require('includes/languages/' . $language . '/upgrade.php');
include('includes/functions/file_check.php');
  $script_filename = getenv('SCRIPT_FILENAME');

  $script_filename = str_replace('\\', '/', $script_filename);
  $script_filename = str_replace('//', '/', $script_filename);

  $dir_fs_www_root_array = explode('/', dirname($script_filename));
  $dir_fs_www_root = array();
  for ($i=0, $n=sizeof($dir_fs_www_root_array)-1; $i<$n; $i++) {
    $dir_fs_www_root[] = $dir_fs_www_root_array[$i];
  }
  $dir_fs_catalog  = implode('/', $dir_fs_www_root) . '/';
//echo $dir_fs_catalog ;
// intilize variables
$text_error_configure = 'false';
$ok_to_continue = 1;
$config_exsists = 0;
$config_writable = 0;
$config_admin_exsists = 0;
$config_admin_writable  = 0;
$pci = (isset($_GET['pci']) && $_GET['pci'] == '1') ? true : false;
$php_version = '5.2.0';
$form_action = ($pci == true) ? 'upgrade.php?step=1&pci=1' : 'upgrade.php?step=1';
?>

<h1><?php echo TEXT_INSTALL_1; ?></h1>
<form name="installer" action="<?php echo $form_action; ?>" method="post">
<h3 class="installation-form-head">Required Extensions</h3>
<div class="installation-form-body">
  <table border="0" cellpadding="4" cellspacing="0" width="100%" align="center">
        <tr>
          <td><a href="http://www.php.net/downloads.php" target="php">PHP >= <?php echo $php_version; ?></a></td>
          <td align="left" width="70"><?php
            if(phpversion() < $php_version) { $ok_to_continue = 0; }
            echo phpversion() < $php_version ? '<b><font color="red">' . TEXT_CHECK_2 . '</font></b>' : '<b><font color="green">' .TEXT_CHECK_1 .'</font></b>';
          ?>
          </td>
        </tr>
        <tr>
          <td><a href="http://www.php.net/manual/en/ref.mysql.php" target="php">PHP MySQL</a></td>
          <td align="left"><?php
            if(!extension_loaded( 'mysql' )) { $ok_to_continue = 0; }
            echo extension_loaded( 'mysql' ) ? '<b><font color="green">' . TEXT_CHECK_5 .'</font></b>' : '<b><font color="red">' . TEXT_CHECK_6 .'</font></b>';
          ?>
          </td>
        </tr>
        <tr>
          <td><a href="http://www.php.net/manual/en/ref.pcre.php" target="php">PHP PCRE</a></td>
          <td align="left"><?php
            if(!extension_loaded( 'pcre' )) { $ok_to_continue = 0; }
            echo extension_loaded( 'pcre' ) ? '<b><font color="green">' . TEXT_CHECK_5 .'</font></b>' : '<b><font color="red">' . TEXT_CHECK_6 .'</font></b>';
          ?>
          </td>
        </tr>
        <tr>
          <td><a href="http://www.php.net/manual/en/ref.zlib.php" target="php">PHP ZLIB</a></td>
          <td align="left"><?php
            if(!function_exists( 'gzencode' )) { $ok_to_continue = 0; }
            echo function_exists( 'gzencode' ) ? '<b><font color="green">' . TEXT_CHECK_5 .'</font></b>' : '<b><font color="red">' . TEXT_CHECK_6 .'</font></b>';
          ?>
          </td>
        </tr>
        <tr>
          <td><a href="http://www.php.net/manual/en/ref.curl.php" target="php">PHP cURL</a></td>
          <td align="left"><?php
            if(!extension_loaded( 'curl' )) { $ok_to_continue = 0; }
            echo extension_loaded( 'curl' ) ? '<b><font color="green">' . TEXT_CHECK_5 .'</font></b>' : '<b><font color="red">' . TEXT_CHECK_6 .'</font></b>';
          ?>
          </td>
        </tr>
        <tr>
          <td><a href="http://www.php.net/manual/en/ref.image.php" target="php">PHP GD</a></td>
          <td align="left"><?php
            if(!extension_loaded( 'gd' )) { $ok_to_continue = 0; }
            echo extension_loaded( 'gd' ) ? '<b><font color="green">' . TEXT_CHECK_5 .'</font></b>' : '<b><font color="red">' . TEXT_CHECK_6 .'</font></b>';
          ?>
          </td>
        </tr>
        <tr>
          <td><a href="http://www.php.net/manual/en/ref.mcrypt.php" target="php">PHP Mcrypt</a></td>
          <td align="left"><?php
            echo extension_loaded( 'mcrypt' ) ? '<b><font color="green">' . TEXT_CHECK_5 .'</font></b>' : '<b><font color="red">' . TEXT_CHECK_6 .'</font></b>';
          ?>
          </td>
        </tr>
        <tr>
          <td><a href="http://us.php.net/manual/en/ref.iconv.php" target="php">PHP ICONV</a></td>
          <td align="left"><?php
            echo extension_loaded( 'iconv' ) ? '<b><font color="green">' . TEXT_CHECK_5 .'</font></b>' : '<b><font color="red">' . TEXT_CHECK_6 .'</font></b>';
          ?>
          </td>
        </tr>
        <tr>
          <td><a href="http://www.php.net/manual/en/ref.exif.php" target="php">PHP EXIF</a></td>
          <td align="left"><?php
            echo extension_loaded( 'exif' ) ? '<b><font color="green">' . TEXT_CHECK_5 .'</font></b>' : '<b><font color="red">' . TEXT_CHECK_6 .'</font></b>';
          ?>
          </td>
        </tr>
        <tr>
          <td><a href="http://www.php.net/manual/en/ref.ftp.php" target="php">PHP FTP</a></td>
          <td align="left"><?php
            echo extension_loaded( 'ftp' ) ? '<b><font color="green">' . TEXT_CHECK_5 .'</font></b>' : '<b><font color="red">' . TEXT_CHECK_6 .'</font></b>';
          ?>
          </td>
        </tr>
  </table>
</div>
<div class="installation-form-foot">&nbsp;</div>


<h3 class="installation-form-head"><?php echo TEXT_CHECK_17 ;?><!-- Required PHP Settings --></h3>
<div class="installation-form-body">
  <table border="0" cellpadding="4" cellspacing="0" width="100%" align="center">
        <tr>
          <td><strong><?php echo TEXT_CHECK_18 ;?></strong></td>
          <td width="70"><strong><?php echo TEXT_CHECK_20 ;?></strong></td>
        </tr>
        <?php
          $php_required_settings = array(array ('safe_mode','OFF'),
                                         array ('file_uploads','ON'),
                                         array ('session.auto_start','OFF'),
                                         array ('magic_quotes_sybase','OFF'),
                                         array ('magic_quotes_runtime','OFF'),
                                         array ('allow_url_fopen','ON'),
                                        );

          foreach ($php_required_settings as $phprec) {
        ?>
        <tr>
          <td class="item"><?php echo $phprec[0]; ?>:</td>
          <td><?php
            if ( get_php_setting($phprec[0]) == $phprec[1] ) {
          ?>
            <font color="green"><b>
            <?php
            } else {
          ?>
            <font color="red"><b>
            <?php
            }
            echo get_php_setting($phprec[0]);
          ?>
            </b></font> </td>
        </tr>
        <?php
        }
        ?>
  </table>
</div>
<div class="installation-form-foot">&nbsp;</div>


<h3 class="installation-form-head"><?php echo TEXT_CHECK_24 ;?><!--Required Directory and File Permissions--></h3>
<div class="installation-form-body">
 <table border="0" cellpadding="4" cellspacing="0" width="100%" align="center">
      <?php
// need to add check to see if fopen is allowed and chmod
// if not then set test for then to false and output message
            if (@file_exists($dir_fs_catalog . '/includes/configure.php') &&  @is_writable( $dir_fs_catalog . '/includes/configure.php' )){
            } else {
             $text_error_configure = 'true';
            }

            if (@file_exists($dir_fs_catalog . '/admin/includes/configure.php') &&  @is_writable( $dir_fs_catalog . '/admin/includes/configure.php' )){
            } else {
             $text_error_configure = 'true';
            }

        if ($text_error_configure == 'true'){
        // set configure names
       $cat_config = @fopen($dir_fs_catalog . "/includes/configure.php", "wb");
        $admin_config = @fopen($dir_fs_catalog . "/admin/includes/configure.php", "wb");
       // close files after creating empty files
        @fclose($cat_config);
        @fclose($admin_config);

       if (@file_exists($dir_fs_catalog . '/includes/configure.php') &&  @is_writable( $dir_fs_catalog . '/includes/configure.php' )){
         @chmod($dir_fs_catalog . "/includes/configure.php", 0777);
       }
       if (@file_exists($dir_fs_catalog . '/admin/includes/configure.php') &&  @is_writable( $dir_fs_catalog . '/admin/includes/configure.php' )){
         @chmod($dir_fs_catalog . "/admin/includes/configure.php", 0777);
        }
     }
        ;?>
        <tr>
          <td valign="top" class="item">/includes/configure.php</td>
          <td align="left" width="70"><?php
             if (@file_exists($dir_fs_catalog . '/includes/configure.php') ){
               $config_exsists = 1;
              }
             if ( @is_writable( $dir_fs_catalog . '/includes/configure.php' )){
              $config_writable = 1;
             }

            if ( ($config_exsists == 1) &&  ($config_writable == 1) ){
              echo '<b><font color="green">' . TEXT_CHECK_8 . '</font></b>';
            } else if ($config_exsists == 0){
              echo '<b><font color="red">' . TEXT_CHECK_25 .'</b><br /><span class="small"><strong>' . TEXT_CHECK_10 . '</strong></span></font>';
               $text_error_configure = 'true';
               $ok_to_continue = '0';
            } else {
              echo '<b><font color="red">' . TEXT_CHECK_9 .'</b><br /><span class="small"><strong>' . TEXT_CHECK_10 . '</strong></span></font>';
              $text_error_configure = 'true';
              $ok_to_continue = 0;
            }
          ?>
          </td>
        </tr>
        <tr>
          <td valign="top" class="item">/admin/includes/configure.php</td>
          <td align="left"><?php
             if (@file_exists($dir_fs_catalog . '/admin/includes/configure.php') ){
               $config_admin_exsists = 1;
              }
             if ( @is_writable($dir_fs_catalog . '/admin/includes/configure.php' )){
              $config_admin_writable = 1;
             }

            if ( ($config_admin_exsists == 1) &&  ($config_admin_writable == 1) ){
              echo '<b><font color="green">' . TEXT_CHECK_8 . '</font></b>';
            } else if ($config_exsists == 0){
              echo '<b><font color="red">' . TEXT_CHECK_25 .'</b><br /><span class="small"><strong>' . TEXT_CHECK_10 . '</strong></span></font>';
               $text_error_configure = 'true';
               $ok_to_continue = '0';
            } else {
              echo '<b><font color="red">' . TEXT_CHECK_9 .'</b><br /><span class="small"><strong>' . TEXT_CHECK_10 . '</strong></span></font>';
               $text_error_configure = 'true';
               $ok_to_continue = 0;
            }
          ?>
          </td>
        </tr>
        <?php if ($text_error_configure == 'true'){ ?>
        <tr>
          <td colspan="2" style="color:red;"><?php echo TEXT_INSTALL_CONFIG_ERROR ;?></td>
        </tr>
        <?php }
        if(!is_writable( "../admin/backups" )) { $ok_to_continue = 0; }
        writableFolder( 'admin/backups' );
        if(!is_writable( "../admin/images/graphs" )) { $ok_to_continue = 0; }
        writableFolder( 'admin/images/graphs' );
        if(!is_writable( "../cache" )) { $ok_to_continue = 0; }
        writableFolder( 'cache' );
        if(!is_writable( "../images" )) { $ok_to_continue = 0; }
        writableFolder( 'images' );
        if(!is_writable( "../images/banners" )) { $ok_to_continue = 0; }
        writableFolder( 'images/banners' );
        if(!is_writable( "../images/logo" )) { $ok_to_continue = 0; }
        writableFolder( 'images/logo' );
        if(!is_writable( "../images/events_images" )) { $ok_to_continue = 0; }
        writableFolder( 'images/events_images' );
        if(!is_writable( "../includes/header_tags.php" )) { $ok_to_continue = 0; }
        writableFile( 'includes/header_tags.php' );
        if(!is_writable( "../debug" )) { $ok_to_continue = 0; }
        writableFolder( 'debug' );
        //BOF List content of debug and check permissions
        $debugpath = 'debug/';
        if ($debugdir = opendir('../'.$debugpath)) {
          while (false !== ($debugfile = readdir($debugdir))) {
            if ($debugfile != "." && $debugfile != ".." && $debugfile != ".htaccess" && $debugfile != ".svn") {
              echo '<tr>';
              echo '<td class="item">' . $debugpath.$debugfile . '</td>';
              echo '<td align="left">';
              echo is_writable( "../$debugpath$debugfile" ) ? '<b><font color="green">' . TEXT_CHECK_8 . '</font></b>' : '<b><font color="red">' . TEXT_CHECK_9 .'</font></b>' . '</td>';
              echo '</tr>';
              if(!is_writable( "../$debugpath$debugfile" )) { $ok_to_continue = 0; }
            }
          }
          closedir($debugdir);
        }
        //EOF List content of debug and check permissions
        if(!is_writable( "../tmp" )) { $ok_to_continue = 0; }
        writableFolder( 'tmp' );
        if(!is_writable( "../temp" )) { $ok_to_continue = 0; }
        writableFolder( 'temp' );
        
        // BOF List includes/languages/$language/mainpage.php
        $path = "includes/languages/" . $language . "/";
        $file = "mainpage.php";
        echo '<tr>';
        echo '<td class="item">' . $path.$file . '</td>';
        echo '<td align="left">';
        echo is_writable( "../$path$file" ) ? '<b><font color="green">' . TEXT_CHECK_8 . '</font></b>' : '<b><font color="red">' . TEXT_CHECK_9 .'</font></b>' . '</td>';
        echo '</tr>';
        if(!is_writable( "../$path$file" )) { $ok_to_continue = 0; }
        // EOF  includes/languages/$language/mainpage.php
        
        // BOF List includes/languages/$language/header_tags.php
        $path = "includes/languages/" . $language . "/";
        $file = "header_tags.php";
        echo '<tr>';
        echo '<td class="item">' . $path.$file . '</td>';
        echo '<td align="left">';
        echo is_writable( "../$path$file" ) ? '<b><font color="green">' . TEXT_CHECK_8 . '</font></b>' : '<b><font color="red">' . TEXT_CHECK_9 .'</font></b>' . '</td>';
        echo '</tr>';
        if(!is_writable( "../$path$file" )) { $ok_to_continue = 0; }
        // EOF  includes/languages/$language/header_tags.php
?>
  </table>
</div>
<div class="installation-form-foot">&nbsp;</div>

<div style="text-align: center;">
<?php
        reset($_POST);
        while (list($key, $value) = each($_POST)) {
          if (($key != 'x') && ($key != 'y') && ($key != 'DB_SERVER') && ($key != 'DB_SERVER_USERNAME') && ($key != 'DB_SERVER_PASSWORD') && ($key != 'DB_DATABASE') && ($key != 'USE_PCONNECT') && ($key != 'STORE_SESSIONS') && ($key != 'DB_TEST_CONNECTION')) {
            if (is_array($value)) {
              for ($i=0; $i < sizeof($value); $i++) {
                echo osc_draw_hidden_field($key . '[]', $value[$i]) . "\n";
              }
            } else {
              echo osc_draw_hidden_field($key, $value) . "\n";
            }
          }
       }
?>
<p>
<?php 
      if($ok_to_continue == 0) {
?>
      <a href="javascript:void(0)" onclick="window.location.reload()"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_CHECK_AGAIN; ?></span><span class="installation-button-right">&nbsp;</span></a>
<?php
      } else { 
?>
      <a href="javascript:void(0)" onclick="submitForm(this); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_CONTINUE ;?></span><span class="installation-button-right">&nbsp;</span></a>
<?php
      }
?>
</p>
</div>
</form>