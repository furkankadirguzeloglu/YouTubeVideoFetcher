<?php
function VideoDumper($id){
    $Opts = array(
	'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n"
	));

    $Context = stream_context_create($Opts);
    $DownloadHTML = file_get_contents("https://youtube.com/watch?v=$id", false, $Context);
    
	$TitleTemp1 = substr($DownloadHTML, (strpos($DownloadHTML, "<title>") + 7), 220);
    $TitleTemp2 = substr($TitleTemp1, 0, strpos($TitleTemp1, "</title>") - 9);
	$Title = htmlspecialchars_decode($TitleTemp2);
	
    $ViewCountTemp1 = substr($DownloadHTML, (strpos($DownloadHTML, '"shortViewCount":{"simpleText":"')), 200);
	$ViewCountTemp2 = explode(',"', $ViewCountTemp1);
	$ViewCount = htmlspecialchars_decode(str_replace('"shortViewCount":{"simpleText":"','', str_replace(' views"}}}', '', $ViewCountTemp2[0])));
	
	$DateTemp1 = substr($DownloadHTML, (strpos($DownloadHTML, 'dateText')), 200);
	$DateTemp2 = explode('"}}},{"', $DateTemp1);
	$Date = htmlspecialchars_decode(str_replace('dateText":{"simpleText":"','', $DateTemp2[0]));
	
	$LikeTemp1 = substr($DownloadHTML, (strpos($DownloadHTML, '{"iconType":"LIKE"},"defaultText":{"accessibility":{"accessibilityData":{')), 200);
    $LikeTemp2 = explode(',"', $LikeTemp1);
	$Like = htmlspecialchars_decode(str_replace('simpleText":"','', str_replace('"}', '', $LikeTemp2[2])));
	
	$DislikeTemp1 = substr($DownloadHTML, (strpos($DownloadHTML, '{"iconType":"DISLIKE"},"defaultText":{"accessibility":{"accessibilityData":{')), 200);
    $DislikeTemp2 = explode(',"', $DislikeTemp1);
	$Dislike = htmlspecialchars_decode(str_replace('simpleText":"','', str_replace('"}', '', $DislikeTemp2[2])));
	
	$DescriptionTemp1 = substr($DownloadHTML, (strpos($DownloadHTML, '"description":{"simpleText":"')), 99999);
	$DescriptionTemp2 = explode('"},"', $DescriptionTemp1);
	$Description = htmlspecialchars_decode(str_replace('"description":{"simpleText":"', '', $DescriptionTemp2[0]));
	
    $Thumbnail = "https://img.youtube.com/vi/$id/sddefault.jpg";

    $AuthorTemp1 = substr($DownloadHTML, (strpos($DownloadHTML, "viewCount")), 200);
    $AuthorTemp2 = explode('","', $AuthorTemp1);
	$Author = htmlspecialchars_decode(str_replace('author":"','', $AuthorTemp2[1]));
	
	$AuthorAvatarTemp1 = substr($DownloadHTML, (strpos($DownloadHTML, "https://yt3.ggpht.com/ytc/")), 200);
	$AuthorAvatarTemp2 = explode('","', $AuthorAvatarTemp1);
	$AuthorAvatar = htmlspecialchars_decode($AuthorAvatarTemp2[0]);
	
    $AuthorSubscriberCountTemp1 = substr($DownloadHTML, (strpos($DownloadHTML, '"subscriberCountText":{"runs":[{"text":')), 200);
	$AuthorSubscriberCountTemp2 = explode('},"', $AuthorSubscriberCountTemp1);
	$AuthorSubscriberCountTemp3 = str_replace('"subscriberCountText":{"runs":[{"text":"', '', str_replace('"}]','',str_replace(' subscribers', '',$AuthorSubscriberCountTemp2[0])));
	$AuthorSubscriberCount = htmlspecialchars_decode($AuthorSubscriberCountTemp3);
	
    $Data = [
        'title'=>$Title,
		'viewcount'=>$ViewCount,
		'date'=>$Date,
		'like'=>$Like,
		'dislike'=>$Dislike,
		'description'=>$Description,
		'thumbnail'=>$Thumbnail,
        'author'=>$Author,
		'author_avatar'=>$AuthorAvatar,
        'author_subscriber_count'=>$AuthorSubscriberCount
    ];
    return $Data;
}

function ArrayDumper(){
    echo (php_sapi_name() !== 'cli') ? '<pre>' : '';
    foreach(func_get_args() as $arg){
        echo preg_replace('#\n{2,}#', "\n", print_r($arg, true));
    }
    echo (php_sapi_name() !== 'cli') ? '</pre>' : '';
}

echo '<pre>';
ArrayDumper(VideoDumper($_GET["id"]));
echo '</pre>';
?>