<?php
require '../../autoload.php';
$Config = new Config();

$datas = array();

$max = $Config->get('processes:max');
if ($max < 1) $max = 5;
$sort = $Config->get('processes:sort');
if ( $sort == 'mem' ) {
    $sort = '-pmem';
} else {
    $sort = '-pcpu';
}

$include = $Config->get('processes:include');
$exclude = $Config->get('processes:exclude');
array_push($exclude,'\[.*\]');
$exclude = '\('.implode('\|',$exclude).'\)';

echo $exclude."\n";


$command = 'ps -eo "pcpu,pmem,args" --noheader --sort '.$sort;
if ($exclude)
    $command .= ' | grep -v "'.$exclude.'"';
$command .= ' | sed -e "s|^\s||" -e "s|\s\+|,|g" | cut -d "," -f 1-3 | sed "s|/.*/||"';

if (!(exec('/bin/df -T -P | awk -v c=`/bin/df -T | grep -bo "Type" | awk -F: \'{print $2}\'` \'{print substr($0,c);}\' | tail -n +2 | awk \'{print $1","$2","$3","$4","$5","$6","$7}\'', $ps)))
{
    $datas[] = array(
        'cpu'     => 0,
        'mem'     => 0,
        'process' => 'N.A'
    );
}
else
{
    $processes = array();
    $inc_processes = array()
    $cores = Misc::getCPUData('cores');
    if ($cores < 1) $cores = 1;

    foreach ($ps as $line) {
        list($cpu, $mem, $process) = explode(',', $line);

        $tmp =  array( 'cpu'      => $cpu/$cores,
                       'mem'      => $mem,
                       'process'  => $process );  

        if (in_array($process, $include))
            array_push($inc_processes, $tmp);
        else
            array_push($processes, $tmp);
    }

    $datas = array_slice(array_merge($inc_processes, $processes),0,$max);

}

echo json_encode($datas);