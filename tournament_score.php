<?php
#┌────────────────────────
#│MahjongscoreProject
#│ tournament_score.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/05/04 新規作成 by airu
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
$p_comment = @$_POST['comment'];                # コメント

$p_uid2 = @$_POST['uid'];                   # ログインユーザＩＤ
$p_pwd2 = @$_POST['pass'];                  # ログインパスワード
$p_kbn2 = @$_POST['kbn'];                   # ログイン区分
$p_id = @$_POST['tournament'];              # 大会ＩＤ
$p_sid = @$_POST['s_tournament'];           # 大会ＩＤ（検索用）
$p_kai = @$_POST['kai'];                    # 回
$p_hanei = @$_POST['hanei'];                # 一般対局に反映

$fscore_25000 = "selected";
$rscore_30000 = "selected";
$hancyan_select = "selected";
// ユーザ情報取得（プルダウン表示用）
$user = USERNAME2($p_1playername,$p_2playername,$p_3playername,$p_4playername);
// 大会情報取得（プルダウン表示用）
$tournament = TOURNAMENT_SELECT2($p_id,1);
// 検索表示用
$s_tournament = TOURNAMENT_SELECT2($p_sid,0);
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
	LOG_INSERT($p_uid2,"対局情報入力（大会対局）",1,err0015."ユーザＩＤ：".$p_uid2);
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
			// ユーザ情報取得（プルダウン表示用）
			$user = USERNAME2($p_1playername,$p_2playername,$p_3playername,$p_4playername);
			// 大会情報取得（プルダウン表示用）
			$tournament = TOURNAMENT_SELECT2($p_id,1);
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
			$err .= moji_check_ad($p_comment,0,50,"コメント");       // コメントの文字長・入力チェック
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
			// 回チェック
			if($p_kai > 999 || $p_kai < 1)
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0019."<BR>";
			}
			// 同大会で同回に同じプレーヤーがいた場合もエラーとする
			if(!TOURNAMENT_CHECK($p_id,$p_kai,$p_1playername,$p_2playername,$p_3playername,$p_4playername))
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0020."<BR>";
			}
			// 大会選択チェック（大会が1つも作成されていないとき空が選択できてしまうのでチェック）
			if($p_id == "")
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0022."<BR>";
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
				// TOURNAMENT_MAXRUI_NO取得
				$to_max_rui_no = TOURNAMENT_MAX_RUINO();
				// 大会スコア登録
				TOURNAMENT_SCORE_INSERT($to_max_rui_no,$p_id,$p_kai,$p_kbn,1,$p_1playername,$p_1score,$score_p1,$lpscore_1p,$lpscore_1p*10,$mfcscore_1p,$p_uid2,$p_firstscore,$p_resscore,$p_comment);
				TOURNAMENT_SCORE_INSERT($to_max_rui_no,$p_id,$p_kai,$p_kbn,2,$p_2playername,$p_2score,$score_p2,$lpscore_2p,$lpscore_2p*10,$mfcscore_2p,$p_uid2,$p_firstscore,$p_resscore,$p_comment);
				TOURNAMENT_SCORE_INSERT($to_max_rui_no,$p_id,$p_kai,$p_kbn,3,$p_3playername,$p_3score,$score_p3,$lpscore_3p,$lpscore_3p*10,$mfcscore_3p,$p_uid2,$p_firstscore,$p_resscore,$p_comment);
				TOURNAMENT_SCORE_INSERT($to_max_rui_no,$p_id,$p_kai,$p_kbn,4,$p_4playername,$p_4score,$score_p4,$lpscore_4p,$lpscore_4p*10,$mfcscore_4p,$p_uid2,$p_firstscore,$p_resscore,$p_comment);
				if($p_kbn == 0) {$syubetsu="東風戦";}
				else{ $syubetsu="半荘戦";}
				LOG_INSERT($p_uid2,"対局情報入力（大会対局）",2,"累計No：".$to_max_rui_no."　対局種別：".$syubetsu."　大会ＩＤ：".$p_id."　回：".mb_convert_kana($p_kai,N,"shift_jis")."回戦");
				LOG_INSERT($p_uid2,"対局情報入力（大会対局）",2,"１位：".$p_1playername."　".mb_convert_kana($p_1score,A,"shift_jis")."点　２位：".$p_2playername."　".mb_convert_kana($p_2score,A,"shift_jis")."点　３位：".$p_3playername."　".mb_convert_kana($p_3score,A,"shift_jis")."点　４位：".$p_4playername."　".mb_convert_kana($p_4score,A,"shift_jis")."点");
				$score_list2 = TOURNAMENT_SCORE_SELECT($p_uid2,$p_kbn2,$p_id);
				// 一般対局に反映する場合は、UN_SCOREに登録
				if($p_hanei == 1)
				{
					// MAXRUI_NO取得
					$max_rui_no = MAX_RUINO();
					// 当月MAX_NO取得
					$max_no = MAX_NO_MONTH();
					// X年前のスコアデータ削除
					UN_SCORE_DELETE();
					// 累計スコア登録
					SCORE_INSERT($max_rui_no,$max_no,$p_kbn,1,$p_1playername,$p_1score,$score_p1,$lpscore_1p,$lpscore_1p*10,$mfcscore_1p,$p_uid2,$p_firstscore,$p_resscore);
					SCORE_INSERT($max_rui_no,$max_no,$p_kbn,2,$p_2playername,$p_2score,$score_p2,$lpscore_2p,$lpscore_2p*10,$mfcscore_2p,$p_uid2,$p_firstscore,$p_resscore);
					SCORE_INSERT($max_rui_no,$max_no,$p_kbn,3,$p_3playername,$p_3score,$score_p3,$lpscore_3p,$lpscore_3p*10,$mfcscore_3p,$p_uid2,$p_firstscore,$p_resscore);
					SCORE_INSERT($max_rui_no,$max_no,$p_kbn,4,$p_4playername,$p_4score,$score_p4,$lpscore_4p,$lpscore_4p*10,$mfcscore_4p,$p_uid2,$p_firstscore,$p_resscore);
					LOG_INSERT($p_uid2,"対局情報入力（一般対局）",2,"累計No：".$max_rui_no."　対局種別：".$syubetsu."　大会ＩＤ：".$p_id."　回：".mb_convert_kana($p_kai,N,"shift_jis")."回戦");
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
						if($t_kbn1 == 0 || $t_kbn1 == 1) {
							$p1_name = $p_1playername;
						}
						if($t_kbn2 == 0 || $t_kbn2 == 1) {
							$p2_name = $p_2playername;
						}
						if($t_kbn3 == 0 || $t_kbn3 == 1) {
							$p3_name = $p_3playername;
						}
						if($t_kbn4 == 0 || $t_kbn4 == 1) {
							$p4_name = $p_4playername;
						}
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
			}
			###### エラーメッセージ表示 ######
			if($err != "")
			{
				$score_list2 = TOURNAMENT_SCORE_SELECT($p_uid2,$p_kbn2,$p_id);
				echo $err;

			}
		}
		// 削除処理
		else if(@$_POST["del"])
		{
			$scoredel_flg = false;    // スコア削除フラグ
			// score1～scoreXまで削除にチェックが入っているものを検索する
			for($i=0; $i<=@$_POST['scorenum'] ;$i++)
			{
				// 削除にチェックが入っている拡張子を削除
				if(@$_POST['score'.$i] != "")
				{
					TOURNAMENT_SCORE_DELETE(@$_POST['score'.$i]);
					$scoredel_flg = true;
					LOG_INSERT($p_uid2,"対局情報入力（大会対局）",2,"削除累計No：".@$_POST['score'.$i]);
				}
				$i=$i+3;
			}
			$score_list2 = TOURNAMENT_SCORE_SELECT($p_uid2,$p_kbn2,$p_sid);
			// 入力チェック(1つも選択されていない場合は、エラーとする)
			if(!$scoredel_flg)
			{
				echo"<img src='./img/error.png' align='middle'> ".err0008;
			}
		}
		// 検索処理
		else if(@$_POST["search"])
		{
			// 大会選択チェック（大会が1つも作成されていないとき空が選択できてしまうのでチェック）
			if($p_sid == "")
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0022."<BR>";
			}
			###### エラーメッセージ表示 ######
			if($err != "")
			{
				$score_list2 = TOURNAMENT_SCORE_SELECT($p_uid2,$p_kbn2,$p_id);
				echo $err;

			}
			else
			{
				$score_list2 = TOURNAMENT_SCORE_SELECT($p_uid2,$p_kbn2,$p_sid);
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
&nbsp; <b>対局情報入力（大会対局）</b></td>
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
  <select name="player1">
  <?php echo $user[0] ?>
  </select>
  最終点数：<input type="text" name="score1st" size="10" value="<?php echo $p_1score  ?>"  maxlength="7" style="ime-mode:disabled">
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">２位<?php echo hissu ?></th>
  <td>
  <select name="player2">
  <?php echo $user[1] ?>
  </select>
  最終点数：<input type="text" name="score2st" size="10" value="<?php echo $p_2score  ?>"  maxlength="7" style="ime-mode:disabled">
  </td>

</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">３位<?php echo hissu ?></th>
  <td>
  <select name="player3">
  <?php echo $user[2] ?>
  </select>
  最終点数：<input type="text" name="score3st" size="10" value="<?php echo $p_3score  ?>"  maxlength="7" style="ime-mode:disabled">
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">４位<?php echo hissu ?></th>
  <td>
  <select name="player4">
  <?php echo $user[3] ?>
  </select>
  最終点数：<input type="text" name="score4st" size="10" value="<?php echo $p_4score  ?>"  maxlength="7" style="ime-mode:disabled">
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">大会<?php echo hissu ?></th>
  <td>
  <select name="tournament">
  <?php echo $tournament ?>
  </select>
    </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">戦<?php echo hissu ?></th>
  <td>
  第<input type="text" name="kai" size="5" value="<?php echo $p_kai  ?>"  maxlength="3" style="ime-mode:disabled">回戦
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
  <th bgcolor="#FFFFFF" width="100">コメント</th>
  <td><input type="text" name="comment" size="75" value="<?php echo $p_comment  ?>" maxlength="50"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="120">一般対局結果反映</th>
  <td>
  <input type=checkbox  name="hanei" value=1>反映する　※選択した場合で削除が必要な場合は、対局情報入力（一般対局）より削除してください。
    </td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="大会結果登録">
    <input type="submit" name=del   style="font-size:15pt;background:#FF66AA" value="大会結果削除">&nbsp;&nbsp;
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## ヘッダ表示部 ########## -->
<BR>
◆大会の対局結果◆
<select name="s_tournament">
<?php echo $s_tournament ?>
</select>
<input type="submit" name=search style="font-size:15pt;background:#FFFF22" value="大会結果検索">
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>NO</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>大会ID</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>戦</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局種別</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>順位</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>プレーヤー</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>最終点数</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>ゲームスコア</b></td>
<!-- <td bgcolor=<?php echo line_color ?> nowrap><b>リーグポイント</b></td> -->
<?php if(mfcscore_disp == 1) { ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>格闘倶楽部ポイント</b></td>
<?php } ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>コメント</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>対局日付</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>削除</b></td>
</tr>
<?php
// 検索された大会の対局結果を表示する
echo $score_list2;
?>
</form></table></Td></Tr></Table>
<?php } ?>
</div></body></html>
