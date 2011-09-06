<?php
/*
  $Id: changesxxxx.php,v 1.1.1.1 2004/03/04 23:41:14 ccwjr Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2005 creloaded

  Released under the GNU General Public License
  This file checks for file permission and end of install
  
*/
//formate  files name/ directory name, permission

function osc_pre_check() {
// file to be checked placed in an array
$file = array(array(file_name = includes/configure.php), array(perm = '777') );
        array(array(file_name = admin/includes/configure.php, array( perm = '777') ); 

osc_pre_check($file);
$return()
}

//check folders status
function osc_post_check() {
filename =>'images' , perm =>'777 , sub=> 'true' ;
filename =>'images' , perm =>'777 , sub=> 'true' ;


foreach (array('cache'=>'777 read/write/execute', 'images'=>'777 read/write/execute (INCLUDE SUBDIRECTORIES TOO)', 'includes/languages/english/html_includes'=>'777 read/write (INCLUDE SUBDIRECTORIES TOO)', 'pub'=>'777 read/write/execute', 'admin/backups'=>'777 read/write', 'admin/images/graphs'=>'777 read/write/execute') as $folder=>$chmod) {
   $folder_status[]=array('folder'=>$folder, 'writable'=>(@is_writable('../'.$folder))?OK:UNWRITABLE, 'class'=> (@is_writable('../'.$folder))?'OK':'WARN', 'chmod'=>$chmod);
}
function osc_pre_check($osc_check_file) {
      foreach ($x as $osc_file_name for $osc_check_file);
      
      if (!is_writeable($osc_file_name)) {
        $this->setError($osc_file_name, 01, true);
      } else {
      $this->setError($osc_file_name, 01, false);
      }
       $return();
      }

function osc_check() {
foreach (array('cache'=>'777 read/write/execute', 'images'=>'777 read/write/execute (INCLUDE SUBDIRECTORIES TOO)', 'includes/languages/english/html_includes'=>'777 read/write (INCLUDE SUBDIRECTORIES TOO)', 'pub'=>'777 read/write/execute', 'admin/backups'=>'777 read/write', 'admin/images/graphs'=>'777 read/write/execute') as $folder=>$chmod) {
   $folder_status[]=array('folder'=>$folder, 'writable'=>(@is_writable('../'.$folder))?OK:UNWRITABLE, 'class'=> (@is_writable('../'.$folder))?'OK':'WARN', 'chmod'=>$chmod);
}



?>