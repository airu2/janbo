<?php
#-------------------------------------------------
#  入力文字チェック(入力文字,最小文字数,最大文字数,入力フィールド)
#-------------------------------------------------
function moji_check($str,$minlength,$maxlength,$field) {
$errmsg = "";
// 最小文字数,最大文字数チェック
if(mb_strlen($str,"sjis") > $maxlength || mb_strlen($str,"sjis") < $minlength)
{
    $errmsg = "<img src='./img/error.png' align='middle'>".$field."の入力文字数は、".$minlength."文字以上".$maxlength."文字以内で入力してください。<BR>";
}
return $errmsg;
}
#-------------------------------------------------
#  入力文字チェック(入力文字,最小数字,最大数字,入力フィールド)
#-------------------------------------------------
function num_check($str,$minnum,$maxnum,$field) {
$errmsg = "";
// 範囲チェック
if($str > $maxnum || $str < $minnum)
{
	$errmsg = "<img src='./img/error.png' align='middle'>".$field."の入力範囲は、".$minnum."～".$maxnum."の間で入力してください。<BR>";
}
return $errmsg;
}
#-------------------------------------------------
#  入力文字チェック(入力文字,最小文字数,最大文字数,入力フィールド)(管理者メニュー専用)
#-------------------------------------------------
function moji_check_ad($str,$minlength,$maxlength,$field) {
$errmsg = "";
// 最小文字数,最大文字数チェック
if(mb_strlen($str,"sjis") > $maxlength || mb_strlen($str,"sjis") < $minlength)
{
	$errmsg = "<img src='./img/error.png' align='middle'>".$field."の入力文字数は、".$minlength."文字以上".$maxlength."文字以内で入力してください。<BR>";
}
return $errmsg;
}
#-------------------------------------------------
#  ホスト名取得
#-------------------------------------------------
function hostname() {
$ip = getenv("REMOTE_ADDR");
$host = getenv("REMOTE_HOST");
if ($host == null || $host == $ip)
$host = @gethostbyaddr($ip);
return $host;
}

#-------------------------------------------------
#  入力可能文字チェック(入力文字,パターン,入力フィールド)
#-------------------------------------------------
function match_check($str,$pettern,$field) {
$errmsg = "";
switch($pettern){
    case 0: // パターン0:A-Z,a-z,0-9のみ
    if(preg_match("/[^A-Za-z0-9]/",$str)){
	$errmsg = "<img src='./img/error.png' align='middle'>".$field."に使用できる文字は、[A-Z,a-z,0-9]のみです<BR>";
    }
    break;
    case 1: // パターン1:0-9のみ
    if(preg_match("/[^0-9]/",$str)){
	$errmsg = "<img src='./img/error.png' align='middle'>".$field."に使用できる文字は、[0-9]のみです<BR>";
    }
    break;
    case 2: // パターン2:メールアドレスチェック用
    if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)) {
    $errmsg = "<img src='./img/error.png' align='middle'>".$field."が不正です。<BR>";
    }
    break;
    case 3: // パターン3:日時チェック用
    if (!preg_match('/^(\d\d\d\d)\/(\d\d)\/(\d\d) (\d\d):(\d\d)$/', $str)) {
    $errmsg = "<img src='./img/error.png' align='middle'>".$field."が不正です。<BR>";
    }
    break;

}
return $errmsg;
}
#-------------------------------------------------
#  管理者パスワードチェック(チェック対象文字列)
#-------------------------------------------------
function adminpass_check($chk_pwd,$chk_uid) {
# マッチしない場合は、エラーとする
if (adminpwd != $chk_pwd || adminusr != $chk_uid)
{
	$chk_kekka = false;
}
else
{
	$chk_kekka = true;
}
return $chk_kekka;
}
#-------------------------------------------------
#  配列格納（カンマ区切りの文字列）
#-------------------------------------------------
function add_array($str) {
if($str != "")
{
	$array = array();
	$str_data = explode( ",", $str);
	for( $i = 0; $i < count( $str_data ); $i++ ) {
		array_push($array, trim( $str_data[ $i ]));
	}
}
return $array;
}
#-------------------------------------------------
#  配給原点人数算出
#-------------------------------------------------
function firstscore_num($p1_score,$p2_score,$p3_score,$p4_score,$first_score) {
$array = array();
$p1_origin_flg = false;
$p2_origin_flg = false;
$p3_origin_flg = false;
$p4_origin_flg = false;
$num = 0;
if($p1_score >= $first_score)
{
	$num++;
	$p1_origin_flg = true;
}
if($p2_score >= $first_score)
{
	$num++;
	$p2_origin_flg = true;
}
if($p3_score >= $first_score)
{
	$num++;
	$p3_origin_flg = true;
}
if($p4_score >= $first_score)
{
	$num++;
	$p4_origin_flg = true;
}
array_push($array, $p1_origin_flg);
array_push($array, $p2_origin_flg);
array_push($array, $p3_origin_flg);
array_push($array, $p4_origin_flg);
array_push($array, $num);
return $array;
}
#-------------------------------------------------
#  補正値返却
#-------------------------------------------------
function hosei($flg,$num) {
$hosei = 0;
if($num == 0)
{
	$hosei = -3;
}
elseif($num == 1 && $flg)
{
	$hosei = 6;
}
elseif($num == 1 && !$flg)
{
	$hosei = -3;
}
elseif($num == 3 && $flg)
{
	$hosei = 2;
}
elseif($num == 3 && !$flg)
{
	$hosei = -6;
}
return $hosei;
}
#-------------------------------------------------
#  集計区分返却
#-------------------------------------------------
function sum_kbn($flg) {
$kbn = "";
if($flg == 0)
{
	$kbn = "東風戦";
}
elseif($flg == 1)
{
	$kbn = "半荘戦";
}
elseif($flg == 2)
{
	$kbn = "全て";
}
return $kbn;
}
#-------------------------------------------------
#  集計結果ファイル作成
#-------------------------------------------------
function create_file($array_all,$array_s,$array_a,$array_b,$array_c,$uid,$p_num,$kbn,$from,$to,$create_kbn) {
$html_file = 'Result_'.$uid.'.html'; // 出力htmlファイル名
$fp = @fopen(c_filedir.$html_file, 'w');
// 出力内容
$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>集計期間：".$from. "～".$to."</div>";
$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>集計区分：".$kbn."（".sum_kbn($kbn)."）</div>";
$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>全員</div>";
$output_moji = $output_moji."<table style='border:solid 1px; border-color:#0000ff'>";
$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."対局者名";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."対局数";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."平均順位";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."得点";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."レーティング";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."1位回数";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."2位回数";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."3位回数";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."4位回数";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."連対率";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."</tr>";
for($i = 0; $i < count($array_all);)
{
	$rentai_rate = 0;
	$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
	if($p_num <= $array_all[$i+1])
	{
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."</td>"; // 対局者
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."</td>"; // 対局数
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."</td>"; // 平均順位
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".score_minus($array_all[$i])."</td>"; // スコア
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."</td>"; // レーティング
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."（".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-4]*100))."%）</td>"; // １位
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."（".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-5]*100))."%）</td>"; // ２位
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."（".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-6]*100))."%）</td>"; // ３位
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."（".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-7]*100))."%）</td>"; // ４位
		// １位と２位の回数が0より大きい場合(0の場合は0で除算するためエラー対策）
		if(($array_all[$i-3]+$array_all[$i-2]) > 0)
		{
			$rentai_rate = round(($array_all[$i-3]+$array_all[$i-2])/$array_all[$i-7]*100,1);
		}
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$rentai_rate."%</td></tr>\r\n"; // 連対率

		$i++;
	}
	else
	{
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."</td>"; // 対局者
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."</td>"; // 対局数
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."</td>"; // 平均順位
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".score_minus($array_all[$i])."</td>"; // スコア
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."</td>"; // レーティング
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."（".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-4]*100))."%）</td>"; // １位
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."（".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-5]*100))."%）</td>"; // ２位
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."（".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-6]*100))."%）</td>"; // ３位
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."（".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-7]*100))."%）</td>"; // ４位
		// １位と２位の回数が0より大きい場合(0の場合は0で除算するためエラー対策）
		if(($array_all[$i-3]+$array_all[$i-2]) > 0)
		{
			$rentai_rate = round(($array_all[$i-3]+$array_all[$i-2])/$array_all[$i-7]*100,1);
		}
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$rentai_rate."%</td></tr>\r\n"; // 連対率
		$i++;
	}
}
$output_moji = $output_moji."</table>";
if($create_kbn == 0)
{
	// Ｓリーグ
	$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>Ｓリーグ</div>";
	$output_moji = $output_moji."<table style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."対局者名";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."対局数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."平均順位";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."得点";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."レーティング";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."1位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."2位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."3位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."4位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."</tr>";
	for($i = 0; $i < count($array_s);)
	{
		$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
		if($p_num <= $array_s[$i+1])
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // 対局者
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // 対局数
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // 平均順位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".score_minus($array_s[$i])."</td>"; // スコア
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // レーティング
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // １位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // ２位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // ３位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td></tr>\r\n"; // ４位
			$i++;
		}
		else
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // 対局者
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // 対局数
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // 平均順位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".score_minus($array_s[$i])."</td>"; // スコア
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // レーティング
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // １位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // ２位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // ３位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td></tr>\r\n"; // ４位
			$i++;
		}
	}
	$output_moji = $output_moji."</table>";
	// Ａリーグ
	$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>Ａリーグ</div>";
	$output_moji = $output_moji."<table style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."対局者名";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."対局数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."平均順位";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."得点";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."レーティング";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."1位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."2位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."3位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."4位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."</tr>";
	for($i = 0; $i < count($array_a);)
	{
		$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
		if($p_num <= $array_a[$i+1])
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // 対局者
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // 対局数
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // 平均順位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".score_minus($array_a[$i])."</td>"; // スコア
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // レーティング
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // １位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // ２位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // ３位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td></tr>\r\n"; // ４位
			$i++;
		}
		else
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // 対局者
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // 対局数
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // 平均順位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".score_minus($array_a[$i])."</td>"; // スコア
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // レーティング
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // １位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // ２位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // ３位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td></tr>\r\n"; // ４位
			$i++;
		}
	}
	$output_moji = $output_moji."</table>";
	// Ｂリーグ
	$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>Ｂリーグ</div>";
	$output_moji = $output_moji."<table style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."対局者名";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."対局数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."平均順位";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."得点";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."レーティング";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."1位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."2位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."3位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."4位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."</tr>";
	for($i = 0; $i < count($array_b);)
	{
		$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
		if($p_num <= $array_b[$i+1])
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // 対局者
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // 対局数
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // 平均順位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".score_minus($array_b[$i])."</td>"; // スコア
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // レーティング
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // １位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // ２位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // ３位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td></tr>\r\n"; // ４位
			$i++;
		}
		else
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // 対局者
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // 対局数
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // 平均順位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".score_minus($array_b[$i])."</td>"; // スコア
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // レーティング
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // １位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // ２位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // ３位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td></tr>\r\n"; // ４位
			$i++;
		}
	}
	$output_moji = $output_moji."</table>";
	// Ｃリーグ
	$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>Ｃリーグ</div>";
	$output_moji = $output_moji."<table style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."対局者名";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."対局数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."平均順位";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."得点";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."レーティング";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."1位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."2位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."3位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."4位回数";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."</tr>";
	for($i = 0; $i < count($array_c);)
	{
		$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
		if($p_num <= $array_c[$i+1])
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // 対局者
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // 対局数
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // 平均順位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".score_minus($array_c[$i])."</td>"; // スコア
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // レーティング
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // １位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // ２位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // ３位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td></tr>\r\n"; // ４位
			$i++;
		}
		else
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // 対局者
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // 対局数
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // 平均順位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".score_minus($array_c[$i])."</td>"; // スコア
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // レーティング
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // １位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // ２位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // ３位
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td></tr>\r\n"; // ４位
			$i++;
		}
	}
}
$output_moji = $output_moji."</table>";
fwrite($fp, $output_moji);
fclose($fp);
}
#-------------------------------------------------
#  ゲームスコア返却
#-------------------------------------------------
function reckoning($gamescore) {

// 切り捨て
if(calc_num == 1)
{
	$gamescore = floor($gamescore);
}
// 切り上げ
if(calc_num == 2)
{
	$gamescore = ceil($gamescore);
}
// 四捨五入
if(calc_num == 3)
{
	$gamescore = round($gamescore);
}
return $gamescore;

}
#-------------------------------------------------
#  他家平均Ｒ取得（ゲスト時対応）
#-------------------------------------------------
function avg_rating($name,$avg_rate) {

// プレーヤーがゲストの場合は、null設定
if($name == null)
{
	$avg_rate = null;
}
return $avg_rate;

}
#-------------------------------------------------
#  スコア▲表示
#-------------------------------------------------
function score_minus($score) {
if($score < 0)
{
	$score = "▲".$score * (-1);
}
return $score;
}
#-------------------------------------------------
#  基本項（レート計算用）
#-------------------------------------------------
function kihon_sc($juni) {
$kihon = 0;
// １位
if($juni == 1)
{
	$kihon = 30;
}
// ２位
else if($juni == 2)
{
	$kihon = 10;
}
// ３位
else if($juni == 3)
{
	$kihon = -10;
}
// ４位
else if($juni == 4)
{
	$kihon = -30;
}
return $kihon;

}
#-------------------------------------------------
#  一般スコア対局検索内容プルダウン作成
#-------------------------------------------------
function ymd_select($min_ymd,$max_ymd,$value_select) {
$select_ymd=array();
$next_year_flg=false;
$st_flg = true;
$chk_flg ="";
// メニュー１：年月
for($i=date_format(date_create($min_ymd), "Y"); $i<=date_format(date_create($max_ymd), "Y"); $i++)
{
	if(!$next_year_flg)
	{
		$st_month = date_format(date_create($min_ymd), "m");
	}
	else
	{
		$st_month = 1;
	}
	if($i == date_format(date_create($max_ymd), "Y") && date_format(date_create($max_ymd), "m") < 12)
	{
		$ed_month = date_format(date_create($max_ymd), "m");
	}
	else
	{
		$ed_month = 12;
	}
	for($j=$st_month; $j<=$ed_month; $j++)
	{
		if(date_format(date_create($min_ymd),"Ymd") < date_format(date_create($i."/".$j."/01"),"Ymd"))
		{
			$st_ymd = $i."/".sprintf("%02d",$j)."/01";
		}
		else
		{
			$st_ymd = date_format(date_create($min_ymd),"Y/m/d");
		}
		if(date_format(date_create($max_ymd),"Ymd") > date_format(date_create($i."/".$j."/01"),"Ymt"))
		{
			$ed_ymd = date_format(date_create($i."/".$j."/01"),"Y/m/t");
		}
		else
		{
			$ed_ymd = date_format(date_create($max_ymd),"Y/m/d");
		}
		if($value_select == $st_ymd.$ed_ymd)
		{
			$chk_flg = "selected";
		}
		else if(date_format(date_create($st_ymd), "Ym") <= date_format(date_create("NOW"), "Ym") &&
		   date_format(date_create($ed_ymd), "Ym") >= date_format(date_create("NOW"), "Ym") && $value_select == "")
		{
			$chk_flg = "selected";
		}
		else
		{
			$chk_flg = "";
		}
		// 年月メニュー作成
		$str_month = $str_month."<option value='".$st_ymd.$ed_ymd."' ".$chk_flg.">".$i."年".$j."月(".$st_ymd."～".$ed_ymd.")\r\n";
	}
	$next_year_flg = true;
}
array_push($select_ymd, $str_month);
// メニュー２：節
$next_year_flg = false;
for($i=date_format(date_create($min_ymd), "Y"); $i<=date_format(date_create($max_ymd), "Y"); $i++)
{
	if(!$next_year_flg)
	{
		$st_month = date_format(date_create($min_ymd), "m");
	}
	else
	{
		$st_month = 1;
	}
	if($i == date_format(date_create($max_ymd), "Y") && date_format(date_create($max_ymd), "m") < 12)
	{
		$ed_month = date_format(date_create($max_ymd), "m");
	}
	else
	{
		$ed_month = 12;
	}
	for($j=$st_month; $j<=$ed_month; $j++)
	{
		if($j == 1 || $j == 4 || $j == 7 || $j == 10)
		{
			$st_flg = true;
		}
		if(date_format(date_create($min_ymd),"Ymd") < date_format(date_create($i."/".$j."/01"),"Ymd") && $st_flg)
		{
			$st_ymd = $i."/".sprintf("%02d",$j)."/01";
			$st_flg = false;
		}
		else if(date_format(date_create($min_ymd),"Ymd") >= date_format(date_create($i."/".$j."/01"),"Ymd"))
		{
			$st_ymd = date_format(date_create($min_ymd),"Y/m/d");
			if(date_format(date_create($min_ymd),"m") >= 1 && date_format(date_create($min_ymd),"m") <= 3)
			{
				$j = 3;
			}
			else if(date_format(date_create($min_ymd),"m") >= 4 && date_format(date_create($min_ymd),"m") <= 6)
			{
				$j = 6;
			}
			else if(date_format(date_create($min_ymd),"m") >= 7 && date_format(date_create($min_ymd),"m") <= 9)
			{
				$j = 9;
			}
			else if(date_format(date_create($min_ymd),"m") >= 10 && date_format(date_create($min_ymd),"m") <= 12)
			{
				$j = 12;
			}
		}
		if(date_format(date_create($max_ymd),"Ymd") > date_format(date_create($i."/".$j."/01"),"Ymt"))
		{
			$ed_ymd = date_format(date_create($i."/".$j."/01"),"Y/m/t");
		}
		else
		{
			$ed_ymd = date_format(date_create($max_ymd),"Y/m/d");
			if(date_format(date_create($max_ymd),"m") >= 1 && date_format(date_create($max_ymd),"m") <= 3)
			{
				$j = 3;
			}
			else if(date_format(date_create($max_ymd),"m") >= 4 && date_format(date_create($max_ymd),"m") <= 6)
			{
				$j = 6;
			}
			else if(date_format(date_create($max_ymd),"m") >= 7 && date_format(date_create($max_ymd),"m") <= 9)
			{
				$j = 9;
			}
			else if(date_format(date_create($max_ymd),"m") >= 10 && date_format(date_create($max_ymd),"m") <= 12)
			{
				$j = 12;
			}
		}
		if($value_select == $st_ymd.$ed_ymd)
		{
			$chk_flg = "selected";
		}
		else if(date_format(date_create($st_ymd), "Ym") <= date_format(date_create("NOW"), "Ym") &&
		        date_format(date_create($ed_ymd), "Ym") >= date_format(date_create("NOW"), "Ym") && $value_select == "")
		{
			$chk_flg = "selected";
		}
		else
		{
			$chk_flg = "";
		}
		switch ($j){
		case 3:
			// 節メニュー作成
			$str_setu = $str_setu."<option value='".$st_ymd.$ed_ymd."' ".$chk_flg.">".($i-1)."年度4節(".$st_ymd."～".$ed_ymd.")\r\n";
			$st_flg = false;
		break;
		case 6:
			// 節メニュー作成
			$str_setu = $str_setu."<option value='".$st_ymd.$ed_ymd."' ".$chk_flg.">".$i."年度1節(".$st_ymd."～".$ed_ymd.")\r\n";
			$st_flg = false;
		break;
		case 9:
			// 節メニュー作成
			$str_setu = $str_setu."<option value='".$st_ymd.$ed_ymd."' ".$chk_flg.">".$i."年度2節(".$st_ymd."～".$ed_ymd.")\r\n";
			$st_flg = false;
		break;
		case 12:
			// 節メニュー作成
			$str_setu = $str_setu."<option value='".$st_ymd.$ed_ymd."' ".$chk_flg.">".$i."年度3節(".$st_ymd."～".$ed_ymd.")\r\n";
			$st_flg = false;
		break;
		}
	}
	$next_year_flg = true;

}
array_push($select_ymd, $str_setu);
return $select_ymd;

}
?>
