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
  $page_file = 'upgrade.php';

  if (isset($_GET['step'])) {
    $step = $_GET['step'] ;
  }elseif (isset($_POST['step'])){
    $step = $_POST['step'] ;
  } else {
    $step = '';
  }

  switch ($step) {

    case '1':
      $page_contents = 'upgrade_1.php';
      $step_dis = 1;
      break;
      
    case '2':
      if (isset($_POST['cre_path'])) {
        $page_contents = 'upgrade_2.php';
        $step_dis = 2;
      } else {
        $page_contents = 'upgrade.php';
        $step_dis = 0;
      }
      break;
      
    case '3':
      if (isset($_POST['cre_path'])) {
        $page_contents = 'upgrade_3.php';
        $step_dis = 3;
      } else {
        $page_contents = 'upgrade.php';
        $step_dis = 0;
      }
      break;
      
    case '4':
      if (isset($_POST['cre_path'])) {
        $page_contents = 'upgrade_4.php';
        $step_dis = 4;
      } else {
        $page_contents = 'upgrade.php';
        $step_dis = 0;
      }
      break;
      
    case '5':
      if (isset($_POST['cre_path'])) {
        $page_contents = 'upgrade_7.php';
        $step_dis = 7;
      } else {
        $page_contents = 'upgrade.php';
        $step_dis = 0;
      }
      break;
      
    default:
      $page_contents = 'upgrade.php';
      $step_dis = 0;
  }

  if ($step == 3) {
    require('templates/main_update_2.php');
  } else {
    require('templates/main_update.php');
  }
?>
