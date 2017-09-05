<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><div class="footer">
  <div class="wrapper">
    <div class="lft"><?php echo $GLOBALS['cfg_powerbycw'];  echo $GLOBALS['cfg_beian']; ?> </div>
    <div class="rgt">
    <?php
$atts = array();
$atts['tagname'] = 'channel';
$atts['typeid'] = '13';
$atts['type'] = 'son';
$blockValue = MakePublicTag($atts,$this->refObj,$fields); 
if(is_array($blockValue) && count($blockValue) > 0){
foreach( $blockValue as $key=>$fields )
{
?>
      <a href='[field:typeurl/]'>[field:typename/]</a>
	<span></span>
    <?php
}
}
?>
    <span style="display:none"><!-- Õ¾³¤Í³¼Æ --></span>
    </div>
  </div>
</div>	

<script>
$(function(){
	$(".nav li dl").each(function(){
		if($(this).find("dd").size()==0){
			$(this).remove();
		}		
	});
	
});
</script>