<?php
// Define of URL
define( 'BASE_DOMAIN', 'https://themeforest.net');
define( 'BASE_URL', BASE_DOMAIN . '/category/wordpress/creative');
define( 'DB_HOST', 'localhost');
define( 'DB_USER', 'root');
define( 'DB_PASSWD', '');
define( 'DB_NAME', 'themeforest');

date_default_timezone_set('Asia/Chungking');

$db_conn = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);

function fill_options($arr, $default) {
  foreach($arr as $val) {
    echo '<option value = \'' . $val . '\'';
    if ($val == $default) echo ' selected';
    echo '>' . $val . '</option>';
  }
}

function checkSunday($sel_month, $day) {
  return date('w', strtotime($sel_month . '-' . sprintf("%02d", $day))) == 0 ? true : false;
}
?>