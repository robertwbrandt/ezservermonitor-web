[eZ Server Monitor](http://www.ezservermonitor.com) Out of the box, eSM Web works perfectly but you can configure some modules. To do this, open the esm.config.json file located in the conf/ folder at the root of the project with a text editor.

[Template](http://twig.sensiolabs.org/)Eventually we want to enable Twig-inspired template functions when {{ variable }} {% HTML TAG/FORMAT %}


- **General** : In this section, you can define the following parameters:
If the script will automatically checks if a new version is available. Default is false.
{ ...
    "esm": { "check_updates": true },
... }

Set the auto_refresh setting to enable the auto refresh of the page (in seconds). Default value is 0. (no refresh)
{ ...
    "esm": { "auto_refresh": 300 },
... }

Set the theme option to switch between different colors. Default is blue.
{
...
    "esm": {
        "theme": "green"
    },
...
}
Available themes :
    blue (default)
    aqua
    green
    light-green
    orange
    red
    light-red
    purple
    slate
    light
    dark

Change the title in the header block. Default is the hostname with LAN IP.
{ ...
    "esm": { "custom_title": "John Doe's server" },
... }

Use a custom favicon. Default is the eZ Server Monitor Gauge favicon
{ ...
    "esm": { "favicon": "/favicon.ico", },
... }

Use a custom logo.
{ ...
    "esm": { "logo": "/images/logo.gif",
    		 "logo_text": "Company Name",
    		 "logo_href": "http://www.companyhome.page/" },
    		 "sublogo_text": "eZ Server Monitor - v{{ config.esm.version }}",
    		 "sublogo_href": "{{ config.esm.website }}"
... }
The logo should have transparency and will be restricted to 30x30 px
The default is the eZ Server Monitor Gauge logo, text and the self page.
The default sub-logo text is shown above.










- **CPU** : In this section, you can define the following CPU parameters:
Show or hide the CPU temperature. Default is true.
{ ...
    "cpu": { "enable_temperature": true },
... }
If you have "N.A" in Temperature section of CPU block, install lm-sensors and detect the sensors with sensors-detect.
This may not work on all computers.


- **Disk usage** : In this section, you can define the following Disk parameters:
Define to show or hide the virtual mountpoints (tmpfs). Default is true
{ ...
    "disk": { "show_tmpfs": false },
... }

By default, the filesystem is displayed for each mounted points. You can disable it with show_filesystem option. Default is true.
{ ...
    "disk": { "show_filesystem": false },
... }


- **Ping** : In this section, you can ping the hosts defined in the configuration file
Define the addresses of websites to ping.
{ ...
    "ping": { "hosts": [
              "free.fr",
              "orange.fr",
              "google.com" ] },
... }


- **Last login** : In this section, you can define the following Last Login parameters:
You can disable this module with the enable option. Default is true.
{ ...
    "last_login": { "enable": false },
... }

You can define the maximum of last login entries to show. Default is 5.
{ ...
    "last_login": { "max": 5 },
... }


- **Services** : In this section, you can define the following services parameters:
You can also change the services for which eSM must check the status :
{ ...
    "services": {
        "show_port": true,
        "list": [
            {   "name": "Web Server (Apache)",
                "host": "localhost",
                "port": 80,
                "protocol": "tcp" },
            {   "name": "FTP Server (ProFTPd)",
                "host": "localhost",
                "port": 21,
                "protocol": "tcp" },
            {   "name": "Databases (MySQL)",
                "host": "localhost",
                "port": 3306,
                "protocol": "tcp" },
            {   "name": "SSH",
                "host": "localhost",
                "port": 22,
                "protocol": "tcp" }
        ]
    }
... }
    show_port : if true, the port number will be shown next to the name
    name : name displayed for this service
    host : host name on which the service check
    port : port number of the service to check
    protocol : tcp or udp
Non-exhaustive list of services / port :
    FTP : 21
    SSH : 22
    Telnet : 23
    SMTP : 25
    DNS : 53
    Web : 80
    Kerberos : 88
    POP3 : 110
    SFTP : 115
    NNTP : 119
    NTP : 123
    SAMBA (SMB : 139
    IMAP : 143
    SNMP : 161
    LDAP : 389
    HTTPS : 443
    MySQL : 3306


