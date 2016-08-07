esm.processes = function()
{
    var module = 'processes';
    
    esm.reloadBlock_spin(module);

    $.get('plugins/'+module+'/'+module+'.json.php', function(data) {

        var $box = $('.box#esm-'+module+' .box-content tbody');
        $box.empty();

        for (var line in data)
        {
            var cpu_class = 'red';
            if (data[line].cpu <= 50)
                cpu_class = 'green';
            else if (data[line].cpu <= 75)
                cpu_class = 'orange';
            
            var mem_class = 'red';
            if (data[line].mem <= 50)
                mem_class = 'green';
            else if (data[line].mem <= 75)
                mem_class = 'orange';

            var html = '';
            html += '<tr>';
            html += '<td>'+data[line].process;
            if (data[line].count > 1)
                html += ' ('+data[line].count+')</td>';
            html += '<td><div class="progressbar-wrap"><div class="progressbar '+cpu_class+'" style="width: '+data[line].cpu+'%;">'+data[line].cpu+'%</div></div></td>';
            html += '<td><div class="progressbar-wrap"><div class="progressbar '+mem_class+'" style="width: '+data[line].mem+'%;">'+data[line].mem+'%</div></div></td>';
            html += '</tr>';

            $box.append(html);
        }
    
        esm.reloadBlock_spin(module);

    }, 'json');

}