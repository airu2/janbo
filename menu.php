<?php
#┌────────────────────────
#│ MahjongscoreProject
#│ menu.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/02/19 新規作成 by airu
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_pwd = @$_POST['pass'];              # パスワード
$p_uid = @$_POST['uid'];               # ユーザＩＤ
$p_job = @$_POST['job'];               # 画面遷移内容
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
<title>メニュー</title></head>
<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" >
<link rel="icon" href="./favicon.ico" >
<body background="<?php echo background ?>" bgcolor="<?php echo bgcolor ?>" text="#000000" link="#0000FF" vlink="#0000FF" alink="#0000FF">
<?php
###### 返信するボタンが押された場合の処理 ######
if($p_job != "back")
{
	// 管理者パスワードチェック
	$admin_chk = adminpass_check($p_pwd,$p_uid);
	// ユーザチェック(DB)
	$user_chk = USERID_CNT($p_uid,$p_pwd);
	if($admin_chk || $user_chk != -1)
	{
		// ワンタイムパスワードデータ削除
		ONETIMEPWD_DELETE($p_uid);
		// ワンタイムパスワードデータ登録
		ONETIMEPWD_INSERT($p_uid,$user_chk);
	}
}
else
{
	// 初期ユーザの場合は、初期ユーザの設定を再度行う
	if($p_uid == adminusr)
	{
		$admin_chk = true;
		$user_chk = -1;
	}
}
// ワンタイムパスワードデータ取得
$usr_data = ONETIMEPWD_SELECT($p_uid);
if (@$_SERVER["REQUEST_METHOD"]=="POST")
{ //ポストで飛ばされてきたら以下を処理

	// パスワード認証失敗の場合は、エラーとする
	if(!$admin_chk && $user_chk == -1)
	{
		LOG_INSERT($p_uid,"メニュー",1,err0001."　入力ユーザＩＤ：".$p_uid."　入力パスワード：".$p_pwd);
		echo"<img src='./img/error.png' align='middle'> ".err0001;
		echo "<Input type=button value='ログイン画面' onClick=location.href='./login.php'>";
	}
 	else {
		// レート計算メニュー表示（初期ユーザのみ）
		if($admin_chk)
		{
			echo "<div align='center'>";
			echo "処理内容を選択してください。";
			echo "<p>";
			echo "<Table border='0' cellspacing='0' cellpadding='0' width='320'>";
			echo "<Tr><Td bgcolor='".line_color."'>";
			echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
			echo "<tr bgcolor='".line_color."'>";
			echo "<td bgcolor='".in_bgcolor."' align='center'>";
			echo "選択";
			echo "</td>";
			echo "<td bgcolor='".in_bgcolor."' width='100%'>";
			echo "&nbsp; 初期ユーザ用";
			echo "</td>";
			echo "</tr>";
			// メニュー１２：レート計算
			echo "<form action='./rate_calc.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu0' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; レート計算";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			echo "</table>";
			echo "</Td></Tr></Table>";
			echo "</div>";
		}
		// 管理者パスワードチェックで正しい場合、メニューを表示する
		if(($user_chk == 0 && $usr_data[2] == 0) || $admin_chk)
		{
			echo "<div align='center'>";
			if(!$admin_chk)
			{
				echo "処理内容を選択してください。　(".version.")";
			}
			echo "<p>";
			echo "<Table border='0' cellspacing='0' cellpadding='0' width='320'>";
			echo "<Tr><Td bgcolor='".line_color."'>";
			echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
			echo "<tr bgcolor='".line_color."'>";
			echo "<td bgcolor='".in_bgcolor."' align='center'>";
			echo "選択";
			echo "</td>";
			echo "<td bgcolor='".in_bgcolor."' width='100%'>";
			echo "&nbsp; 管理用";
			echo "</td>";
			echo "</tr>";
			// メニュー０：ログ
			echo "<form action='./log.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu0' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; 操作ログ履歴";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// メニュー１：ユーザ登録
			echo "<form action='./user.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu1' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; ユーザ登録";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// メニュー２：パスワード変更
			echo "<form action='./pass_change.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu2' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; パスワード変更";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// メニュー３：リーグランク設定
			echo "<form action='./ranksetting.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu3' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; リーグランク設定";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// メニュー４：大会作成
			echo "<form action='./tournament.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu4' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; 大会作成";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// メニュー１２：成績照会（一般対局）
			echo "<form action='./league_all_admin.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu12' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; 一般対局成績照会（管理用）";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			echo "</table>";
			echo "</Td></Tr></Table>";
			echo "</div>";
		}
		if($user_chk == 0 || $user_chk == 1)
		{
			echo "<div align='center'>";
			if($user_chk == 1)
			{
				echo "処理内容を選択してください。　(".version.")";
			}
			echo "<p>";
			echo "<Table border='0' cellspacing='0' cellpadding='0' width='320'>";
			echo "<Tr><Td bgcolor='".line_color."'>";
			echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
			echo "<tr bgcolor='".line_color."'>";
			echo "<td bgcolor='".in_bgcolor."' align='center'>";
			echo "選択";
			echo "</td>";
			echo "<td bgcolor='".in_bgcolor."' width='100%'>";
			echo "&nbsp; 一般スコア関連";
			echo "</td>";
			echo "</tr>";
			// メニュー５：対局情報入力（一般対局）
			echo "<form action='./score.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu5' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; 対局情報入力（一般対局）";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='kbn' value=$usr_data[2]>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// メニュー９：成績照会（一般対局）
			echo "<form action='./league_all.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu9' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; 一般対局成績照会（一般用）";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// メニュー１１：直接対決結果
			echo "<form action='./directconfrontation.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu11' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; 直接対決結果照会（一般対局）";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "<input type=hidden name='u_kbn' value=$usr_data[2]>";
			echo "</form>";
			echo "</table>";
			echo "</Td></Tr></Table>";
			echo "</div>";
			echo "<div align='center'>";
			echo "<p>";
			echo "<Table border='0' cellspacing='0' cellpadding='0' width='320'>";
			echo "<Tr><Td bgcolor='".line_color."'>";
			echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
			echo "<tr bgcolor='".line_color."'>";
			echo "<td bgcolor='".in_bgcolor."' align='center'>";
			echo "選択";
			echo "</td>";
			echo "<td bgcolor='".in_bgcolor."' width='100%'>";
			echo "&nbsp; 大会スコア関連";
			echo "</td>";
			echo "</tr>";
			// メニュー６：対局情報入力（大会対局）
			echo "<form action='./tournament_score.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu6' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; 対局情報入力（大会対局）";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='kbn' value=$usr_data[2]>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// メニュー１０：成績照会（大会対局）
			echo "<form action='./tournament_search.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu10' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; 大会対局成績照会";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			echo "</table>";
			echo "</Td></Tr></Table>";
			echo "</div>";
			echo "<div align='center'>";
			echo "<p>";
			echo "<Table border='0' cellspacing='0' cellpadding='0' width='320'>";
			echo "<Tr><Td bgcolor='".line_color."'>";
			echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
			echo "<tr bgcolor='".line_color."'>";
			echo "<td bgcolor='".in_bgcolor."' align='center'>";
			echo "選択";
			echo "</td>";
			echo "<td bgcolor='".in_bgcolor."' width='100%'>";
			echo "&nbsp; その他";
			echo "</td>";
			echo "</tr>";
			// 牌譜登録メニュー表示する場合
			if(haihu_touroku_flg == 1)
			{
				// メニュー７：牌譜情報入力
				echo "<form action='./haihu.php' method='post'>";
				echo "<tr bgcolor='#FFFFFF'>";
				echo "<td bgcolor='#FFFFFF' align='center'>";
				echo "<input type='submit' name='menu7' value='選択'>";
				echo "</td>";
				echo "<td bgcolor='#FFFFFF' width='100%'>";
				echo "&nbsp; 牌譜情報入力";
				echo "</td>";
				echo "</tr>";
				echo "<input type=hidden name='kbn' value=$usr_data[2]>";
				echo "<input type=hidden name='uid' value=$p_uid>";
				echo "<input type=hidden name='pass' value=$usr_data[0]>";
				echo "</form>";
			}
			// メニュー８：当月分スコア修正
			echo "<form action='./score_fix.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu8' value='選択'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; 対局情報修正（一般対局のみ）";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='kbn' value=$usr_data[2]>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			echo "</table>";
			echo "</Td></Tr></Table>";
			echo "</div>";
		}
 	}

}
if(@$_POST['login'] == "ログイン" && $user_chk != -1)
{
	// ログイン日時書込
	LOGIN_DATE($p_uid);
	$uname = MYUSERNAME($p_uid);
	LOG_INSERT($p_uid,"メニュー",0,$uname."様がログインしました。");
}
?>
</body>