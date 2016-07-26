<?php

class Config
{
    public $default = null;
    public $config = null;


    public function __construct()
    {
        $this->_checkPHPVersion(5.3);
        $this->default = $this->_readFile( __DIR__.'/../../conf/esm.default.json' );
        $this->config = $this->_readFile( __DIR__.'/../../conf/esm.config.json' );
    }


    private function _readFile($file)
    {
        if (!file_exists($file))
            throw new \Exception('Config file '.basename($file).' not found');

        $content = file_get_contents($file);
        $temp = json_decode(utf8_encode($content), true);
        if ($temp == null && json_last_error() != JSON_ERROR_NONE)
            throw new \LogicException(sprintf("Failed to parse config file '%s'. Error: '%s'", basename($file) , json_last_error_msg()));
        return $temp;
    }


    private function _get($var, $file = 'config')
    {
        $tab = $file == 'config' ? $this->config : $this->default;
        
        $explode = explode(':', $var);
        
        foreach ($explode as $vartmp)
        {
            if (isset($tab[$vartmp]))
                $tab = $tab[$vartmp];
            else
                return null;
        }
        return $tab == $this->config ? null : $tab;
    }


    /**
     * Returns a specific config variable
     * Ex : get('ping:hosts')
     */
    public function get($var)
    {
        $tab = $this->_get($var,'config');
        if ($tab == null)
            $tab = $this->_get($var,'default');
        return $tab;
    }

    
    /**
     * Returns all config variables
     */
    public function getAll()
    {
        return array_merge($this->default,$this->config);
    }


    /**
     * Checks the PHP version compared to the required version
     */
    private function _checkPHPVersion($min)
    {
        if (!version_compare(phpversion(), $min, '>='))
            throw new \Exception('Your PHP version is too old ! PHP '.$min.' is required.');

        return true;
    }


    /**
     * Checks if there is an eSM`Web update available
     */
    public function checkUpdate()
    {
        if ($this->get('esm:check_updates') === false)
            return null;
        
        $response       = null;
        $this_version   = $this->get('esm:version');
        $update_url     = $this->get('esm:website').'/esm-web/update/'.$this_version;

        if (!function_exists('curl_version'))
        {
            $tmp = @file_get_contents($update_url);
            $response = json_decode($tmp, true);
        }
        else
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_CONNECTTIMEOUT  => 10,
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_SSL_VERIFYPEER  => false,
                CURLOPT_TIMEOUT         => 60,
                CURLOPT_USERAGENT       => 'eZ Server Monitor `Web',
                CURLOPT_URL             => $update_url,
            ));

            $response = json_decode(curl_exec($curl), true);

            curl_close($curl);
        }

        if (!is_null($response) && !empty($response))
        {
            if (is_null($response['error']))
            {
                return $response['datas'];
            }
        }
    }


    /**
     * Formats the string before it is written for HTML
     *
     * @param  string   $string  config variable
     * @param  array    $string  array containing previous config variable (to check for infinite recursion)
     * @return string            formatted string
     */
    public function format($var, $recursion = array())
    {
        // if (in_array($var, $recursion))
        //     throw new \Exception('Infinite recursion detected in configurations: ' . $var . '->' . implode('->', array_reverse($recursion)));
        array_push($recursion, $var);

        $patterns = array();
        $replacements = array();        
        $pattern[0] = '/({{.*?}})/';
        $pattern[1] = '/({%.*?%})/';
        $replacement[0] = "\x0" . '${1}' . "\x0";
        $replacement[1] = "\x0" . '${1}' . "\x0";
        $tmp = preg_replace( $pattern, $replacement, $this->get($var) );

        $output = '';
        $count = 0;

        foreach ( explode("\x0", $tmp) as $line) {
            if ($line) {
                if (( substr($line, 0, 2) == "{{") and (substr($line, -2) == "}}" )) {
                    $line = explode(":",substr($line, 2, -2));
                    if ($line[0] == "env") {
                        $line = getenv($line[1]);
                    } elseif ($line[0] == "esm") {
                        switch ($line[1]) {
                        case 'ip':
                            $line = Misc::getLanIp();
                            break;
                        case 'hostname':
                            $line = Misc::getHostname();
                            break;
                        case 'os':
                            $line = Misc::getOS();
                            break;
                        case 'release':
                            $line = Misc::getRelease();
                            break;
                        case 'kernelversion':
                            $line = Misc::getVersion();
                            break;
                        case 'machinetype':
                            $line = Misc::getMachineType();
                            break;
                        case 'uptime':
                            $line = Misc::getUpTime();
                            break;
                        case 'boottime':
                            $line = Misc::getBootTime();
                            break;
                        case 'currentusers':
                            $line = Misc::getCurrentUsers();
                            break;
                        case 'currentdate':
                            $line = Misc::getCurrentDate();
                            break;
                        case 'cpu':
                            switch ($line[2]) {
                            case 'cores':
                            case 'model':
                            case 'frequency':
                            case 'cache':
                            case 'bogomips':
                            case 'temperature':
                                $line = Misc::getCPUData($line[2]);
                            }
                            break;
                        default:
                            $line = implode(":", $line);
                            array_push($recursion, $line);
                            $line = $this->format($line, $recursion);
                        }
                    } else {
                        $line = implode(":", $line);
                        array_push($recursion, $line);
                        $line = $this->format($line, $recursion);
                    }
                    $line = htmlentities($line);
                } elseif (( substr($line, 0, 2) == "{%") and (substr($line, -2) == "%}" )) {
                    $line = substr($line, 2, -2);
                }

                $output .= strval($line);
        
                // $output .= strval($count) . "=" . strval($line) . "|";
                // $count += 1;
            }
        }
        return $output;
    }    
}


// PHP 5.5.0
if (!function_exists('json_last_error_msg'))
{
    function json_last_error_msg()
    {
        static $errors = array(
            JSON_ERROR_NONE             => null,
            JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH   => 'Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR        => 'Unexpected control character found',
            JSON_ERROR_SYNTAX           => 'Syntax error, malformed JSON',
            JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded'
        );
        $error = json_last_error();
        return array_key_exists($error, $errors) ? $errors[$error] : "Unknown error ({$error})";
    }
}