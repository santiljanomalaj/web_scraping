<?php
/*
	Title	: Get Music from www.alliteboos.com
	URL		: index.php
	Author	: SongMi Ri
	Created By : 2020.01.22
*/
define ('FOLDER_MP3', 'mp3');
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

  $con = mysqli_connect('localhost', 'root', '', 'elbooks');
  mysqli_set_charset($con, 'utf8');
  


$arrCategory = array (
  array (
    'url' => 'web-development',
    'title' => 'Web Development'
  ),
  array (
    'url' => 'programming',
    'title' => 'Programming'
  ),
  array (
    'url' => 'datebases',
    'title' => 'Datebases'
  ),
  array (
    'url' => 'graphics-design',
    'title' => 'Graphics & Design'
  ),
  array (
    'url' => 'operating-systems',
    'title' => 'Operating Systems'
  ),
  array (
    'url' => 'networking-cloud-computing',
    'title' => 'Networking & Cloud Computing'
  ),
  array (
    'url' => 'administration',
    'title' => 'Administration'
  ),
  array (
    'url' => 'certification',
    'title' => 'Certification'
  ),
  array (
    'url' => 'computers-technology',
    'title' => 'Computers & Technology'
  ),
  array (
    'url' => 'enterprise',
    'title' => 'Enterprise'
  ),
  array (
    'url' => 'game-programming',
    'title' => 'Game Programming'
  ),
  array (
    'url' => 'hardware',
    'title' => 'Hardware & DIY'
  ),
  array (
    'url' => 'marketing-seo',
    'title' => 'Marketing & SEO'
  ),
  array (
    'url' => 'security',
    'title' => 'Security'
  ),
  array (
    'url' => 'software',
    'title' => 'Software'
  )
);

foreach ($arrCategory as $node ) {

  $contents = file_get_contents(main_url . $node['url']);
  $arrElements = select_elements('.pages', $contents); 
   
  $pagecount = explode('Pages',explode('/', $arrElements[0]['text'])[1])[0];   // 매 분류의 페지수를 얻는다. 
 
  $pageNum = settype($pagecount,'int');
  // echo '<h1>'. $pagecount .'</h1>';
  // echo '<h1>'. gettype($pagecount) .'</h1>';
  $pagenumber = 1;
  for ($i = 0; $pagenumber <= $pagecount; $i++) {

    $book_lists = '';
     
      if($i == 0) { //  page number is 1
        $pagenumber = 1;
        $book_lists = $contents; 

      }else{
        $pagenumber++;
        $book_lists = file_get_contents(main_url . $node['url'] . '/page/' . $pagenumber . '/'); // if page number is not 1, add '/page/' page number to url.
        // echo $book_lists;
        // echo main_url . $node['url'] . '/page/' . $pagenumber . '/<br>';

      }
      $entry_titles = select_elements('.entry-title', $book_lists); // get book lists of every page

      foreach($entry_titles as $entry_title) {         
        
        $book_details_url = $entry_title['children'][0]['attributes']['href']; // 매 book의 경로를 얻는다.
        $book_details_contents = file_get_contents($book_details_url); // get each book data 
        echo $book_details_url . '<br>';
        $book_title = select_elements('.single-title',$book_details_contents); //
        $book_title = isset($book_title) ? $book_title[0]['text']:[];
        $book_content = select_elements('dl', $book_details_contents);
        $book_content = isset($book_content) ? $book_content : [];
        $book = array();
        for($i = 0; $i < count($book_content[0]['children']); $i += 2) {  // get details of book like author, pages , isbn , year, category
          $key = $book_content[0]['children'][$i]['text'];
          $book[$key] = $book_content[0]['children'][$i+1]['text'];          
        } 
        $book_description = strip_tags(select_elements('.entry-content',$book_details_contents)[0]['text']);
        $book_description = str_replace("\n", " ", $book_description);
        $book_description = str_replace("  ", "", $book_description);
        $book_description = str_replace("'", "", $book_description);
        $book_description = trim(str_replace("Book Description:", "", $book_description));

        $book_download_link = select_elements('.download-links',$book_details_contents);
        $book_download_link = isset($book_download_link) ? $book_download_link[0]['children'][0]['attributes']['href']: '';

        // Check exist
        $sql = 'SELECT id FROM books WHERE download_link = \'' . $book_download_link . '\'';
        $result = mysqli_query($con, $sql);
        if ((!$result || mysqli_num_rows($result) == 0) && $book_title) {
          // Build SQL Statement
          // title,  author, year, isbn , pages ,category1, category2 , description, download_link , is_downloaded 
          $sql = 'INSERT INTO books SET';
          $sql .= ' title = \'' . $book_title . '\'';
          $sql .= ', author = \'' . $book['Author:'] . '\'';
          $sql .= ', year = \'' . $book['Year:'] . '\'';
          $sql .= ', isbn = \'' . $book['ISBN-10:'] . '\'';
          $sql .= ', pages = \'' . $book['Pages:'] . '\'';
          $sql .= ', category1 = \'' . $node['title'] . '\'';
          $sql .= ', category2 = \'' . $book['Category:'] . '\'';
          $sql .= ', description = \'' . $book_description . '\'';
          $sql .= ', download_link = \'' . $book_download_link . '\'';
          $sql .= ', is_downloaded = 0'; 
          mysqli_query($con, $sql);
        }  
      }              
  }  
  echo $pagenumber;
}
mysqli_close($con);
?>
  </body>
</html>