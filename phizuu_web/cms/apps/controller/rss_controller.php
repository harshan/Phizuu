<?php 
class rssClass
{

  public function feedNews($user_id)
  {
    $news = new NewsModel();
	$all_news =$news -> listAllNews($user_id);

 	 $rssNews  ='<?xml version="1.0" encoding="ISO-8859-1"?>';
	 $rssNews .='<rss version="2.0">';
	 $rssNews .='<records>';
	   
    foreach ($all_news as $news) {
		
		$rssNews .='<item>';
		$rssNews .='<title>'.$news->title.'</title>';
		$rssNews .='<date>'.$news->date.'</date>';
		$rssNews .='<description>'.$news->description.'</description>';
		
	  $rssNews .='</item>';
    }


	 $rssNews .='</records>';
	 $rssNews .='</rss>';

	return $rss_feed = $rssNews;
  }
  
}
?>