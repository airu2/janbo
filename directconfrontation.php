<?php
#┌────────────────────────
#│MahjongscoreProject
#│ directconfrontation.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/05/20 新規作成 by candy
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
$p_uid2 = @$_POST['uid'];                   # ログインユーザＩＤ
$p_pwd2 = @$_POST['pass'];                  # ログインパスワード
$p_ukbn = @$_POST['u_kbn'];                 # ログイン区分（0:管理者、1:一般）
$playername = @$_POST['player'];            # プレーヤー名
$p0_select="";                              # 集計区分（東風）セレクト
$p1_select="";                              # 集計区分（半荘）セレクト
$p2_select="";                              # 集計区分（全て）セレクト
// メニューから遷移された場合は、検索初期値を設定
if(@$_POST['menu11'] != "")
{
	$p_from_y = date("Y");                  # FROM(年)
	$p_from_m = date("n");                  # FROM(月)
	$p_from_d = 1;                          # FROM(日)
	$p_to_y = date("Y");                    # TO(年)
	$p_to_m = date("n");                    # TO(月)
	$p_to_d = date("j");                    # TO(日)
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
	LOG_INSERT($p_uid2,"直接対決結果照会（一般対局）",1,err0015."ユーザＩＤ：".$p_uid2);
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
				if (strtotime($p_from_y."-".$p_from_m."-".$p_from_d) > strtotime($p_to_y."-".$p_to_m."-".$p_to_d))
				{
					$err .= "<img src='./img/error.png' align='middle'>".err0011."<BR>";
				}
			}
			// エラーでない場合
			if($err == "")
			{
				if($p_kbn == 0) {$syubetsu="東風戦";}
				else if($p_kbn == 1) {$syubetsu="半荘戦";}
				else{ $syubetsu="全て";}
				LOG_INSERT($p_uid2,"直接対決結果",0,"ＦＲＯＭ：".($p_from_y."/".$p_from_m."/".$p_from_d)."　ＴＯ：".($p_to_y."/".$p_to_m."/".$p_to_d)."　集計区分：".$syubetsu."　検索対象ユーザ：".$playername);
				// wk_confrontationデータ削除
				WK_CONFRONTATION_DELETE($p_uid2);
				// wk_confrontationにデータ書込
				CONFRONTATION_CREATE(($p_from_y."/".$p_from_m."/".$p_from_d),($p_to_y."/".$p_to_m."/".$p_to_d),$p_kbn,$playername,$p_uid2);
				// データ取得
				$confrontation_data = CONFRONTATION_SELECT($p_uid2);
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
	<input type=hidden name='u_kbn' value=<?php echo $p_ukbn ?>>
	<input type=hidden name='job' value="back">
  </form>
</tr></table>
<Table border="0" cellspacing="0" cellpadding="0" width="100%">
<Tr bgcolor="<?php echo line_color ?>"><Td bgcolor="<?php echo line_color ?>"  class="td0">
<table border="0" cellspacing="1" cellpadding="5" width="100%">
<tr bgcolor="<?php echo in_bgcolor ?>"><td bgcolor="<?php echo in_bgcolor ?>" nowrap width="100%" class="td0">
<img src="./img/id_card_view.png" align="middle">
&nbsp; <b>直接対決結果照会（一般対局）</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type=hidden name='u_kbn' value=<?php echo $p_ukbn ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">検索対象ユーザ<?php echo hissu ?></th>
  <td>
  <?php if($p_ukbn == 1)  {
  $myuser = MYUSERNAME($p_uid2);
  echo $myuser ?>
  <input type=hidden name=player value=<?php echo $myuser ?>>
 <?php } else { ?>
 <select name="player" >
  <?php  $member = USERNAME2($playername,"","","");
  echo $member[0]; ?>
  </select>
  <?php } ?>
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">対局日付<?php echo hissu ?></th>
  <td><input type="text" name="from_y" style="ime-mode:disabled" value="<?php echo $p_from_y  ?>" maxlength="4" size="6">/
  <input type="text" name="from_m" style="ime-mode:disabled" value="<?php echo $p_from_m  ?>" maxlength="2" size="3">/
  <input type="text" name="from_d" style="ime-mode:disabled" value="<?php echo $p_from_d  ?>" maxlength="2" size="3">　～　
  <input type="text" name="to_y" style="ime-mode:disabled" value="<?php echo $p_to_y  ?>" maxlength="4" size="6">/
  <input type="text" name="to_m" style="ime-mode:disabled" value="<?php echo $p_to_m  ?>" maxlength="2" size="3">/
  <input type="text" name="to_d" style="ime-mode:disabled" value="<?php echo $p_to_d  ?>" maxlength="2" size="3">
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
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="直接対決結果照会">&nbsp;&nbsp;
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## ヘッダ表示部 ########## -->
<BR>
<table border="0" cellspacing="1" cellpadding="5" width="50%">
集計期間：<?php if(@$_POST["regit"] && $err =="") { echo $p_from_y."/".$p_from_m."/".$p_from_d ?>　～　<?php echo $p_to_y."/".$p_to_m."/".$p_to_d; } ?><br>
集計区分：<?php if(@$_POST["regit"] && $err =="") { echo $p_kbn."（".sum_kbn($p_kbn)."）"; } ?><br>
</table>
<table border="0" cellspacing="1" cellpadding="5" width="50%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局者</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局数</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>勝</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>敗</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>勝率</b></td>
</tr>
<?php echo $confrontation_data ?>
</table></Td></Tr></Table>
</form>
<?php } ?>
</div></body></html>
