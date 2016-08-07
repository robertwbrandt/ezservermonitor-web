<?php
require '../../autoload.php';
$Config = new Config();

$datas = array();

if (count($Config->get('deamons:list')) > 0)
{
    foreach ($Config->get('deamons:list') as $service)
    {
        $name     = $service['name'];
        $command  = $service['command'];

        $output = array();
        $return_var = 1;
        exec($command, $output, $return_var);

        $datas[] = array(
            'name'      => $name,
            'status'    => $return_var,
            'output'    => $output
        );
    }
}

echo json_encode($datas, JSON_PRETTY_PRINT);
