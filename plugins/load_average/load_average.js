esm.load_average = function() {

    var module = 'load_average';
    
    esm.reloadBlock_spin(module);
    $.get('plugins/'+module+'/'+module+'.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content');

        esm.reconfigureGauge($('input#load-average_1', $box), data[0]);
        esm.reconfigureGauge($('input#load-average_5', $box), data[1]);
        esm.reconfigureGauge($('input#load-average_15', $box), data[2]);

        esm.reloadBlock_spin(module);
    }, 'json');
}