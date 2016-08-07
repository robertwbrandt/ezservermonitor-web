esm.last_login = function() {

    var module = 'last_login';
    
    esm.reloadBlock_spin(module);
    $.get('plugins/'+module+'/'+module+'.json.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');

        $box.empty();

        for (var line in data)
        {
            var html = '';
            html += '<tr>';
            html += '<td>'+data[line].user+'</td>';
            html += '<td class="w50p">'+data[line].date+'</td>';
            html += '</tr>';

            $box.append(html);
        }
    
        esm.reloadBlock_spin(module);
    }, 'json');
}
