<?php
$slim_root = __DIR__ . '/Slim/';

require_once($slim_root . 'Exception/Pass.php');
require_once($slim_root . 'Exception/RequestSlash.php');
require_once($slim_root . 'Exception/Stop.php');

require_once($slim_root . 'Http/Headers.php');
require_once($slim_root . 'Http/Request.php');
require_once($slim_root . 'Http/Response.php');
require_once($slim_root . 'Http/Util.php');

require_once($slim_root . 'Middleware/Interface.php');
require_once($slim_root . 'Middleware/ContentTypes.php');
require_once($slim_root . 'Middleware/Flash.php');
require_once($slim_root . 'Middleware/MethodOverride.php');
require_once($slim_root . 'Middleware/PrettyExceptions.php');
require_once($slim_root . 'Middleware/SessionCookie.php');

require_once($slim_root . 'Environment.php');
require_once($slim_root . 'Log.php');
require_once($slim_root . 'LogFileWriter.php');
require_once($slim_root . 'Route.php');
require_once($slim_root . 'Router.php');
require_once($slim_root . 'View.php');
require_once($slim_root . 'Slim.php');
