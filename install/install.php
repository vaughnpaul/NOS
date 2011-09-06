<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.


   Released under the GNU General Public License

*/

  require('includes/application.php');

  $page_title = TITLE;
  $page_file = 'install.php';

    if (isset($_GET['step'])) {
    $step = $_GET['step'] ;
    }else if (isset($_POST['step'])){
    $step = $_POST['step'] ;
    } else {
    $step = '' ;
   }



  switch ($step) {

    case '3':
      if ((osc_in_array('database_1', $_POST['install'])) || (osc_in_array('database_2', $_POST['install'])) ) {
        $page_contents = 'install_3.php';
        $step_dis = '3';
      } elseif (osc_in_array('configure', $_POST['install'])) {
        $page_contents = 'install_5.php';
        $step_dis = '5';
      } else {
        $page_contents = 'install.php';
        $step_dis = '1';
      }
      break;
      
    case '4':
      if ((osc_in_array('database_1', $_POST['install'])) || (osc_in_array('database_2', $_POST['install'])) ) {
        $page_contents = 'install_4.php';
        $step_dis = '4';
      } else {
        $page_contents = 'install.php';
        $step_dis = '1';
      }
      break;
      
    case '5':
      if (osc_in_array('configure', $_POST['install'])) {
        $page_contents = 'install_5.php';
        $step_dis = '5';
      } else {
        $page_contents = 'install.php';
        $step_dis = '1';
      }
      break;
      
    case '8':
      if (osc_in_array('configure', $_POST['install'])) {
        $page_contents = 'install_8.php';
        $step_dis = '8';
      } else {
        $page_contents = 'install_8.php';
        $step_dis = '8';
      }
      break;
      
    case '9':
      if (osc_in_array('configure', $_POST['install'])) {
        $page_contents = 'install_11.php';
        $step_dis = '11';
      } else {
        $page_contents = 'install.php';
        $step_dis = '1';
      }
      break;
      
    default:
      $page_contents = 'install.php';
      $step_dis = '1';
  }

  require('templates/main_page.php');
?>
