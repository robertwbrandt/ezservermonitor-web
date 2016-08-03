esm.cpu = function() {

    var module = 'cpu';
    
    esm.reloadBlock_spin(module);

    $.get('plugins/'+module+'/'+module+'.php', function(data) {

        var $box = $('.box#esm-'+module+' .box-content tbody');

        esm.insertDatas($box, module, data);

        esm.reloadBlock_spin(module);

    }, 'json');

}
