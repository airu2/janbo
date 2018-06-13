<?php
#┌────────────────────────
#│MahjongscoreProject
#│ ranksetting.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/03/20 新規作成 by Reina
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_uid2 = @$_POST['uid'];                   # ログインユーザＩＤ
$p_pwd2 = @$_POST['pass'];                  # ログインパスワード
?>
<!-- ヘッダ部 -->
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=shift_jis">
<meta http-equiv="content-style-type" content="text/css">
<meta name="viewport" content="width=device-width,user-scalable=yes,initial-scale=1.0,maximum-scale=3.0" />
<link rel="STYLESHEET" type="text/css" href="./css/bbspatio.css">
<style type="text/css">
<!--
body,td,th { font-size:13px;font-family:"MS UI Gothic", Osaka, "ＭＳ Ｐゴシック"; }
a:hover { color:#DD0000 }
.num { font-size:12px; font-family:Verdana,Helvetica,Arial; }
.s1  { font-size:10px; font-family:Verdana,Helvetica,Arial; }
.s2  { font-size:10px; font-family:""MS UI Gothic", Osaka, "ＭＳ Ｐゴシック""; }
-->
</style>
<?php
// 管理者パスワードチェック
$admin_chk = adminpass_check($p_pwd2,$p_uid2);
// ユーザチェック(DB)
$user_chk = USERID_CNT3($p_uid2,$p_pwd2);
// パスワード認証失敗の場合は、エラーとする
if(!$admin_chk && $user_chk == -1)
{
	echo"<img src='./img/error.png' align='middle'> ".err0001;
	echo "<Input type=button value='ログイン画面' onClick=location.href='./login.php'>";
}
else if($user_chk == 0)
{
	LOG_INSERT($p_uid2,"リーグランク設定",1,err0015."ユーザＩＤ：".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='ログイン画面' onClick=location.href='./login.php'>";
}
else
{
	###### ユーザ作成ボタンが押された場合の処理 ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{ //ポストで飛ばされてきたら以下を処理

		if(@$_POST["regit"])
		{
			$info = "";# データ登録メッセージ領域初期化
			// user1～userXまでチェックが入っているものを検索する
			for($i=1; $i<=@$_POST['usernum'] ;$i++)
			{
				RANK_UPDATE(@$_POST['user'.$i],@$_POST['rank'.$i]);
				if(@$_POST['rank'.$i] == 0)	{$rank = "Ｓ";}
				if(@$_POST['rank'.$i] == 1)	{$rank = "Ａ";}
				if(@$_POST['rank'.$i] == 2)	{$rank = "Ｂ";}
				if(@$_POST['rank'.$i] == 3)	{$rank = "Ｃ";}
				LOG_INSERT($p_uid2,"リーグランク設定",2,"ユーザＩＤ：".@$_POST['user'.$i]."　更新ランク：".$rank);
			}
			###### データ登録メッセージ表示 ######
		 	$info = msg0002;
		 	echo "<body background='".background."' bgcolor='#FFFFFF' text='#000000' link='#0000FF' vlink='#0000FF' alink='#0000FF'>";
		 	echo "<br><br><div align='center'>";
		 	echo "<Table border='0' cellspacing='0' cellpadding='0' width='400'>";
		 	echo "<Tr><Td bgcolor='".line_color."'>";
		 	echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
		 	echo "<tr bgcolor='#FFFFFF'>";
		 	echo "<td bgcolor='#FFFFFF' nowrap align='center' height='60'>";
		 	echo "<h3 style='font-size:15px'>".$info."</h3>";
		 	echo "</body></div></td></tr></table></Td></Tr></Table>";
		 	echo "<form action='./menu.php' method='post'>";
		 	echo "<input type=hidden name='uid' value=$p_uid2>";
		 	echo "<input type=hidden name='pass' value=$p_pwd2>";
		 	echo "<input type=hidden name='job' value='back'>";
		 	echo "<Input type=submit value='メニュー画面に戻る'>";
		 	echo "</form>";
		}
	}
}
// リーグランク設定メッセージが設定されていない場合、フォーム画面を表示する
if($info == "")
{
?>

<!-- HTML本体部 -->

<title><?php echo boardtitle ?></title></head>
<body background="<?php echo background ?>" bgcolor="<?php echo bgcolor ?>" text="#000000" link="#0000FF" vlink="#800080" alink="#DD0000">
<div id="container">
<?php // パスワード認証成功の場合は、フォーム表示
if($admin_chk || ($user_chk != -1 && $user_chk != 0))
{?>
<table width="100%">
<tr>
  <form action="./menu.php" method='post' >
	<Input type=submit value='メニュー画面に戻る'>
	<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
	<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
	<input type=hidden name='job' value="back">
  </form>
</tr></table>
<Table border="0" cellspacing="0" cellpadding="0" width="100%">
<Tr bgcolor="<?php echo line_color ?>"><Td bgcolor="<?php echo line_color ?>"  class="td0">
<table border="0" cellspacing="1" cellpadding="5" width="100%">
<tr bgcolor="<?php echo in_bgcolor ?>"><td bgcolor="<?php echo in_bgcolor ?>" nowrap width="100%" class="td0">
<img src="./img/star.png" align="middle">
&nbsp; <b>リーグランク設定</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
	<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
	<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="リーグランク設定"> &nbsp;&nbsp;
</tr><br><br><br>
◆ユーザ一覧◆
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>NO</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>ユーザID</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>ユーザ名</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>ユーザカナ</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>会員区分</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>所属リーグ</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>会員状態</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>最終ログイン日時</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>ユーザ登録日時</b></td>
</tr>
<?php
// 登録されているユーザの情報を表示する
$member_list = MEMBER_SELECT_RANK();
echo $member_list;
?>
</form>
<?php } ?>
</div></body></html>
<?php } ?>