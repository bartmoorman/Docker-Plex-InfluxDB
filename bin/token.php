$host = 'https://plex.tv/users/sign_in.json';
$user = '';
$pass = '';
$uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
  mt_rand(0, 0xffff), mt_rand(0, 0xffff),
  mt_rand(0, 0xffff),
  mt_rand(0, 0x0fff) | 0x4000,
  mt_rand(0, 0x3fff) | 0x8000,
  mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
);
$headers = [
  'X-Plex-Client-Identifier: ' . $uuid,
  'X-Plex-Platform: ' . php_uname('s'),
  'X-Plex-Platform-Version: ' . php_uname('r'),
  'X-Plex-Device: Docker Container',
  'X-Plex-Device-Name: ' . php_uname('n'),
  'X-Plex-Product: Plex-InfluxDB',
  'X-Plex-Version: 1.0'
];
$options = [
  CURLOPT_URL => $host,
  CURLOPT_POST => 1,
  CURLOPT_USERNAME => $user,
  CURLOPT_PASSWORD => $pass,
  CURLOPT_HTTPHEADER => $headers,
  CURLOPT_RETURNTRANSFER => true
];
$ch = curl_init();
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result);
print_r($json);
