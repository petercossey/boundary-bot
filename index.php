<?php

$result = array();

if (file_exists('settings.php')) {
  require_once('settings.php');

  $db_connection = pg_connect(implode(' ', array_map(function ($v, $k) {
    return $k . '=' . $v;
  }, $connection, array_keys($connection))));


  if (isset($_GET['dataset'], $_GET['api_key'], $_GET['lat'], $_GET['lon']) && is_numeric($_GET['lat']) && is_numeric($_GET['lon'])) {

    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    $dataset = pg_escape_string($db_connection, $_GET['dataset']);
    $api_key = $_GET['api_key'];

    $db_result = pg_query_params($db_connection, 'SELECT datasets.geofield FROM public.datasets WHERE name = $1 AND token = $2;', array(
      'name' => $dataset,
      'api_key' => $api_key,
    ));

    if ($db_result && $dataset_info = pg_fetch_row($db_result)) {
      $geofield = $dataset_info[0];

      $db_result2 = pg_query($db_connection, "SELECT * FROM public.$dataset WHERE ST_Contains($dataset.$geofield, ST_GeomFromText('POINT($lat $lon)'));");

      while ($row = pg_fetch_assoc($db_result2)) {
        unset($row[$geofield]);
        $result[] = $row;
      }
    }
  }
}

header('Content-type: application/json');
print json_encode($result);
