<?php
#-------------------------------------------------
#  ▼DB接続
#-------------------------------------------------

function DB_CONNECT() {
 # DBに接続
$db=mysql_connect(hostname,dbuser,dbpass);
if(!$db)
{
    die("MySQL接続失敗: ".mysql_error());
}
 # 文字コードをsjisに設定
//mysql_query("SET NAMES SJIS",$db); //クエリの文字コードを設定
mysql_query("SET NAMES CP932",$db); //クエリの文字コードを設定
 # DB名選択
$db_selected=mysql_select_db(dbname,$db);
}
#-------------------------------------------------
#  ▼DB切断
#-------------------------------------------------

function DB_DISCONNECT() {
# DB切断
#mysql_close($db);
}
#-------------------------------------------------
#  ▼メンバー情報取得１（メニュー画面専用）
#-------------------------------------------------
function USERID_CNT($userid,$pwd){
# DB接続
DB_CONNECT();
# SQL実行
$user = -1;
$crypted_password = md5($pwd); // パスワード(md5で暗号化)
$sql=sprintf("SELECT COUNT(USERID) COUNT,MEMBER_KBN FROM MS_MEMBER WHERE USERID = '%s' AND PASSWORD = '%s' AND DEL_FLG =1",
mysql_real_escape_string($userid),// ユーザＩＤ;
mysql_real_escape_string($crypted_password));      // パスワード;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
	$kbn = $row['MEMBER_KBN'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$user = $kbn;
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $user;
}
#-------------------------------------------------
#  ▼メンバー情報取得３（ワンタイムパスワード用）
#-------------------------------------------------
function USERID_CNT3($userid,$pwd){
$nowtime = date_format(date_create("NOW"), "Ymd Gis"); // 現在時刻取得
# DB接続
DB_CONNECT();
# SQL実行
$user = -1;
$sql=sprintf("SELECT COUNT(USERID) COUNT,YUKO_TIME FROM WK_ONETIME_PWD WHERE USERID = '%s' AND PASSWORD = '%s'",
mysql_real_escape_string($userid),// ユーザＩＤ
mysql_real_escape_string($pwd));      // パスワード
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
	$yko_time = $row['YUKO_TIME'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$user = $count;
	$yko_time = date_format(date_create($yko_time), "Ymd Gis");
	if($nowtime > $yko_time)
	{
		$user = 0;
	}
}
# DB切断
DB_DISCONNECT();
# 取得データを返却(-1:データなし,0:有効期限切れ,1:データ有)
return $user;
}
#-------------------------------------------------
#  ▼メンバー情報取得
#-------------------------------------------------
function USERNAME(){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT USER_NAME FROM MS_MEMBER WHERE MEMBER_KBN=1 ORDER BY USER_KANA");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
    $user = $user."<option value=".$row['USER_NAME'].">".$row['USER_NAME']."\r\n";
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $user;
}
#-------------------------------------------------
#  ▼メンバー情報取得２（カウント用）
#-------------------------------------------------
function USERID_CNT2($userid,$uname){
# DB接続
DB_CONNECT();
# SQL実行
$user = true;
$crypted_password = md5($pwd); // パスワード(md5で暗号化)
$sql=sprintf("SELECT COUNT(USERID) COUNT FROM MS_MEMBER WHERE USERID = '%s' OR USER_NAME='%s'",
mysql_real_escape_string($userid),// ユーザＩＤ;
mysql_real_escape_string($uname));      // ユーザ名;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$user = false;
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $user;
}
#-------------------------------------------------
#  ▼ログイン日時書込
#-------------------------------------------------
function LOGIN_DATE($uid){
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s");
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("UPDATE MS_MEMBER SET LOGIN_DATE='%s' WHERE USERID='%s'",
mysql_real_escape_string($nowtime),
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼ユーザ登録
#-------------------------------------------------
function USER_INSERT($uid,$pwd,$uname,$ukana,$kbn){
$crypted_password = md5($pwd); // パスワード(md5で暗号化)
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // 現在時刻取得
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("INSERT INTO MS_MEMBER VALUES('%s','%s','%s','%s',%d,%d,%d,1,null,'%s')",
mysql_real_escape_string($uid),
mysql_real_escape_string($crypted_password),
mysql_real_escape_string($uname),
mysql_real_escape_string($ukana),
mysql_real_escape_string($kbn),
mysql_real_escape_string(3),
mysql_real_escape_string(1500),
mysql_real_escape_string($nowtime));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼ユーザ更新
#-------------------------------------------------
function USER_UPDATE($uid,$delflg){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("UPDATE MS_MEMBER SET DEL_FLG=%d WHERE USERID='%s'",
mysql_real_escape_string($delflg),
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼パスワード更新
#-------------------------------------------------
function PASS_CHANGE($uname,$pwd){
$crypted_password = md5($pwd); // パスワード(md5で暗号化)
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("UPDATE MS_MEMBER SET PASSWORD='%s' WHERE USER_NAME='%s'",
mysql_real_escape_string($crypted_password),
mysql_real_escape_string($uname));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼ユーザ削除
#-------------------------------------------------
function USER_DELETE($uid){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("DELETE FROM MS_MEMBER WHERE USERID='%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼ユーザ情報取得
#-------------------------------------------------
function MEMBER_SELECT($uid){
$userlist ="";
$nowtime = date_format(date_create("NOW"), "Y-m-d");
$nowtime = $nowtime." 00:00:00"; // 比較対象の日時に設定
# DB接続
DB_CONNECT();
# SQL実行(データを取得)
$sql=sprintf("SELECT USERID,USER_NAME,USER_KANA,MEMBER_KBN,RANK,DEL_FLG,LOGIN_DATE,TOUROKU_DATE FROM MS_MEMBER ORDER BY TOUROKU_DATE DESC");
$res=mysql_query($sql) or die(mysql_error());
$num = 0; //NO
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// 会員区分
	if($row['MEMBER_KBN'] == 0)
	{
		$cel_color = '#DDDD77';
	}
	else
	{
		$cel_color = '#FFFFFF';
	}
	$num++;
	$userlist = $userlist. "<td bgcolor=".$cel_color. " align=right nowrap class='num'>".$num."</td>\r\n";
	$userlist = $userlist. "<td bgcolor=".$cel_color. " align=right nowrap class='num'>".$row['USERID']."</td>\r\n";
	$userlist = $userlist. "<td bgcolor=".$cel_color. " align=right nowrap class='num'>".$row['USER_NAME']."</td>\r\n";
	$userlist = $userlist. "<td bgcolor=".$cel_color. " align=right nowrap class='num'>".$row['USER_KANA']."</td>\r\n";
	// 会員区分
	if($row['MEMBER_KBN'] == 0)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>管理者</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>一般</td>\r\n";
	}
	// ランク
	if($row['RANK'] == 0 && $row['MEMBER_KBN'] == 1)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>Ｓ</td>\r\n";
	}
	else if($row['RANK'] == 1 && $row['MEMBER_KBN'] == 1)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>Ａ</td>\r\n";
	}
	else if($row['RANK'] == 2 && $row['MEMBER_KBN'] == 1)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>Ｂ</td>\r\n";
	}
	else if($row['RANK'] == 3 && $row['MEMBER_KBN'] == 1)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>Ｃ</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>－</td>\r\n";
	}
	// 会員状態
	if($row['DEL_FLG'] == 0)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>無効</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>有効</td>\r\n";
	}
	// 最終ログイン日時
	if($row['LOGIN_DATE'] >= $nowtime)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=right nowrap class='num'><font color=red>".$row['LOGIN_DATE']."</font></td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=right nowrap class='num'>".$row['LOGIN_DATE']."</td>\r\n";
	}
	$userlist = $userlist. "<td bgcolor=".$cel_color. " align=right nowrap class='num'>".$row['TOUROKU_DATE']."</td>\r\n";
	// 論理削除
	if($row['DEL_FLG'] == 1 && $uid != $row['USERID'])
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'><input type=checkbox name=r_delflg".$num." value=".$row['USERID']."></td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>－</td>\r\n";
	}
	// 物理削除
	if(UNSCORE_USER_CNT($row['USER_NAME']) && UNTOURNAMENT_USER_CNT($row['USER_NAME']) && $uid != $row['USERID'])
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'><input type=checkbox name=b_delflg".$num." value=".$row['USERID']."></td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>削除不可</td>\r\n";
	}
	// 復活
	if($row['DEL_FLG'] == 0)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'><input type=checkbox name=resurreflg".$num." value=".$row['USERID']."></td></tr>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>－</td></tr>\r\n";
	}
}
// ユーザの総件数（削除時の判定用として使用する）
$userlist = $userlist. "<input type=hidden name=usernum value=".$num.">";
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $userlist;
}
#-------------------------------------------------
#  ▼スコアのユーザ登録情報取得
#-------------------------------------------------
function UNSCORE_USER_CNT($uname){
# DB接続
DB_CONNECT();
# SQL実行
$flg = true;
$sql=sprintf("SELECT COUNT(USER_NAME) COUNT FROM UN_SCORE WHERE USER_NAME='%s'",
mysql_real_escape_string($uname));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$flg = false;
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $flg;
}
#-------------------------------------------------
#  ▼今月のスコア件数
#-------------------------------------------------
function UNSCORE_MONTH_CNT(){
$nowdate = date_format(date_create("NOW"), "Y-m"); // 現在月取得
$nowdate = $nowdate."-01";
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT COUNT(NO) COUNT FROM UN_SCORE WHERE GAME_DATE >= '%s' AND RANKING=1",
mysql_real_escape_string($nowdate));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $count;
}
#-------------------------------------------------
#  ▼牌譜登録件数
#-------------------------------------------------
function HAIHU_CNT2(){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT COUNT(HAIHU_CODE) COUNT FROM MS_HAIHU");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $count;
}
#-------------------------------------------------
#  ▼牌譜情報取得
#-------------------------------------------------
function HAIHU_SELECT($uid,$kbn,$limit_s,$limit_e){
$haihulist ="";
# DB接続
DB_CONNECT();
# SQL実行(データを取得)
$sql=sprintf("SELECT USERID,HAIHU_CODE,COMMENT,TOUROKU_DATE FROM MS_HAIHU ORDER BY TOUROKU_DATE DESC LIMIT %d,%d",
mysql_real_escape_string($limit_s),
mysql_real_escape_string($limit_e));
$res=mysql_query($sql) or die(mysql_error());
$num = 0; //NO
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$num++;
	$haihulist = $haihulist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$num."</td>\r\n";
	$haihulist = $haihulist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['HAIHU_CODE']."</td>\r\n";
	$haihulist = $haihulist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['COMMENT']."</td>\r\n";
	$haihulist = $haihulist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['TOUROKU_DATE']."</td>\r\n";
	if($uid == $row['USERID'] || $kbn == 0)
	{
		$haihulist = $haihulist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'><input type=checkbox name=haihu".$num." value=".$row['HAIHU_CODE']."></td></tr>\r\n";
	}
	else
	{
		$haihulist = $haihulist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'></td></tr>\r\n";
	}
}
if($haihulist !="")
{
	// 牌譜ファイルの総件数（削除時の判定用として使用する）
	$haihulist = $haihulist. "<input type=hidden name=haihunum value=".$num.">";
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $haihulist;
}
#-------------------------------------------------
#  ▼当月対局情報取得
#-------------------------------------------------
function UN_SCORE_MONTH_SELECT($uid,$kbn,$limit_s,$limit_e){
$scorelist ="";
$nowdate = date_format(date_create("NOW"), "Y-m"); // 現在月取得
$nowdate = $nowdate."-01";
# DB接続
DB_CONNECT();
$num = 0; //NO
# SQL実行(データを取得)
$sql=sprintf("SELECT RUI_NO,NO,PLAY_KBN,RANKING,USER_NAME,SCORE,GAME_SCORE,LP,LPSCORE,FIGHTCLUB_SCORE,GAME_DATE,USERID FROM UN_SCORE WHERE GAME_DATE >= '%s' ORDER BY NO DESC,RANKING ASC LIMIT %d,%d",
mysql_real_escape_string($nowdate),
mysql_real_escape_string($limit_s),
mysql_real_escape_string($limit_e));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// 交互にtdcolorを設定
	if($row['NO'] % 2 == 0)
	{
		$bgcolor = "#FFAAEE";
	}
	else
	{
		$bgcolor = "#AAEEFF";
	}
	if($num % 4 == 0)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap rowspan=4 class='num'>".$row['NO']."</td>\r\n";
		if($row['PLAY_KBN'] == 0)
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'>東風</td>\r\n";
		}
		else
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'>半荘</td>\r\n";
		}
	}
	$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['RANKING']."</td>\r\n";
	$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['USER_NAME']."</td>\r\n";
	if($row['SCORE'] < 0)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=red>".$row['SCORE']."</font></td>\r\n";
	}
	else
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['SCORE']."</td>\r\n";
	}
	if($row['GAME_SCORE'] < 0 && $row['GAME_SCORE'] > -20)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>▲".($row['GAME_SCORE'])*(-1)."</td>\r\n";
	}
	elseif($row['GAME_SCORE'] <= -20)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=red>▲".($row['GAME_SCORE'])*(-1)."</font></td>\r\n";
	}
	else
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['GAME_SCORE']."</td>\r\n";
	}
	//if($row['LP'] < 0)
	//{
	//	$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=red>".$row['LP']."</font></td>\r\n";
	//}
	//else
	//{
	//	$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['LP']."</td>\r\n";
	//}
	if(mfcscore_disp == 1)
	{
		if($row['FIGHTCLUB_SCORE'] < 0)
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=red>".$row['FIGHTCLUB_SCORE']."</font></td>\r\n";
		}
		else if($row['FIGHTCLUB_SCORE'] >= 3)
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=blue>".$row['FIGHTCLUB_SCORE']."</font></td>\r\n";
		}
		else
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['FIGHTCLUB_SCORE']."</td>\r\n";
		}
	}
	if($num % 4 == 0)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'>".$row['GAME_DATE']."</td>\r\n";
	}

	if($uid == $row['USERID'] || $kbn == 0)
	{
		if($num % 4 == 0)
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'><input type=checkbox name=score".$num." value=".$row['RUI_NO']."></td></tr>\r\n";
		}
		else
		{
			$scorelist = $scorelist. "</tr>\r\n";
		}
	}
	else
	{
		if($num % 4 == 0)
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'></td></tr>\r\n";
		}
		else
		{
			$scorelist = $scorelist. "</tr>\r\n";
		}
	}
	$num++;
}
// スコアの総件数（削除時の判定用として使用する）
if($scorelist != "")
{
	$scorelist = $scorelist. "<input type=hidden name=scorenum value=".$num.">";
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $scorelist;
}
#-------------------------------------------------
#  ▼スコア削除
#-------------------------------------------------
function SCORE_DELETE($code){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("DELETE FROM UN_SCORE WHERE RUI_NO=%d",
mysql_real_escape_string($code));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼牌譜登録情報取得
#-------------------------------------------------
function HAIHU_CNT($code){
# DB接続
DB_CONNECT();
# SQL実行
$haihu = true;
$sql=sprintf("SELECT COUNT(HAIHU_CODE) COUNT FROM MS_HAIHU WHERE HAIHU_CODE='%s'",
mysql_real_escape_string($code));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$haihu = false;
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $haihu;
}
#-------------------------------------------------
#  ▼牌譜登録
#-------------------------------------------------
function HAIHU_INSERT($uid,$code,$comment){
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // 現在時刻取得
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("INSERT INTO MS_HAIHU VALUES('%s','%s','%s','%s')",
mysql_real_escape_string($uid),
mysql_real_escape_string($code),
mysql_real_escape_string($comment),
mysql_real_escape_string($nowtime));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼牌譜削除
#-------------------------------------------------
function HAIHU_DELETE($code){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("DELETE FROM MS_HAIHU WHERE HAIHU_CODE='%s'",
mysql_real_escape_string($code));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼ワンタイムパスワード削除
#-------------------------------------------------
function ONETIMEPWD_DELETE($uid){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("DELETE FROM WK_ONETIME_PWD WHERE USERID='%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼ワンタイムパスワード登録
#-------------------------------------------------
function ONETIMEPWD_INSERT($uid,$kbn){
$yukotime = date_format(date_create(yukotime." hours"), "Y/m/d H:i:s"); // X時間後の取得
//生成する文字列の長さを指定
$str_len = rand(5,100);
//この文字列からランダムに文字を抜き出す
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
for(; $i < $str_len; $i++){
	//変数に入った文字列からランダムに1文字を抜き出して「$res」に追記
	$res .= $chars{mt_rand(0, strlen($chars)-1)};
}
$pwd = $res;
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("INSERT INTO WK_ONETIME_PWD VALUES('%s',%d,'%s','%s')",
mysql_real_escape_string($uid),
mysql_real_escape_string($kbn),
mysql_real_escape_string($pwd),
mysql_real_escape_string($yukotime));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼ワンタイムパスワード登録情報取得
#-------------------------------------------------
function ONETIMEPWD_SELECT($uid){
$result = array();
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT PASSWORD,YUKO_TIME,MEMBER_KBN FROM WK_ONETIME_PWD WHERE USERID='%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$pwd = $row['PASSWORD'];
	$yuko_time = $row['YUKO_TIME'];
	$kbn = $row['MEMBER_KBN'];
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
array_push($result, $pwd,$yuko_time,$kbn);
# 取得データを返却
return $result;
}
#-------------------------------------------------
#  ▼MAXRUI_NO取得
#-------------------------------------------------
function MAX_RUINO(){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT MAX(RUI_NO) FROM UN_SCORE");
$res=mysql_query($sql) or die(mysql_error());
list($num) = mysql_fetch_row($res);
if($num == null){
	$num = 1;
}
else
{
	$num++;
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $num;
}
#-------------------------------------------------
#  ▼当月MAX_NO_MONTH取得
#-------------------------------------------------
function MAX_NO_MONTH(){
$nowdate = date_format(date_create("NOW"), "Y-m"); // 現在月取得
$nowdate = $nowdate."-01";
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT MAX(NO) FROM UN_SCORE WHERE GAME_DATE >= '%s'",
mysql_real_escape_string($nowdate));
$res=mysql_query($sql) or die(mysql_error());
list($num) = mysql_fetch_row($res);
if($num == null){
	$num = 1;
}
else
{
	$num++;
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $num;
}
#-------------------------------------------------
#  ▼累計スコア登録
#-------------------------------------------------
function SCORE_INSERT($rui_no,$no,$playkbn,$rank,$username,$score,$gamescore,$lp,$lpscore,$mfcscore,$userid,$firstscore,$resscore){
$nowtime = date_format(date_create("NOW"), "Y-m-d"); // 現在日付取得
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("INSERT INTO UN_SCORE VALUES(%d,%d,%d,%d,'%s',%d,%.1f,%d,%.1f,%.2f,'%s',%d,%d,'%s')",
mysql_real_escape_string($rui_no),
mysql_real_escape_string($no),
mysql_real_escape_string($playkbn),
mysql_real_escape_string($rank),
mysql_real_escape_string($username),
mysql_real_escape_string($score),
mysql_real_escape_string($gamescore),
mysql_real_escape_string($lp),
mysql_real_escape_string($lpscore),
mysql_real_escape_string($mfcscore),
mysql_real_escape_string($userid),
mysql_real_escape_string($firstscore),
mysql_real_escape_string($resscore),
mysql_real_escape_string($nowtime));

$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼累計スコア更新
#-------------------------------------------------
function SCORE_UPDATE($rui_no,$playkbn,$rank,$username,$score,$gamescore,$lp,$lpscore,$mfcscore,$firstscore,$resscore){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("UPDATE UN_SCORE SET PLAY_KBN=%d,USER_NAME='%s',SCORE=%d,GAME_SCORE=%.1f,LP=%d,LPSCORE=%.1f,FIGHTCLUB_SCORE=%.2f,FIRST_SCORE=%d,RES_SCORE=%d WHERE RUI_NO=%d AND RANKING=%d",
mysql_real_escape_string($playkbn),
mysql_real_escape_string($username),
mysql_real_escape_string($score),
mysql_real_escape_string($gamescore),
mysql_real_escape_string($lp),
mysql_real_escape_string($lpscore),
mysql_real_escape_string($mfcscore),
mysql_real_escape_string($firstscore),
mysql_real_escape_string($resscore),
mysql_real_escape_string($rui_no),
mysql_real_escape_string($rank));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼ユーザ情報取得(ランク設定用）
#-------------------------------------------------
function MEMBER_SELECT_RANK(){
$userlist ="";
# DB接続
DB_CONNECT();
# SQL実行(データを取得)
$sql=sprintf("SELECT USERID,USER_NAME,USER_KANA,MEMBER_KBN,RANK,DEL_FLG,LOGIN_DATE,TOUROKU_DATE FROM MS_MEMBER WHERE MEMBER_KBN=1 ORDER BY USER_KANA ASC");
$res=mysql_query($sql) or die(mysql_error());
$num = 0; //NO
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$num++;
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$num."</td>\r\n";
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['USERID']."</td>\r\n";
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['USER_NAME']."</td>\r\n";
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['USER_KANA']."</td>\r\n";
	$userlist = $userlist. "<input type=hidden name=user".$num. " value=".$row['USERID'].">\r\n";
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>一般</td>\r\n";
	// ランク
	if($row['RANK'] == 0)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type='radio' name=rank".$num. " checked value=0>Ｓ<input type='radio' name=rank".$num. " value=1>Ａ<input type='radio' name=rank".$num. " value=2>Ｂ<input type='radio' name=rank".$num. " value=3>Ｃ</td>\r\n";
	}
	else if($row['RANK'] == 1)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type='radio' name=rank".$num. " value=0>Ｓ<input type='radio' name=rank".$num. " checked value=1>Ａ<input type='radio' name=rank".$num. " value=2>Ｂ<input type='radio' name=rank".$num. " value=3>Ｃ</td>\r\n";
	}
	else if($row['RANK'] == 2)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type='radio' name=rank".$num. " value=0>Ｓ<input type='radio' name=rank".$num. " value=1>Ａ<input type='radio' name=rank".$num. " checked value=2>Ｂ<input type='radio' name=rank".$num. " value=3>Ｃ</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type='radio' name=rank".$num. " value=0>Ｓ<input type='radio' name=rank".$num. " value=1>Ａ<input type='radio' name=rank".$num. " value=2>Ｂ<input type='radio' name=rank".$num. " checked value=3>Ｃ</td>\r\n";
	}
	// 会員状態
	if($row['DEL_FLG'] == 0)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>無効</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>有効</td>\r\n";
	}
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['LOGIN_DATE']."</td>\r\n";
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['TOUROKU_DATE']."</td></tr>\r\n";
}
// ユーザの総件数（更新時の判定用として使用する）
$userlist = $userlist. "<input type=hidden name=usernum value=".$num.">";
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $userlist;
}
#-------------------------------------------------
#  ▼リーグランク更新
#-------------------------------------------------
function RANK_UPDATE($user,$rank){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("UPDATE MS_MEMBER SET RANK=%d WHERE USERID='%s'",
mysql_real_escape_string($rank),
mysql_real_escape_string($user));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼スコア登録情報取得→WK_PERFORMANCEテーブルに格納
#-------------------------------------------------
function SCORE_CNT($d_from,$d_to,$kbn,$c_uid,$k_num){
# DB接続
DB_CONNECT();
# ユーザ名(ワーク用)
$wk_user = "";
$cnt_1 = 0;# １位回数
$cnt_2 = 0;# ２位回数
$cnt_3 = 0;# ３位回数
$cnt_4 = 0;# ４位回数
$i = 1;
$data_umu = false;
// 区分が2の場合は、半荘、東風戦のトータルのデータを取得
if($kbn == 2)
{
	$kbn = '0,1';
}
$sql=sprintf("SELECT USER_NAME,RANKING,COUNT(USER_NAME) CNT,ROUND(SUM(GAME_SCORE),1) SUM_SCORE,SUM(LP) SUM_LP,SUM(LPSCORE) SUM_LPSCORE,SUM(FIGHTCLUB_SCORE) SUM_FSCORE FROM UN_SCORE WHERE PLAY_KBN IN(%s) AND GAME_DATE BETWEEN '%s' AND '%s' GROUP BY USER_NAME,RANKING ORDER BY USER_NAME,RANKING,SUM(GAME_SCORE) DESC",
mysql_real_escape_string($kbn),
mysql_real_escape_string($d_from),
mysql_real_escape_string($d_to));
//echo $sql;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$data_umu = true;
	if($wk_user !="" && $wk_user != $row['USER_NAME'])
	{
		// リーグランク取得
		$rank = LEAGUE($wk_user);
		// wk_performanceにデータ書込
		PERFORMANCE_INSERT($c_uid,$wk_user,$rank,($cnt_1+$cnt_2+$cnt_3+$cnt_4),$k_num,$cnt_1,$cnt_2,$cnt_3,$cnt_4,$sum_score,$sum_lp,$sum_lpscore,$sum_fscore,$d_from,$d_to);
		// 添字を初期化
		$i = 1;
		$cnt_1 = 0;# １位回数初期化
		$cnt_2 = 0;# ２位回数初期化
		$cnt_3 = 0;# ３位回数初期化
		$cnt_4 = 0;# ４位回数初期化
		$sum_score = 0;
		$sum_lp = 0;
		$sum_lpscore = 0;
		$sum_fscore = 0;
	}
	while($i <= 4)
	{
		if($i == $row['RANKING'])
		{
			$sum_score = $sum_score + $row['SUM_SCORE'];
			$sum_lp = $sum_lp + $row['SUM_LP'];
			$sum_lpscore = $sum_lpscore + $row['SUM_LPSCORE'];
			$sum_fscore = $sum_fscore + $row['SUM_FSCORE'];
			// 各ランキングの回数を設定
			switch($row['RANKING'])
			{
				//１位の場合
				case 1:
				$cnt_1 = $row['CNT'];
				break;
				//２位の場合
				case 2:
				$cnt_2 = $row['CNT'];
				break;
				//３位の場合
				case 3:
				$cnt_3 = $row['CNT'];
				break;
				//４位の場合
				case 4:
				$cnt_4 = $row['CNT'];
				break;
			}
			$wk_user = $row['USER_NAME'];
			break;
		}
		else
		{
			$i++;
		}
	}
}
// データが存在する場合のみ以下の処理を行う
if($data_umu)
{
	// 最後のユーザのデータ書き込み処理
	// リーグランク取得
	$rank = LEAGUE($wk_user);
	// wk_performanceにデータ書込
	PERFORMANCE_INSERT($c_uid,$wk_user,$rank,($cnt_1+$cnt_2+$cnt_3+$cnt_4),$k_num,$cnt_1,$cnt_2,$cnt_3,$cnt_4,$sum_score,$sum_lp,$sum_lpscore,$sum_fscore,$d_from,$d_to);
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼所属リーグ情報取得
#-------------------------------------------------
function LEAGUE($uname){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT RANK FROM MS_MEMBER WHERE USER_NAME='%s'",
mysql_real_escape_string($uname));      // ユーザ名;;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$rank = $row['RANK'];
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $rank;
}
#-------------------------------------------------
#  ▼累計スコア登録
#-------------------------------------------------
function PERFORMANCE_INSERT($c_userid,$uname,$rank,$t_num,$k_num,$no1,$no2,$no3,$no4,$gamescore,$lp,$lpscore,$fscore,$from,$to){
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // 現在時刻取得
if($t_num < $k_num)
{
	$sort_jun = 1;
}
else
{
	$sort_jun = 0;
}
// 平均順位
$avg_jun = ($no1 + ($no2*2) + ($no3*3) + ($no4*4)) / $t_num;
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("INSERT INTO WK_PERFORMANCE VALUES('%s','%s',%d,%d,%d,%.5f,%d,%d,%d,%d,%.1f,%d,%.1f,%.2f,'%s','%s','%s')",
mysql_real_escape_string($c_userid),
mysql_real_escape_string($uname),
mysql_real_escape_string($rank),
mysql_real_escape_string($t_num),
mysql_real_escape_string($sort_jun),
mysql_real_escape_string($avg_jun),
mysql_real_escape_string($no1),
mysql_real_escape_string($no2),
mysql_real_escape_string($no3),
mysql_real_escape_string($no4),
mysql_real_escape_string($gamescore),
mysql_real_escape_string($lp),
mysql_real_escape_string($lpscore),
mysql_real_escape_string($fscore),
mysql_real_escape_string($from),
mysql_real_escape_string($to),
mysql_real_escape_string($nowtime));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼WK_PERFORMANCEデータ削除
#-------------------------------------------------
function WK_PERFORMANCE_DELETE($uid){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("DELETE FROM WK_PERFORMANCE WHERE C_USERID = '%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}

#-------------------------------------------------
#  ▼成績情報取得
#-------------------------------------------------
function PERFORMANCE_SELECT($uid){
$array_all = array();
$array_s = array();
$array_a = array();
$array_b = array();
$array_c = array();
$res_array = array();
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT WK.USER_NAME,MS.RATING,WK.RANK,WK.AVG_JUN,WK.NUM,WK.GAME_SCORE,WK.NO1,WK.NO2,WK.NO3,WK.NO4 FROM WK_PERFORMANCE WK,MS_MEMBER MS WHERE C_USERID='%s' AND WK.USER_NAME = MS.USER_NAME ORDER BY SORT_JUN,GAME_SCORE DESC",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// 全体表示の配列にデータ格納
	array_push($array_all, $row['USER_NAME'],$row['NUM'],$row['AVG_JUN'],$row['GAME_SCORE'],$row['RATING'],$row['NO1'],$row['NO2'],$row['NO3'],$row['NO4']);
	// 各ランク別に配列にデータを格納
	switch($row['RANK'])
	{
		//Ｓの場合
		case 0:
		array_push($array_s, $row['USER_NAME'],$row['NUM'],$row['AVG_JUN'],$row['GAME_SCORE'],$row['RATING'],$row['NO1'],$row['NO2'],$row['NO3'],$row['NO4']);
		break;
		//Ａの場合
		case 1:
		array_push($array_a, $row['USER_NAME'],$row['NUM'],$row['AVG_JUN'],$row['GAME_SCORE'],$row['RATING'],$row['NO1'],$row['NO2'],$row['NO3'],$row['NO4']);
		break;
		//Ｂの場合
		case 2:
		array_push($array_b, $row['USER_NAME'],$row['NUM'],$row['AVG_JUN'],$row['GAME_SCORE'],$row['RATING'],$row['NO1'],$row['NO2'],$row['NO3'],$row['NO4']);
		break;
		//Ｃの場合
		case 3:
		array_push($array_c, $row['USER_NAME'],$row['NUM'],$row['AVG_JUN'],$row['GAME_SCORE'],$row['RATING'],$row['NO1'],$row['NO2'],$row['NO3'],$row['NO4']);
		break;
	}
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
// 返却用の配列を作成
array_push($res_array,$array_all,$array_s,$array_a,$array_b,$array_c);
# 取得データを返却
return $res_array;
}
#-------------------------------------------------
#  ▼ゲストスコア
#-------------------------------------------------
function GUEST_SCORE($from,$to,$kbn){
if($kbn == 102)
{
	$kbn = '100,101';
}
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT ROUND(SUM(GAME_SCORE),1) SUM_SCORE FROM UN_SCORE WHERE PLAY_KBN IN(%s) AND GAME_DATE BETWEEN '%s' AND '%s'",
mysql_real_escape_string($kbn), // 区分;
mysql_real_escape_string($from),// 集計期間(FROM);
mysql_real_escape_string($to)); // 集計期間(TO);
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$score = $row['SUM_SCORE'];
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
if($score <= -100)
{
	$res_data = "<font color=red>".$score."</font>";
}
else if ($score >= 100)
{
	$res_data = "<font color=blue>".$score."</font>";
}
else
{
	$res_data = $score;
}
# 取得データを返却
return $res_data;
}
#-------------------------------------------------
#  ▼スコア修正対象対局情報取得
#-------------------------------------------------
function SCORE_FIX_DATA($uid,$kbn,$syuseino){
$nowmonth = date_format(date_create("NOW"), "Y-m");
$nowmonth = $nowmonth."-01";
# DB接続
DB_CONNECT();
# SQL実行
if($kbn == 0)
{
	$sql=sprintf("SELECT DISTINCT RUI_NO,NO,GAME_DATE FROM UN_SCORE ORDER BY RUI_NO DESC");
}
else
{
	$sql=sprintf("SELECT DISTINCT RUI_NO,NO,GAME_DATE FROM UN_SCORE WHERE USERID='%s' AND GAME_DATE >= '%s' ORDER BY RUI_NO DESC",
	mysql_real_escape_string($uid),
	mysql_real_escape_string($nowmonth));
}
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	if($kbn == 1)
	{
		if($syuseino == $row['RUI_NO'])
		{
			$user = $user."<option value=".$row['RUI_NO']." selected>第".$row['NO']."戦（".$row['GAME_DATE']."）\r\n";
		} else {
			$user = $user."<option value=".$row['RUI_NO'].">第".$row['NO']."戦（".$row['GAME_DATE']."）\r\n";
		}
	}
	else
	{
		if($syuseino == $row['RUI_NO'])
		{
			$user = $user."<option value=".$row['RUI_NO']." selected>第".$row['RUI_NO']."戦（".$row['GAME_DATE']."）\r\n";
		} else {
			$user = $user."<option value=".$row['RUI_NO'].">第".$row['RUI_NO']."戦（".$row['GAME_DATE']."）\r\n";
		}
	}
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $user;
}

#-------------------------------------------------
#  ▼成績情報取得
#-------------------------------------------------
function SCORE_FIX_SELECT($num){
$array_all = array();
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT RANKING,USER_NAME,SCORE,FIRST_SCORE,RES_SCORE,GAME_DATE,PLAY_KBN FROM UN_SCORE WHERE RUI_NO=%d ORDER BY RANKING",
mysql_real_escape_string($num));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// 各ランク別に配列にデータを格納
	switch($row['RANKING'])
	{
		//１位の場合
		case 1:
			array_push($array_all, $row['PLAY_KBN'],$row['FIRST_SCORE'],$row['RES_SCORE'],$row['GAME_DATE'],$row['USER_NAME'],$row['SCORE']);
			break;
		// ２，３，４位の場合
		case 2:
		case 3:
		case 4:
			array_push($array_all, $row['PLAY_KBN'],$row['USER_NAME'],$row['SCORE']);
			break;
	}
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $array_all;
}
#-------------------------------------------------
#  ▼メンバー情報取得（スコア修正用）
#-------------------------------------------------
function USERNAME2($player1,$player2,$player3,$player4){
$array_all = array();
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT USER_NAME FROM MS_MEMBER WHERE MEMBER_KBN=1 ORDER BY USER_KANA");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	if($player1 == $row['USER_NAME'])
	{
		$user1 = $user1."<option value=".$row['USER_NAME']." selected>".$row['USER_NAME']."\r\n";
	} else {
		$user1 = $user1."<option value=".$row['USER_NAME'].">".$row['USER_NAME']."\r\n";
	}
	if($player2 == $row['USER_NAME'])
	{
		$user2 = $user2."<option value=".$row['USER_NAME']." selected>".$row['USER_NAME']."\r\n";
	} else {
		$user2 = $user2."<option value=".$row['USER_NAME'].">".$row['USER_NAME']."\r\n";
	}
	if($player3 == $row['USER_NAME'])
	{
		$user3 = $user3."<option value=".$row['USER_NAME']." selected>".$row['USER_NAME']."\r\n";
	} else {
		$user3 = $user3."<option value=".$row['USER_NAME'].">".$row['USER_NAME']."\r\n";
	}
	if($player4 == $row['USER_NAME'])
	{
		$user4 = $user4."<option value=".$row['USER_NAME']." selected>".$row['USER_NAME']."\r\n";
	} else {
		$user4 = $user4."<option value=".$row['USER_NAME'].">".$row['USER_NAME']."\r\n";
	}
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
array_push($array_all, $user1,$user2,$user3,$user4);
# 取得データを返却
return $array_all;
}
#-------------------------------------------------
#  ▼スコア削除
#-------------------------------------------------
function UN_SCORE_DELETE(){
if(score_del_year > 0)
{
	$nowdate = date_format(date_create("NOW"), "Y"); // 現在年取得
	$nowdate = ($nowdate - score_del_year)."01-01";
	# DB接続
	DB_CONNECT();
	# SQL実行
	$sql=sprintf("DELETE FROM UN_SCORE WHERE GAME_DATE < '%s'",
	mysql_real_escape_string($nowdate));
	$res=mysql_query($sql) or die(mysql_error());
	# DB切断
	DB_DISCONNECT();
}
}
#-------------------------------------------------
#  ▼大会削除
#-------------------------------------------------
function TOURNAMENT_DELETE($id){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("DELETE FROM MS_TOURNAMENT WHERE TOURNAMENT_ID = %d",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
# SQL実行
$sql=sprintf("DELETE FROM UN_TOURNAMENT WHERE TOURNAMENT_ID = %d",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼大会名登録チェック
#-------------------------------------------------
function TOURNAMENT_CNT($tournament){
# DB接続
DB_CONNECT();
# SQL実行
$cnt = true;
$sql=sprintf("SELECT COUNT(TOURNAMENT_NAME) COUNT FROM MS_TOURNAMENT WHERE TOURNAMENT_NAME = '%s'",
mysql_real_escape_string($tournament));      // 大会名;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$cnt = false;
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $cnt;
}
#-------------------------------------------------
#  ▼大会名登録
#-------------------------------------------------
function TOURNAMENT_INSERT($tournament){
# DB接続
DB_CONNECT();
$cnt = 0;
$sql=sprintf("SELECT MAX(TOURNAMENT_ID) COUNT FROM MS_TOURNAMENT");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$cnt = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
$cnt++;
# SQL実行
$sql=sprintf("INSERT INTO MS_TOURNAMENT VALUES(%d,'%s',1,null)",
mysql_real_escape_string($cnt),
mysql_real_escape_string($tournament));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼大会登録情報取得
#-------------------------------------------------
function TOURNAMENT_CNT2($id){
# DB接続
DB_CONNECT();
# SQL実行
$flg = true;
$sql=sprintf("SELECT COUNT(TOURNAMENT_ID) COUNT FROM UN_TOURNAMENT WHERE TOURNAMENT_ID = %d",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$flg = false;
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $flg;
}
#-------------------------------------------------
#  ▼大会情報取得
#-------------------------------------------------
function TOURNAMENT_SELECT(){
$userlist ="";
# DB接続
DB_CONNECT();
$num = 0; //NO
# SQL実行(データを取得)
$sql=sprintf("SELECT TOURNAMENT_ID,TOURNAMENT_NAME,TOURNAMENT_FLG,CLOSE_DATE FROM MS_TOURNAMENT ORDER BY TOURNAMENT_ID DESC");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$num++;
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['TOURNAMENT_ID']."</td>\r\n";
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['TOURNAMENT_NAME']."</td>\r\n";
	// 大会状態
	if($row['TOURNAMENT_FLG'] == 0)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>開催終了</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>開催中</td>\r\n";
	}
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['CLOSE_DATE']."</td>\r\n";
	// 大会終了
	if($row['TOURNAMENT_FLG'] == 1)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type=checkbox name=r_delflg".$num." value=".$row['TOURNAMENT_ID']."></td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>－</td>\r\n";
	}
	// 物理削除
	//if(TOURNAMENT_CNT2($row['TOURNAMENT_ID']))
	//{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type=checkbox name=b_delflg".$num." value=".$row['TOURNAMENT_ID']."></td>\r\n";
	//}
	//else
	//{
	//	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>削除不可</td>\r\n";
	//}
	// 復活
	if($row['TOURNAMENT_FLG'] == 0)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type=checkbox name=resurreflg".$num." value=".$row['TOURNAMENT_ID']."></td></tr>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>－</td></tr>\r\n";
	}
}
// 大会の総件数（削除時の判定用として使用する）
$userlist = $userlist. "<input type=hidden name=tournamentnum value=".$num.">";
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $userlist;
}
#-------------------------------------------------
#  ▼大会更新
#-------------------------------------------------
function TOURNAMENT_UPDATE($id,$delflg){
if($delflg == 0)
{
	$nowtime = date_format(date_create("NOW"), "Y-m-d"); // 現在時刻取得
}
else
{
	$nowtime = null;
}
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("UPDATE MS_TOURNAMENT SET TOURNAMENT_FLG=%d,CLOSE_DATE='%s' WHERE TOURNAMENT_ID=%d",
mysql_real_escape_string($delflg),
mysql_real_escape_string($nowtime),
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼大会データ取得（プルダウン表示用）
#-------------------------------------------------
function TOURNAMENT_SELECT2($id,$flg){
# DB接続
DB_CONNECT();
# SQL実行
if($flg == 1)
{
	$sql=sprintf("SELECT TOURNAMENT_ID,TOURNAMENT_NAME FROM MS_TOURNAMENT WHERE TOURNAMENT_FLG=%d ORDER BY TOURNAMENT_ID DESC",
	mysql_real_escape_string($flg));
}
else
{
	$sql=sprintf("SELECT TOURNAMENT_ID,TOURNAMENT_NAME FROM MS_TOURNAMENT ORDER BY TOURNAMENT_ID DESC");
}
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	if($id == $row['TOURNAMENT_ID'])
	{
		$tournament = $tournament."<option value=".$row['TOURNAMENT_ID']." selected>".$row['TOURNAMENT_NAME']."\r\n";
	} else {
		$tournament = $tournament."<option value=".$row['TOURNAMENT_ID'].">".$row['TOURNAMENT_NAME']."\r\n";
	}

}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $tournament;
}
#-------------------------------------------------
#  ▼同プレーヤが同大会同回の登録チェック
#-------------------------------------------------
function TOURNAMENT_CHECK($id,$kai,$player1,$player2,$player3,$player4){
# DB接続
DB_CONNECT();
# SQL実行
$cnt = true;
$sql=sprintf("SELECT COUNT(TOURNAMENT_ID) COUNT FROM UN_TOURNAMENT WHERE TOURNAMENT_ID = %d AND NUM = %d AND USER_NAME IN('%s','%s','%s','%s')",
mysql_real_escape_string($id),       // ＩＤ;
mysql_real_escape_string($kai),      // 回;
mysql_real_escape_string($player1),  // プレーヤー１;
mysql_real_escape_string($player2),  // プレーヤー２;
mysql_real_escape_string($player3),  // プレーヤー３;
mysql_real_escape_string($player4)); // プレーヤー４;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$cnt = false;
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $cnt;
}
#-------------------------------------------------
#  ▼TOURNAMENT_MAXRUI_NO取得
#-------------------------------------------------
function TOURNAMENT_MAX_RUINO(){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT MAX(RUI_NO) FROM UN_TOURNAMENT");
$res=mysql_query($sql) or die(mysql_error());
list($num) = mysql_fetch_row($res);
if($num == null){
	$num = 1;
}
else
{
	$num++;
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $num;
}
#-------------------------------------------------
#  ▼大会スコア登録
#-------------------------------------------------
function TOURNAMENT_SCORE_INSERT($rui_no,$id,$num,$playkbn,$rank,$username,$score,$gamescore,$lp,$lpscore,$mfcscore,$userid,$firstscore,$resscore,$comment){
$nowtime = date_format(date_create("NOW"), "Y-m-d"); // 現在日付取得
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("INSERT INTO UN_TOURNAMENT VALUES(%d,%d,%d,%d,%d,'%s',%d,%.1f,%d,%.1f,%.2f,'%s',%d,%d,'%s','%s')",
mysql_real_escape_string($rui_no),
mysql_real_escape_string($id),
mysql_real_escape_string($num),
mysql_real_escape_string($playkbn),
mysql_real_escape_string($rank),
mysql_real_escape_string($username),
mysql_real_escape_string($score),
mysql_real_escape_string($gamescore),
mysql_real_escape_string($lp),
mysql_real_escape_string($lpscore),
mysql_real_escape_string($mfcscore),
mysql_real_escape_string($userid),
mysql_real_escape_string($firstscore),
mysql_real_escape_string($resscore),
mysql_real_escape_string($comment),
mysql_real_escape_string($nowtime));

$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼大会対局情報取得
#-------------------------------------------------
function TOURNAMENT_SCORE_SELECT($uid,$kbn,$tournament_id){
$scorelist ="";
# DB接続
DB_CONNECT();
$num = 0; //NO
# SQL実行(データを取得)
$sql=sprintf("SELECT RUI_NO,TOURNAMENT_ID,NUM,PLAY_KBN,RANKING,USER_NAME,SCORE,GAME_SCORE,LP,LPSCORE,FIGHTCLUB_SCORE,GAME_DATE,USERID,COMMENT FROM UN_TOURNAMENT WHERE TOURNAMENT_ID = %d ORDER BY RUI_NO DESC,RANKING ASC",
mysql_real_escape_string($tournament_id));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// 交互にtdcolorを設定
	if($row['RUI_NO'] % 2 == 0)
	{
		$bgcolor = "#FFAAEE";
	}
	else
	{
		$bgcolor = "#AAEEFF";
	}
	if($num % 4 == 0)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap rowspan=4 class='num'>".$row['RUI_NO']."</td>\r\n";
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap rowspan=4 class='num'>".$row['TOURNAMENT_ID']."</td>\r\n";
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap rowspan=4 class='num'>".$row['NUM']."</td>\r\n";
		if($row['PLAY_KBN'] == 0)
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'>東風</td>\r\n";
		}
		else
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'>半荘</td>\r\n";
		}
	}
	$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['RANKING']."</td>\r\n";
	$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['USER_NAME']."</td>\r\n";
	if($row['SCORE'] < 0)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=red>".$row['SCORE']."</font></td>\r\n";
	}
	else
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['SCORE']."</td>\r\n";
	}
	if($row['GAME_SCORE'] < 0 && $row['GAME_SCORE'] > -20)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>▲".($row['GAME_SCORE'])*(-1)."</td>\r\n";
	}
	elseif($row['GAME_SCORE'] <= -20)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=red>▲".($row['GAME_SCORE'])*(-1)."</font></td>\r\n";
	}
	else
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['GAME_SCORE']."</td>\r\n";
	}
	//if($row['LP'] < 0)
	//{
	//	$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=red>".$row['LP']."</font></td>\r\n";
	//}
	//else
	//{
	//	$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['LP']."</td>\r\n";
	//}
	if(mfcscore_disp == 1)
	{
		if($row['FIGHTCLUB_SCORE'] < 0)
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=red>".$row['FIGHTCLUB_SCORE']."</font></td>\r\n";
		}
		else if($row['FIGHTCLUB_SCORE'] >= 3)
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=blue>".$row['FIGHTCLUB_SCORE']."</font></td>\r\n";
		}
		else
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>".$row['FIGHTCLUB_SCORE']."</td>\r\n";
		}
	}
	if($num % 4 == 0)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap rowspan=4 class='num'>".$row['COMMENT']."</td>\r\n";
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'>".$row['GAME_DATE']."</td>\r\n";
	}

	if($uid == $row['USERID'] || $kbn == 0)
	{
		if($num % 4 == 0)
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'><input type=checkbox name=score".$num." value=".$row['RUI_NO']."></td></tr>\r\n";
		}
		else
		{
			$scorelist = $scorelist. "</tr>\r\n";
		}
	}
	else
	{
		if($num % 4 == 0)
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'></td></tr>\r\n";
		}
		else
		{
			$scorelist = $scorelist. "</tr>\r\n";
		}
	}
	$num++;
}
// 大会スコアの総件数（削除時の判定用として使用する）
$scorelist = $scorelist. "<input type=hidden name=scorenum value=".$num.">";
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $scorelist;
}
#-------------------------------------------------
#  ▼大会スコア削除
#-------------------------------------------------
function TOURNAMENT_SCORE_DELETE($code){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("DELETE FROM UN_TOURNAMENT WHERE RUI_NO=%d",
mysql_real_escape_string($code));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼MAXNUM取得
#-------------------------------------------------
function MAXNUM($id){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT MAX(NUM) FROM UN_TOURNAMENT WHERE TOURNAMENT_ID=%d",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
list($num) = mysql_fetch_row($res);
if($num == null){
	$num = 0;
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $num;
}
#-------------------------------------------------
#  ▼各ユーザの戦の最大数を取得
#-------------------------------------------------
function USER_MAXNUM($id){
$array_all = array();
$array_usr = array();
$array_num = array();
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT MAX(NUM) NUM,USER_NAME FROM UN_TOURNAMENT WHERE TOURNAMENT_ID=%d GROUP BY USER_NAME ORDER BY SUM(GAME_SCORE) DESC",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	array_push($array_usr, $row['USER_NAME']);
	array_push($array_num, $row['NUM']);
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
array_push($array_all, $array_usr,$array_num);
# 取得データを返却
return $array_all;
}
#-------------------------------------------------
#  ▼各ユーザの戦の大会スコアを取得
#-------------------------------------------------
function USER_TOURNAMENTSCORE($id,$uid){
$array_all = array();
$array_usr = array();
$array_num = array();
$array_score = array();
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT TOUR.NUM NUM,TOUR.USER_NAME USER_NAME,TOUR.GAME_SCORE GAME_SCORE FROM UN_TOURNAMENT TOUR,WK_TOURNAMENTSUB SUB WHERE TOUR.TOURNAMENT_ID=%d AND TOUR.USER_NAME = SUB.USER_NAME AND SUB.C_USERID='%s' ORDER BY SUB.SORT_JUN,TOUR.NUM",
mysql_real_escape_string($id),
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	array_push($array_usr, $row['USER_NAME']);
	array_push($array_num, $row['NUM']);
	array_push($array_score, $row['GAME_SCORE']);
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
array_push($array_all, $array_usr,$array_num,$array_score);
# 取得データを返却
return $array_all;
}
#-------------------------------------------------
#  ▼大会のユーザ登録情報取得
#-------------------------------------------------
function UNTOURNAMENT_USER_CNT($uname){
# DB接続
DB_CONNECT();
# SQL実行
$flg = true;
$sql=sprintf("SELECT COUNT(USER_NAME) COUNT FROM UN_TOURNAMENT WHERE USER_NAME='%s'",
mysql_real_escape_string($uname));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$flg = false;
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $flg;
}
#-------------------------------------------------
#  ▼ユーザ名取得
#-------------------------------------------------
function MYUSERNAME($uid){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT USER_NAME FROM MS_MEMBER WHERE USERID='%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$user = $row['USER_NAME'];
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $user;
}
#-------------------------------------------------
#  ▼WK_CONFRONTATIONデータ削除
#-------------------------------------------------
function WK_CONFRONTATION_DELETE($uid){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("DELETE FROM WK_CONFRONTATION WHERE C_USERID = '%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼スコア登録情報取得→WK_CONFRONTATIONテーブルに格納
#-------------------------------------------------
function CONFRONTATION_CREATE($d_from,$d_to,$kbn,$u_name,$uid){
# DB接続
DB_CONNECT();
// 区分が2の場合は、半荘、東風戦のトータルのデータを取得
if($kbn == 2)
{
	$kbn = '0,1';
}
$sql=sprintf("SELECT RUI_NO,USER_NAME,RANKING FROM UN_SCORE WHERE PLAY_KBN IN(%s) AND RUI_NO IN(SELECT RUI_NO FROM UN_SCORE WHERE GAME_DATE BETWEEN '%s' AND '%s' AND USER_NAME='%s') ORDER BY RUI_NO,RANKING",
mysql_real_escape_string($kbn),
mysql_real_escape_string($d_from),
mysql_real_escape_string($d_to),
mysql_real_escape_string($u_name));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$data_umu = true;
	// 処理中のRUI_NOと１つ前のRUI_NOが違う場合、勝ちフラグを初期化
	if($s_ruino != $row['RUI_NO'])
	{
		$winflg = false;
	}
	if($u_name == $row['USER_NAME'])
	{
		$winflg = true;
	}
	// 選択ユーザ以外のデータを書き込む
	else
	{
		// 勝っている場合
		if($winflg)
		{
			// wk_confrontationにデータ書込
			CONFRONTATION_INSERT($uid,$row['RUI_NO'],$row['USER_NAME'],1,0);
		} else {
			// wk_confrontationにデータ書込
			CONFRONTATION_INSERT($uid,$row['RUI_NO'],$row['USER_NAME'],0,1);
		}
	}
	$s_ruino = $row['RUI_NO'];
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼対決情報登録
#-------------------------------------------------
function CONFRONTATION_INSERT($c_userid,$rui_no,$uname,$win,$lose){
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // 現在時刻取得
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("INSERT INTO WK_CONFRONTATION VALUES('%s',%d,'%s',%d,%d,'%s')",
mysql_real_escape_string($c_userid),
mysql_real_escape_string($rui_no),
mysql_real_escape_string($uname),
mysql_real_escape_string($win),
mysql_real_escape_string($lose),
mysql_real_escape_string($nowtime));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼牌譜情報取得
#-------------------------------------------------
function CONFRONTATION_SELECT($uid){
$confrontationlist ="";
# DB接続
DB_CONNECT();
# SQL実行(データを取得)
$sql=sprintf("SELECT USER_NAME,SUM(WIN) WIN,SUM(LOSE) LOSE FROM WK_CONFRONTATION WHERE C_USERID='%s' GROUP BY USER_NAME ORDER BY USER_NAME",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
$num = 0; //NO
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$win_ritu = sprintf("%01.3f",$row['WIN']/($row['WIN']+$row['LOSE'])); // 比較用
	$win_ritu2 = sprintf("%.3f",$row['WIN']/($row['WIN']+$row['LOSE']));  // 表示用
	$confrontationlist = $confrontationlist. "<tr><td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['USER_NAME']."</td>\r\n";
	$confrontationlist = $confrontationlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".($row['WIN']+$row['LOSE'])."</td>\r\n";
	$confrontationlist = $confrontationlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['WIN']."</td>\r\n";
	$confrontationlist = $confrontationlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['LOSE']."</td>\r\n";
	if($win_ritu >= 0.5)
	{
		$confrontationlist = $confrontationlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'><font color=blue>".$win_ritu2."</font></td></tr>\r\n";
	}
	else if($win_ritu <= 0.1)
	{
		$confrontationlist = $confrontationlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'><font color=red>".$win_ritu2."</font></td></tr>\r\n";
	}
	else
	{
		$confrontationlist = $confrontationlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$win_ritu2."</td></tr>\r\n";
	}
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $confrontationlist;
}
#-------------------------------------------------
#  ▼操作ログ登録
#-------------------------------------------------
function LOG_INSERT($userid,$display,$colflg,$content){
//if(logstate == 1 && $userid != adminusr)
if(logstate == 1)
{
	$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // 現在時刻取得
	// microtimeの返り値をexplodeで分割して別々の変数に保存
	list($micro, $Unixtime) = explode(" ", microtime());
	$sec = $micro + date_format(date_create("NOW"), "s"); // 秒"s"とマイクロ秒を足す
	$nowtimesec = $sec;

	$host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
	# DB接続
	DB_CONNECT();
	if(log_day >= 0)
	{
		$log_day = log_day * (-1);
		$del_day = date_format(date_create($log_day."DAYS"), "Y/m/d");
		$del_day = $del_day. " 00:00:00";
		# SQL実行
		$sql=sprintf("DELETE FROM UN_LOG WHERE DAYTIME < '%s'",
		mysql_real_escape_string($del_day));
		$res=mysql_query($sql) or die(mysql_error());
	}
	$uname = MYUSERNAME($userid);
	if($userid == adminusr)
	{
		$uname = "【初期ユーザ】";
	}
	else if($uname == "")
	{
		$uname = "【存在しないユーザ】";
	}

	# SQL実行
	$sql=sprintf("INSERT INTO UN_LOG VALUES('%s','%s','%s','%s','%s','%s',%d,'%s')",
	mysql_real_escape_string($nowtime),
	mysql_real_escape_string($nowtimesec),
	mysql_real_escape_string($userid),
	mysql_real_escape_string($uname),
	mysql_real_escape_string($host),
	mysql_real_escape_string($display),
	mysql_real_escape_string($colflg),
	mysql_real_escape_string($content));
	$res=mysql_query($sql) or die(mysql_error());
	# DB切断
	DB_DISCONNECT();
}
}
#-------------------------------------------------
#  ▼ログ取得
#-------------------------------------------------
function LOG_SELECT($limit_s,$limit_e){
$loglist ="";
# DB接続
DB_CONNECT();
# SQL実行(データを取得)
$sql=sprintf("SELECT DAYTIME,USER_NAME,DISPLAY,COLFLG,CONTENT,HOST_NAME FROM UN_LOG ORDER BY DAYTIME DESC,DAYTIMESEC DESC LIMIT %d,%d",
mysql_real_escape_string($limit_s),
mysql_real_escape_string($limit_e));
$res=mysql_query($sql) or die(mysql_error());
$num = 0; //NO
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// 赤
	if($row['COLFLG'] == 1)
	{
		$loglist = $loglist. "<tr><td bgcolor='#DDDD77' align=right nowrap class='num'>".$row['DAYTIME']."</td>\r\n";
		$loglist = $loglist. "<td bgcolor='#DDDD77' align=right nowrap class='num'>".$row['USER_NAME']."</td>\r\n";
		$loglist = $loglist. "<td bgcolor='#DDDD77' align=right nowrap class='num'>".$row['DISPLAY']."</td>\r\n";
		$loglist = $loglist. "<td bgcolor='#DDDD77' align=right nowrap class='num'><font color=red>".$row['CONTENT']."</font></td>\r\n";
		if(host_disp == 1)
		{
			$loglist = $loglist. "<td bgcolor='#DDDD77' align=right nowrap class='num'>".$row['HOST_NAME']."</td>\r\n";
		}
	}
	// 青
	else if($row['COLFLG'] == 2)
	{
		$loglist = $loglist. "<tr><td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['DAYTIME']."</td>\r\n";
		$loglist = $loglist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['USER_NAME']."</td>\r\n";
		$loglist = $loglist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['DISPLAY']."</td>\r\n";
		$loglist = $loglist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'><font color=blue>".$row['CONTENT']."</font></td>\r\n";
		if(host_disp == 1)
		{
			$loglist = $loglist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['HOST_NAME']."</td>\r\n";
		}
	}
	else
	{
		$loglist = $loglist. "<tr><td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['DAYTIME']."</td>\r\n";
		$loglist = $loglist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['USER_NAME']."</td>\r\n";
		$loglist = $loglist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['DISPLAY']."</td>\r\n";
		$loglist = $loglist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['CONTENT']."</td>\r\n";
		if(host_disp == 1)
		{
			$loglist = $loglist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['HOST_NAME']."</td>\r\n";
		}
	}
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $loglist;
}
#-------------------------------------------------
#  ▼ログ件数
#-------------------------------------------------
function LOG_CNT(){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT COUNT(DAYTIME) COUNT FROM UN_LOG");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $count;
}
#-------------------------------------------------
#  ▼WK_TOURNAMENTSUBデータ削除
#-------------------------------------------------
function WK_TOURNAMENTSUB_DELETE($uid){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("DELETE FROM WK_TOURNAMENTSUB WHERE C_USERID = '%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼大会の合計スコアをメンバごとに算出→ソート順データ作成
#-------------------------------------------------
function TOURNAMENT_TOP($id,$uid){
$num = 1;
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT USER_NAME FROM UN_TOURNAMENT WHERE TOURNAMENT_ID=%d GROUP BY USER_NAME ORDER BY SUM(GAME_SCORE) DESC",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$uname = $row['USER_NAME'];
	WK_TOURNAMENTSUB_INSERT($uid,$uname,$num);
	$num++;
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $count;
}
#-------------------------------------------------
#  ▼ソート順登録（大会検索用）
#-------------------------------------------------
function WK_TOURNAMENTSUB_INSERT($uid,$uname,$sortjun){
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // 現在時刻取得
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("INSERT INTO WK_TOURNAMENTSUB VALUES('%s',%d,'%s','%s')",
mysql_real_escape_string($uid),
mysql_real_escape_string($sortjun),
mysql_real_escape_string($uname),
mysql_real_escape_string($nowtime));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼ゲスト以外の対局者のカウント数を取得
#-------------------------------------------------
function UNSCORE_RESULT($ruino){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT COUNT(USER_NAME) COUNT FROM UN_SCORE WHERE RUI_NO=%d AND PLAY_KBN IN(0,1)",
mysql_real_escape_string($ruino));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $count;
}
#-------------------------------------------------
#  ▼対戦ユーザの試合数を取得
#-------------------------------------------------
function GAME_NUM($name,$rui_no){
$num = null;
# DB接続
DB_CONNECT();
if($name != null)
{
	# SQL実行
	$sql=sprintf("SELECT COUNT(RUI_NO) COUNT FROM UN_SCORE WHERE USER_NAME = '%s' AND RUI_NO <= %d",
	mysql_real_escape_string($name),
	mysql_real_escape_string($rui_no));
	$res=mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($res, MYSQL_BOTH ))
	{
		$num = $row['COUNT'];
	}
	// 結果セットの解放
	mysql_free_result($res);
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $num;
}
#-------------------------------------------------
#  ▼ユーザの現在のレーティングを取得
#-------------------------------------------------
function NOW_RATING($name){
$rate = 0;
# DB接続
DB_CONNECT();
if($name != null)
{
	# SQL実行
	$sql=sprintf("SELECT RATING FROM MS_MEMBER WHERE USER_NAME = '%s'",
	mysql_real_escape_string($name));
	$res=mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($res, MYSQL_BOTH ))
	{
		$rate = $row['RATING'];
	}
	// 結果セットの解放
	mysql_free_result($res);
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $rate;
}
#-------------------------------------------------
#  ▼Ｒ更新
#-------------------------------------------------
function RATE_UPD($uname,$rate){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("UPDATE MS_MEMBER SET RATING='%s' WHERE USER_NAME='%s'",
mysql_real_escape_string($rate),
mysql_real_escape_string($uname));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼対戦者の名前、プレイ区分を取得（Ｒ計算用）
#-------------------------------------------------
function UNSCORE_SELECTS($ruino){
$data = array();
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT USER_NAME,PLAY_KBN FROM UN_SCORE WHERE RUI_NO=%d ORDER BY RANKING",
mysql_real_escape_string($ruino));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	array_push($data,$row['USER_NAME'],$row['PLAY_KBN']);
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $data;
}
#-------------------------------------------------
#  ▼ユーザ削除フラグ更新
#-------------------------------------------------
function USERFLG_UPDATE($delflg,$before_flg){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("UPDATE MS_MEMBER SET DEL_FLG=%d WHERE DEL_FLG=%d",
mysql_real_escape_string($delflg),
mysql_real_escape_string($before_flg));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼ワンタイムパスワード初期ユーザ以外削除
#-------------------------------------------------
function ONETIMEPWD_DELETE_ALMOST(){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("DELETE FROM WK_ONETIME_PWD WHERE USERID <> '%s'",
mysql_real_escape_string(adminusr));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼レーティング初期化
#-------------------------------------------------
function RATING_ALLUPDATE($rate){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("UPDATE MS_MEMBER SET RATING=%d",
mysql_real_escape_string($rate));
$res=mysql_query($sql) or die(mysql_error());
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼スコアのユーザ登録情報取得
#-------------------------------------------------
function RATECALC_FLG($flg){
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT COUNT(CALC_FLG) COUNT FROM UN_RATECALC");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$sql=sprintf("UPDATE UN_RATECALC SET CALC_FLG=%d",
	mysql_real_escape_string($flg));
	$res=mysql_query($sql) or die(mysql_error());
}
else
{
	$sql=sprintf("INSERT INTO UN_RATECALC VALUES(%d)",
	mysql_real_escape_string($flg));
	$res=mysql_query($sql) or die(mysql_error());
}
# DB切断
DB_DISCONNECT();
}
#-------------------------------------------------
#  ▼レーティングフラグ取得
#-------------------------------------------------
function RATECALC_FLG_SELECT(){
# DB接続
DB_CONNECT();
# SQL実行
$flg = false;
$sql=sprintf("SELECT COUNT(CALC_FLG) COUNT FROM UN_RATECALC");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// 結果セットの解放
mysql_free_result($res);
if($count > 0)
{
	$sql=sprintf("SELECT CALC_FLG FROM UN_RATECALC");
	$res=mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($res, MYSQL_BOTH ))
	{
		$calc_flg = $row['CALC_FLG'];
	}
	if($calc_flg == 1)
	{
		$flg = true;
	}
}
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $flg;
}
#-------------------------------------------------
#  ▼一般スコア検索より最小年月日、最大年月日を取得
#-------------------------------------------------
function UNSCORE_YMD_SELECTS(){
$data = array();
# DB接続
DB_CONNECT();
# SQL実行
$sql=sprintf("SELECT MAX(GAME_DATE) MAX_GAMEDATE,MIN(GAME_DATE) MIN_GAMEDATE FROM UN_SCORE",
mysql_real_escape_string($ruino));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	array_push($data,$row['MIN_GAMEDATE'],$row['MAX_GAMEDATE']);
}
// 結果セットの解放
mysql_free_result($res);
# DB切断
DB_DISCONNECT();
# 取得データを返却
return $data;
}
?>