<?php
$objCurl = curl_init();
$content = @file_get_contents("php://input");
curl_setopt($objCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($objCurl, CURLOPT_POST, 1);
curl_setopt($objCurl, CURLOPT_POSTFIELDS, $content);
curl_setopt($objCurl, CURLOPT_URL, 'http://api.preguntados.com' . $_SERVER['REQUEST_URI']);
curl_setopt($objCurl, CURLOPT_HTTPHEADER, array(
	'Cookie: ' . $_SERVER['HTTP_COOKIE'],
	'Connection: keep-alive',
	'Host: api.preguntados.com',
	'User-Agent: ' . $_SERVER['HTTP_USER_AGENT'],
	'Content-Length: ' . strlen($content),
	'Content-Type: application/json'
));
$strResponse = curl_exec($objCurl);
header('Content-Type: application/json');
$data = json_decode($strResponse, 1);
if (isset($data['spins_data'])) {
	foreach ($data['spins_data']['spins'][0]['questions'] as $q_id => $q_sect) {
		foreach ($data['spins_data']['spins'][0]['questions'][$q_id] as $id => $question) {
			$data['spins_data']['spins'][0]['questions'][$q_id][$id]['answers'][$question['correct_answer']] = 'CORRECT ANSWER';
		}
	}
}
die(json_encode($data));
?>