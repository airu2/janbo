<?php
#┌────────────────────────
#│ MahjongscoreProject
#│ login.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/02/19 新規作成 by airu
#└────────────────────────
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
body,td,th { font-size:13px;	font-family:"MS UI Gothic", Osaka, "ＭＳ Ｐゴシック"; }
-->
</style>
<title>ログイン</title></head>
<body background="<?php echo background ?>" bgcolor="<?php echo bgcolor ?>" text="#000000" link="#0000FF" vlink="#0000FF" alink="#0000FF">
<blockquote>
<table border="0" cellspacing="0" cellpadding="0" width="340">
<tr><td align="left">
	<fieldset>
	<legend>
	▼ユーザＩＤ・パスワード入力
	</legend>
	<form action="./menu.php" method="post">
	ユーザＩＤ：<input type="text" name="uid" style="ime-mode:disabled" maxlength="25" size="39"><br>
	パスワード：<input type="password" name="pass" maxlength="25" size="30">
	<img src="./img/keys.png" align="middle">
	<input type="submit" name=login style="font-size:15pt;background:#FF66AA" value="ログイン">
	</form>
	</fieldset>
</td></tr>
</table>
</blockquote>
</body>
</html>