esm.system = function() {

    var module = 'system';
    
    esm.reloadBlock_spin(module);
    $.get('plugins/'+module+'/'+module+'.json.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');

        esm.insertDatas($box, module, data);

        esm.reloadBlock_spin(module);
    }, 'json');
}