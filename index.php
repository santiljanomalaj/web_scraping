<?php
/*
	Title	: Get Music from www.alliteboos.com
	URL		: index.php
	Author	: SongMi Ri
	Created By : 2020.01.22
*/
define ('UPLOADS', './uploads/');
define ('main_url', 'http://www.allitebooks.org/');
define ('URL_MP3', 'http://www.uriminzokkiri.com/uri_foreign/download.php?ptype=music&no=');

require_once('includes/selector.inc');
?>
<html lang="ko">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE, NO-STORE, must-revalidate">
    <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    <META HTTP-EQUIV="EXPIRES" CONTENT=0>
  </head>
  <body>
  <PRE>
<?php
  // connect DB.
  $con = mysqli_connect('localhost', 'root', '', 'elbooks');
  mysqli_set_charset($con, 'utf8');
  // get all
  $sql = 'SELECT * FROM books WHERE is_downloaded = 0';
  $result = mysqli_query($con, $sql);
  // $count = mysqli_fetch_all($con, $sql);
  // $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // fetch each record...
    
    $directory = UPLOADS . $row['category1'] . '/' . $row['category2'] . '/';     
    $title = strip_tags($row['title']);
    $title = trim($title, "\r");
    $title = trim($title, "\n");
    $title = trim($title);
    $title = iconv('UTF-8', 'ISO-8859-1', $title);
    $ext = explode('.', $row['download_link']);
    $extension = $ext[count($ext) - 1];
    $fileName = $directory . $title . '.' . $extension;    
    if (!file_exists($directory)) {
      mkdir($directory, 0777, true); // to mkdir() must be specified.
    }
    if (!file_exists($fileName)) {
      $contentMp3 = file_get_contents($row['download_link']);
      $fp = fopen($fileName, 'w');
      fwrite($fp, $contentMp3);
      fclose($fp);
      echo $title . '<br>';
      // if download is successfully done...
      $update = "UPDATE books SET is_downloaded =1 WHERE id=" . $row['id'];
      mysqli_query($con, $update);
      // exit;
    }
  }    
  mysqli_close($con);
  ?>
  </body>
</html>
