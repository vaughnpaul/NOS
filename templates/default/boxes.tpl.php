<?php
/*
  $Id: boxes.tpl.php,v 1.6

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class tableBox {
      var  $table_border, $table_width, $table_cellspacing, $table_cellpadding, $table_parameters;
      var  $table_row_parameters, $table_data_parameters;
      var $border_left, $border_right, $css_suffix;
      
      // class constructor
      function tableBox($contents, $direct_output = false, $border_left = '', $border_right = '', $css_suffix = '') {
          $this->table_border = TEMPLATE_TABLE_BORDER;
          $this->table_width = TEMPLATE_TABLE_WIDTH;
          $this->table_cellspacing = TEMPLATE_TABLE_CELLSPACING;
          //$this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
          //$this->table_parameters = TEMPLATE_TABLE_PARAMETERS;
          $this->table_row_parameters = TEMPLATE_TABLE_ROW_PARAMETERS;
          $this->table_data_parameters = TEMPLATE_TABLE_DATA_PARAMETERS;
          $this->table_content_cellpadding = TEMPLATE_TABLE_CONTENT_CELLPADING;
    
      $tableBox_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  <tr';
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
            if (TEMPLATE_INFOBOX_BORDER_IMAGE_LEFT !== '' && TEMPLATE_INFOBOX_BORDER_LEFT == 'true' && $border_left == 'true') {
                $tableBox_string .= '<td align="left"  width="'.SIDE_BOX_LEFT_WIDTH.'" style="background-image: url(' . DIR_WS_TEMPLATE_IMAGES . 'infobox/' . TEMPLATE_INFOBOX_BORDER_IMAGE_LEFT .');background-repeat: repeat-y;">' . tep_image_infobox(TEMPLATE_INFOBOX_BORDER_IMAGE_LEFT, TEMPLATE_INFOBOX_BORDER_IMAGE_LEFT, SIDE_BOX_LEFT_WIDTH) . '</td>';
            } else if (TEMPLATE_INFOBOX_BORDER_IMAGE_LEFT == '' && TEMPLATE_INFOBOX_BORDER_LEFT == 'true'  && $border_left == 'true') {
                $tableBox_string .= '<td class="BoxBorderLeft">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td>';
            }
        $tableBox_string .= '<td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>';
          if (TEMPLATE_INFOBOX_BORDER_IMAGE_RIGHT !== '' && TEMPLATE_INFOBOX_BORDER_RIGHT == 'true' && $border_right == 'true') {
              $tableBox_string .= '<td width="'.SIDE_BOX_RIGHT_WIDTH.'" style="background-image: url(' . DIR_WS_TEMPLATE_IMAGES . 'infobox/' . TEMPLATE_INFOBOX_BORDER_IMAGE_RIGHT .');background-repeat: repeat-y;">' . tep_image_infobox(TEMPLATE_INFOBOX_BORDER_IMAGE_RIGHT, TEMPLATE_INFOBOX_BORDER_IMAGE_LEFT, SIDE_BOX_RIGHT_WIDTH) . '</td>' . "\n";
          } else if (TEMPLATE_INFOBOX_BORDER_IMAGE_RIGHT == '' && TEMPLATE_INFOBOX_BORDER_RIGHT == 'true' && $border_right == 'true') {
              $tableBox_string .= '<td class="BoxBorderRight">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td>';
          }
        }

        $tableBox_string .= '  </tr>' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }

  class infoBox extends tableBox {
    function infoBox($contents, $border_left = 'false', $border_right = 'false', $css_suffix = '') {
    //setting defined in template.php
        $this->table_border = TEMPLATE_TABLE_BORDER;
        $this->table_width = TEMPLATE_TABLE_WIDTH;
        $this->table_cellspacing = TEMPLATE_TABLE_CELLSPACING;
        $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
        $this->table_parameters = TEMPLATE_TABLE_PARAMETERS;
        $this->table_data_parameters = TEMPLATE_TABLE_DATA_PARAMETERS;
    
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents, $css_suffix));
      $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
      $this->table_parameters = 'class="' . $css_suffix . 'infoBox"';
      $this->tableBox($info_box_contents, true, $border_left, $border_right, $css_suffix);
    }

    function infoBoxContents($contents, $css_suffix = '') {
      $this->table_cellpadding = TEMPLATE_TABLE_CONTENT_CELLPADING;
      $this->table_parameters = 'class="' . $css_suffix . 'infoBoxContents"';
      $info_box_contents = array();
      $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText" ' . (isset($contents[$i]['params']) ? $contents[$i]['params'] : ''),
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }
      $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      return $this->tableBox($info_box_contents);
    }
  }

  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents, $right_link = '', $css_suffix = '') {
       $this->table_width = TEMPLATE_TABLE_WIDTH;
       $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
       $this->table_parameters = 'class="' . $css_suffix . 'infoBoxHeading"';
       
       if (TEMPLATE_INFOBOX_TOP_LEFT == 'true' && TEMPLATE_INFOBOX_IMAGE_TOP_LEFT !='') {
           $left_corner = tep_image_infobox(TEMPLATE_INFOBOX_IMAGE_TOP_LEFT);
       } else {
           $left_corner = '&nbsp;';
       }
       
       if (TEMPLATE_INFOBOX_TOP_RIGHT == 'true' && TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT != '') {
           if ($right_link != '') {
               $right_arrow = '<a href="' . $right_link . '">' . tep_image_infobox(TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT_ARROW, ICON_ARROW_RIGHT) . '</a>';
           } else {
               $right_arrow = tep_image_infobox(TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT);
           }
      } else {
          $right_arrow = '&nbsp;';
      } 
 
      $center_arrow = '';
      if(tep_not_null($right_link) && TEMPLATE_INFOBOX_TOP_RIGHT != 'true' ){
          $center_arrow =' &nbsp; <a href="' . $right_link . '" title="more info">&nbsp; &raquo; &nbsp;</a>';
      }
 
      $info_box_contents = array();
      $cell = '';
      if (TEMPLATE_INFOBOX_TOP_LEFT == 'true') {
      $cell[] = array('params' => 'class="' . $css_suffix . 'infoBoxHeadingLeft"',
                                  'text' => $left_corner);
      }
      $cell[] = array('params' => 'class="' . $css_suffix . 'infoBoxHeadingCenter"',
                                  'text' => $contents[0]['text'] . $center_arrow );
      if (TEMPLATE_INFOBOX_TOP_RIGHT == 'true') {
      $cell[] = array('params' => 'class="' . $css_suffix . 'infoBoxHeadingRight"',
                                  'text' => $right_arrow);
      }
      $info_box_contents[] = $cell;
      $this->tableBox($info_box_contents, true);
    }
  }

  class infoboxFooter extends tableBox {
    function infoboxFooter($contents, $css_suffix = '') {
         $this->table_width = TEMPLATE_TABLE_WIDTH;
         $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
         $this->table_cellpadding = '0';
         $this->table_parameters = 'class="' . $css_suffix . 'infoBoxFooter"';
         
         if (TEMPLATE_BOX_IMAGE_FOOTER_LEFT == 'true' && TEMPLATE_INFOBOX_IMAGE_FOOTER_LEFT != '') {
             $left_corner = tep_image_infobox(TEMPLATE_INFOBOX_IMAGE_FOOTER_LEFT);
         } else {
             $left_corner = '&nbsp;';
         }
         
         if (TEMPLATE_BOX_IMAGE_FOOTER_RIGHT == 'true' && TEMPLATE_INFOBOX_IMAGE_FOOTER_RIGHT != '') {
             $right_corner = tep_image_infobox(TEMPLATE_INFOBOX_IMAGE_FOOTER_RIGHT);
         } else {
             $right_corner = '&nbsp;';
         }
 
      $info_box_contents = array();
      $cell = '';
      if (TEMPLATE_BOX_IMAGE_FOOTER_LEFT == 'true') {
      $cell[] = array('params' => 'class="' . $css_suffix . 'infoBoxFooterLeft"',
                                         'text' => $left_corner);
      }
      $cell[] = array('params' => 'class="' . $css_suffix . 'infoBoxFooterCenter"',
                                         'text' => $contents[0]['text'] );
      if (TEMPLATE_BOX_IMAGE_FOOTER_RIGHT == 'true') {
      $cell[] = array('params' => 'class="' . $css_suffix . 'infoBoxFooterRight"',
                                         'text' => $right_corner);
      }
      $info_box_contents[] = $cell;
      $this->tableBox($info_box_contents, true);
    }
  }

  class contentBox extends tableBox {
    function contentBox($contents, $border_left = 'false', $border_right = 'false', $css_suffix = '') {
            $this->table_border = TEMPLATE_TABLE_BORDER;
            $this->table_width = TEMPLATE_TABLE_WIDTH;
            $this->table_cellspacing = TEMPLATE_TABLE_CELLSPACING;
            $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
            $this->table_parameters = TEMPLATE_TABLE_PARAMETERS;
            $this->table_data_parameters = TEMPLATE_TABLE_DATA_PARAMETERS;
            
            $info_box_contents = array();
            $info_box_contents[] = array('text' => $this->contentBoxContents($contents));
            $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
            $this->table_parameters = 'class="' . $css_suffix . 'contentBox"';
            $this->tableBox($info_box_contents, true, $border_left, $border_right, $css_suffix);
    }

    function contentBoxContents($contents, $css_suffix = '') {
      $this->table_cellpadding = TEMPLATE_TABLE_CENTER_CONTENT_CELLPADING;
      $this->table_parameters = 'class="contentBoxContents' . $css_suffix . '"';
      return $this->tableBox($contents);
    }
  }

  class contentBoxHeading extends tableBox {
    function contentBoxHeading($contents, $right_link='', $css_suffix = '') {
      $this->table_width = TEMPLATE_CONTENT_TABLE_WIDTH;
      $this->table_cellpadding = TEMPLATE_CONTENT_TABLE_CELLPADDIING;
      $this->table_cellspacing = TEMPLATE_CONTENT_TABLE_CELLSPACING;
      $this->table_parameters = 'class="contentBoxHeading' . $css_suffix . '"';
       if (TEMPLATE_CONTENTBOX_TOP_LEFT == 'true') {
           $left_corner = tep_image_infobox(TEMPLATE_CONTENTBOX_IMAGE_TOP_LEFT);
       } else {
           $left_corner = '';
       }
       
       if (TEMPLATE_CONTENTBOX_TOP_RIGHT == 'true') {
           if ($right_link != '') {
               $right_arrow = '<a href="' . $right_link . '">' . tep_image_infobox(TEMPLATE_CONTENTBOX_IMAGE_TOP_RIGHT_ARROW, ICON_ARROW_RIGHT) . '</a>';
           } else {
               $right_arrow = tep_image_infobox(TEMPLATE_CONTENTBOX_IMAGE_TOP_RIGHT);
           }
      } else {
          $right_arrow = '';
      } 
 
      $center_arrow = '';
      if(tep_not_null($right_link) && TEMPLATE_CONTENTBOX_TOP_RIGHT != 'true'){
          $center_arrow =' &nbsp; <a href="' . $right_link . '" title="more info">&nbsp; &raquo; &nbsp;</a>';
      }

      $info_box_contents = array();        
      $cell = '';
      if (TEMPLATE_CONTENTBOX_TOP_RIGHT == 'true') {
       $cell[] = array('params' => 'class="contentBoxHeadingLeft' . $css_suffix . '"',
                                         'text' => $left_corner);
      } 
      $cell[] = array('params' => 'width="100%" class="contentBoxHeadingCenter' . $css_suffix . '"',
                                         'text' => $contents[0]['text'] . $center_arrow);
      if (TEMPLATE_CONTENTBOX_TOP_RIGHT == 'true') {
      $cell[] = array('params' => 'class="contentBoxHeadingRight' . $css_suffix . '"',
                                         'text' => $right_arrow);
      }    
      $info_box_contents[] = $cell;
      $this->tableBox($info_box_contents, true);
    }
  }

  class contentBoxFooter extends tableBox {
  
    function contentBoxFooter($contents, $css_suffix = '') {
        $this->table_width = TEMPLATE_CONTENT_TABLE_WIDTH;
        $this->table_cellpadding = TEMPLATE_CONTENT_TABLE_CELLPADDIING;
        $this->table_cellspacing = TEMPLATE_CONTENT_TABLE_CELLSPACING;
        $this->table_parameters = 'class="contentBoxFooter' . $css_suffix . '"';
         
         if (TEMPLATE_CONTENTBOX_FOOTER_LEFT == 'true') {
             $left_corner = tep_image_infobox(TEMPLATE_CONTENTBOX_IMAGE_FOOT_LEFT);
         } else {
             $left_corner = '';
         }
         
         if (TEMPLATE_CONTENTBOX_FOOTER_RIGHT == 'true') {
             $right_corner = tep_image_infobox(TEMPLATE_CONTENTBOX_IMAGE_FOOT_RIGHT);
         } else {
             $right_corner = '';
         }
 
      $info_box_contents = array();
      $cell = '';
      if (TEMPLATE_CONTENTBOX_FOOTER_LEFT == 'true') {
      $cell[] = array('params' => 'class="contentBoxFooterLeft' . $css_suffix . '"',
                                         'text' => $left_corner);
      }
      $cell[] = array('params' => 'width="100%" class="contentBoxFooterCenter' . $css_suffix . '"',
                                         'text' => $contents[0]['text'] );
      if (TEMPLATE_CONTENTBOX_FOOTER_RIGHT == 'true') {
      $cell[] = array('params' => 'class="contentBoxFooterRight' . $css_suffix . '"',
                                         'text' => $right_corner);
      }
      $info_box_contents[] = $cell;
      $this->tableBox($info_box_contents, true);
    }
  }
  
 /* Cstomize store popus */
   class popupBox extends tableBox {
    function popupBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->popupBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="popupBox"';
      $this->tableBox($info_box_contents, true);
    }

    function popupBoxContents($contents) {
      $this->table_cellpadding = '4';
      $this->table_parameters = 'class="popupBoxContents"';
      return $this->tableBox($contents);
    }
  }

  class popupBoxHeading extends tableBox {
    function popupBoxHeading($contents) {
      $this->table_width = '100%';
      $this->table_cellpadding = '0';
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'class="popupBoxHeading"', 'text' => $contents[0]['text']));
      $this->tableBox($info_box_contents, true);
    }
  }
  
   class popupBoxFooter extends tableBox {
    function popupBoxFooter($contents) {
      $this->table_width = '100%';
      $this->table_cellpadding = '0';
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'class="popupBoxFooter"', 'text' => $contents[0]['text']));
      $this->tableBox($info_box_contents, true);
    }
  }
  
  /* popup boxes eof */


  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents, true);
    }
  }

  class productListingBox extends tableBox {
    function productListingBox($contents) {
      $this->table_parameters = 'class="productListing"';
      $this->tableBox($contents, true);
    }
  }
?>