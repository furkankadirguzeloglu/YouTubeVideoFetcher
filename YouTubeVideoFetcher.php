<?php

class YouTubeVideoFetcher {
    private $videoInfo;

    public function __construct($videoId) {
        $videoUrl = "https://www.youtube.com/watch?v=" . htmlspecialchars($videoId);
        $videoData = $this->downloadString($videoUrl);
        $this->videoInfo = $this->extractVideoInfo($videoData);
    }

    private function downloadString($url, $acceptLanguage = 'en-US,en;q=0.5') {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Accept-Language: $acceptLanguage"]
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    private function extractVideoInfo($videoData) {
        $videoInfo = [
            "videoTitle" => null,
            "viewCount" => null,
            "likeCount" => null,
            "publishDate" => null,
            "description" => null,
            "channelName" => null,
            "channelUrl" => null,
            "channelAvatar" => null,
            "channelSubscriberCount" => null
        ];

        preg_match('/<meta\s+name="title"\s+content="([^"]+)"\s*\/?>/i', $videoData, $matches);
        $videoInfo['videoTitle'] = $matches[1] ?? null;

        preg_match('/"viewCount":\{"simpleText":"([^"]+)"\}/i', $videoData, $matches);
        $videoInfo['viewCount'] = str_replace(' views', '', $matches[1] ?? null);

        preg_match('/"label":\{"simpleText":"Likes"\},"accessibilityText":"([^"]+)"}/i', $videoData, $matches);
        $videoInfo['likeCount'] = $matches[1] ?? null;

        preg_match('/"publishDate":\{"simpleText":"([^"]+)"\}/i', $videoData, $matches);
        $videoInfo['publishDate'] = $matches[1] ?? null;

        preg_match('/"description":\{"simpleText":"([^"]+)"\}/i', $videoData, $matches);
        $videoInfo['description'] = $matches[1] ?? null;

        preg_match('/"itemListElement"\s*:\s*\[\s*{\s*"@type"\s*:\s*"ListItem",\s*"position"\s*:\s*\d+,\s*"item"\s*:\s*{\s*"@id"\s*:\s*"([^"]+)",\s*"name"\s*:\s*"([^"]+)"\s*}\s*}\s*\]/i', $videoData, $matches);
        if ($matches[1] && $matches[2]) {
            $videoInfo['channelUrl'] = str_replace('http', 'https', str_replace('\/', '/', $matches[1]));
            $videoInfo['channelName'] = $matches[2];
        }

        preg_match('/"subscriberCountText":\{"accessibility":\{"accessibilityData":\{"label":"([^"]+)"\}/i', $videoData, $matches);
        $videoInfo['channelSubscriberCount'] = str_replace(' subscribers', '', $matches[1] ?? null);

        preg_match('/"width":88,"height":88},{"url":"([^"]+)"/i', $videoData, $matches);
        $videoInfo['channelAvatar'] = $matches[1] ?? null;
        return $videoInfo;
    }

    public function getInfo() {
        return $this->videoInfo;
    }
}