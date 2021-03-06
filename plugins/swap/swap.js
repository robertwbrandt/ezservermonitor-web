esm.swap = function() {

    var module = 'swap';
    
    esm.reloadBlock_spin(module);
    $.get('plugins/'+module+'/'+module+'.json.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');

        esm.insertDatas($box, module, data);

        // Percent bar
        var $progress = $('.progressbar', $box);

        $progress
            .css('width', data.percent_used+'%')
            .html(data.percent_used+'%')
            .removeClass('green orange red');

        if (data.percent_used <= 50)
            $progress.addClass('green');
        else if (data.percent_used <= 75)
            $progress.addClass('orange');
        else
            $progress.addClass('red');
    
        esm.reloadBlock_spin(module);
    }, 'json');
}
