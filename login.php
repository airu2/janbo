<?php
#��������������������������������������������������
#�� MahjongscoreProject
#�� login.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/02/19 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
?>
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=shift_jis">
<meta http-equiv="content-style-type" content="text/css">
<meta name="viewport" content="width=device-width,user-scalable=yes,initial-scale=1.0,maximum-scale=3.0" />
<link rel="STYLESHEET" type="text/css" href="./css/bbspatio.css">
<style type="text/css">
<!--
body,td,th { font-size:13px;	font-family:"MS UI Gothic", Osaka, "�l�r �o�S�V�b�N"; }
-->
</style>
<title>���O�C��</title></head>
<body background="<?php echo background ?>" bgcolor="<?php echo bgcolor ?>" text="#000000" link="#0000FF" vlink="#0000FF" alink="#0000FF">
<blockquote>
<table border="0" cellspacing="0" cellpadding="0" width="340">
<tr><td align="left">
	<fieldset>
	<legend>
	�����[�U�h�c�E�p�X���[�h����
	</legend>
	<form action="./menu.php" method="post">
	���[�U�h�c�F<input type="text" name="uid" style="ime-mode:disabled" maxlength="25" size="39"><br>
	�p�X���[�h�F<input type="password" name="pass" maxlength="25" size="30">
	<img src="./img/keys.png" align="middle">
	<input type="submit" name=login style="font-size:15pt;background:#FF66AA" value="���O�C��">
	</form>
	</fieldset>
</td></tr>
</table>
</blockquote>
</body>
</html>