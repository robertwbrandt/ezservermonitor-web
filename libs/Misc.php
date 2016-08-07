<?php
class Misc
{
    /**
     * Returns human size
     *
     * @param  float $filesize   File size
     * @param  int   $precision  Number of decimals
     * @return string            Human size
     */
    public static function getSize($filesize, $precision = 2)
    {
        $units = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
        foreach ($units as $idUnit => $unit)
        {
            if ($filesize > 1024)
                $filesize /= 1024;
            else
                break;
        }
        
        return round($filesize, $precision).' '.$units[$idUnit].'B';
    }
    
    
    /**
     * Returns hostname
     *
     * @return  string  Hostname
     */
    public static function getHostname()
    {
        return php_uname('n');
    }
    /**
     * Returns server IP
     *
     * @return string Server local IP
     */
    public static function getLanIp()
    {
        return $_SERVER['SERVER_ADDR'];
    }
    /**
     * Seconds to human readable text
     * Eg: for 36545627 seconds => 1 year, 57 days, 23 hours and 33 minutes
     * 
     * @return string Text
     */
    public static function getHumanTime($seconds)
    {
        $units = array(
            'year'   => 365*86400,
            'day'    => 86400,
            'hour'   => 3600,
            'minute' => 60,
            // 'second' => 1,
        );
     
        $parts = array();
     
        foreach ($units as $name => $divisor)
        {
            $div = floor($seconds / $divisor);
     
            if ($div == 0)
                continue;
            else
                if ($div == 1)
                    $parts[] = $div.' '.$name;
                else
                    $parts[] = $div.' '.$name.'s';
            $seconds %= $divisor;
        }
     
        $last = array_pop($parts);
     
        if (empty($parts))
            return $last;
        else
            return join(', ', $parts).' and '.$last;
    }
    /**
     * Returns a command that exists in the system among $cmds
     *
     * @param  array  $cmds             List of commands
     * @param  string $args             List of arguments (optional)
     * @param  bool   $returnWithArgs   If true, returns command with the arguments
     * @return string                   Command
     */
    public static function whichCommand($cmds, $args = '', $returnWithArgs = true)
    {
        $return = '';
        foreach ($cmds as $cmd)
        {
            if (trim(shell_exec($cmd.$args)) != '')
            {
                $return = $cmd;
                
                if ($returnWithArgs)
                    $return .= $args;
                break;
            }
        }
        return $return;
    }
    /**
     * Allows to pluralize a word based on a number
     * Ex : echo 'mot'.Misc::pluralize(5); ==> prints mots
     * Ex : echo 'cheva'.Misc::pluralize(5, 'ux', 'l'); ==> prints chevaux
     * Ex : echo 'cheva'.Misc::pluralize(1, 'ux', 'l'); ==> prints cheval
     * 
     * @param  int       $nb         Number
     * @param  string    $plural     String for plural word
     * @param  string    $singular   String for singular word
     * @return string                String pluralized
     */
    public static function pluralize($nb, $plural = 's', $singular = '')
    {
        return $nb > 1 ? $plural : $singular;
    }
    /**
     * Checks if a port is open (TCP or UPD)
     *
     * @param  string   $host       Host to check
     * @param  int      $port       Port number
     * @param  string   $protocol   tcp or udp
     * @param  integer  $timeout    Timeout
     * @return bool                 True if the port is open else false
     */
    public static function scanPort($host, $port, $protocol = 'tcp', $timeout = 3)
    {
        if ($protocol == 'tcp')
        {
            $handle = @fsockopen($host, $port, $errno, $errstr, $timeout);
            if (!$handle)
            {
                return false;
            }
            else
            {
                fclose($handle);
                return true;
            }
        }
        elseif ($protocol == 'udp')
        {
            $handle = @fsockopen('udp://'.$host, $port, $errno, $errstr, $timeout);
            socket_set_timeout($handle, $timeout);
            $write = fwrite($handle, 'x00');
            $startTime = time();
            $header = fread($handle, 1);
            $endTime = time();
            $timeDiff = $endTime - $startTime; 
            
            fclose($handle);
            if ($timeDiff >= $timeout)
                return true;
            else
                return false;
        }
        return false;
    }
    /**
     * Returns Operating System
     *
     * @return  string  Operating System
     */
    public static function getOS()
    {
        // OS
        if (!($os = shell_exec('/usr/bin/lsb_release -ds | cut -d= -f2 | tr -d \'"\'')))
        {
            if (!($os = shell_exec('cat /etc/system-release | cut -d= -f2 | tr -d \'"\'')))
            {
                if (!($os = shell_exec('cat /etc/os-release | grep PRETTY_NAME | tail -n 1 | cut -d= -f2 | tr -d \'"\'')))
                {
                    if (!($os = shell_exec('find /etc/*-release -type f -exec cat {} \; | grep PRETTY_NAME | tail -n 1 | cut -d= -f2 | tr -d \'"\'')))
                    {
                        $os = 'N.A';
                    }
                }
            }
        }
        $os = trim($os, '"');
        return str_replace("\n", '', $os);
    }
    /**
     * Returns Release name (Kernel version)
     *
     * @return  string  Release name
     */
    public static function getRelease()
    {
        return php_uname('r');
    }
    /**
     * Returns Version information
     *
     * @return  string  Version information
     */
    public static function getVersion()
    {
        return php_uname('v');
    }
    /**
     * Returns Machine type
     *
     * @return  string  Machine type
     */
    public static function getMachineType()
    {
        return php_uname('m');
    }
    /**
     * Returns Server Uptime
     *
     * @return  string  Server Uptime
     */
    public static function getUpTime()
    {
        if (!($totalSeconds = shell_exec('/usr/bin/cut -d. -f1 /proc/uptime')))
            $uptime = 'N.A';
        else
            $uptime = Misc::getHumanTime($totalSeconds);
        return $uptime;
    }
    /**
     * Returns Server Last Boot Time
     *
     * @return  string  Server Last Boot Time
     */
    public static function getBootTime()
    {
        if (!($upt_tmp = shell_exec('cat /proc/uptime')))
            $last_boot = 'N.A';
        else
            $last_boot = date('Y-m-d H:i:s', time() - intval(explode(' ', $upt_tmp)[0]));
        return $last_boot;
    }
    /**
     * Returns Server Current Users
     *
     * @return  string  Current Users
     */
    public static function getCurrentUsers()
    {
        if (!($current_users = shell_exec('who -u | wc -l')))
            $current_users = 'N.A';
        return $current_users;
    }
    /**
     * Returns Server Current Date
     *
     * @return  string  Current Date
     */
    public static function getCurrentDate()
    {
        if (!($server_date = shell_exec('/bin/date')))
            $server_date = date('Y-m-d H:i:s');
        return $server_date;
    }
    /**
     * Returns Server CPU Data
     *
     * @param   string  $parameter    CPU Parameter ( cores|model|frequency|cache|bogomips|temperature|all )
     * @return  string                CPU Data
     */
    public static function getCPUData($parameter = 'all')
    {
        $cpu_data = array(
            'model'       => 'N.A',
            'cores'       => 0,
            'frequency'   => 'N.A',
            'cache'       => 'N.A',
            'bogomips'    => 'N.A',
            'temperature' => 'N.A' );

        if ($cpuinfo = shell_exec('cat /proc/cpuinfo'))
            foreach (preg_split('/\s?\n\s?\n/', trim($cpuinfo)) as $processor)
                foreach (preg_split('/\n/', $processor, -1, PREG_SPLIT_NO_EMPTY) as $detail)
                {
                    list($key, $value) = preg_split('/\s*:\s*/', trim($detail));
                    switch (strtolower($key))
                    {
                        case 'processor':
                            $cpu_data['model'] = $value;
                            $cpu_data['cores'] += 1;
                        break;

                        case 'model name':
                        case 'cpu model':
                        case 'cpu':
                            $cpu_data['model'] = $value;
                        break;


                        case 'cpu mhz':
                        case 'clock':
                            $cpu_data['frequency'] = $value.' MHz';
                        break;

                        case 'cache size':
                        case 'l2 cache':
                            $cpu_data['cache'] = $value;
                        break;

                        case 'bogomips':
                            $cpu_data['bogomips'] = $value;
                        break;
                    }
                }

        if ((($parameter == 'all') or ($parameter == 'cores')) and ($cpu_data['cores'] <= 0))
            if (!($cpu_data['cores'] = trim(shell_exec('/usr/bin/nproc'))))
                $cpu_data['cores'] = 1;
            if ((int)$cpu_data['cores'] <= 0)
                $cpu_data['cores'] = 1;

        if ((($parameter == 'all') or ($parameter == 'frequency')) and ($cpu_data['frequency'] == 'N.A'))
            if ($f = shell_exec('cat /sys/devices/system/cpu/cpu0/cpufreq/cpuinfo_max_freq'))
                $cpu_data['frequency'] = ($f / 1000).' MHz';

        if (($parameter == 'all') or ($parameter == 'temperature'))
            if (exec('/usr/bin/sensors | grep -E "^(CPU Temp|Core 0)" | cut -d \'+\' -f2 | cut -d \'.\' -f1', $t))
                if (isset($t[0]))
                    $cpu_data['temperature'] = $t[0].' °C';
            else
                if (exec('cat /sys/class/thermal/thermal_zone0/temp', $t))
                    $cpu_data['temperature'] = round($t[0] / 1000).' °C';

        if ($parameter == 'all') 
            return $cpu_data;
        else
            return $cpu_data[$parameter];
    }

}
