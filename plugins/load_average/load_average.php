<?php
require '../../autoload.php';

if (!($load_tmp = shell_exec('cat /proc/loadavg | awk \'{print $1","$2","$3}\'')))
{
    $load = array(0, 0, 0);
}
else
{
    // Number of cores
    $cores = Misc::getCPUData('cores');

    $load_exp = explode(',', $load_tmp);

    $load = array_map(
        function ($value, $cores) {
            $v = (int)($value * 100 / $cores);
            if ($v > 100)
                $v = 100;
            return $v;
        }, 
        $load_exp,
        array_fill(0, 3, $cores)
    );
}


$datas = $load;

echo json_encode($datas);