<?php
#┌────────────────────────
#│MahjongscoreProject
#│ league_all.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│  2012/04/01 新規作成 by airu
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_from_y = @$_POST['from_y'];              # FROM(年)
$p_from_m = @$_POST['from_m'];              # FROM(月)
$p_from_d = @$_POST['from_d'];              # FROM(日)
$p_to_y = @$_POST['to_y'];                  # TO(年)
$p_to_m = @$_POST['to_m'];                  # TO(月)
$p_to_d = @$_POST['to_d'];                  # TO(日)
$p_kbn = @$_POST['kbn'];                    # 集計区分
$p_num = @$_POST['num'];                    # 試合規定数
$p_uid2 = @$_POST['uid'];                   # ログインユーザＩＤ
$p_pwd2 = @$_POST['pass'];                  # ログインパスワード
$p0_select="";                              # 集計区分（東風）セレクト
$p1_select="";                              # 集計区分（半荘）セレクト
$p2_select="";                              # 集計区分（全て）セレクト

// メニューから遷移された場合は、検索初期値を設定
if(@$_POST['menu12'] != "")
{
	$p_num = 0;                                        # 試合規定数
	$p_from_y = date_format(date_create("NOW"), "Y");  # FROM(年)
	$p_from_m = date_format(date_create("NOW"), "n");  # FROM(月)
	$p_from_d = 1;                                     # FROM(日)
	$p_to_y = date_format(date_create("NOW"), "Y");    # TO(年)
	$p_to_m = date_format(date_create("NOW"), "n");    # TO(月)
	$p_to_d = date_format(date_create("NOW"), "j");    # TO(日)
}
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
.s2  { font-size:10px; font-family:"MS UI Gothic", Osaka, "ＭＳ Ｐゴシック""; }
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
	LOG_INSERT($p_uid2,"一般対局成績照会（管理用）",1,err0015."ユーザＩＤ：".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='ログイン画面' onClick=location.href='./login.php'>";
}
else
{
	###### ボタンが押された場合の処理 ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{
		// 登録処理
		if(@$_POST["regit"])
		{
			if($p_kbn == 0) { $p0_select="selected"; }
			if($p_kbn == 1) { $p1_select="selected"; }
			else { $p2_select="selected"; }
			$err = "";
			$err .= moji_check_ad($p_from_y,1,4,"集計期間（ＦＲＯＭ（年））");  // ＦＲＯＭ（年）の文字長・入力チェック
			$err .= moji_check_ad($p_from_m,1,2,"集計期間（ＦＲＯＭ（月））");  // ＦＲＯＭ（月）の文字長・入力チェック
			$err .= moji_check_ad($p_from_d,1,2,"集計期間（ＦＲＯＭ（日））");  // ＦＲＯＭ（日）の文字長・入力チェック
			$err .= moji_check_ad($p_to_y,1,4,"集計期間（ＴＯ（年））");        // ＴＯ（年）の文字長・入力チェック
			$err .= moji_check_ad($p_to_m,1,2,"集計期間（ＴＯ（月））");        // ＴＯ（月）の文字長・入力チェック
			$err .= moji_check_ad($p_to_d,1,2,"集計期間（ＴＯ（日））");        // ＴＯ（日）の文字長・入力チェック
			$err .= moji_check_ad($p_num,1,3,"試合規定数");                     // 試合規定数の文字長・入力チェック
			if($err == "")
			{
				// 日付型チェック
				if(!checkdate($p_from_m, $p_from_d, $p_from_y) || !checkdate($p_to_m, $p_to_d, $p_to_y))
				{
					$err .= "<img src='./img/error.png' align='middle'>".err0010."<BR>";
				}
			}
			// 日付(期間指定チェック)
			if($err == "")
			{
				if(date_format(date_create($p_from_y."/".$p_from_m."/".$p_from_d),"Ymd") > date_format(date_create($p_to_y."/".$p_to_m."/".$p_to_d),"Ymd"))
				{
					$err .= "<img src='./img/error.png' align='middle'>".err0011."<BR>";
				}
			}
			// 規定数の数字型チェック
			if($err == "")
			{
				if (!is_numeric($p_num))
				{
					$err .= "<img src='./img/error.png' align='middle'>".err0012."<BR>";
				}
			}
			// 規定数の0未満チェック
			if($err == "")
			{
				if ($p_num < 0)
				{
					$err .= "<img src='./img/error.png' align='middle'>".err0013."<BR>";
				}
			}
			// エラーでない場合
			if($err == "")
			{
				// wk_performanceデータ削除
				WK_PERFORMANCE_DELETE($p_uid2);
				// wk_performanceにデータ書込
				SCORE_CNT(($p_from_y."/".$p_from_m."/".$p_from_d),($p_to_y."/".$p_to_m."/".$p_to_d),$p_kbn,$p_uid2,$p_num);
				// データ取得
				$performance_data = PERFORMANCE_SELECT($p_uid2);
				// ゲストデータ取得
				$guest_score = GUEST_SCORE(($p_from_y."/".$p_from_m."/".$p_from_d),($p_to_y."/".$p_to_m."/".$p_to_d),($p_kbn + 100));
				// データ作成
				create_file($performance_data[0],$performance_data[1],$performance_data[2],$performance_data[3],$performance_data[4],$p_uid2,$p_num,$p_kbn,($p_from_y."/".$p_from_m."/".$p_from_d),($p_to_y."/".$p_to_m."/".$p_to_d),0);
				if($p_kbn == 0) {$syubetsu="東風戦";}
				else if($p_kbn == 1){ $syubetsu="半荘戦";}
				else { $syubetsu="全て";}
				LOG_INSERT($p_uid2,"一般対局成績照会（管理用）",0,"ＦＲＯＭ：".($p_from_y."/".$p_from_m."/".$p_from_d)."　ＴＯ：".($p_to_y."/".$p_to_m."/".$p_to_d)."　対局種別：".$syubetsu."　試合規定数：".$p_num);
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
<img src="./img/gurafu.png" align="middle">
&nbsp; <b>一般対局成績照会（管理用）</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">対局日付<?php echo hissu ?></th>
  <td><input type="text" name="from_y" style="ime-mode:disabled" value="<?php echo $p_from_y  ?>" maxlength="4" size="6" >/
  <input type="text" name="from_m" style="ime-mode:disabled" value="<?php echo $p_from_m  ?>" maxlength="2" size="3" >/
  <input type="text" name="from_d" style="ime-mode:disabled" value="<?php echo $p_from_d  ?>" maxlength="2" size="3" >　～　
  <input type="text" name="to_y" style="ime-mode:disabled" value="<?php echo $p_to_y  ?>" maxlength="4" size="6" >/
  <input type="text" name="to_m" style="ime-mode:disabled" value="<?php echo $p_to_m  ?>" maxlength="2" size="3" >/
  <input type="text" name="to_d" style="ime-mode:disabled" value="<?php echo $p_to_d  ?>" maxlength="2" size="3" >
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">集計区分<?php echo hissu ?></th>
  <td><select name="kbn">
  <option value=2 <?php echo $p2_select ?>>全て
  <option value=0 <?php echo $p0_select ?>>東風戦
  <option value=1 <?php echo $p1_select ?>>半荘戦
  </select></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">試合規定数<?php echo hissu ?></th>
  <td><input type="text" name="num" style="ime-mode:disabled" value="<?php echo $p_num  ?>" maxlength="3" size="5">※試合規定数を指定しない場合は、0を入力してください。試合規定数未満の場合は集計上紫で表示されます。</td>
</tr>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="成績照会">&nbsp;&nbsp;
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## ヘッダ表示部 ########## -->
<BR>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
集計期間：<?php if(@$_POST["regit"] && $err =="") { echo $p_from_y."/".$p_from_m."/".$p_from_d ?>　～　<?php echo $p_to_y."/".$p_to_m."/".$p_to_d; } ?><br>
集計区分：<?php if(@$_POST["regit"] && $err =="") { echo $p_kbn."（".sum_kbn($p_kbn)."）"; } ?><br>
ファイル：<?php if(@$_POST["regit"] && $err =="") { echo "<a href='./output/Result_".$p_uid2.".html'>Result_".$p_uid2.".html</a>"; }?><br>
<?php if(!RATECALC_FLG_SELECT()) { echo "<font color=red><b>".err0024."</b></font>"; } ?><br><br>
</table>
<?php if(guest_use_flg == 1) { ?>
<Table cellspacing="0" cellpadding="0" width="20%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th width=100 bgcolor="#FFFFFF" >ゲストスコア</th>
  <td><center><?php echo $guest_score ?></center></td>
</tr>
</tr></table>
</Td></Tr></Table><br>
<?php } ?>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<b>全員</b>　※レーティングは、初戦～現在までの記録なので期間指定は関係ありません。
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局者</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局数</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>平均順位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>スコア</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>レーティング</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>１位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>２位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>３位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>４位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>連対率</b></td>
</tr>
<?php
for($i = 0; $i < count($performance_data[0]);)
{
	$rentai_rate = 0;
	if($p_num <= $performance_data[0][$i+1])
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // 対局者
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // 対局数
		$i++;
		if($performance_data[0][$i] <= 2)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // 平均順位
		}
		elseif($performance_data[0][$i] >= 3)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // 平均順位
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // 平均順位
		}
		$i++;
		if($performance_data[0][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // スコア
		}
		else if($performance_data[0][$i] >= 100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // スコア
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // スコア
		}
		$i++;
		if($performance_data[0][$i] <= 999)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // レーティング
		}
		else if($performance_data[0][$i] >= 1700)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // レーティング
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // レーティング
		}
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // １位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // ２位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // ３位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // ４位
		if(($performance_data[0][$i-3]+$performance_data[0][$i-2]) > 0)
		{
			$rentai_rate = round(($performance_data[0][$i-3]+$performance_data[0][$i-2])/$performance_data[0][$i-7]*100,1);
		}
		if($rentai_rate >= 50)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$rentai_rate."</font></td></tr>\r\n"; // 連対率
		}
		else if($rentai_rate < 20)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$rentai_rate."</font></td></tr>\r\n"; // 連対率
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$rentai_rate."</td></tr>\r\n"; // 連対率
		}
		$i++;
	}
	// 規定数未満
	else
	{
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // 対局者
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // 対局数
		$i++;
		if($performance_data[0][$i] <= 2)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // 平均順位
		}
		elseif($performance_data[0][$i] >= 3)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // 平均順位
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // 平均順位
		}
		$i++;
		if($performance_data[0][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // スコア
		}
		else if($performance_data[0][$i] >= 100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // スコア
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // スコア
		}
		$i++;
			if($performance_data[0][$i] <= 999)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // レーティング
		}
		else if($performance_data[0][$i] >= 1700)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // レーティング
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // レーティング
		}
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // １位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // ２位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // ３位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // ４位
		if(($performance_data[0][$i-3]+$performance_data[0][$i-2]) > 0)
		{
			$rentai_rate = round(($performance_data[0][$i-3]+$performance_data[0][$i-2])/$performance_data[0][$i-7]*100,1);
		}
		if($rentai_rate >= 50)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$rentai_rate."</font></td></tr>\r\n"; // 連対率
		}
		else if($rentai_rate < 20)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$rentai_rate."</font></td></tr>\r\n"; // 連対率
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$rentai_rate."</td></tr>\r\n"; // 連対率
		}
		$i++;
	}
}
?>
</table></Td></Tr></Table>
<br><br>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<b>Ｓリーグ</b>
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局者</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局数</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>平均順位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>スコア</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>レーティング</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>１位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>２位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>３位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>４位</b></td>
</tr>
<?php
for($i = 0; $i < count($performance_data[1]);)
{
	if($p_num <= $performance_data[1][$i+1])
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // 対局者
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // 対局数
		$i++;
		if($performance_data[1][$i] <= 2)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // 平均順位
		}
		elseif($performance_data[1][$i] >= 3)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // 平均順位
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // 平均順位
		}
		$i++;
		if($performance_data[1][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // スコア
		}
		else if($performance_data[1][$i] >= 100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // スコア
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // スコア
		}
		$i++;
			if($performance_data[1][$i] <= 999)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // レーティング
		}
		else if($performance_data[1][$i] >= 1700)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // レーティング
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // レーティング
		}
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // １位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // ２位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // ３位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td></tr>\r\n"; // ４位
		$i++;
	}
	else
	{
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // 対局者
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // 対局数
		$i++;
		if($performance_data[1][$i] <= 2)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // 平均順位
		}
		elseif($performance_data[1][$i] >= 3)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // 平均順位
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // 平均順位
		}
		$i++;
		if($performance_data[1][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // スコア
		}
		else if($performance_data[1][$i] >= 100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // スコア
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // スコア
		}
		$i++;
			if($performance_data[1][$i] <= 999)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // レーティング
		}
		else if($performance_data[1][$i] >= 1700)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // レーティング
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // レーティング
		}
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // １位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // ２位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // ３位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td></tr>\r\n"; // ４位
		$i++;
	}
}
?>
</table></Td></Tr></Table>
<br><br>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<b>Ａリーグ</b>
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局者</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局数</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>平均順位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>スコア</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>レーティング</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>１位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>２位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>３位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>４位</b></td>
</tr>
<?php
for($i = 0; $i < count($performance_data[2]);)
{
	if($p_num <= $performance_data[2][$i+1])
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // 対局者
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // 対局数
		$i++;
		if($performance_data[2][$i] <= 2)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // 平均順位
		}
		elseif($performance_data[2][$i] >= 3)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // 平均順位
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // 平均順位
		}
		$i++;
		if($performance_data[2][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // スコア
		}
		else if($performance_data[2][$i] >= 100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // スコア
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // スコア
		}
		$i++;
			if($performance_data[2][$i] <= 999)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // レーティング
		}
		else if($performance_data[2][$i] >= 1700)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // レーティング
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // レーティング
		}
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // １位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // ２位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // ３位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td></tr>\r\n"; // ４位
		$i++;
	}
	else
	{
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // 対局者
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // 対局数
		$i++;
		if($performance_data[2][$i] <= 2)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // 平均順位
		}
		elseif($performance_data[2][$i] >= 3)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // 平均順位
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // 平均順位
		}
		$i++;
		if($performance_data[2][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // スコア
		}
		else if($performance_data[2][$i] >= 100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // スコア
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // スコア
		}
		$i++;
			if($performance_data[2][$i] <= 999)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // レーティング
		}
		else if($performance_data[2][$i] >= 1700)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // レーティング
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // レーティング
		}
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // １位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // ２位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // ３位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td></tr>\r\n"; // ４位
		$i++;
	}
}
?>
</table></Td></Tr></Table>
<br><br>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<b>Ｂリーグ</b>
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局者</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局数</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>平均順位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>スコア</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>レーティング</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>１位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>２位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>３位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>４位</b></td>
</tr>
<?php
for($i = 0; $i < count($performance_data[3]);)
{
	if($p_num <= $performance_data[3][$i+1])
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // 対局者
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // 対局数
		$i++;
		if($performance_data[3][$i] <= 2)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // 平均順位
		}
		elseif($performance_data[3][$i] >= 3)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // 平均順位
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // 平均順位
		}
		$i++;
		if($performance_data[3][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // スコア
		}
		else if($performance_data[3][$i] >= 100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // スコア
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // スコア
		}
		$i++;
			if($performance_data[3][$i] <= 999)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // レーティング
		}
		else if($performance_data[3][$i] >= 1700)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // レーティング
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // レーティング
		}
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // １位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // ２位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // ３位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td></tr>\r\n"; // ４位
		$i++;
	}
	else
	{
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // 対局者
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // 対局数
		$i++;
		if($performance_data[3][$i] <= 2)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // 平均順位
		}
		elseif($performance_data[3][$i] >= 3)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // 平均順位
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // 平均順位
		}
		$i++;
		if($performance_data[3][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // スコア
		}
		else if($performance_data[3][$i] >= 100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // スコア
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // スコア
		}
		$i++;
			if($performance_data[3][$i] <= 999)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // レーティング
		}
		else if($performance_data[3][$i] >= 1700)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // レーティング
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // レーティング
		}
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // １位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // ２位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // ３位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td></tr>\r\n"; // ４位
		$i++;
	}
}
?>
</table></Td></Tr></Table>
<br><br>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<b>Ｃリーグ</b>
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局者</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局数</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>平均順位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>スコア</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>レーティング</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>１位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>２位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>３位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>４位</b></td>
</tr>
<?php
for($i = 0; $i < count($performance_data[4]);)
{
	if($p_num <= $performance_data[4][$i+1])
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // 対局者
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // 対局数
		$i++;
		if($performance_data[4][$i] <= 2)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // 平均順位
		}
		elseif($performance_data[4][$i] >= 3)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // 平均順位
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // 平均順位
		}
		$i++;
		if($performance_data[4][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // スコア
		}
		else if($performance_data[4][$i] >= 100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // スコア
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // スコア
		}
		$i++;
			if($performance_data[4][$i] <= 999)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // レーティング
		}
		else if($performance_data[4][$i] >= 1700)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // レーティング
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // レーティング
		}
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // １位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // ２位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // ３位
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td></tr>\r\n"; // ４位
		$i++;
	}
	else
	{
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // 対局者
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // 対局数
		$i++;
		if($performance_data[4][$i] <= 2)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // 平均順位
		}
		elseif($performance_data[4][$i] >= 3)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // 平均順位
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // 平均順位
		}
		$i++;
		if($performance_data[4][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // スコア
		}
		else if($performance_data[4][$i] >= 100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // スコア
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // スコア
		}
		$i++;
			if($performance_data[4][$i] <= 999)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // レーティング
		}
		else if($performance_data[4][$i] >= 1700)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // レーティング
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // レーティング
		}
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // １位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // ２位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // ３位
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td></tr>\r\n"; // ４位
		$i++;
	}
}
?>
</table></Td></Tr></Table>
</form>
<?php } ?>
</div></body></html>
