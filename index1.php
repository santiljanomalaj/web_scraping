<?php
/*
	Title	: Get Music from uriminzokkiri.com
	URL		: index.php
	Author	: Jubin Ri
	Created By : 2017.07.08
*/
define ('FOLDER_MP3', 'mp3');
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
$arrCategory = array (
  array (
    'url' => 'software',
    'title' => 'Software'
  ),
  array (
    'url' => 'programming',
    'title' => 'Programming'
  ),
  array (
    'url' => 'enterprise',
    'title' => 'Enterprise'
  ),
);


echo FOLDER_MP3;
// Get contents
          $contents = file_get_contents('content.html');
          $arrElements = select_elements('.song_body_cont', $contents);
          $i = 0;
          foreach ($arrElements as $node ) {
          	if ($i == 0) print_r($node);
          	exit;
            $index = $node['children'][1]['children'][0]['attributes']['value'];
            $title = $node['children'][3]['text'];

  /* Search my charset convertion
  $tab = array("UTF-8", "ASCII", "Windows-1252", "ISO-8859-15", "ISO-8859-1", "ISO-8859-6", "CP1256");
  $chain = "";
  foreach ($tab as $i)
  {
      foreach ($tab as $j)
      {
          $chain .= " $i$j ".iconv($i, $j, "$title") . '<BR>';
      }
  }
  
  echo $chain;
  */

  // Make the file name
            $title = strip_tags($title);
            $title = trim($title, "\r");
            $title = trim($title, "\n");
            $title = trim($title);
            $title = iconv('UTF-8', 'ISO-8859-1', $title);

            $fileName = FOLDER_MP3 . '/' . $title . '.mp3';

            // Download MP3 file
            if (!file_exists($fileName)) {
              $contentMp3 = file_get_contents(URL_MP3 . $index);
              $fp = fopen($fileName, 'w');
              fwrite($fp, $contentMp3);
              fclose($fp);

              echo $title . '<BR>';
            } else {
              continue;
            }
          }
?>
  </body>
</html>