<?php

function eSMAutoload($class)
{
    include __DIR__.'/libs/'.$class.'.php';
}

spl_autoload_register('eSMAutoload');