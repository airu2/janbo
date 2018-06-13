<?php
#┌────────────────────────
#│MahjongscoreProject
#│ score_fix.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/04/13 新規作成 by airu
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_1playername = @$_POST['player1'];            # １位プレーヤー名
$p_2playername = @$_POST['player2'];            # ２位プレーヤー名
$p_3playername = @$_POST['player3'];            # ３位プレーヤー名
$p_4playername = @$_POST['player4'];            # ４位プレーヤー名
$p_1score = @$_POST['score1'];                  # １位スコア
$p_2score = @$_POST['score2'];                  # ２位スコア
$p_3score = @$_POST['score3'];                  # ３位スコア
$p_4score = @$_POST['score4'];                  # ４位スコア
$p_guest1 =  @$_POST['guest1'];                 # ゲスト１
$p_guest2 =  @$_POST['guest2'];                 # ゲスト２
$p_guest3 =  @$_POST['guest3'];                 # ゲスト３
$p_guest4 =  @$_POST['guest4'];                 # ゲスト４
$p_kbn = @$_POST['kbn2'];                       # プレイ区分(0:東風戦、1:半荘戦)
$p_firstscore = @$_POST['1stscore'];            # 原点(スコア計算時に使用)
$p_resscore = @$_POST['resscore'];              # 返し点(スコア計算時に使用)

$p_uid2 = @$_POST['uid'];                   # ログインユーザＩＤ
$p_pwd2 = @$_POST['pass'];                  # ログインパスワード
$p_kbn2 = @$_POST['kbn'];                   # ログイン区分
$p_syuseino = @$_POST['syuseino'];          # 修正対象
$g1_check = "";   # ゲスト１チェックなし状態
$g2_check = "";   # ゲスト２チェックなし状態
$g3_check = "";   # ゲスト３チェックなし状態
$g4_check = "";   # ゲスト４チェックなし状態
$p1_useflg = "";  # 対戦者１選択可能状態
$p2_useflg = "";  # 対戦者２選択可能状態
$p3_useflg = "";  # 対戦者３選択可能状態
$p4_useflg = "";  # 対戦者４選択可能状態
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
<script type="text/javascript">
<!--
function checks(guest_player) {
// １位
if(guest_player ==1)
{
	if(document.myFORM.guest1.checked)
	{
		document.myFORM.player1.disabled = true;
	} else {
		document.myFORM.player1.disabled = false;
	}
}
//２位
if(guest_player ==2)
{
	if(document.myFORM.guest2.checked)
	{
		document.myFORM.player2.disabled = true;
	} else {
		document.myFORM.player2.disabled = false;
	}
}
//３位
if(guest_player ==3)
{
	if(document.myFORM.guest3.checked)
	{
		document.myFORM.player3.disabled = true;
	} else {
		document.myFORM.player3.disabled = false;
	}
}
//４位
if(guest_player ==4)
{
	if(document.myFORM.guest4.checked)
	{
		document.myFORM.player4.disabled = true;
	} else {
		document.myFORM.player4.disabled = false;
	}
}
}
//-->
</script>
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
	LOG_INSERT($p_uid2,"対局情報修正（一般対局）",1,err0015."ユーザＩＤ：".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='ログイン画面' onClick=location.href='./login.php'>";
}
else
{
	###### ボタンが押された場合の処理 ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{
		// 修正処理
		if(@$_POST["syusei"])
		{
			$tonpu_select = "";
			$hancyan_select = "";
			$fscore_20000 = "";
			$fscore_25000 = "";
			$fscore_26000 = "";
			$fscore_27000 = "";
			$fscore_30000 = "";
			$rscore_25000 = "";
			$rscore_30000 = "";
			$rscore_35000 = "";
			$fix_list = SCORE_FIX_SELECT($p_syuseino);
			$p_1score = $fix_list[5];  // １位スコア
			$p_2score = $fix_list[8];  // ２位スコア
			$p_3score = $fix_list[11];  // ３位スコア
			$p_4score = $fix_list[14]; // ４位スコア
			// 対局種別
			if($fix_list[0] == 0 || $fix_list[0] == 100)
			{
				$tonpu_select = "selected";
			} else {
				$hancyan_select = "selected";
			}
			// 配給原点
			if($fix_list[1] == 20000)
			{
				$fscore_20000 = "selected";
			}
			else if($fix_list[1] == 25000){
				$fscore_25000 = "selected";
			}
			else if($fix_list[1] == 26000){
				$fscore_26000 = "selected";
			}
			else if($fix_list[1] == 27000){
				$fscore_27000 = "selected";
			}
			else if($fix_list[1] == 30000){
				$fscore_30000 = "selected";
			}
			// 原点
			if($fix_list[2] == 25000)
			{
				$rscore_25000 = "selected";
			}
			else if($fix_list[2] == 30000)
			{
				$rscore_30000 = "selected";
			}
			else
			{
				$rscore_35000 = "selected";
			}
			// 対局区分（１位）
			if($fix_list[0] == 100 || $fix_list[0] == 101)
			{
				$g1_check ="checked";
				$p1_useflg = "disabled";
			}
			// 対局区分（２位）
			if($fix_list[6] == 100 || $fix_list[6] == 101)
			{
				$g2_check ="checked";
				$p2_useflg = "disabled";
			}
			// 対局区分（３位）
			if($fix_list[9] == 100 || $fix_list[9] == 101)
			{
				$g3_check ="checked";
				$p3_useflg = "disabled";
			}
			// 対局区分（４位）
			if($fix_list[12] == 100 || $fix_list[12] == 101)
			{
				$g4_check ="checked";
				$p4_useflg = "disabled";
			}
			// ユーザ情報取得（プルダウン表示用）
			$user = USERNAME2($fix_list[4],$fix_list[7],$fix_list[10],$fix_list[13]);
			LOG_INSERT($p_uid2,"対局情報修正（一般対局）",0,"スコア修正対象No：".$p_syuseino);
		}
		// 更新処理
		if(@$_POST["regit"])
		{
			$err = "";
			$tonpu_select = "";
			$hancyan_select = "";
			$fscore_20000 = "";
			$fscore_25000 = "";
			$fscore_26000 = "";
			$fscore_27000 = "";
			$fscore_30000 = "";
			$rscore_25000 = "";
			$rscore_30000 = "";
			$rscore_35000 = "";
			// 対局種別
			if($p_kbn == 0)
			{
				$tonpu_select = "selected";
			} else {
				$hancyan_select = "selected";
			}
			// 配給原点
			if($p_firstscore == 20000)
			{
				$fscore_20000 = "selected";
			}
			else if($p_firstscore == 25000){
				$fscore_25000 = "selected";
			}
			else if($p_firstscore == 26000){
				$fscore_26000 = "selected";
			}
			else if($p_firstscore == 27000){
				$fscore_27000 = "selected";
			}
			else {
				$fscore_30000 = "selected";
			}
			// 原点
			if($p_resscore == 25000)
			{
				$rscore_25000 = "selected";
			}
			else if($p_resscore == 30000){
				$rscore_30000 = "selected";
			}
			else {
				$rscore_35000 = "selected";
			}
			$t_kbn1 = $p_kbn;
			$t_kbn2 = $p_kbn;
			$t_kbn3 = $p_kbn;
			$t_kbn4 = $p_kbn;
			// １位の人がゲストの場合
			if($p_guest1 == 1)
			{
				$t_kbn1 = $t_kbn1 + 100; // 区分+100をゲスト区分とする
				$p_1playername = "ゲスト１";
				$g1_check = "checked";
				$p1_useflg = "disabled";
			}
			// ２位の人がゲストの場合
			if($p_guest2 == 1)
			{
				$t_kbn2 = $t_kbn2 + 100; // 区分+100をゲスト区分とする
				$p_2playername = "ゲスト２";
				$g2_check = "checked";
				$p2_useflg = "disabled";
			}
			// ３位の人がゲストの場合
			if($p_guest3 == 1)
			{
				$t_kbn3 = $t_kbn3 + 100; // 区分+100をゲスト区分とする
				$p_3playername = "ゲスト３";
				$g3_check = "checked";
				$p3_useflg = "disabled";
			}
			// ４位の人がゲストの場合
			if($p_guest4 == 1)
			{
				$t_kbn4 = $t_kbn4 + 100; // 区分+100をゲスト区分とする
				$p_4playername = "ゲスト４";
				$g4_check = "checked";
				$p4_useflg = "disabled";
			}
			// ユーザ情報取得（プルダウン表示用）
			$user = USERNAME2($p_1playername,$p_2playername,$p_3playername,$p_4playername);
			// 必須入力チェック
			$err .= moji_check_ad($p_1score,1,7,"１位スコア");
			$err .= moji_check_ad($p_2score,1,7,"２位スコア");
			$err .= moji_check_ad($p_3score,1,7,"３位スコア");
			$err .= moji_check_ad($p_4score,1,7,"４位スコア");
			// 範囲チェック
			$err .= num_check($p_1score,-300000,400000,"１位スコア");
			$err .= num_check($p_2score,-300000,400000,"２位スコア");
			$err .= num_check($p_3score,-300000,400000,"３位スコア");
			$err .= num_check($p_4score,-300000,400000,"４位スコア");
			// 1,2,3,4位スコア入力チェック(1位 < 2位等の点数になっていないかチェック)
			if($p_1score < $p_2score || $p_2score < $p_3score || $p_3score < $p_4score)
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0005."<BR>";
			}
			// ４人の合計スコアが原点×４より大きい場合もエラーとする
			if(($p_1score + $p_2score + $p_3score + $p_4score) > ($p_firstscore * 4))
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0006."<BR>";
			}
			// １００点単位で入力されていない場合もエラーとする
			if($p_1score % 100 != 0 || $p_2score % 100 != 0 || $p_3score % 100 != 0 || $p_4score % 100 != 0)
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0007."<BR>";
			}
			// 対戦者重複チェック
			if($p_1playername == $p_2playername || $p_1playername == $p_3playername || $p_1playername == $p_4playername ||
					$p_2playername == $p_3playername || $p_2playername == $p_4playername || $p_3playername == $p_4playername)
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0009."<BR>";
			}
			// 配給原点＞原点の場合もエラーとする
			if($p_firstscore > $p_resscore)
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0023."<BR>";
			}
			// エラーでない場合
			if($err == "")
			{
				// ４位スコア算出
				$score_p4 = reckoning(($p_4score - $p_resscore) / 1000);
				// ３位スコア算出
				$score_p3 = reckoning(($p_3score - $p_resscore) / 1000);
				// ２位スコア算出
				$score_p2 = reckoning(($p_2score - $p_resscore) / 1000);
				// １位スコア算出
				$score_p1 = ($score_p4 + $score_p3 + $score_p2) * (-1);
				// 配給原点人数算出
				$score_list = firstscore_num($p_1score,$p_2score,$p_3score,$p_4score,$p_firstscore);
				// １位のリーグスコア算出
				$lpscore_1p = 10;
				$mfcscore_1p = 4;
				if($score_list[0])
				{
					$lpscore_1p = $lpscore_1p +3 +(($p_1score - $p_firstscore) / 1000);
				}
				else
				{
					$lpscore_1p = $lpscore_1p -3 +(($p_1score - $p_firstscore) / 1000);
				}

				$lpscore_1p = $lpscore_1p +hosei($score_list[0],$score_list[4]);
				$mfcscore_1p = $mfcscore_1p + (($p_1score - $p_firstscore) / 10000);
				// ２位のリーグスコア算出
				$lpscore_2p = 5;
				$mfcscore_2p = 3;
				if($score_list[1])
				{
					$lpscore_2p = $lpscore_2p +3 +(($p_2score - $p_firstscore) / 1000);
				}
				else
				{
					$lpscore_2p = $lpscore_2p -3 +(($p_2score - $p_firstscore) / 1000);
				}
				$lpscore_2p = $lpscore_2p +hosei($score_list[1],$score_list[4]);
				$mfcscore_2p = $mfcscore_2p + (($p_2score - $p_firstscore) / 10000);
				// ３位のリーグスコア算出
				$lpscore_3p = -5;
				$mfcscore_3p = 2;
				if($score_list[2])
				{
					$lpscore_3p = $lpscore_3p +3 +(($p_3score - $p_firstscore) / 1000);
				}
				else
				{
					$lpscore_3p = $lpscore_3p -3 +(($p_3score - $p_firstscore) / 1000);
				}
				$lpscore_3p = $lpscore_3p +hosei($score_list[2],$score_list[4]);
				$mfcscore_3p = $mfcscore_3p + (($p_3score - $p_firstscore) / 10000);
				// ４位のリーグスコア算出
				$lpscore_4p = -10;
				$mfcscore_4p = 1;
				if($score_list[3])
				{
					$lpscore_4p = $lpscore_4p +3 +(($p_4score - $p_firstscore) / 1000);
				}
				else
				{
					$lpscore_4p = $lpscore_4p -3 +(($p_4score - $p_firstscore) / 1000);
				}
				$lpscore_4p = $lpscore_4p +hosei($score_list[3],$score_list[4]);
				$mfcscore_4p = $mfcscore_4p + (($p_4score - $p_firstscore) / 10000);
				// 累計スコア更新
				SCORE_UPDATE($p_syuseino,$t_kbn1,1,$p_1playername,$p_1score,$score_p1,$lpscore_1p,$lpscore_1p*10,$mfcscore_1p,$p_firstscore,$p_resscore);
				SCORE_UPDATE($p_syuseino,$t_kbn2,2,$p_2playername,$p_2score,$score_p2,$lpscore_2p,$lpscore_2p*10,$mfcscore_2p,$p_firstscore,$p_resscore);
				SCORE_UPDATE($p_syuseino,$t_kbn3,3,$p_3playername,$p_3score,$score_p3,$lpscore_3p,$lpscore_3p*10,$mfcscore_3p,$p_firstscore,$p_resscore);
				SCORE_UPDATE($p_syuseino,$t_kbn4,4,$p_4playername,$p_4score,$score_p4,$lpscore_4p,$lpscore_4p*10,$mfcscore_4p,$p_firstscore,$p_resscore);
				// レート計算フラグ更新
				RATECALC_FLG(0);
				if($t_kbn1 == 0 || $t_kbn1 == 100) {$syubetsu="東風戦";}
				else{ $syubetsu="半荘戦";}
				LOG_INSERT($p_uid2,"対局情報修正（一般対局）",2,"累計No：".$p_syuseino."　対局種別：".$syubetsu);
				LOG_INSERT($p_uid2,"対局情報修正（一般対局）",2,"１位：".$p_1playername."　".mb_convert_kana($p_1score,A,"shift_jis")."点　２位：".$p_2playername."　".mb_convert_kana($p_2score,A,"shift_jis")."点　３位：".$p_3playername."　".mb_convert_kana($p_3score,A,"shift_jis")."点　４位：".$p_4playername."　".mb_convert_kana($p_4score,A,"shift_jis")."点");

			}
			###### エラーメッセージ表示 ######
			 echo "<table>".$err."</table>";
			 if($err != ""){
			 }
			 ###### データ登録メッセージ表示 ######
			 else
			 {
			 	$info = msg0003;
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
}

// ユーザ登録メッセージが設定されていない場合、フォーム画面を表示する
if($info == "")
{
?>

<div id="container">
<?php // パスワード認証成功の場合は、フォーム表示
if($admin_chk || ($user_chk != -1 && $user_chk != 0))
{
	$fix_data = SCORE_FIX_DATA($p_uid2,$p_kbn2,$p_syuseino);
?>
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
<img src="./img/money.png" align="middle">
&nbsp; <b><?php if ($p_kbn2 == 1 ){ echo "当月対局情報修正 "; } else { echo "全対局情報修正"; } ?></b></td>
</tr></table></Td></Tr></Table>
<?php
if($fix_data == "")
{
	echo"<img src='./img/error.png' align='middle'> ".err0016;
} else { ?>
<form action="#" method="post" name="myFORM2" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type=hidden name='kbn' value=<?php echo $p_kbn2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">修正対局<?php echo hissu ?></th>
  <td>
  <select name="syuseino">
  <?php echo SCORE_FIX_DATA($p_uid2,$p_kbn2,$p_syuseino); ?>
  </select>
    </td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=syusei style="font-size:15pt;background:#FF66AA" value="修正">
</tr>
</tr></table>
</Td></Tr></Table></form>

<?php
// 修正処理
if(@$_POST["syusei"]|| (@$_POST["regit"] && $err !=""))
{ ?>
<!-- ########## ヘッダ表示部 ########## -->
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<BR>
<center>
<img src='./img/download.png' align='center'>
</center>
<BR>
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type=hidden name='kbn' value=<?php echo $p_kbn2 ?>>
<input type="hidden" name=syuseino value=<?php echo $p_syuseino ?>>
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">１位<?php echo hissu ?><img src='./img/gold.png'></th>
  <td>
  <select name="player1" <?php echo $p1_useflg ?>>
  <?php echo $user[0] ?>
  </select>
  最終点数：<input type="text" name="score1" size="10" value="<?php echo $p_1score  ?>"  maxlength="7" style="ime-mode:disabled">
  <input type=checkbox name=guest1 value="1"  <?php echo $g1_check ?> onClick="checks(1)">ゲストとして使用する
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">２位<?php echo hissu ?></th>
  <td>
  <select name="player2" <?php echo $p2_useflg ?>>
  <?php echo $user[1] ?>
  </select>
  最終点数：<input type="text" name="score2" size="10" value="<?php echo $p_2score  ?>"  maxlength="7" style="ime-mode:disabled">
  <input type=checkbox name=guest2 value="1"  <?php echo $g2_check ?> onClick="checks(2)">ゲストとして使用する
  </td>

</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">３位<?php echo hissu ?></th>
  <td>
  <select name="player3" <?php echo $p3_useflg ?>>
  <?php echo $user[2] ?>
  </select>
  最終点数：<input type="text" name="score3" size="10" value="<?php echo $p_3score  ?>"  maxlength="7" style="ime-mode:disabled">
  <input type=checkbox name=guest3 value="1"  <?php echo $g3_check ?> onClick="checks(3)">ゲストとして使用する
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">４位<?php echo hissu ?></th>
  <td>
  <select name="player4" <?php echo $p4_useflg ?>>
  <?php echo $user[3] ?>
  </select>
  最終点数：<input type="text" name="score4" size="10" value="<?php echo $p_4score  ?>"  maxlength="7" style="ime-mode:disabled">
  <input type=checkbox name=guest4 value="1"  <?php echo $g4_check ?> onClick="checks(4)">ゲストとして使用する
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">対局種別<?php echo hissu ?></th>
  <td>
  <select name="kbn2">
  <option value=0 <?php echo $tonpu_select ?>>東風戦
  <option value=1 <?php echo $hancyan_select ?>>半荘戦
  </select>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">配給原点<?php echo hissu ?></th>
  <td>
  <select name="1stscore">
  <option value=20000 <?php echo $fscore_20000 ?>>２００００
  <option value=25000 <?php echo $fscore_25000 ?>>２５０００
  <option value=26000 <?php echo $fscore_26000 ?>>２６０００
  <option value=27000 <?php echo $fscore_27000 ?>>２７０００
  <option value=30000 <?php echo $fscore_30000 ?>>３００００
  </select>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">原点<?php echo hissu ?></th>
  <td>
  <select name="resscore">
  <option value=25000 <?php echo $rscore_25000 ?>>２５０００
  <option value=30000 <?php echo $rscore_30000 ?>>３００００
  <option value=35000 <?php echo $rscore_35000 ?>>３５０００
  </select>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="対局結果更新">　※対局日は、更新されません。
</tr>
</tr></table>
</Td></Tr></Table>
<?php } } } } ?></form>
</div></body></html>
