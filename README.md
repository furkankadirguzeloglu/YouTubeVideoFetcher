# YouTube Video Fetcher

YouTube Video Fetcher is a project that enables you to obtain basic information about YouTube videos without the need for an API key, using PHP and Curl.

## Usage 
```php
<?php
require_once 'YouTubeVideoFetcher.php';

$videoInfoProvider = new YouTubeVideoFetcher($videoId);
$videoInfo = $videoInfoProvider->getInfo();
print_r($videoInfo);
```

Demo:
```php
require_once 'YouTubeVideoFetcher.php';

if (!isset($_GET["v"]) || empty($_GET["v"])) {
    echo '<p>No video ID provided!</p>';
    exit;
}

$videoId = $_GET["v"];
$videoInfoProvider = new YouTubeVideoFetcher($videoId);
$videoInfo = $videoInfoProvider->getInfo();

echo '<pre>';
print_r($videoInfo);
echo '</pre>';
?>
```

## License
This project is licensed under the terms of the MIT license. See the [LICENSE](https://raw.githubusercontent.com/furkankadirguzeloglu/YouTubeVideoFetcher/main/LICENSE) file for details.