<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

if (isset($_GET['url']) && $_GET['url'] != '') {

  $html = file_get_contents($_GET['url']);
  // Get Info 
  preg_match("/film-title\">(.*?)>(.*?)<\/a>/si", $html, $title);
  preg_match("/film-title\">(.*?)>(.*?)<\/h1>/si", $html, $years);
  preg_match("/\((.*?)\)/si", $years[2], $year );
  preg_match("/<img class=\"img-responsive\" itempropx=\"image\" src=\"(.*?)\"/si", $html, $poster);
  preg_match("/class=\"inline\">Native Title:<\/b>(.*?)<\/li>/si", $html, $nativeAll);
  preg_match_all("/>(.*?)<\/a>/si", $nativeAll[1], $native);
  preg_match("/class=\"inline\">Also Known As:<\/b> <span class=\"mdl-aka-titles\">(.*?)<\/span> <\/li>/si", $html, $alsoknownAll);
  preg_match_all("/\">(.*?)<\/a>/si", $alsoknownAll[1], $alsoknown);
  preg_match("/class=\"inline\">Genres:<\/b>(.*?)<\/li>/si", $html, $genresAll);
  preg_match_all("/\">(.*?)<\/a>/si", $genresAll[1], $genres); 
  preg_match("/<li class=\"list-item p-a-0\"><b class=\"inline\">Episodes:<\/b>\s(.*?)<\/li>/", $html, $total_eps);
  preg_match("/<li class=\"list-item p-a-0\"><b class=\"inline\">Country:<\/b>\s(.*?)\s<i class=\"flag flags-c3\"><\/i><\/li>/", $html, $country);
  preg_match("/class=\"inline\">Aired:<\/b>(.*?)<\/li>/si", $html, $aired);
  preg_match("/class=\"inline\">Aired on:<\/b>(.*?)<\/li>/si", $html, $day);
  preg_match("/class=\"inline\">Original Network:<\/b>(.*?)<\/li>/si", $html, $networkAll);
  preg_match_all("/>(.*?)<\/a>/si", $networkAll[1], $network);
  preg_match("/class=\"inline\">Score:<\/b>(.*?)<span/si", $html, $score);
  preg_match("/class=\"inline\">Content Rating:<\/b>(.*?)<\/li>/si", $html, $rating);

  preg_match("/<li class=\"list-item p-a-0\"><b class=\"inline duration\">Duration:<\/b>\s(.*?)<\/li>/", $html, $duration);
  preg_match("/class=\"inline\">Director:<\/b>(.*?)<\/li>/si", $html, $directorAll);
  preg_match_all("/\">(.*?)<\/a>/si", $directorAll[1], $director);
  preg_match("/class=\"inline\">Screenwriter:<\/b>(.*?)<\/li>/si", $html, $screenwriterAll);
  preg_match_all("/\">(.*?)<\/a>/si", $screenwriterAll[1], $screenwriter);
  preg_match("/<ul class=\"list no-border p-b\">(.*?)<\/ul>/si", $html, $casts);
  preg_match_all("/<li class=\"list-item col-sm-4\">(.*?)<\/li>/si", $casts[1], $cast);
  preg_match("/class=\"inline\">Tags:<\/b>(.*?)<\/li>/si", $html, $tagsAll);
  preg_match_all("/\">(.*?)<\/a>/si", $tagsAll[1], $tag);
  preg_match("/show-synopsis\">(.*?)>(.*?)<\/span>/si", $html, $syx);
  
  $result = [];
  
  foreach ($cast[1] as $castx) {
    preg_match('/itempropx=\"name\">(.*?)<\/b>/si', $castx, $nama);
    preg_match('/lass=\"text-muted\">(.*?)<\/small>/si', $castx, $role);
    $result[] = [
        'nama' => $nama[1],
        'role' => $role[1],
    ];
  }

  $synopsis = str_replace(['<span class="read-more-hidden">', '<span>', '<p>'], ' ', $syx[2]);
  $tags = str_replace('(Vote or add tags)', ' ', $tag[1]);
  
  // Send Response JSON
  header('Content-Type: application/json');

  echo json_encode([
    'title'         => $title[2],
    'years'         => $year[1],
    'poster'        => $poster[1],
    'native'        => $native[1],
    'alsoknown'     => $alsoknown[1],
    'genres'        => $genres[1],
    'total_eps'     => $total_eps[1],
    'country'       => $country[1],
    'aired'         => $aired[1],
    'aired_on'      => $day[1],
    'network'       => $network[1],
    'score'         => $score[1],
    'rating'        => $rating[1],
    'duration'      => $duration[1],
    'director'      => $director[1],
    'screenwriter'  => $screenwriter[1],
    'cast'          => $result,
    'tags'          => $tags,
    'synopsis'      => $synopsis,
  ]);

  exit();
} 

