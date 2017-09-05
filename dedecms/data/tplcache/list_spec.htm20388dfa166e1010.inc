<?php
$z[0]=Array("global","",236,264);
$z[0][4]['name']="cfg_soft_lang";
$z[1]=Array("global","",282,308);
$z[1][4]['name']="cfg_webname";
$z[2]=Array("field","",349,378);
$z[2][4]['name']="keywords";
$z[3]=Array("field","",418,476);
$z[3][4]['name']="description";
$z[3][4]['function']="html2text(@me)";
$z[4]=Array("global","",494,526);
$z[4][4]['name']="cfg_templets_skin";
$z[5]=Array("include","",635,670);
$z[5][4]['filename']="head.htm";
$z[6]=Array("list","\r\n				<li>\r\n      				[field:array runphp='yes']@me = (empty(@me['litpic']) ? \"\" : \"<a href='{@me['arcurl']}' class='preview'><img src='{@me['litpic']}'/></a>\"); [/field:array]\r\n					<a href=\"[field:arcurl/]\" class=\"title\">[field:title/]</a>\r\n					<span class=\"info\">\r\n						<small>ÈÕÆÚ£º</small>[field:pubdate function=\"GetDateTimeMK(@me)\"/]\r\n						<small>µã»÷£º</small>[field:click/]\r\n					</span>\r\n					<p class=\"intro\">\r\n						[field:description/]...\r\n					</p>\r\n				</li>\r\n",884,1399);
$z[6][4]['pagesize']="10";
$z[7]=Array("pagelist","",1505,1576);
$z[7][4]['listitem']="info,index,end,pre,next,pageno";
$z[7][4]['listsize']="5";
$z[8]=Array("arclist","\r\n          	<li><a href=\"[field:arcurl/]\">[field:title/]</a>\r\n            	<p>[field:description function='cn_substr(@me,80)'/]...</p>\r\n            </li>",1793,2018);
$z[8][4]['flag']="c";
$z[8][4]['channelid']="-1";
$z[8][4]['titlelen']="42";
$z[8][4]['row']="6";
$z[9]=Array("mda","",2129,2159);
$z[9][4]['name']="250x250_SSP";
$z[10]=Array("arclist","\r\n                    	<li><a href=\"[field:arcurl/]\">[field:title/]</a></li>\r\n                    ",2311,2459);
$z[10][4]['row']="10";
$z[10][4]['orderby']="click";
$z[11]=Array("mda","",2560,2586);
$z[11][4]['name']="250x250";
$z[12]=Array("mda","",2693,2718);
$z[12][4]['name']="728x90";

?>