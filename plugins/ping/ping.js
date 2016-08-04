esm.ping = function() {

    var module = 'ping';
    
    esm.reloadBlock_spin(module);
    $.get('plugins/'+module+'/'+module+'.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');


        $box.empty();

        for (var line in data)
        {
            var html = '';
            html += '<tr>';
            html += '<td>'+data[line].host+'</td>';
            html += '<td>'+data[line].ping+' ms</td>';
            html += '</tr>';

            $box.append(html);
        }
    
        esm.reloadBlock_spin(module);
    }, 'json');
}

