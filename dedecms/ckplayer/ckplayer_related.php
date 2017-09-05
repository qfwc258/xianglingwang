<?php
/**
 * ckplayer精彩视频推荐模块
 *
 * @version        $Id: ckplayer_related.php 17:20 2015年4月30日 by tufei $
 * @package        ckplayer for dedecms
 * @copyright      Copyright (c) 2013 - 2015, dedejs.com
 * @link           http://www.dedejs.com
 */
require_once (dirname(__FILE__) . "/../include/common.inc.php");
require_once DEDEINC."/arc.partview.class.php";
$pv = new PartView();
$pv->SetTemplet($cfg_basedir . $cfg_templets_dir . "/plus/ckplayer_related.htm");
header("Content-type:application/xml");
$pv->Display();
?>