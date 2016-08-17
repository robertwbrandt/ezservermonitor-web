esm.cpu = function(module = 'cpu', plugin = 'cpu') {

    // var module = 'cpu';

    esm.reloadBlock_spin(module);
    $.get('plugins/'+plugin+'/'+plugin+'.json.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');

        esm.insertDatas($box, module, data);

        esm.reloadBlock_spin(module);
    }, 'json');
}
