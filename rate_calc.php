<?php
#┌────────────────────────
#│MahjongscoreProject
#│ rate_calc.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/08/04 新規作成 by airu
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_uid2 = @$_POST['uid'];                   # ログインユーザＩＤ
$p_pwd2 = @$_POST['pass'];                  # ログインパスワード
$p_confirm = @$_POST['CONFIRM'];            # 確認メッセージ
?>
<!-- ヘッダ部 -->
<html lang="ja">
<head>
<script type="text/javascript">
<!--
 function sendconfirm(){

     if(confirm("最新レートを計算しますか？ \n 【重要】ログインユーザがいないか再度ご確認下さい。\n ログインユーザがいる場合、強制的に切断されます。\n １局ごとに計算するため処理に時間がかかる場合があります。\n 処理が完了するまで他の操作（ブラウザ閉、画面遷移等）を \n 行わないでください。\n 上記条件でご了承いただける場合は、ＯＫを押してください。"))
     {
     	document.forms[1].CONFIRM.value=1;//ＯＫの場合
     	return true;
     }
     else
     {
         document.forms[1].CONFIRM.value=0;//キャンセルの場合
         return false;
     }

 }
//-->
 </script>
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
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='ログイン画面' onClick=location.href='./login.php'>";
}
else
{
	if($p_confirm == 1)
	{
		###### 最新レート計算ボタンが押された場合の処理 ######
		if (@$_SERVER["REQUEST_METHOD"]=="POST")
		{ //ポストで飛ばされてきたら以下を処理

			if(@$_POST["regit"])
			{
				$info = "";# データ登録メッセージ領域初期化
				$score_update_cnt = 0; // レート更新件数
				// 各メンバーのDELFLGを1(有効)→99(メンテ中)に変更(ログイン阻止１)
				USERFLG_UPDATE(99,1);
				// ONETIME_PWDテーブルよりデータ削除(初期ユーザのみ有効)(ログイン阻止２)
				ONETIMEPWD_DELETE_ALMOST();
				// プレーヤーのレートを初期化する
				RATING_ALLUPDATE(1500);
				// MAXRUI_NO取得
				$max_rui_no = MAX_RUINO();
				// 第1戦～現在までのレートを計算
				for($i=1; $i<=$max_rui_no ;$i++)
				{
					// スコア登録対象のメンバー人数算出（ゲスト以外）
					$score_member_cnt = UNSCORE_RESULT($i);
					// スコア登録対象のメンバー人数（ゲスト以外）が2以上の場合、以下の処理を行う
					if($score_member_cnt >= 2)
					{
						// １～４位までのメンバーの試合数算出（ゲスト以外）=>ゲストの場合はnullを設定
						$p1_name=null; // １位プレーヤー名初期化
						$p2_name=null; // ２位プレーヤー名初期化
						$p3_name=null; // ３位プレーヤー名初期化
						$p4_name=null; // ４位プレーヤー名初期化
						$play_info=null; // プレイ情報（対戦者、順位）
						$game_num =array(); // １位～４位のプレーヤーの現在の試合数
						$now_rate =array(); // １位～４位のプレーヤーの現在のＲ
						$other_avg_rate =array(); // １位～４位のプレーヤーの他家のＲ
						$play_info = UNSCORE_SELECTS($i);
						$score_member_cnt--;
						if($play_info[1] == 0 || $play_info[1] == 1) {
							$p1_name = $play_info[0];
						}
						if($play_info[3] == 0 || $play_info[3] == 1) {
							$p2_name = $play_info[2];
						}
						if($play_info[5] == 0 || $play_info[5] == 1) {
							$p3_name = $play_info[4];
						}
						if($play_info[7] == 0 || $play_info[7] == 1) {
							$p4_name = $play_info[6];
						}
						array_push($game_num, GAME_NUM($p1_name,$i),GAME_NUM($p2_name,$i),GAME_NUM($p3_name,$i),GAME_NUM($p4_name,$i));
						// １～４位までの各プレーヤーの現在のＲを取得
						array_push($now_rate, NOW_RATING($p1_name),NOW_RATING($p2_name),NOW_RATING($p3_name),NOW_RATING($p4_name));
						// 各順位のメンバーに対しての他家平均Ｒを算出 =>ゲストの場合はnullを設定
						array_push($other_avg_rate, avg_rating($p1_name,($now_rate[1]+$now_rate[2]+$now_rate[3])/$score_member_cnt),avg_rating($p2_name,($now_rate[0]+$now_rate[2]+$now_rate[3])/$score_member_cnt),avg_rating($p3_name,($now_rate[0]+$now_rate[1]+$now_rate[3])/$score_member_cnt),avg_rating($p4_name,($now_rate[0]+$now_rate[1]+$now_rate[2])/$score_member_cnt));
						// １位の最新Ｒを取得
						if($other_avg_rate[0] != null)
						{
							if($game_num[0] > 400){ $game_num[0] = 400; } // 試合数が400超えている場合は強制的に400にする
							$new_rate1 = floor($now_rate[0]+(1-$game_num[0]*0.002)*(kihon_sc(1)+($other_avg_rate[0]-$now_rate[0])/40));
							//LOG_INSERT($p_uid2,"レート更新",2,$p1_name."floor(".$now_rate[0]."+(".kihon_sc(1)."-((".$other_avg_rate[0]."-".$now_rate[0].")/300))*(1+((400-".$game_num[0].")/100))))");
							//LOG_INSERT($p_uid2,"レート更新",2,$score_member_cnt);


						}
						// ２位の最新Ｒを取得
						if($other_avg_rate[1] != null)
						{
							if($game_num[1] > 400){	$game_num[1] = 400;	} // 試合数が400超えている場合は強制的に400にする
							$new_rate2 = floor($now_rate[1]+(1-$game_num[1]*0.002)*(kihon_sc(2)+($other_avg_rate[1]-$now_rate[1])/40));
							//LOG_INSERT($p_uid2,"レート更新",2,$p2_name."floor(".$now_rate[1]."+(".kihon_sc(2)."-((".$other_avg_rate[1]."-".$now_rate[1].")/300))*(1+((400-".$game_num[1].")/100))))");
							//LOG_INSERT($p_uid2,"レート更新",2,$score_member_cnt);
						}
						// ３位の最新Ｒを取得
						if($other_avg_rate[2] != null)
						{
							if($game_num[2] > 400){	$game_num[2] = 400;	} // 試合数が400超えている場合は強制的に400にする
							$new_rate3 = floor($now_rate[2]+(1-$game_num[2]*0.002)*(kihon_sc(3)+($other_avg_rate[2]-$now_rate[2])/40));
						}
						// ４位の最新Ｒを取得
						if($other_avg_rate[3] != null)
						{
							if($game_num[3] > 400){	$game_num[3] = 400;	} // 試合数が400超えている場合は強制的に400にする
							$new_rate4 = floor($now_rate[3]+(1-$game_num[3]*0.002)*(kihon_sc(4)+($other_avg_rate[3]-$now_rate[3])/40));
						}
						// １位の最新Ｒを更新
						if($other_avg_rate[0] != null)
						{
							RATE_UPD($p1_name,$new_rate1);
							$score_update_cnt++;
							LOG_INSERT($p_uid2,"レート更新",2,$p1_name."　現在の試合数：".mb_convert_kana($game_num[0],A,"shift_jis")."　更新前レート：".mb_convert_kana($now_rate[0],A,"shift_jis")."　更新後レート：".mb_convert_kana($new_rate1,A,"shift_jis")."　（第".mb_convert_kana($i,A,"shift_jis")."戦）");
						}
						// ２位の最新Ｒを更新
						if($other_avg_rate[1] != null)
						{
							RATE_UPD($p2_name,$new_rate2);
							$score_update_cnt++;
							LOG_INSERT($p_uid2,"レート更新",2,$p2_name."　現在の試合数：".mb_convert_kana($game_num[1],A,"shift_jis")."　更新前レート：".mb_convert_kana($now_rate[1],A,"shift_jis")."　更新後レート：".mb_convert_kana($new_rate2,A,"shift_jis")."　（第".mb_convert_kana($i,A,"shift_jis")."戦）");
						}
						// ３位の最新Ｒを更新
						if($other_avg_rate[2] != null)
						{
							RATE_UPD($p3_name,$new_rate3);
							$score_update_cnt++;
							LOG_INSERT($p_uid2,"レート更新",2,$p3_name."　現在の試合数：".mb_convert_kana($game_num[2],A,"shift_jis")."　更新前レート：".mb_convert_kana($now_rate[2],A,"shift_jis")."　更新後レート：".mb_convert_kana($new_rate3,A,"shift_jis")."　（第".mb_convert_kana($i,A,"shift_jis")."戦）");
						}
						// ４位の最新Ｒを更新
						if($other_avg_rate[3] != null)
						{
							RATE_UPD($p4_name,$new_rate4);
							$score_update_cnt++;
							LOG_INSERT($p_uid2,"レート更新",2,$p4_name."　現在の試合数：".mb_convert_kana($game_num[3],A,"shift_jis")."　更新前レート：".mb_convert_kana($now_rate[3],A,"shift_jis")."　更新後レート：".mb_convert_kana($new_rate4,A,"shift_jis")."　（第".mb_convert_kana($i,A,"shift_jis")."戦）");
						}
					}
				}
				// スコア更新件数をログに出力
				LOG_INSERT($p_uid2,"レート更新",2,"レート更新件数".mb_convert_kana($score_update_cnt,A,"shift_jis")."件");
				// レート計算フラグ更新
				RATECALC_FLG(1);
				// 各メンバーのDELFLGを99(メンテ中)→1(有効)に変更
				USERFLG_UPDATE(1,99);
				###### データ登録メッセージ表示 ######
			 	$info = msg0006;
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
// 最新レート計算メッセージが設定されていない場合、フォーム画面を表示する
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
<img src="./img/chart.gif" align="middle">
&nbsp; <b>最新レート更新</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data" onSubmit="return sendconfirm()">
<input type="hidden" name="CONFIRM" value="" />
	<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
	<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="最新レート更新"> &nbsp;&nbsp;
</tr><br><br><br>
</form>
<?php } ?>
</div></body></html>
<?php } ?>