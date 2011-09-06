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
  <script type="text/javascript" src="includes/javascript/prototype.js"></script>
  <script type="text/javascript" src="includes/javascript/JsHttpRequest-form.js"></script>
  <script type="text/javascript" src="includes/javascript/JsHttpRequest-prototype.js"></script>
  <script type="text/javascript" src="includes/db_access.js"></script>
</head>

<body onload="db_process();">
  <div id="container">

<!-- header start //-->
    <table id="header-container" border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td valign="middle" align="left">
          <div><a id="logo" href="http://www.creloaded.com/" target="_blank"></a></div>
        </td>
        <td valign="middle">
          <div align="right"><div style="font-size: 24px; font-weight: bold; color: #333;"><?php echo TEXT_WELCOME_INSTALL; ?></div>
          <div style="font-size: 12px;"><?php echo TEXT_WIZARD_TYPE; ?></div></div>
        </td>
      </tr>
    </table>
    
    <div id="body-container">
      <table width="800" align="center" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <?php
                  if ($step_dis < 0) echo '<td class="progress-button progress-button-gray-first">';
                  elseif ($step_dis == 0) echo '<td class="progress-button progress-button-active-first">';
                  else echo '<td class="progress-button progress-button-blue-first">';
                  echo TEXT_UPGRADE_STEP_1;
                  echo '</td>';
  
                  if ($step_dis < 1) echo '<td class="progress-button progress-button-gray">';
                  elseif ($step_dis == 1 || $step_dis == 2 || $step_dis == 3) echo '<td class="progress-button progress-button-active">';
                  else echo '<td class="progress-button progress-button-blue">';
                  echo TEXT_UPGRADE_STEP_2;
                  echo '</td>';
  
                  if ($step_dis < 4) echo '<td class="progress-button progress-button-gray">';
                  elseif ($step_dis == 4) echo '<td class="progress-button progress-button-active">';
                  else echo '<td class="progress-button progress-button-blue">';
                  echo TEXT_UPGRADE_STEP_3;
                  echo '</td>';
  
                  if ($step_dis < 5) echo '<td class="progress-button progress-button-gray-last">';
                  elseif ($step_dis == 5 || $step_dis == 5) echo '<td class="progress-button progress-button-active-last">';
                  else echo '<td class="progress-button progress-button-blue-last">';
                  echo TEXT_UPGRADE_STEP_4;
                  echo '</td>';
                ?>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
<!-- header eof //-->