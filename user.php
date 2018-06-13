<?php
#┌────────────────────────
#│MahjongscoreProject
#│ user.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/02/21 新規作成 by airu
#│ 2012/05/05 ユーザ物理削除時のごみファイル削除対応 by airu (#1)
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_userid = @$_POST['c_userid'];            # ユーザＩＤ
$p_pwd = @$_POST['c_pwd'];                  # パスワード
$p_name = @$_POST['c_name'];                # 名前
$p_kana = @$_POST['c_kana'];                # カナ
$p_kbn = @$_POST['c_kbn'];                  # 区分
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
	LOG_INSERT($p_uid2,"ユーザ登録",1,err0015."ユーザＩＤ：".$p_uid2);
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
			 $err = ""; # エラー出力領域を初期化
			 $info = "";# データ登録メッセージ領域初期化

			 ###### 文字長・必須入力チェック ######
			 $err .= moji_check($p_userid,1,50,"ユーザＩＤ");   // ユーザＩＤの文字長・入力チェック
			 $err .= moji_check($p_pwd,1,25,"パスワード");      // パスワードの文字長・入力チェック
			 $err .= moji_check($p_name,1,25,"名前");           // 名前の文字長・入力チェック
			 $err .= moji_check($p_kana,0,25,"カナ");           // カナの文字長・入力チェック
			 // 初期ユーザを登録する場合もエラーとする
			 if($p_userid == adminusr)
			 {
			 	$err .="<img src='./img/error.png' align='middle'>".err0002."<BR>";
			 }
			 # エラーが発生しなかった場合、MS_MEMBERテーブルにデータを書く
			 if($err == "")
			 {
			 	// ユーザが登録されていなかった場合、ユーザ登録する
			 	if(USERID_CNT2($p_userid,$p_name))
			 	{
			 		USER_INSERT($p_userid,$p_pwd,$p_name,$p_kana,$p_kbn);
			 		LOG_INSERT($p_uid2,"ユーザ登録",2,"作成ユーザＩＤ：".$p_userid."　ユーザ名：".$p_name."　区分：".$p_kbn);
			 	}
			 	else
			 	{   // ユーザが登録されていた場合、エラーとする
			 		LOG_INSERT($p_uid2,"ユーザ登録",1,err0002."入力ユーザＩＤ：".$p_userid."　入力ユーザ名：".$p_name);
			 		$err .="<img src='./img/error.png' align='middle'>".err0002."<BR>";
			 	}
			 }

			 ###### エラーメッセージ表示 ######
			 if($err != ""){
			 echo "<table>".$err."</table>";}
			 ###### データ登録メッセージ表示 ######
			 else
			 {
			 	$info = msg0001;
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
		// ユーザ削除・復活ボタンが押された場合
		elseif(@$_POST["del"])
		{
			$b_delflg = false;    // 物理削除フラグ
			$r_delflg = false;    // 論理削除フラグ
			$resurreflg = false;  // 復活フラグ
			// b_delflg1～b_delflgXまで削除にチェックが入っているものを検索する
			for($i=0; $i<=@$_POST['usernum'] ;$i++)
			{
				// 削除にチェックが入っているユーザを物理削除
				if(@$_POST['b_delflg'.$i] != "")
				{
					USER_DELETE(@$_POST['b_delflg'.$i]);
					// #1 START
					// ワンタイムパスワードデータ削除
					ONETIMEPWD_DELETE(@$_POST['b_delflg'.$i]);
					// wk_performanceデータ削除
					WK_PERFORMANCE_DELETE(@$_POST['b_delflg'.$i]);
					// wk_confrontationデータ削除
					WK_CONFRONTATION_DELETE(@$_POST['b_delflg'.$i]);
					// wk_tounamentsubデータ削除
					WK_TOURNAMENTSUB_DELETE(@$_POST['b_delflg'.$i]);
					if(file_exists(c_filedir.'Result_'.@$_POST['b_delflg'.$i].'.html'))
					{
						// 集計結果ファイル削除
						unlink(c_filedir.'Result_'.@$_POST['b_delflg'.$i].'.html');
					}
					// #1 END
					$b_delflg = true;
					LOG_INSERT($p_uid2,"ユーザ登録",2,"物理削除ユーザＩＤ：".@$_POST['b_delflg'.$i]);
				}
				// 削除にチェックが入っているユーザを論理削除
				if(@$_POST['r_delflg'.$i] != "")
				{
					USER_UPDATE(@$_POST['r_delflg'.$i],0);
					$r_delflg = true;
					LOG_INSERT($p_uid2,"ユーザ登録",2,"論理削除ユーザＩＤ：".@$_POST['r_delflg'.$i]);
				}
				// 復活にチェックが入っているユーザを復活
				if(@$_POST['resurreflg'.$i] != "")
				{
					USER_UPDATE(@$_POST['resurreflg'.$i],1);
					$resurreflg = true;
					LOG_INSERT($p_uid2,"ユーザ登録",2,"復活ユーザＩＤ：".@$_POST['resurreflg'.$i]);
				}
			}
			// 入力チェック(1つも選択されていない場合は、エラーとする)
			if(!$b_delflg && !$r_delflg && !$resurreflg)
			{
				echo"<img src='./img/error.png' align='middle'> ".err0014;
			}
		}
	}
}
// ユーザ登録メッセージが設定されていない場合、フォーム画面を表示する
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
<img src="./img/users.png" align="middle">
&nbsp; <b>ユーザ登録フォーム</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">ユーザＩＤ<?php echo hissu ?></th>
  <td><input type="text" name="c_userid" size="50" value="<?php echo $p_userid  ?>" maxlength="25" style="ime-mode:disabled"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">パスワード<?php echo hissu ?></th>
  <td><input type="password" name="c_pwd" size="50" value="<?php echo $p_pwd  ?>" maxlength="25"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">名前<?php echo hissu ?></th>
  <td><input type="text" name="c_name" size="50" value="<?php echo $p_name  ?>" maxlength="25"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">カナ</th>
  <td><input type="text" name="c_kana" size="50" value="<?php echo $p_kana  ?>" maxlength="25"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">区分</th>
  <td><select name="c_kbn">
<option value=0>管理者
<option value=1 selected>一般
</select></td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="ユーザ作成"> &nbsp;&nbsp;
    <input type="submit" name=del style="font-size:15pt;background:#FF66AA" value="ユーザ削除・復活"> &nbsp;&nbsp;
</tr>
</tr></table>

</Td></Tr></Table><br><br>
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
<td bgcolor=<?php echo line_color ?> nowrap><b>論理削除</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>物理削除</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>復活</b></td>
</tr>
<?php
// 登録されているユーザを表示する
$member_list = MEMBER_SELECT($p_uid2);
echo $member_list;
} ?>
</form>
</div></body></html>
<?php } ?>