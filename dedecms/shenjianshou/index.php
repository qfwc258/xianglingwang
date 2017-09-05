<?php
define('DEDEASK',dirname(__FILE__));
$page_start_time = microtime(TRUE);
define('APPNAME', 'shenjianshou');

require_once(dirname(__file__).'/../include/common.inc.php');
require_once(DEDEINC.'/request.class.php');
require_once(DEDEASK.'/libraries/config.php');
require_once(DEDEASK.'/libraries/ta.functions.php');
//对站点根网址最后/进行过滤
$cfg_basehost = preg_replace("#/$#",'',$cfg_basehost);

//载入配置文件
require_once(DEDEASK.'/data/common.inc.php');
require_once("./lang/".$cfg_db_language.".php");
global $dede_charset,$cfg_db_language;

$ct = Request('ct', 'index');
$ac = Request('ac', 'index');
// 统一应用程序入口
RunApp($ct, $ac);