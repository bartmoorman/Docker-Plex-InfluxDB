#!/usr/bin/php
<?php
require('vendor/autoload.php');
$influxClient = new InfluxDB\Client(getenv('INFLUXDB_HOST') ?: 'influxdb', getenv('INFLUXDB_PORT') ?: 8086, getenv('INFLUXDB_USERNAME'), getenv('INFLUXDB_PASSWORD'));

$influxDatabase = $influxClient->selectDB(getenv('INFLUXDB_DATABASE') ?: 'plex');
if (!$influxDatabase->exists()) {
  $influxDatabase->create();
}

while (true) {
  $time = -microtime(true);

  $influxPoints = [];
  if ($sessions = simplexml_load_file(sprintf('http://%s:%u/status/sessions?X-Plex-Token=%s', getenv('PLEX_HOST') ?: 'plex', getenv('PLEX_PORT') ?: 32400, getenv('PLEX_TOKEN')))) {
    $tags = [];
    $fields = [
      'total_streams' => 0, 'directplay_streams' => 0, 'directstream_streams' => 0, 'transcode_streams' => 0,
      'total_bandwidth' => 0, 'cellular_bandwidth' => 0, 'lan_bandwidth' => 0, 'wan_bandwidth' => 0
    ];
    foreach ($sessions as $session) {
      if ($session->Session) {
        if ($session->TranscodeSession && $session->TranscodeSession['videoDecision'] != 'transcode' && $session->TranscodeSession['audioDecision'] != 'transcode') {
          $decision = 'directstream';
        } else {
          $decision = (string) $session->Media->Part['decision'];
        }

        $location = (string) $session->Session['location'];
        $bandwidth = (int) $session->Session['bandwidth'];

        $fields['total_streams'] += 1;
        $fields[$decision . '_streams'] += 1;
        $fields['total_bandwidth'] += $bandwidth;
        $fields[$location . '_bandwidth'] += $bandwidth;
      }
    }
    $influxPoints[] = new InfluxDB\Point('activity', null, $tags, $fields);
    $influxDatabase->writePoints($influxPoints);
  }

  $time += microtime(true);
  usleep((getenv('INTERVAL') ?: 10 - $time) * pow(10, 6));
}
?>
