<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
 global $dede_charset; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $dede_charset['tpl']['title_plug'];?></title>
<link href="static/style/admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form name="form" action="?ct=configController&ac=edit_save" method="post" style="padding-top: 15px;">
    <input type="hidden" name="ta_password_pid" value="<?php echo $ta_password_pid;?>">
    <input type="hidden" name="ta_unique_pid" value="<?php echo $ta_unique_pid;?>">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tbtitle" style="background:#E2F5BC;">
                    <tbody>
        <tr>
          <td height="25" colspan="2"><?php echo $dede_charset['tpl']['title_publish'];?></td>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF"> <div align="left"><?php echo $dede_charset['tpl']['version'];?></div></td>
          <td height="25" bgcolor="#FFFFFF">
              <b><?php echo $taconfig['version']; ?></b>
          </td>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF"><?php echo $dede_charset['website'];?></td>
          <td height="25" bgcolor="#FFFFFF"> <a target="_blank" href="http://www.shenjianshou.cn/" target="_blank" style="color:blue;">www.shenjianshou.cn</a>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF"><?php echo $dede_charset['tpl']['publish_url'];?></td>
          <td height="25" bgcolor="#FFFFFF"> <span style="font-weight: 600;"><?php echo $basic_web_address; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#666666"><?php echo $dede_charset['tpl']['publish_url_tip']; ?></font>
        </tr>
        <tr>
          <td width="17%" height="25" bgcolor="#FFFFFF"> <div align="left"><?php echo $dede_charset['tpl']['publish_passwd']; ?></div></td>
          <td width="83%" height="25" bgcolor="#FFFFFF"> <input name="ta_password" type="text" id="ta_password" value="<?php echo $ta_password; ?>" size="35">
            <font color="#666666"><?php echo $dede_charset['tpl']['publish_passwd_tip']; ?></font></td>
        </tr>
        <tr>
            <td height="25" bgcolor="#FFFFFF"><?php echo $dede_charset['tpl']['publish_title_unique']; ?></td>
            <td height="25" bgcolor="#FFFFFF">
                <?php if($ta_unique){ ?>
                    <input name="ta_unique" type="checkbox" value="1" checked style="margin-left: 5px">
                <?php }else{ ?>
                    <input name="ta_unique" type="checkbox" value="1" style="margin-left: 5px">
                <?php } ?>
                &nbsp;&nbsp;<font color="#666666"><?php echo $dede_charset['tpl']['publish_unique_tip']; ?></font>
            </td>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF"></td>
          <td height="25" bgcolor="#FFFFFF"> <input type="submit" name="Submit" value="<?php echo $dede_charset['tpl']['publish_setting']; ?>">
            &nbsp;&nbsp;&nbsp; </td>
        </tr>
        </tbody>
    </table>
</form>

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
	<tr>
		<td><?php echo $dede_charset['tpl']['remarks']; ?>: </td>
	</tr>
	<tr>
            <td><?php echo $dede_charset['tpl']['remarks_one']; ?></td>
	</tr>
	<tr>
            <td><?php echo $dede_charset['tpl']['remarks_two']; ?></td>
	</tr>
        <tr>
            <td><?php echo $dede_charset['tpl']['remarks_three']; ?></td>
	</tr>
</table>
</body>
</html>