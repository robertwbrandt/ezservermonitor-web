<?php
header('Content-type: text/javascript');
?>
var esm = {};

<?php
require '../autoload.php';
$Config = new Config();
foreach ($Config->getPluginNames() as $plugin) {
    if (($filename = $Config->plugins[$plugin]['config']['js']) !== null)
        require __DIR__.'/../plugins/'.$plugin.'/'.$filename;
	echo "\n\n";
}
?>

esm.reloadBlock = function(block) {

    esm.mapping[block]();

}

esm.reloadBlock_spin = function(block) {

    var $module = $('.box#esm-'+block);

    $('.reload', $module).toggleClass('spin disabled');
    $('.box-content', $module).toggleClass('faded');

}

esm.insertDatas = function($box, block, datas) {
    for (var item in datas)
    {
        $('#'+block+'-'+item, $box).html(datas[item]);
    }
}

esm.reconfigureGauge = function($gauge, newValue) {
    // Change colors according to the percentages
    var colors = { green : '#7BCE6C', orange : '#E3BB80', red : '#CF6B6B' };
    var color  = '';

    if (newValue <= 50)
        color = colors.green;
    else if (newValue <= 75)
        color = colors.orange;
    else
        color = colors.red;

    $gauge.trigger('configure', { 
        'fgColor': color,
        'inputColor': color,
        'fontWeight': 'normal',
        'format' : function (value) {
            return value + '%';
        }
    });

    // Change gauge value
    $gauge.val(newValue).trigger('change');
}

<?php
echo "esm.all = function() {\n";
foreach ($Config->getPluginNames() as $plugin)
    foreach ($Config->getPluginFunctions($plugin) as $function)
        echo "\t".$function."();\n";
echo "}\n";
?>

<?php
echo "esm.mapping = {\n\tall: esm.all";
foreach ($Config->getPluginNames() as $plugin)
    foreach ($Config->getPluginFunctions($plugin) as $function)
        echo ",\n\t".$plugin.": ".$function;
echo "\n}\n";
?>
