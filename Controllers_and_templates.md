# Introduction #

Every MVC web-application work with controllers.

In **"F"** Controllers implemented by class **F\_App\_Controller**.

Every request to a **"F"** web application processed by one of it's controller.

By default **main controller** located in **<project dir>/code/Ctrl.php**

# Processing requests #

Every controller must implement abstact method **`_initialize()`** and one or more **actions**.
Action must be named strictly, depend on processing URL.

For example we create apprication what can process two URLs:

  * http://test.local/
  * http://test.local/second/

in code/Ctrl.php we shoud write:
```
class Ctrl extends F_App_Controller {

    protected $params = array();

    function _initialize() {
        // called before any action processed
    }

    function actionIndex($params) {
        // called when http://test.local/
    }

    function actionSecond($params) {
        // called when http://test.local/second
    }
}

```

# Controller templates #

Every controller **must have templates for every** it's action.

If we does not need in template we can use empty templates.

Templates for controlles locates by default in **<project dir>/tmpl/** directory.

For controller below we need to create two template files:

  * **<project dir>/tmpl/Ctrl/Index.html**
  * **<project dir>/tmpl/Ctrl/Second.html**

We can transfer data from controller to it's template,  and show it templated.

By default **F Application** use **F\_App\_Template\_Native** class.

In **F** template objects for controllers creating by system, and we does not need access them directly.

For assigning template variables we must use **` _assign() `** protected method of controller:

```
class Ctrl extends F_App_Controller {

    ...

    function actionIndex($params) {
        
       // some code defining $var1 and $var2
       $this->_assign("first", $var1);
       $this->_assign("second", $var2);

    }

    ...
}
```

And when we can use this variables in template **<project dir>/tmpl/Ctrl/Index.html**:

```
<html>
<body>
<h1><?=$first?></h1>
<h2><?=$second?></h2>
</body>
</html>
```

# Using request parameters #

It is **strictly not recomended** using in **F** application global variables such us **`$_GET $_POST $_SESSION`** directly.

In **F** we have **F\_App\_Request** class that support usable features for request parameters processing, but **in F application we have more simple way**.

We just need **describe** params what needed to action in **$params** field of **Controller**, and **Controller give as it's as argument оf action**.

```

class Ctrl extends F_App_Controller {
    
     protected $params = array(

        'Index' => array(
            array('name' => 'param1', 'type' => 'int', 'strict' => true),
            array('name' => 'param2', 'type' => 'str', 'strict' => false, 'default' => 'test'),
        ),
    );
    ...

    function actionIndex($params) {
        
       var_dump($params);

    }

    ...
}

```

If reqest parameter is **strict** and value is null or undefined Exception will be raised by controller.
If reqest parameter is **strict** default value ignored for this parameter.

If parameter is not **strict** we can define default values for it.

All parameters will be converteed to its **type**, before strict and default validation.

Supported **types**:

  * string (str)
  * integer (int)
  * float
  * bool

Every parameter can be **binded** to one of the supported **sources**:

  * get ($_GET)
  * post ($_POST)
  * request ($_REQUEST)
  * coockie ($_COOKIE)

By default parameters binded to **request source**;

For example:

```

 class Ctrl extends F_App_Controller {
    
     protected $params = array(

        'Index' => array(
            array('name' => 'param1', 'type' => 'int', 'strict' => true, 'source' => 'get'),
            array('name' => 'param2', 'type' => 'str', 'strict' => false, 'default' => 'test'),
        ),
    );
    ...

    function actionIndex($params) {
        
       var_dump($params);

    }

    ...
}

```
Will be wait **param1** only from url  **http://test.local/?param1=...** but if **param1** in **POST** data of request it will be ignored.