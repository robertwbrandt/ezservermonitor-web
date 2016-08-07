esm.deamons = function() {

// I used the following sysv return code specifications from LSB 3.1 
// Linux Standard Base Core Specification 3.1
// If the status action is requested, the init script will return the following exit status codes. 
//  0       program is running or service is OK
//  1       program is dead and /var/run pid file exists
//  2       program is dead and /var/lock lock file exists
//  3       program is not running
//  4       program or service status is unknown
//  5-99    reserved for future LSB use
//  100-149 reserved for distribution use
//  150-199 reserved for application use
//  200-254 reserved 
// However since 1 is generally used for as a genric "Not Running" I will count that the same as 3




    var module = 'deamons';
    
    esm.reloadBlock_spin(module);
    $.get('plugins/'+module+'/'+module+'.json.php', function(data) {
        var $box = $('.box#esm-'+module+' .box-content tbody');

        $box.empty();

        for (var line in data)
        {
            var label_color  = 'warning'; //orange
            var label_status = 'unknown';

            switch(data[line].status) {
                case 0:
                    label_color  = 'success'; //green
                    label_status = 'running';
                    break;
                case 1:
                case 3:
                    label_color  = 'error'; //red
                    label_status = 'not running';
                    break;
                case 2:
                    label_color  = 'error'; //red
                    label_status = 'dead';
                    break;
                case 4:
                    label_color  = 'error'; //red
                    break;
            } 

            var html = '';
            html += '<tr>';
            html += '<td class="w15p"><span class="label '+label_color+'">'+label_status+'</span></td>';
            html += '<td>'+data[line].name+'</td>';
            html += '</tr>';

            $box.append(html);
        }
    
        esm.reloadBlock_spin(module);
    }, 'json');
}