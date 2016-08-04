<?php
require '../../autoload.php';
$Config = new Config();

$datas = array();

$max = $Config->get('processes:max');
if ($max < 1) $max = 5;
$sort = $Config->get('processes:sort');
if ( $sort !== 'mem' ) $sort = 'cpu';

$include = $Config->get('processes:include');
$exclude = $Config->get('processes:exclude');
array_push($exclude,'\[.*\]','\\_');
$exclude = '\('.implode('\|',$exclude).'\)';

$command = 'ps xf -o "pcpu,pmem,args" --noheader';
if ($exclude)
    $command .= ' | grep -v "'.$exclude.'"';
$command .= ' | sed -e "s|^\s||" -e "s|\s\+|,|g" | cut -d "," -f 1-3 | sed "s|/.*/||"';

print_r() $command."\n" );

if (!(exec($command, $ps))) {
    $datas[] = array(
        'cpu'     => 0,
        'mem'     => 0,
        'process' => 'N.A' );
} else {
    $datas = $ps;
    // $processes = array();
    // $inc_processes = array();
    // $cores = Misc::getCPUData('cores');
    // if ($cores < 1) $cores = 1;

    // foreach ($ps as $line) {
    //     list($cpu, $mem, $process) = explode(',', $line);

    //     $tmp =  array( 'cpu'      => $cpu/$cores,
    //                    'mem'      => $mem,
    //                    'process'  => $process );  

    //     if (in_array($process, $include))
    //         array_push($inc_processes, $tmp);
    //     else
    //         array_push($processes, $tmp);
    // }

    // $datas = array_merge($inc_processes, $processes);
    // // $datas = array_slice(array_merge($inc_processes, $processes),0,$max);

}

print_r( json_encode($datas) );