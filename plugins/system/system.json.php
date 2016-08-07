<?php
require '../../autoload.php';
date_default_timezone_set(@date_default_timezone_get());

$datas = array(
    'hostname'      => Misc::getHostname(),
    'os'            => Misc::getOS(),
    'kernel'        => Misc::getRelease(),
    'uptime'        => Misc::getUpTime(),
    'last_boot'     => Misc::getBootTime(),
    'current_users' => Misc::getCurrentUsers(),
    'server_date'   => Misc::getCurrentDate(),
);

echo json_encode($datas, JSON_PRETTY_PRINT);
