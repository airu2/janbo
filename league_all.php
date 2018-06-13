<?php
#┌────────────────────────
#│MahjongscoreProject
#│ league_all.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/11/03 新規作成 by airu
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_ymd_month = @$_POST['ymd_month'];        # 年月日（月検索）
$p_ymd_setu = @$_POST['ymd_setu'];          # 年月日（節検索）
$search_value = @$_POST['search_value'];    # 検索区分
$p_kbn = @$_POST['kbn'];                    # 集計区分
$p_num = @$_POST['num'];                    # 試合規定数
$p_uid2 = @$_POST['uid'];                   # ログインユーザＩＤ
$p_pwd2 = @$_POST['pass'];                  # ログインパスワード
$p0_select="";                              # 集計区分（東風）セレクト
$p1_select="";                              # 集計区分（半荘）セレクト
$p2_select="";                              # 集計区分（全て）セレクト
if($p_num == "")
{
	$p_num = 0;
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
<script type="text/javascript">
<!--
function checks(flg) {
if(flg == 1)
{
	document.myFORM.ymd_setu.disabled = true;
} else {
	document.myFORM.ymd_setu.disabled = false;
}


if(flg == 2)
{
	document.myFORM.ymd_month.disabled = true;
} else {
	document.myFORM.ymd_month.disabled = false;
}

}
//-->
</script>
<?php
// 管理者パスワードチェック
$admin_chk = adminpass_check($p_pwd2,$p_uid2);
// ユーザチェック(DB)
$user_chk = USERID_CNT3($p_uid2,$p_pwd2);
// 登録されている対局年月日の最小と最大を取得
$score_ymd = UNSCORE_YMD_SELECTS();
// プルダウンメニュー作成(年月、節）
$ymd_search = ymd_select($score_ymd[0],$score_ymd[1],"");
// ユーザチェックがNGの場合、ID,PASSWORDのエリアは空白とする(一般公開用)
if(!$admin_chk && !$user_chk)
{
	$p_uid2 = "";
	$p_pwd2 = "";
}
###### ボタンが押された場合の処理 ######
if (@$_SERVER["REQUEST_METHOD"]=="POST")
{
	// 検索処理
	if(@$_POST["regit"])
	{
		if($p_kbn == 0) { $p0_select="selected"; }
		if($p_kbn == 1) { $p1_select="selected"; }
		else { $p2_select="selected"; }
		$err = "";
		$err .= moji_check_ad($p_num,1,3,"試合規定数");                     // 試合規定数の文字長・入力チェック
		// エラーでない場合
		if($err == "")
		{
			// ゲストがユーザの場合、ユーザIDを仮発行する(IPアドレスを数値化）
			// ゲストユーザの場合、ユーザが存在しないため複数アクセス時、共存してしまい
			// 検索に不具合が生じる可能性があるため仮発行する
			if($p_uid2 == "")
			{
				// IPアドレスを取得して変数にセットする
				$ipAddress = $_SERVER["REMOTE_ADDR"];
				// IPアドレスを数値として取得する場合
				$ipLong = ip2long($ipAddress);
				// 仮ユーザID発行
				$p_uid2 = "[".$ipLong."]";
			}
			// 月検索を選択した場合
			if($search_value == 1)
			{
				$p_from_ymd = substr($p_ymd_month,  0, 10);
				$p_to_ymd   = substr($p_ymd_month, 10, 10);
			}
			// それ以外の場合
			else
			{
				$p_from_ymd = substr($p_ymd_setu,  0, 10);
				$p_to_ymd   = substr($p_ymd_setu, 10, 10);
			}
			// wk_performanceデータ削除
			WK_PERFORMANCE_DELETE($p_uid2);
			// wk_performanceにデータ書込
			SCORE_CNT($p_from_ymd,$p_to_ymd,$p_kbn,$p_uid2,$p_num);
			// データ取得
			$performance_data = PERFORMANCE_SELECT($p_uid2);
			// ゲストデータ取得
			$guest_score = GUEST_SCORE($p_from_ymd,$p_to_ymd,($p_kbn + 100));
			// データ作成
			create_file($performance_data[0],$performance_data[1],$performance_data[2],$performance_data[3],$performance_data[4],$p_uid2,$p_num,$p_kbn,$p_from_ymd,$p_to_ymd,1);
			if($p_kbn == 0) {$syubetsu="東風戦";}
			else if($p_kbn == 1){ $syubetsu="半荘戦";}
			else { $syubetsu="全て";}
			if($p_uid2 !="")
			{
				LOG_INSERT($p_uid2,"一般対局成績照会（一般用）",0,"ＦＲＯＭ：".$p_from_ymd."　ＴＯ：".$p_to_ymd."　対局種別：".$syubetsu."　試合規定数：".$p_num);
			}
		}
		###### エラーメッセージ表示 ######
		if($err != "")
		{
			echo $err;
		}
		// 検索条件等をPOST後も引き継ぐ
		if($search_value == 1)
		{
			$g1_check = "checked";
			$g2_check = "";
			$ymd_month_useflg = "";
			$ymd_setu_useflg = "disabled";
			// プルダウンメニュー作成(年月）
			$ymd_search = ymd_select($score_ymd[0],$score_ymd[1],$p_ymd_month);
		}
		else
		{
			$g1_check = "";
			$g2_check = "checked";
			$ymd_month_useflg = "disabled";
			$ymd_setu_useflg = "";
			// プルダウンメニュー作成(年月）
			$ymd_search = ymd_select($score_ymd[0],$score_ymd[1],$p_ymd_setu);
		}
	}
	else
	{
		// 初期検索を月検索とする
		$g1_check ="checked";
	}
}
else
{
	// 初期検索を月検索とする
	$g1_check ="checked";
}
?>

<title><?php echo boardtitle ?></title></head>
<body background="<?php echo background ?>" bgcolor="<?php echo bgcolor ?>" text="#000000" link="#0000FF" vlink="#800080" alink="#DD0000">
<div id="container">
<?php // パスワード認証成功の場合は、フォーム表示
if($admin_chk || ($user_chk != -1 && $user_chk != 0) && @$_POST['menu9'] != "")
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
<?php } ?>
<Table border="0" cellspacing="0" cellpadding="0" width="100%">
<Tr bgcolor="<?php echo line_color ?>"><Td bgcolor="<?php echo line_color ?>"  class="td0">
<table border="0" cellspacing="1" cellpadding="5" width="100%">
<tr bgcolor="<?php echo in_bgcolor ?>"><td bgcolor="<?php echo in_bgcolor ?>" nowrap width="100%" class="td0">
<img src="./img/gurafu.png" align="middle">
&nbsp; <b>一般対局成績照会（一般用）</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='menu9' value="menu9">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">対局期間<?php echo hissu ?></th>
  <td>
  <select name="ymd_month" <?php echo $ymd_month_useflg ?>>
  <?php echo $ymd_search[0] ?>
  </select>
  <input type=radio name=search_value value="1"  <?php echo $g1_check ?> onClick="checks(1)">年月検索(1ヶ月検索)
  <br>
  <select name="ymd_setu" <?php echo $ymd_setu_useflg ?>>
  <?php echo $ymd_search[1] ?>
  </select>
  <input type=radio name=search_value value="2"  <?php echo $g2_check ?> onClick="checks(2)">節検索(3ヶ月検索)
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
集計期間：<?php if(@$_POST["regit"] && $err =="") { echo $p_from_ymd ?>　～　<?php echo $p_to_ymd; } ?><br>
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
<?php }
// 節検索を不可視(初期状態)
if (!@$_POST["regit"])
{
	echo "<script language=javascript>checks(1)</script>";
}
?>
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
		if($performance_data[0][$i] < 0 && $performance_data[0][$i] > -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>▲".($performance_data[0][$i])*(-1)."</td>"; // スコア
		}
		else if($performance_data[0][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>▲".($performance_data[0][$i])*(-1)."</font></td>"; // スコア
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
		if($performance_data[0][$i] < 0 && $performance_data[0][$i] > -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>▲".($performance_data[0][$i])*(-1)."</td>"; // スコア
		}
		else if($performance_data[0][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>▲".($performance_data[0][$i])*(-1)."</font></td>"; // スコア
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
</form>
</div></body></html>
