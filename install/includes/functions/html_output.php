<?php
/*
  $Id: html_output.php,v 1.1.1.1 2004/03/04 23:41:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
function osc_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = false, $search_engine_safe = false) {
    global $request_type, $session_started, $SID, $spider_flag;

    if (!osc_not_null($page)) {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }

     if (osc_not_null($parameters)) {
     $link = '';
      $link .= $page . '?' . osc_output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

     if (isset($_sid) && !$spider_flag) {
      $link .= $separator . osc_output_string($_sid);
    }

    return $link;
  }

  function osc_draw_form($name, $action, $parameters = '', $method = 'post', $params = '', $connection = 'NONSSL') {
    $form = '<form name="' . osc_output_string($name) . '" action="';

    if (osc_not_null($parameters)) {
      $form .= osc_href_link($action, $parameters, $connection);
    } else {
      $form .= osc_href_link($action, '', $connection);
    }
    $form .= '" method="' . osc_output_string($method) . '"';
    if (osc_not_null($params)) {
      $form .= ' ' . $params;
    }
    $form .= '>';

    return $form;
  }

  function osc_draw_input_field($name, $text = '', $type = 'text', $parameters = '', $reinsert_value = true) {
    $field = '<input type="' . $type . '" name="' . $name . '"';
    if ( ($key = $GLOBALS[$name]) || ($key = $_GET[$name]) || ($key = $_POST[$name]) || ($key = $_SESSION[$name]) && ($reinsert_value) ) {
      $field .= ' value="' . $key . '"';
    } elseif ($text != '') {
      $field .= ' value="' . $text . '"';
    }
    if ($parameters) $field.= ' ' . $parameters;
    $field .= '>';

    return $field;
  }

  function osc_draw_password_field($name, $text = '') {
    return osc_draw_input_field($name, $text, 'password', '', false);
  }

  function osc_draw_hidden_field($name, $value) {
    return '<input type="hidden" name="' . $name . '" value="' . $value . '">';
  }

  function osc_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input type="' . $type . '" name="' . $name . '"';
    if ($value != '') $selection .= ' value="' . $value . '"';
    if ( ($checked == true) || ($GLOBALS[$name] == 'on') || ($value == 'on') || ($value && $GLOBALS[$name] == $value) ) {
      $selection .= ' CHECKED';
    }
    if ($parameters) $selection .= ' ' . $parameters;
    $selection .= '>';

    return $selection;
  }

  function osc_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return osc_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

  function osc_draw_radio_field($name, $value = '', $checked = false) {
    return osc_draw_selection_field($name, 'radio', $value, $checked);
  }
    function osc_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
      $field = '<select name="' . osc_output_string($name) . '"';

      if (osc_not_null($parameters)) $field .= ' ' . $parameters;

      $field .= '>';

      if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

      for ($i=0, $n=sizeof($values); $i<$n; $i++) {
        $field .= '<option value="' . osc_output_string($values[$i]['id']) . '"';
        if ($default == $values[$i]['id']) {
          $field .= ' SELECTED';
        }

        $field .= '>' . osc_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
      }
      $field .= '</select>';

      if ($required == true) $field .= TEXT_FIELD_REQUIRED;

      return $field;
    }
////
// The HTML image wrapper function
  function osc_image($src, $alt = '', $width = '', $height = '', $params = '') {
    $image = '<img src="' . $src . '" border="0" alt="' . $alt . '"';
    if ($alt) {
      $image .= ' title=" ' . $alt . ' "';
    }
    if ($width) {
      $image .= ' width="' . $width . '"';
    }
    if ($height) {
      $image .= ' height="' . $height . '"';
    }
    if ($params) {
      $image .= ' ' . $params;
    }
    $image .= '>';

    return $image;
  }

?>