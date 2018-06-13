<?php
#┌────────────────────────
#│MahjongscoreProject
#│ tournament_search.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/05/05 新規作成 by airu
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_uid2 = @$_POST['uid'];                   # ログインユーザＩＤ
$p_pwd2 = @$_POST['pass'];                  # ログインパスワード
$p_id = @$_POST['tournament'];              # 大会ＩＤ
// 大会情報取得（プルダウン表示用）
$tournament = TOURNAMENT_SELECT2($p_id,0);
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
<title><?php echo boardtitle ?></title></head>
<body background="<?php echo background ?>" bgcolor="<?php echo bgcolor ?>" text="#000000" link="#0000FF" vlink="#800080" alink="#DD0000">
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
	LOG_INSERT($p_uid2,"対局成績照会（大会対局）",1,err0015."ユーザＩＤ：".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='ログイン画面' onClick=location.href='./login.php'>";
}
else
{
	###### ボタンが押された場合の処理 ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{
		// 検索処理
		if(@$_POST["regit"])
		{
			$err = "";
			// 枠作成のため、最大戦のMAX値を取得
			$max_num = MAXNUM($p_id);
			// 各ユーザの戦の最大数を取得
			$max_array_data = USER_MAXNUM($p_id);
			// WK_TOURNAMENTSUBデータ削除
			WK_TOURNAMENTSUB_DELETE($p_uid2);
			// 大会の合計スコアをメンバごとに算出→ソート順データ作成
			TOURNAMENT_TOP($p_id,$p_uid2);
			// 各ユーザの戦の大会スコアを取得
			$array_data = USER_TOURNAMENTSCORE($p_id,$p_uid2);
			if($max_num == 0)
			{
				LOG_INSERT($p_uid2,"対局成績照会（大会対局）",1,err0021."　大会ＩＤ：".$p_id);
				$err .= "<img src='./img/error.png' align='middle'>".err0021."<BR>";
			}
			else
			{
				LOG_INSERT($p_uid2,"対局成績照会（大会対局）",0,"大会ＩＤ：".$p_id."のデータ取得");
			}
			// 大会選択チェック（大会が1つも作成されていないとき空が選択できてしまうのでチェック）
			if($p_id == "")
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0022."<BR>";
			}
			###### エラーメッセージ表示 ######
			if($err != "")
			{
				echo $err;
			}
		}
	}
}
?>

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
&nbsp; <b>対局成績照会（大会対局）</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type=hidden name='kbn' value=<?php echo $p_kbn2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">大会<?php echo hissu ?></th>
  <td>
  <select name="tournament">
  <?php echo $tournament ?>
  </select>
    </td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="大会成績照会">
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## ヘッダ表示部 ########## -->
<BR>
◆大会成績◆
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<?php if($max_num > 0) { ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局者</b></td>
<?php for($i=1; $i<=$max_num; $i++) { ?>
<td bgcolor=<?php echo line_color ?> nowrap><b><?php echo mb_convert_kana($i,N,"shift_jis")."回戦"?></b></td>
<?php } ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>合計</b></td>
<?php } ?>
</tr>
<?php
// 検索された大会の対局結果を表示する
$k=0;
for($i = 0; $i < count($max_array_data[0]);)
{
	$sum_score = 0;
	echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$max_array_data[0][$i]."</td>"; // 対局者
	for($j = 0; $j < $max_array_data[1][$i]; $j++)
	{
		if($max_array_data[0][$i] == $array_data[0][$k] && $j+1 == $array_data[1][$k])
		{
			if($array_data[2][$k] < 0 && $array_data[2][$k] > -20)
			{
				echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>▲".($array_data[2][$k])*(-1)."</td>"; // 対局スコア
			}
			else if($array_data[2][$k] <= -20)
			{
				echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>▲".($array_data[2][$k])*(-1)."</font></td>"; // 対局スコア
			}
			else
			{
				echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$array_data[2][$k]."</td>"; // 対局スコア
			}
			$sum_score = $sum_score + $array_data[2][$k];
			$k++;
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'></td>"; // 対局スコア(空文字)
		}
		if($max_array_data[0][$i] != $array_data[0][$k])
		{
			for($l = $max_array_data[1][$i]; $l < $max_num; $l++)
			{
				echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'></td>"; // 対局スコア(空文字)
			}
		}
	}
	if($sum_score < 0 && $sum_score > -20)
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>▲".($sum_score)*(-1)."</font></td></tr>\r\n"; // 合計
	}
	else if($sum_score <= -20)
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>▲".($sum_score)*(-1)."</font></td></tr>\r\n"; // 合計
	}
	else
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$sum_score."</td></tr>\r\n"; // 合計
	}
	$i++;
}
?>
</form></table></Td></Tr></Table>
<?php } ?>
</div></body></html>
