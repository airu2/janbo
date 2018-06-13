<?php
#┌────────────────────────
#│MahjongscoreProject
#│ score.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/03/10 新規作成 by candy
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_1playername = @$_POST['player1'];            # １位プレーヤー名
$p_2playername = @$_POST['player2'];            # ２位プレーヤー名
$p_3playername = @$_POST['player3'];            # ３位プレーヤー名
$p_4playername = @$_POST['player4'];            # ４位プレーヤー名
$p_1score = @$_POST['score1st'];                # １位スコア
$p_2score = @$_POST['score2st'];                # ２位スコア
$p_3score = @$_POST['score3st'];                # ３位スコア
$p_4score = @$_POST['score4st'];                # ４位スコア
$p_kbn = @$_POST['kbn2'];                       # プレイ区分(0:東風戦、1:半荘戦)
$p_firstscore = @$_POST['1stscore'];            # 原点(スコア計算時に使用)
$p_resscore = @$_POST['resscore'];              # 返し点(スコア計算時に使用)
$p_guest1 =  @$_POST['guest1'];                 # ゲスト１
$p_guest2 =  @$_POST['guest2'];                 # ゲスト２
$p_guest3 =  @$_POST['guest3'];                 # ゲスト３
$p_guest4 =  @$_POST['guest4'];                 # ゲスト４

$p_uid2 = @$_POST['uid'];                   # ログインユーザＩＤ
$p_pwd2 = @$_POST['pass'];                  # ログインパスワード
$p_kbn2 = @$_POST['kbn'];                   # ログイン区分

$fscore_25000 = "selected";
$rscore_30000 = "selected";
$hancyan_select = "selected";
$g1_check = "";   # ゲスト１チェックなし状態
$g2_check = "";   # ゲスト２チェックなし状態
$g3_check = "";   # ゲスト３チェックなし状態
$g4_check = "";   # ゲスト４チェックなし状態
$p1_useflg = "";  # 対戦者１選択可能状態
$p2_useflg = "";  # 対戦者２選択可能状態
$p3_useflg = "";  # 対戦者３選択可能状態
$p4_useflg = "";  # 対戦者４選択可能状態
$limit_e = page_num * 4;
// メニューから遷移された場合は、検索初期値を設定
if(@$_POST['menu5'] != "")
{
	$page_num = 1; # ページ番号
	$limit_s = 0;
	$first_page_flg = "disabled";
	$num = UNSCORE_MONTH_CNT();
	$max_page_num = $num / page_num;
	if($max_page_num <= 1)
	{
		$last_page_flg = "disabled";
	}
	else if($max_page_num > 1)
	{
		$last_page_flg = "";
	}
}

//$user = USERNAME();
// ユーザ情報取得（プルダウン表示用）
$user = USERNAME2($p_1playername,$p_2playername,$p_3playername,$p_4playername);
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
<?php if(guest_use_flg == 1) { ?>
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
<?php } ?>
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
	LOG_INSERT($p_uid2,"対局情報入力（一般対局）",1,err0015."ユーザＩＤ：".$p_uid2);
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
				// MAXRUI_NO取得
				$max_rui_no = MAX_RUINO();
				// 当月MAX_NO取得
				$max_no = MAX_NO_MONTH();
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
				// X年前のスコアデータ削除
				UN_SCORE_DELETE();
				// 累計スコア登録
				SCORE_INSERT($max_rui_no,$max_no,$t_kbn1,1,$p_1playername,$p_1score,$score_p1,$lpscore_1p,$lpscore_1p*10,$mfcscore_1p,$p_uid2,$p_firstscore,$p_resscore);
				SCORE_INSERT($max_rui_no,$max_no,$t_kbn2,2,$p_2playername,$p_2score,$score_p2,$lpscore_2p,$lpscore_2p*10,$mfcscore_2p,$p_uid2,$p_firstscore,$p_resscore);
				SCORE_INSERT($max_rui_no,$max_no,$t_kbn3,3,$p_3playername,$p_3score,$score_p3,$lpscore_3p,$lpscore_3p*10,$mfcscore_3p,$p_uid2,$p_firstscore,$p_resscore);
				SCORE_INSERT($max_rui_no,$max_no,$t_kbn4,4,$p_4playername,$p_4score,$score_p4,$lpscore_4p,$lpscore_4p*10,$mfcscore_4p,$p_uid2,$p_firstscore,$p_resscore);
				if($t_kbn1 == 0 || $t_kbn1 == 100) { $syubetsu="東風戦";}
				else{ $syubetsu="半荘戦";}
				LOG_INSERT($p_uid2,"対局情報入力（一般対局）",2,"累計No：".$max_rui_no."　当月No：".$max_no."　対局種別：".$syubetsu);
				LOG_INSERT($p_uid2,"対局情報入力（一般対局）",2,"１位：".$p_1playername."　".mb_convert_kana($p_1score,A,"shift_jis")."点　２位：".$p_2playername."　".mb_convert_kana($p_2score,A,"shift_jis")."点　３位：".$p_3playername."　".mb_convert_kana($p_3score,A,"shift_jis")."点　４位：".$p_4playername."　".mb_convert_kana($p_4score,A,"shift_jis")."点");
				// ■レーティング対応■ start
				// スコア登録対象のメンバー人数算出（ゲスト以外）
				$score_member_cnt = UNSCORE_RESULT($max_rui_no);
				// スコア登録対象のメンバー人数（ゲスト以外）が2以上の場合、以下の処理を行う
				if($score_member_cnt >= 2)
				{
					// １～４位までのメンバーの試合数算出（ゲスト以外）=>ゲストの場合はnullを設定
					$p1_name=null; // １位プレーヤー名初期化
					$p2_name=null; // ２位プレーヤー名初期化
					$p3_name=null; // ３位プレーヤー名初期化
					$p4_name=null; // ４位プレーヤー名初期化
					$game_num =array(); // １位～４位のプレーヤーの現在の試合数
					$now_rate =array(); // １位～４位のプレーヤーの現在のＲ
					$other_avg_rate =array(); // １位～４位のプレーヤーの他家のＲ
					$score_member_cnt--;
					if($t_kbn1 == 0 || $t_kbn1 == 1) { $p1_name = $p_1playername; }
					if($t_kbn2 == 0 || $t_kbn2 == 1) { $p2_name = $p_2playername; }
					if($t_kbn3 == 0 || $t_kbn3 == 1) { $p3_name = $p_3playername; }
					if($t_kbn4 == 0 || $t_kbn4 == 1) { $p4_name = $p_4playername; }
					array_push($game_num, GAME_NUM($p1_name,$max_rui_no),GAME_NUM($p2_name,$max_rui_no),GAME_NUM($p3_name,$max_rui_no),GAME_NUM($p4_name,$max_rui_no));
					// １～４位までの各プレーヤーの現在のＲを取得
					array_push($now_rate, NOW_RATING($p1_name),NOW_RATING($p2_name),NOW_RATING($p3_name),NOW_RATING($p4_name));
					// 各順位のメンバーに対しての他家平均Ｒを算出 =>ゲストの場合はnullを設定
					array_push($other_avg_rate, avg_rating($p1_name,($now_rate[1]+$now_rate[2]+$now_rate[3])/$score_member_cnt),avg_rating($p2_name,($now_rate[0]+$now_rate[2]+$now_rate[3])/$score_member_cnt),avg_rating($p3_name,($now_rate[0]+$now_rate[1]+$now_rate[3])/$score_member_cnt),avg_rating($p4_name,($now_rate[0]+$now_rate[1]+$now_rate[2])/$score_member_cnt));
					// １位の最新Ｒを取得
					if($other_avg_rate[0] != null)
					{
						if($game_num[0] > 400){	$game_num[0] = 400;	} // 試合数が400超えている場合は強制的に400にする
						$new_rate1 = floor($now_rate[0]+(1-$game_num[0]*0.002)*(kihon_sc(1)+($other_avg_rate[0]-$now_rate[0])/40));
					}
					// ２位の最新Ｒを取得
					if($other_avg_rate[1] != null)
					{
						if($game_num[1] > 400){ $game_num[1] = 400;	} // 試合数が400超えている場合は強制的に400にする
						$new_rate2 = floor($now_rate[1]+(1-$game_num[1]*0.002)*(kihon_sc(2)+($other_avg_rate[1]-$now_rate[1])/40));
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
						LOG_INSERT($p_uid2,"対局情報入力（一般対局）",2,$p1_name."　現在の試合数：".mb_convert_kana($game_num[0],A,"shift_jis")."　更新前レート：".mb_convert_kana($now_rate[0],A,"shift_jis")."　更新後レート：".mb_convert_kana($new_rate1,A,"shift_jis")."　（第".mb_convert_kana($max_rui_no,A,"shift_jis")."戦）");
					}
					// ２位の最新Ｒを更新
					if($other_avg_rate[1] != null)
					{
						RATE_UPD($p2_name,$new_rate2);
						LOG_INSERT($p_uid2,"対局情報入力（一般対局）",2,$p2_name."　現在の試合数：".mb_convert_kana($game_num[1],A,"shift_jis")."　更新前レート：".mb_convert_kana($now_rate[1],A,"shift_jis")."　更新後レート：".mb_convert_kana($new_rate2,A,"shift_jis")."　（第".mb_convert_kana($max_rui_no,A,"shift_jis")."戦）");
					}
					// ３位の最新Ｒを更新
					if($other_avg_rate[2] != null)
					{
						RATE_UPD($p3_name,$new_rate3);
						LOG_INSERT($p_uid2,"対局情報入力（一般対局）",2,$p3_name."　現在の試合数：".mb_convert_kana($game_num[2],A,"shift_jis")."　更新前レート：".mb_convert_kana($now_rate[2],A,"shift_jis")."　更新後レート：".mb_convert_kana($new_rate3,A,"shift_jis")."　（第".mb_convert_kana($max_rui_no,A,"shift_jis")."戦）");
					}
					// ４位の最新Ｒを更新
					if($other_avg_rate[3] != null)
					{
						RATE_UPD($p4_name,$new_rate4);
						LOG_INSERT($p_uid2,"対局情報入力（一般対局）",2,$p4_name."　現在の試合数：".mb_convert_kana($game_num[3],A,"shift_jis")."　更新前レート：".mb_convert_kana($now_rate[3],A,"shift_jis")."　更新後レート：".mb_convert_kana($new_rate4,A,"shift_jis")."　（第".mb_convert_kana($max_rui_no,A,"shift_jis")."戦）");
					}
				}
				// ■レーティング対応■ end
			}
			###### エラーメッセージ表示 ######
			if($err != "")
			{
				echo $err;
			}
			$limit_s = 0;
			$page_num = 1; # ページ番号
			$first_page_flg = "disabled";
			$num = UNSCORE_MONTH_CNT();
			$max_page_num = $num / page_num;
			if($max_page_num <= $page_num)
			{
				$last_page_flg = "disabled";
			}
		}
		// 削除処理
		else if(@$_POST["del"])
		{
			$scoredel_flg = false;    // スコア削除フラグ
			// score1～scoreXまで削除にチェックが入っているものを検索する
			for($i=0; $i<=@$_POST['scorenum'] ;$i++)
			{
				// 削除にチェックが入っているスコアを削除
				if(@$_POST['score'.$i] != "")
				{
					SCORE_DELETE(@$_POST['score'.$i]);
					$scoredel_flg = true;
					LOG_INSERT($p_uid2,"対局情報入力（一般対局）",2,"削除累計No：".@$_POST['score'.$i]);
				}
				$i=$i+3;
			}
			// 入力チェック(1つも選択されていない場合は、エラーとする)
			if(!$scoredel_flg)
			{
				echo"<img src='./img/error.png' align='middle'> ".err0008;
			}
			else
			{
				// レート計算フラグ更新
				RATECALC_FLG(0);
			}
			$limit_s = 0;
			$page_num = 1; # ページ番号
			$first_page_flg = "disabled";
			$num = UNSCORE_MONTH_CNT();
			$max_page_num = $num / page_num;
			if($max_page_num <= $page_num)
			{
				$last_page_flg = "disabled";
			}
		}
		// ページ遷移処理
		else if(@$_POST["page_regit1"] || @$_POST["page_regit2"] || @$_POST["page_regit3"] || @$_POST["page_regit4"])
		{
			$num = UNSCORE_MONTH_CNT();
			$max_page_num = ceil($num / page_num);
			$page_num = @$_POST['pagenum'];
			// 「＜＜」押下時
			if(@$_POST["page_regit1"])
			{
				$page_num = 1; # ページ番号
				$first_page_flg = "disabled";
			}
			// 「＜」押下時
			elseif(@$_POST["page_regit2"])
			{
				$page_num = $page_num-1; # ページ番号
				if($page_num <= 1)
				{
					$first_page_flg = "disabled";
				}
				else
				{
					$first_page_flg = "";
				}
			}
			// 「＞」押下時
			elseif(@$_POST["page_regit3"])
			{
				$page_num = $page_num+1; # ページ番号
				if($max_page_num <= $page_num)
				{
					$last_page_flg = "disabled";
				}
				else
				{
					$last_page_flg = "";
				}
			}
			// 「＞＞」押下時
			elseif(@$_POST["page_regit4"])
			{
					$page_num = $max_page_num; # ページ番号
					$last_page_flg = "disabled";
			}
			$limit_s = ($page_num -1)*4*page_num;
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
<img src="./img/money.png" align="middle">
&nbsp; <b>対局情報入力（一般対局）</b></td>
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
  <th bgcolor="#FFFFFF" width="100">１位<?php echo hissu ?><img src='./img/gold.png'></th>
  <td>
  <select name="player1" <?php echo $p1_useflg ?>>
  <?php echo $user[0] ?>
  </select>
  最終点数：<input type="text" name="score1st" size="10" value="<?php echo $p_1score  ?>"  maxlength="7" style="ime-mode:disabled">
  <?php if(guest_use_flg == 1) { ?>
  <input type=checkbox name=guest1 value="1"  <?php echo $g1_check ?> onClick="checks(1)">ゲストとして使用する
  <?php } ?>
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">２位<?php echo hissu ?></th>
  <td>
  <select name="player2" <?php echo $p2_useflg ?>>
  <?php echo $user[1] ?>
  </select>
  最終点数：<input type="text" name="score2st" size="10" value="<?php echo $p_2score  ?>"  maxlength="7" style="ime-mode:disabled">
  <?php if(guest_use_flg == 1) { ?>
  <input type=checkbox name=guest2 value="1"  <?php echo $g2_check ?> onClick="checks(2)">ゲストとして使用する
  <?php } ?>
  </td>

</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">３位<?php echo hissu ?></th>
  <td>
  <select name="player3" <?php echo $p3_useflg ?>>
  <?php echo $user[2] ?>
  </select>
  最終点数：<input type="text" name="score3st" size="10" value="<?php echo $p_3score  ?>"  maxlength="7" style="ime-mode:disabled">
  <?php if(guest_use_flg == 1) { ?>
  <input type=checkbox name=guest3 value="1"  <?php echo $g3_check ?> onClick="checks(3)">ゲストとして使用する
  <?php } ?>
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">４位<?php echo hissu ?></th>
  <td>
  <select name="player4" <?php echo $p4_useflg ?>>
  <?php echo $user[3] ?>
  </select>
  最終点数：<input type="text" name="score4st" size="10" value="<?php echo $p_4score  ?>"  maxlength="7" style="ime-mode:disabled">
  <?php if(guest_use_flg == 1) { ?>
  <input type=checkbox name=guest4 value="1"  <?php echo $g4_check ?> onClick="checks(4)">ゲストとして使用する
  <?php } ?>
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
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="対局結果登録">
    <input type="submit" name=del   style="font-size:15pt;background:#FF66AA" value="対局結果削除">
    ※削除する場合は、ページ毎に削除を行ってください。&nbsp;&nbsp;
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## ヘッダ表示部 ########## -->
<BR>
◆今月の対局結果◆
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>NO</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局種別</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>順位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>プレーヤー</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>最終点数</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>ゲームスコア</b></td>
<!-- <td bgcolor=<?php echo line_color ?> nowrap><b>リーグポイント</b></td> -->
<?php if(mfcscore_disp == 1) { ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>格闘倶楽部ポイント</b></td>
<?php } ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局日付</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>削除</b></td>
</tr>
<?php
// 登録されている対局結果を表示する
$score_list = UN_SCORE_MONTH_SELECT($p_uid2,$p_kbn2,$limit_s,$limit_e);
echo $score_list;
?>
</table></Td></Tr></Table>
<?php }
if($score_list !="")
{ ?>
<center>
<input type="submit" name=page_regit1 <?php echo $first_page_flg ?> style="font-size:15pt;background:#99AA33" value="＜＜">
<input type="submit" name=page_regit2 <?php echo $first_page_flg ?> style="font-size:15pt;background:#99AA33" value="＜">
<font face='Century Gothic' size=15pt><?php echo $page_num ?></font>
<input type=hidden name=pagenum value=<?php echo $page_num ?>>
<input type="submit" name=page_regit3 <?php echo $last_page_flg ?> style="font-size:15pt;background:#99AA33" value="＞">
<input type="submit" name=page_regit4 <?php echo $last_page_flg ?> style="font-size:15pt;background:#99AA33" value="＞＞">
</center>
<?php } ?>
</form>
</div></body></html>
