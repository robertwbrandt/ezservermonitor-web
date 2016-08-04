<?php
require '../../autoload.php';
$Config = new Config();

$datas = array();

$max = intval($Config->get('processes:max'));
if ($max < 1) $max = 5;
$sort = $Config->get('processes:sort');
if ( $sort !== 'mem' ) $sort = 'cpu';

$include = $Config->get('processes:include');
if ($exclude = $Config->get('processes:exclude'))
    $exclude = '\('.implode('\|',$exclude).'\)';

$command = 'ps xf -eo "pcpu,pmem,args" --noheader';
$command = 'ps -eo "pcpu,pmem,args" --noheader';
if ($exclude)
    $command .= ' | grep -v "'.$exclude.'"';
// Exclude sub-processes
$command .= ' | grep -v "\\_"';
// Convert spaces to commas
$command .= ' | sed -e "s|\[| |" -e "s|\]| |" -e "s|^\s||" -e "s|\s\+|,|g" | cut -d "," -f 1-3';
// Cleanup unruley process names
$command .= ' | sed  -e "s|\.*/|/|" -e "s|/.*/||" -e "s|,/|,|" -e "s|/.*||"';

if (!(exec($command, $ps))) {
    $datas[] = array(
        'cpu'     => 0.0,
        'mem'     => 0.0,
        'process' => 'N.A' );
} else {
    $all_procs = array(); $all = 0;
    $inc_procs = array(); $inc = 0;
    $cores = floatval(Misc::getCPUData('cores'));
    if ($cores < 1.0) $cores = 1.0;

    foreach ($ps as $line) {
        list($cpu, $mem, $process) = explode(',', $line);

        $cpu = floatval($cpu)/$cores;
        $mem = floatval($mem);

        if (in_array($process, $include)) {
            if ( ($key = Misc::multiarray_search($inc_procs,'process',$process)) !== False) {
                $inc_procs[$key]['cpu'] += $cpu;
                $inc_procs[$key]['mem'] += $mem;
                $inc_procs[$key]['count']++;
            } else {
                $inc_procs[] = array( 'process' => $process, 'cpu' => $cpu, 'mem' => $mem, 'count' => 1 );
            }
        } else {
            if ( ($key = Misc::multiarray_search($all_procs,'process',$process)) !== False) {
                $all_procs[$key]['cpu'] += $cpu;
                $all_procs[$key]['mem'] += $mem;
                $all_procs[$key]['count']++;                
            } else {
                $all_procs[] = array( 'process' => $process, 'cpu' => $cpu, 'mem' => $mem, 'count' => 1 );                
            }
        }
    }
    // var_dump($all_procs);
    usort($inc_procs, function($a, $b) { global $sort; return $b[$sort] - $a[$sort]; });
    $datas = array_slice($inc_procs,0,$max);
    if (count($datas) < $max) {
        usort($all_procs, function($a, $b) { global $sort; return $b[$sort] - $a[$sort]; });
        $datas = array_merge($datas, array_slice($all_procs,0,$max-count($datas)));
        usort($datas, function($a, $b) { global $sort; return $b[$sort] - $a[$sort]; });
    }
}

echo json_encode($datas, JSON_PRETTY_PRINT);
