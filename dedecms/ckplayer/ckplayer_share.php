<?php
/**
 * ckplayer视频分享模块
 *
 * @version        $Id: ckplayer_share.php 15:15 2015年4月30日 by tufei $
 * @package        ckplayer for dedecms
 * @copyright      Copyright (c) 2013 - 2015, dedejs.com
 * @link           http://www.dedejs.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once DEDEINC."/arc.partview.class.php";
$pv = new PartView();
$pv->SetTemplet($cfg_basedir . $cfg_templets_dir . "/plus/ckplayer_share.htm");
header("Content-type:application/xml");
$pv->Display();

?>