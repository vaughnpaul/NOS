<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

   Released under the GNU General Public License
*/


function get_php_setting($val) {
    $r =  (ini_get($val) == '1' ? 1 : 0);
    return $r ? 'ON' : 'OFF';
}

function writableFolder( $folder ) {
global $filewritable ;
    echo '<tr>';
    echo '<td class="item">' . $folder . '/</td>';
    echo '<td align="left">';
    echo is_writable( "../$folder" ) ? '<b><font color="green">Writeable</font></b>' : '<b><font color="red">Unwriteable</font></b>' . '</td>';
    echo '</tr>';

     if (is_writable( "../$folder" )){
         $filewritable = 'true';
         }else{
         $filewritable = 'false';
         return $filewritable ;
         }
}
function writableFile( $file ) {
    echo '<tr>';
    echo '<td class="item">' . $file . '</td>';
    echo '<td align="left">';
    echo is_writable( "../$file" ) ? '<b><font color="green">Writeable</font></b>' : '<b><font color="red">Unwriteable</font></b>' . '</td>';
    echo '</tr>';

    if (is_writable( "../$file" )){
 $filewritable = 'true';
 return $filewritable ;
 }
}
?>