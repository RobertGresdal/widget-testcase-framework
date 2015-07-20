# Setup #
<ol>
<li>Checkout the project and put it somewhere Apache has read access. </li>
<li>It is recommended to add a virtual host pointing to the '/public' directory of the application.<br>
<br>
Example:<br>
<pre><code>&lt;VirtualHost 127.0.0.1&gt;<br>
   ServerName   wtf.localhost<br>
   DocumentRoot "C:/xampp/htdocs/wtf/public"<br>
&lt;/VirtualHost&gt;<br>
</code></pre>
You also need to edit your hosts file. In Windows, this file is located at "c:\windows\system32\drivers\etc\hosts". Add the following line:<br>
<pre><code>127.0.0.1   wtf.localhost <br>
</code></pre>
</li>
<li>Edit the configuration file, "application/config/app.ini", and set the directory for where the script can find the widget testcases. The default directory points to "data/testcases", which already contains a few examples.<br>
</li>
<li>Make sure Apache has loaded mod_rewrite.so. If you installed php using the release from Apachefriends, the default setting is that mod_rewrite is disabled. You will find the setting for loading this in httpd.conf.<br>
</li>
<li>Now you can open <a href='http://wtf.localhost'>http://wtf.localhost</a> in your browser.</li>
</ol>


# Linux #

Linux is case sensitive, so you might need to run for example `ln -s zend/ Zend`.

~~hg cannot store empty directories, so you need to add some missing directores manually~~ _Fixed in revision 667a5103ff_

You might have issues with the `.htaccess`. This is the version running on http://wtf.webvm.net/

```
	Options +FollowSymLinks

	RewriteEngine On
	RewriteCond $1 !\.(gif|jpe?g|png)$ [NC]
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(.*)$ /index.php/$1 [L] 
```