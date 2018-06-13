<?php
#┌────────────────────────
#│MahjongscoreProject
#│ tournament.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/05/03 新規作成 by airu
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_tournament = @$_POST['c_tournament'];    # 大会名
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
	LOG_INSERT($p_uid2,"大会作成",1,err0015."ユーザＩＤ：".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='ログイン画面' onClick=location.href='./login.php'>";
}
else
{
	###### 大会作成ボタンが押された場合の処理 ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{ //ポストで飛ばされてきたら以下を処理

		if(@$_POST["regit"])
		{
			 $err = ""; # エラー出力領域を初期化
			 $info = "";# データ登録メッセージ領域初期化

			 ###### 文字長・必須入力チェック ######
			 $err .= moji_check($p_tournament,1,100,"大会名");   // 大会名の文字長・入力チェック
			 # エラーが発生しなかった場合、MS_MEMBERテーブルにデータを書く
			 if($err == "")
			 {
			 	// 大会が登録されていなかった場合、大会登録する
			 	if(TOURNAMENT_CNT($p_tournament))
			 	{
			 		TOURNAMENT_INSERT($p_tournament);
			 		LOG_INSERT($p_uid2,"大会作成",2,"大会名：".$p_tournament);
			 	}
			 	else
			 	{   // 大会が登録されていた場合、エラーとする
			 		$err .="<img src='./img/error.png' align='middle'>".err0017."<BR>";
			 		LOG_INSERT($p_uid2,"大会作成",1,err0017."　入力大会名：".$p_tournament);
			 	}
			 }

			 ###### エラーメッセージ表示 ######
			 if($err != ""){
			 echo "<table>".$err."</table>";}
			 ###### データ登録メッセージ表示 ######
			 else
			 {
			 	$info = msg0004;
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
		// 大会削除・復活ボタンが押された場合
		elseif(@$_POST["del"])
		{
			$b_delflg = false;    // 物理削除フラグ
			// b_delflg1～b_delflgXまで削除にチェックが入っているものを検索する
			for($i=0; $i<=@$_POST['tournamentnum'] ;$i++)
			{
				// 削除にチェックが入っている大会を物理削除
				if(@$_POST['b_delflg'.$i] != "")
				{
					TOURNAMENT_DELETE(@$_POST['b_delflg'.$i]);
					$b_delflg = true;
					LOG_INSERT($p_uid2,"大会作成",2,"物理削除大会ＩＤ：".@$_POST['b_delflg'.$i]);
				}
				// 削除にチェックが入っている大会を論理削除
				if(@$_POST['r_delflg'.$i] != "")
				{
					TOURNAMENT_UPDATE(@$_POST['r_delflg'.$i],0);
					$r_delflg = true;
					LOG_INSERT($p_uid2,"大会作成",2,"大会終了ＩＤ：".@$_POST['r_delflg'.$i]);
				}
				// 復活にチェックが入っている大会を復活
				if(@$_POST['resurreflg'.$i] != "")
				{
					TOURNAMENT_UPDATE(@$_POST['resurreflg'.$i],1);
					$resurreflg = true;
					LOG_INSERT($p_uid2,"大会作成",2,"復活大会ＩＤ：".@$_POST['resurreflg'.$i]);
				}

			}
			// 入力チェック(1つも選択されていない場合は、エラーとする)
			if(!$b_delflg && !$r_delflg && !$resurreflg)
			{
				echo"<img src='./img/error.png' align='middle'> ".err0018;
			}
		}
	}
}
// 大会登録メッセージが設定されていない場合、フォーム画面を表示する
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
<img src="./img/troffi.png" align="middle">
&nbsp; <b>大会作成</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">大会名<?php echo hissu ?></th>
  <td><input type="text" name="c_tournament" size="120" value="<?php echo $p_tournament  ?>" maxlength="100"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="大会作成"> &nbsp;&nbsp;
    <input type="submit" name=del style="font-size:15pt;background:#FF66AA" value="大会終了・削除・復活"> &nbsp;&nbsp;
</tr>
</tr></table>

</Td></Tr></Table><br><br>
◆大会一覧◆
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>大会ＩＤ</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>大会名</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>大会状態</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>大会終了日</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>大会終了</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>物理削除</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>大会復活</b></td>
</tr>
<?php
// 登録されている大会を表示する
$tournament_list = TOURNAMENT_SELECT();
echo $tournament_list;
} ?>
</form>
</div></body></html>
<?php } ?>