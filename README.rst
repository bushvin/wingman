Simple Invoicing
================

This is an attemt at writing a webapp to allow simple invoicing.


Installation
------------
1. Point the webroot of your virtual host to the htdocs directory
2. Create a Mysql DB and import install/core.sql and install/default_customer.sql into it, replacing ##_ with a prefix of your liking
   
   eg sed -i 's/\#\#_/wmn_/g' install/*.sql
3. Edit the configuration file to your liking (lib/configuration.php)
4. Point your webbrowser to the root of your virtual host
   username: root
   password: root



