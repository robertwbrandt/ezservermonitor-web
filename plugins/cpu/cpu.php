<?php
require '../../autoload.php';
$Config = new Config();

$tmp = Misc::getCPUData();

$datas = array(
    'model'      => $tmp['model'],
    'num_cores'  => $tmp['cores'],
    'frequency'  => $tmp['frequency'],
    'cache'      => $tmp['cache'],
    'bogomips'   => $tmp['bogomips'],
    'temp'       => $tmp['temperature'],
);

echo json_encode($datas);