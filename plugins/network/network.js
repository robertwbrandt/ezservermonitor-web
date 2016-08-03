esm.network = function() {

    var module = 'network';
    
    esm.reloadBlock_spin(module);
    $.get('plugins/'+module+'/'+module+'.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');

        $box.empty();

        for (var line in data)
        {
            var html = '';
            html += '<tr>';
            html += '<td>'+data[line].interface+'</td>';
            html += '<td>'+data[line].ip+'</td>';
            html += '<td class="t-center">'+data[line].receive+'</td>';
            html += '<td class="t-center">'+data[line].transmit+'</td>';
            html += '</tr>';

            $box.append(html);
        }

        esm.reloadBlock_spin(module);
    }, 'json');
}