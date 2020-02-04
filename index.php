<?php

/* 
	На папку cache необходимо поставить права на запись 777

	НАСТРОЙКИ
	1. Кодировка сайта
	2. Полный адрес домена, который надо скопировать. Если на сайте все урлы с WWW, то и тут должно быть также
*/	

$charset = 'UTF-8';
$site = 'https://www.uytny-dom39.ru/';






/////////////////////////////////////////////////////////////////////////////

$host = $_SERVER['HTTP_HOST'];
$home_url 	 = preg_replace('@^www\.@i','', $host);
$dom_replace = rtrim(preg_replace('@^https?://(www\.)?@i', '', $site), '/');
$request = ltrim($_SERVER['REQUEST_URI'], '/');

$rr = pathinfo(preg_replace('/\?.*$/', '', $request));
$ext = isset($rr['extension']) ? $rr['extension'] : 'html';

$hash = md5($request);
$dir = 'cache/';
$fname = $dir.$hash.'.'.$ext;

if(!file_exists($fname)) {
	if(!file_exists($dir) || !is_dir($dir)) {
		if(!@mkdir($dir)) die('Couldt write to cache dir');
	}
	$data = file_get_contents($site.$request);
	
	$data = preg_replace('@([a-z0-9]+\.)?'.$dom_replace.'@i', $home_url, $data);
	if($ext == 'html' || $ext == 'php' || $ext == 'htm') cutall($data);
	file_put_contents($fname, $data);
} 

header('Content-Type: '.my_mime_type($fname).'; charset='.$charset);
readfile($fname);





function cutall(&$data)
{
	$data = preg_replace('@<!--LiveInternet counter-->.*<!--/LiveInternet-->@s', '', $data);
	$data = preg_replace('@<!-- begin of Top100 logo -->.*<!-- end of Top100 logo -->@s', '', $data);
	$data = preg_replace('@<!-- begin of Top100 code -->.*<!-- end of Top100 code -->@s', '', $data);
	$data = preg_replace('@<script src="http\:\/\/www\.google\-analytics\.com.*<script type="text/javascript">.*urchinTracker\(\).*<\/script>@Us', '', $data);
	$data = preg_replace('@<a\s+href="http\:\/\/www\.yandex\.ru/cy\?.*<img src="http\:\/\/www\.yandex\.ru\/cycounter.*>\s*<\/a>@Us', '', $data);
}



 function my_mime_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        
		$tmp = explode('.',$filename);
		$ext = strtolower(array_pop($tmp));
		
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
}
