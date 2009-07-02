SETUP INSTRUCTIONS

1. Make sure apache has loaded mod_rewrite.so. If you installed php using the release from Apachefriends, the default setting is mod_rewrite disabled. You will find the setting for loading this in httpd.conf.

2. Edit the configuration file, application/config/app.ini, and set the directory for where the script can find the widget testcases. 

3. Download zend.zip and smarty.zip from the project page and extract them in the library directory. The archive should create the required folders, so if you are using 7-zip, Winzip or others, choose "Extract Here" and not "Extract to '*\'". 

4. Setup apache to point to the '/public' directory. Example:
<VirtualHost 127.0.0.1>
   ServerName wtf.example.com
   DocumentRoot "C:/xampp/htdocs/wtf/public"
</VirtualHost>

TODO: Point 1 and 2 should be detected on first run and a setup screen should guide the user.
TODO: Point 3 should be automated.
