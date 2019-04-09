#!/usr/bin/php
<?php
require('vendor/autoload.php');
$influxClient = new InfluxDB\Client(getenv('INFLUXDB_HOST'), getenv('INFLUXDB_PORT'), getenv('INFLUXDB_USERNAME'), getenv('INFLUXDB_PASSWORD'));

$influxDatabase = $influxClient->selectDB(getenv('INFLUXDB_DATABASE'));
if (!$influxDatabase->exists()) {
  $influxDatabase->create();
}

while (true) {
  $time = -microtime(true);

  $influxPoints = [];
  if ($sessions = simplexml_load_file(sprintf('http://%s:%s/status/sessions?X-Plex-Token=%s', getenv('PLEX_HOST'), getenv('PLEX_PORT'), getenv('PLEX_TOKEN')))) {
    $tags = [];
    $fields = ['total_streams' => 0, 'total_bandwidth' => 0];
    foreach ($sessions as $session) {
      if ($session->Session) {
        if ($session->TranscodeSession && $session->TranscodeSession['videoDecision'] != 'transcode' && $session->TranscodeSession['audioDecision'] != 'transcode') {
          $decision = 'directstream';
        } else {
          $decision = (string) $session->Media->Part['decision'];
        }

        $location = (string) $session->Session['location'];

        $fields['total_streams'] += 1;
        array_key_exists($decision . '_streams', $fields) ? $fields[$decision . '_streams'] += 1 : $fields[$decision . '_streams'] = 1;
        $fields['total_bandwidth'] += (int) $session->Session['bandwidth'];
        array_key_exists($location . '_bandwidth', $fields) ? $fields[$location . '_bandwidth'] += (int) $session->Session['bandwidth'] : $fields[$location . '_bandwidth'] = (int) $session->Session['bandwidth'];
      }
    }
    $influxPoints[] = new InfluxDB\Point('activity', null, $tags, $fields);
    $influxDatabase->writePoints($influxPoints);
  }

  $time += microtime(true);
  usleep((getenv('INTERVAL') - $time) * pow(10, 6));
}
?>
