<?php
//z-php框架
define('IN',str_replace('\\','/',dirname(__FILE__)) . '/');        //定义http请求的根目录
define('APP_PATH','home');                                    //定义应用目录名称
define('DEBUG',1);                                            //开启debug
define('ERROR_LOG','php_error_log');                        //错误日志目录:rundir目录下
require('../core/core.php');                                  //加载框架
\z\z::start();