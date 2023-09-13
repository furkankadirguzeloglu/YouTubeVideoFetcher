<?php
function VideoDumper($id) {
    $opts = array('http' => array('method' => "GET", 'header' => "Accept-language: en\r\n"));
    $context = stream_context_create($opts);
    $downloadHTML = file_get_contents("https://youtube.com/watch?v=$id", false, $context);
    $title = "null";
    if (strpos($downloadHTML, '<title>') !== false && strpos($downloadHTML, '</title>') !== false) {
        $titleTemp1 = substr($downloadHTML, strpos($downloadHTML, "<title>") + 7, 220);
        $titleTemp2 = substr($titleTemp1, 0, strpos($titleTemp1, "</title>") - 9);
        $title = htmlspecialchars_decode($titleTemp2);
    }
    $viewCount = "null";
    if (strpos($downloadHTML, '"shortViewCount":{"simpleText":"') !== false) {
        $viewCountTemp1 = substr($downloadHTML, strpos($downloadHTML, '"shortViewCount":{"simpleText":"'), 200);
        $viewCountTemp2 = explode(',"', $viewCountTemp1);
        $viewCount = htmlspecialchars_decode(str_replace('"shortViewCount":{"simpleText":"', '', str_replace(' views"}', '', $viewCountTemp2[0])));
    }
    $date = "null";
    if (strpos($downloadHTML, 'dateText') !== false) {
        $dateTemp1 = substr($downloadHTML, strpos($downloadHTML, 'dateText'), 200);
        $dateTemp2 = explode('"},"', $dateTemp1);
        $date = htmlspecialchars_decode(str_replace('dateText":{"simpleText":"', '', $dateTemp2[0]));
    }
    $like = "null";
    if (strpos($downloadHTML, '{"iconType":"LIKE"},"defaultText":{"accessibility":{"accessibilityData":{') !== false) {
        $likeTemp1 = substr($downloadHTML, strpos($downloadHTML, '{"iconType":"LIKE"},"defaultText":{"accessibility":{"accessibilityData":{'), 200);
        $likeTemp2 = explode(',"', $likeTemp1);
        $like = htmlspecialchars_decode(str_replace('simpleText":"', '', str_replace('"}', '', $likeTemp2[2])));
    }
    $dislike = "null";
    if (strpos($downloadHTML, '{"iconType":"DISLIKE"},"defaultText":{"accessibility":{"accessibilityData":{') !== false) {
        $dislikeTemp1 = substr($downloadHTML, strpos($downloadHTML, '{"iconType":"DISLIKE"},"defaultText":{"accessibility":{"accessibilityData":{'), 200);
        $dislikeTemp2 = explode(',"', $dislikeTemp1);
        $dislike = htmlspecialchars_decode(str_replace('simpleText":"', '', str_replace('"}', '', $dislikeTemp2[2])));
    }
    $description = "null";
    if (strpos($downloadHTML, '"description":{"simpleText":"') !== false) {
        $descriptionTemp1 = substr($downloadHTML, strpos($downloadHTML, '"description":{"simpleText":"'), 99999);
        $descriptionTemp2 = explode('"},"', $descriptionTemp1);
        $description = htmlspecialchars_decode(str_replace('"description":{"simpleText":"', '', $descriptionTemp2[0]));
    }
    $thumbnail = "https://img.youtube.com/vi/$id/sddefault.jpg";
    $author = "null";
    if (strpos($downloadHTML, 'viewCount') !== false) {
        $authorTemp1 = substr($downloadHTML, strpos($downloadHTML, "viewCount"), 200);
        $authorTemp2 = explode('","', $authorTemp1);
        $author = htmlspecialchars_decode(str_replace('author":"', '', $authorTemp2[1]));
    }
    $authorAvatar = "null";
    if (strpos($downloadHTML, ',"width":88,"height":88},{"url":"') !== false) {
        $authorAvatarTemp1 = substr($downloadHTML, strpos($downloadHTML, ',"width":88,"height":88},{"url":"'), 200);
        $authorAvatarTemp2 = explode('"https://yt3.ggpht.com/ytc/', $authorAvatarTemp1);
        $authorAvatarTemp3 = explode('","', $authorAvatarTemp2[1]);
        $authorAvatar = htmlspecialchars_decode("https://yt3.ggpht.com/ytc/" . str_replace('', '', $authorAvatarTemp3[0]));
    }
    $authorSubscriberCount = "null";
    if (strpos($downloadHTML, 'subscriberCountText":{"accessibility":{"accessibilityData":{"label":"') !== false) {
        $authorSubscriberCountTemp1 = substr($downloadHTML, strpos($downloadHTML, 'subscriberCountText":{"accessibility":{"accessibilityData":{"label":"'), 200);
        $authorSubscriberCountTemp2 = explode('},"', $authorSubscriberCountTemp1);
        $authorSubscriberCountTemp3 = str_replace('subscriberCountText":{"accessibility":{"accessibilityData":{"label":"', '', str_replace('"}', '', str_replace(' subscribers', '', $authorSubscriberCountTemp2[0])));
        $authorSubscriberCount = htmlspecialchars_decode($authorSubscriberCountTemp3);
    }
    $data = ['title' => $title, 'viewcount' => $viewCount, 'date' => $date, 'like' => $like, 'dislike' => $dislike, 'description' => $description, 'thumbnail' => $thumbnail, 'author' => $author, 'author_avatar' => $authorAvatar, 'author_subscriber_count' => $authorSubscriberCount];
    return $data;
}
function ArrayDumper($data) {
    echo (php_sapi_name() !== 'cli') ? '<pre>' : '';
    echo preg_replace('#\n{2,}#', "\n", print_r($data, true));
    echo (php_sapi_name() !== 'cli') ? '</pre>' : '';
}
ArrayDumper(VideoDumper($_GET["id"]));
?>
