<?php
$access_token = 'OiFfXS/z6c3apamAsO/5EHnQtIbsw104XLcrFtUV+/wwF/3nd7h49zb/MzmR9QVoenYVUbi7ClKuXf/dFK7juWapND6faujmbYJL79hE1n3iJ6h8yWuHNhh3WeoHCe/ou5VnZL2DrK/e8N/M6UkMaAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message') {
			// Get text sent
			$groupId = $event['source']['groupId'];
			$userId = $event ['source']['userId'];
			$messageId = $event ['message']['id'];
			$messageType = $event ['message']['type'];
			$massage = $event['message']['text'];
			$timestamp = $event['timestamp'];
			$replyToken = $event['replyToken'];


			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.line.me/v2/bot/group/$groupId/member/$userId",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "authorization: Bearer OiFfXS/z6c3apamAsO/5EHnQtIbsw104XLcrFtUV+/wwF/3nd7h49zb/MzmR9QVoenYVUbi7ClKuXf/dFK7juWapND6faujmbYJL79hE1n3iJ6h8yWuHNhh3WeoHCe/ou5VnZL2DrK/e8N/M6UkMaAdB04t89/1O/w1cDnyilFU=",
			    "cache-control: no-cache",
			    "postman-token: d6f19e7a-b3b4-741d-f9ee-075cdd77cc2f"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
				$data = json_decode($response);
				$displayName = $data->displayName;
				$pictureUrl = $data->pictureUrl;
			  echo $data->userId;
			}

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => "GROUPID = $groupId USERID = $userId  MESSAGEID = $messageId MESSAGE = $massage Time =
				$timestamp Displayname = $displayName Pictureurl = $pictureUrl"
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";


			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.line.me/v2/bot/message/push",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "{\n    \"to\": \"$groupId\",\n    \"messages\": [\n\t      {\n    \"type\": \"image\",\n    \"originalContentUrl\": \"$pictureUrl\",\n    \"previewImageUrl\": \"$pictureUrl\"\n}\n    ]\n}",
			  CURLOPT_HTTPHEADER => array(
			    "authorization: Bearer OiFfXS/z6c3apamAsO/5EHnQtIbsw104XLcrFtUV+/wwF/3nd7h49zb/MzmR9QVoenYVUbi7ClKuXf/dFK7juWapND6faujmbYJL79hE1n3iJ6h8yWuHNhh3WeoHCe/ou5VnZL2DrK/e8N/M6UkMaAdB04t89/1O/w1cDnyilFU=",
			    "cache-control: no-cache",
			    "content-type: application/json",
			    "postman-token: 762f0329-a9eb-332a-af27-b8c8e7307c13"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  echo $response;
			}


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.line.me/v2/bot/message/$messageId/content",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer $access_token",
    "cache-control: no-cache",
    "postman-token: 0516cb11-a858-b745-ff4d-f611525c0471"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
	file_put_contents("image/$messageId.jpg", $response);
}

$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.line.me/v2/bot/message/push",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "{\n    \"to\": \"$groupId\",\n    \"messages\": [\n\t      {\n    \"type\": \"image\",\n    \"originalContentUrl\": \"https://6769d893.ngrok.io/work/OCRBot/source_group/image/$messageId.jpg\",\n    \"previewImageUrl\": \"https://6769d893.ngrok.io/work/OCRBot/source_group/image/$messageId.jpg\"\n}\n    ]\n}",
	CURLOPT_HTTPHEADER => array(
		"authorization: Bearer OiFfXS/z6c3apamAsO/5EHnQtIbsw104XLcrFtUV+/wwF/3nd7h49zb/MzmR9QVoenYVUbi7ClKuXf/dFK7juWapND6faujmbYJL79hE1n3iJ6h8yWuHNhh3WeoHCe/ou5VnZL2DrK/e8N/M6UkMaAdB04t89/1O/w1cDnyilFU=",
		"cache-control: no-cache",
		"content-type: application/json",
		"postman-token: 762f0329-a9eb-332a-af27-b8c8e7307c13"
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}
		}
	}
}
echo "O kub K";
