<div data-am-widget="list_news" class="am-list-news am-list-news-default">
			<!--列表标题-->
			<div class="am-list-news-hd am-cf" style="background-color:#EBEBEB">
				<!--带更多链接-->
				<a href="list.php?tid={dede:field name='id'/}"></a>
					 <h2><strong>&#160;&#160;&#8226;&#160;&#160;{dede:field name='typename'/}</strong></h2>
				
		</div>
		
		
			<div class="am-list-news-bd">
				<ul class="am-list">{dede:arclist titlelen='60' row='8'}
					<li class="am-g"> <a href="view.php?aid=[field:id/]" class="am-list-item-hd">[field:title /]</a>
					</li>{/dede:arclist}</ul>
			</div>
			<!--更多在底部-->
			<div class="am-list-news-ft"> <a class="am-list-news-more am-btn am-btn-default" href="list.php?tid={dede:field name='id'/}">查看更多 &#187;</a>
			</div>
		</div>