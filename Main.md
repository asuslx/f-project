# Introduction #

F is a fast, simple php MVC framework.
It may be used for develop advanced web applications.
It is light and easy to use.
It's KISS.


# Deploy new App #

It is in few simple steps.

  * Download and extract http://code.google.com/p/f-project/downloads/detail?name=f-skeleton.tar.gz
  * Rename f-skeleton directory as u wish (it's your new project)
  * make web server settings (app root is "www" subdirectory)
Apache example:

```

<VirtualHost *:80>
DocumentRoot "/webhome/myapp/www"
ServerName myapp.local
ServerAlias www.myapp.local


Unknown end tag for &lt;/VirtualHost&gt;


```
  * checkout F and place it as u wish. Recently in php include path or near project directory. Directory must be named "F".
```

svn checkout http://f-project.googlecode.com/svn/trunk/ F
```
  * make sure that application use correct F location (in myapp/www/index.php)
```

include_once "../../F/App.php";
include_once "../cfg/main.php";

F_App::instance()->run();```

  * Run F app http:://myapp.local in this case