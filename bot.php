<?php

ob_start();

$API_KEY = '<YOUR TELEGRAM BOT API TOKEN>';
define('API_KEY', $API_KEY);
function bot ($method,$data=[]){
	$url = "https://api.telegram.org/bot".API_KEY."/".$method;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$res = curl_exec($ch);
	if (curl_error($ch)) {
		var_dump(curl_error($ch));
	}else{
		return json_decode($res);
	}
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$text = $message->text;
$chat_id = $message->chat->id;

$url = $text;
if (isset($url) AND !empty($url) AND 
filter_var($url, FILTER_VALIDATE_URL)):
//Encode URL
$urlEncode =urlencode($url);
//Decode URL
$urlDecode = htmlspecialchars(urldecode($urlEncode), ENT_QUOTES);
//Get Headers
$getHeaders = get_headers($urlDecode, 1);
if(is_array($getHeaders['Location'])):
$location = current($getHeaders['Location']);
else:
$location = $getHeaders['Location'];
endif;
//If Redirect 301, 302 or 303 : Display URL
if (strpos($getHeaders[0], '301') || strpos($getHeaders[0], '302') || strpos($getHeaders[0], '303') !== false):
$location=  $location ;
endif;
endif;

$result = $location;
if ($text == '/start'){
  bot ('sendMessage', [
  'chat_id'=> $chat_id,
  'text'=> 'ENTER VALID goo.gl or bit.ly URL TO UNSHORTEN'."\n\nIf any problem occurs contact @Gopi_killer"]);
  }else{
bot ('sendMessage', [
'chat_id'=> $chat_id,
'text'=> $result]);
}
?>
