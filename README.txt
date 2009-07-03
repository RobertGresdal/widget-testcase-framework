SETUP INSTRUCTIONS

1. Setup apache to point to the '/public' directory of the application. 

Example:

<VirtualHost 127.0.0.1>
   ServerName wtf.example.com
   DocumentRoot "C:/xampp/htdocs/wtf/public"
</VirtualHost>

2. Edit the configuration file, application/config/app.ini, and set the directory for where the script can find the widget testcases. 

3. Make sure apache has loaded mod_rewrite.so. If you installed php using the release from Apachefriends, the default setting is mod_rewrite disabled. You will find the setting for loading this in httpd.conf.
