<?php
error_reporting(E_ALL);

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('BASE_PATH') or define('BASE_PATH', dirname(dirname(__FILE__)));
defined('FRAMEWORK_PATH') or define('FRAMEWORK_PATH', BASE_PATH . DS . 'vendor' . DS . 'yiisoft' . DS . 'yii' . DS . 'framework');

require(BASE_PATH . DS . 'vendor' . DS . 'autoload.php');
require_once(FRAMEWORK_PATH . DS . 'yiit.php');

$config = [
  'basePath' => BASE_PATH . DS . 'src',
  'runtimePath' => BASE_PATH . DS . 'tests' . DS . 'runtime',
  'components'=>[
    'cache' => [
      'class' => 'application.YiiTestCache',
    ]
  ]
];
Yii::createWebApplication($config);
set_error_handler(array('PHPUnit_Util_ErrorHandler', 'handleError'), E_ALL);

function p($data)
{
  echo CVarDumper::dumpAsString($data);
  return $data;
}