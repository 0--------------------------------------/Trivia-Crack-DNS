<?php
$objCurl = curl_init();
curl_setopt($objCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($objCurl, CURLOPT_URL, 'http://api.preguntados.com' . $_SERVER['REQUEST_URI']);
curl_setopt($objCurl, CURLOPT_HTTPHEADER, array(
	'Cookie: ' . $_SERVER['HTTP_COOKIE'],
	'Connection: keep-alive',
	'Host: api.preguntados.com',
	'User-Agent: ' . $_SERVER['HTTP_USER_AGENT']
));
$strResponse = curl_exec($objCurl);
header('Content-Type: application/json');
$data = json_decode($strResponse, 1);
$data['coins'] = 999999;
$data['extra_shots'] = 999999;
$data['lives']['max'] = 999999;
$data['lives']['quantity'] = 999999;
foreach ($data['list'] as $l_id => $list) {
	if (($list['game_status'] == 'ACTIVE' || $list['game_status'] == 'PENDING_APPROVAL') && isset($list['spins_data'])) {
		foreach ($list['spins_data']['spins'][0]['questions'] as $q_id => $q_sect) {
			foreach ($list['spins_data']['spins'][0]['questions'][$q_id] as $id => $question) {
				$data['list'][$l_id]['spins_data']['spins'][0]['questions'][$q_id][$id]['answers'][$question['correct_answer']] = 'CORRECT ANSWER';
			}
		}
	}
}
die(json_encode($data));
?>