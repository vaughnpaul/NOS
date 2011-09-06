<?php
/*
    Chain Reaction Works, Inc
    Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.


  Released under the GNU General Public License
*/

require('includes/languages/' . $language . '/header.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta name="ROBOTS" content="NOFOLLOW">
  <title>CRE Loaded Installation Wizard</title>
  <script language="javascript" src="script.js" type="text/javascript"></script>
  <link href="stylesheet.css" rel="stylesheet" type="text/css">
  <!--[if lt IE 7]>
  <link href="stylesheet-ie.css" rel="stylesheet" type="text/css" />
  <![endif]-->
</head>

<body>
  <div id="container">

<!-- header start //-->
    <table id="header-container" border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td valign="middle"  align="left">
          <div><a id="logo" href="http://www.creloaded.com/" target="_blank"></a></div>
        </td>
         <td>
    <div align="right">    <?php echo TEXT_SELECT_LANG ;
              echo osc_draw_form('languages', basename($_SERVER['PHP_SELF']), '', 'post');
              echo osc_draw_pull_down_menu('language_code', $languages_list1, $languages_code, 'onChange="this.form.submit();"') ;
        ?>
        </form></div>
        </td>
        <td valign="middle" width="250">
       <div align="right">   <div style="font-size: 24px; font-weight: bold; color: #333;"><?php echo TEXT_WELCOME_INSTALL; ?></div>
          <div style="font-size: 12px;"><?php echo VERSION ?></div></div>
        </td>
       
      </tr>
    </table>
    
    <div id="body-container">
      <table width="800" align="center" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>
<!-- header eof //-->