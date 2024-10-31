#!/bin/bash

name=$1
WEB_ROOT_DIR='/var/www/prodapp/public'

email=${3-'webmaster@localhost'}
sitesAvailable='/etc/apache2/sites-available/'
sitesAvailabledomain=$sitesAvailable$name.conf

### create virtual host rules file
echo "
<VirtualHost *:80>
    ServerAdmin $email
    ServerName $name
    DocumentRoot $WEB_ROOT_DIR
    
    <Directory />
        Options FollowSymLinks
        AllowOverride None
    </Directory>

    <Directory $WEB_ROOT_DIR>
	    Options Indexes FollowSymLinks MultiViews
	    AllowOverride All
	    Order deny,allow
	    allow from all
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    LogLevel warn
    CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>" > $sitesAvailabledomain

a2ensite $name
service apache2 reload
