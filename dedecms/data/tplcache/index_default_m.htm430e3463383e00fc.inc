<?php
$z[0]=Array("global","",61,89);
$z[0][4]['name']="cfg_soft_lang";
$z[1]=Array("field","",102,121);
$z[1][4]['name']="title";
$z[2]=Array("global","",122,148);
$z[2][4]['name']="cfg_webname";
$z[3]=Array("include","",1669,1708);
$z[3][4]['filename']="header_m.htm";
$z[4]=Array("channelartlist","\r\n		<div data-am-widget=\"list_news\" class=\"am-list-news am-list-news-default\">\r\n			<!--列表标题-->\r\n			<div class=\"am-list-news-hd am-cf\">\r\n				<!--带更多链接-->\r\n					 <h2>{dede:field name='typename'/}</h2>\r\n			</div>\r\n			<div class=\"am-list-news-bd\">\r\n				<ul class=\"am-list\">{dede:arclist titlelen='60' row='8'}\r\n					<li class=\"am-g\"> <a href=\"view.php?aid=[field:id/]\" class=\"am-list-item-hd\">[field:title /]</a>\r\n					</li>{/dede:arclist}</ul>\r\n			</div>\r\n			<!--更多在底部-->\r\n			<div class=\"am-list-news-ft\"> <a class=\"am-list-news-more am-btn am-btn-default\" href=\"list.php?tid={dede:field name='id'/}\">查看更多 &raquo;</a>\r\n			</div>\r\n		</div>",1869,2565);
$z[5]=Array("arclist","\r\n							<li><a href=\"view.php?aid=[field:id/]\">[field:title/]</a>\r\n							</li>",2798,2945);
$z[5][4]['row']="5";
$z[5][4]['orderby']="pubdate";
$z[5][4]['titlelen']="40";
$z[6]=Array("arclist","\r\n							<li><a href=\"view.php?aid=[field:id/]\">[field:title/]</a>\r\n							</li>",3102,3245);
$z[6][4]['row']="5";
$z[6][4]['orderby']="hot";
$z[6][4]['titlelen']="40";
$z[7]=Array("include","",3300,3339);
$z[7][4]['filename']="footer_m.htm";

?>