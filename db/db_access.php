<?php
#-------------------------------------------------
#  ��DB�ڑ�
#-------------------------------------------------

function DB_CONNECT() {
 # DB�ɐڑ�
$db=mysql_connect(hostname,dbuser,dbpass);
if(!$db)
{
    die("MySQL�ڑ����s: ".mysql_error());
}
 # �����R�[�h��sjis�ɐݒ�
//mysql_query("SET NAMES SJIS",$db); //�N�G���̕����R�[�h��ݒ�
mysql_query("SET NAMES CP932",$db); //�N�G���̕����R�[�h��ݒ�
 # DB���I��
$db_selected=mysql_select_db(dbname,$db);
}
#-------------------------------------------------
#  ��DB�ؒf
#-------------------------------------------------

function DB_DISCONNECT() {
# DB�ؒf
#mysql_close($db);
}
#-------------------------------------------------
#  �������o�[���擾�P�i���j���[��ʐ�p�j
#-------------------------------------------------
function USERID_CNT($userid,$pwd){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$user = -1;
$crypted_password = md5($pwd); // �p�X���[�h(md5�ňÍ���)
$sql=sprintf("SELECT COUNT(USERID) COUNT,MEMBER_KBN FROM MS_MEMBER WHERE USERID = '%s' AND PASSWORD = '%s' AND DEL_FLG =1",
mysql_real_escape_string($userid),// ���[�U�h�c;
mysql_real_escape_string($crypted_password));      // �p�X���[�h;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
	$kbn = $row['MEMBER_KBN'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
if($count > 0)
{
	$user = $kbn;
}
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $user;
}
#-------------------------------------------------
#  �������o�[���擾�R�i�����^�C���p�X���[�h�p�j
#-------------------------------------------------
function USERID_CNT3($userid,$pwd){
$nowtime = date_format(date_create("NOW"), "Ymd Gis"); // ���ݎ����擾
# DB�ڑ�
DB_CONNECT();
# SQL���s
$user = -1;
$sql=sprintf("SELECT COUNT(USERID) COUNT,YUKO_TIME FROM WK_ONETIME_PWD WHERE USERID = '%s' AND PASSWORD = '%s'",
mysql_real_escape_string($userid),// ���[�U�h�c
mysql_real_escape_string($pwd));      // �p�X���[�h
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
	$yko_time = $row['YUKO_TIME'];
}
// ���ʃZ�b�g�̉��
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
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp(-1:�f�[�^�Ȃ�,0:�L�������؂�,1:�f�[�^�L)
return $user;
}
#-------------------------------------------------
#  �������o�[���擾
#-------------------------------------------------
function USERNAME(){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT USER_NAME FROM MS_MEMBER WHERE MEMBER_KBN=1 ORDER BY USER_KANA");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
    $user = $user."<option value=".$row['USER_NAME'].">".$row['USER_NAME']."\r\n";
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $user;
}
#-------------------------------------------------
#  �������o�[���擾�Q�i�J�E���g�p�j
#-------------------------------------------------
function USERID_CNT2($userid,$uname){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$user = true;
$crypted_password = md5($pwd); // �p�X���[�h(md5�ňÍ���)
$sql=sprintf("SELECT COUNT(USERID) COUNT FROM MS_MEMBER WHERE USERID = '%s' OR USER_NAME='%s'",
mysql_real_escape_string($userid),// ���[�U�h�c;
mysql_real_escape_string($uname));      // ���[�U��;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
if($count > 0)
{
	$user = false;
}
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $user;
}
#-------------------------------------------------
#  �����O�C����������
#-------------------------------------------------
function LOGIN_DATE($uid){
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s");
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("UPDATE MS_MEMBER SET LOGIN_DATE='%s' WHERE USERID='%s'",
mysql_real_escape_string($nowtime),
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����[�U�o�^
#-------------------------------------------------
function USER_INSERT($uid,$pwd,$uname,$ukana,$kbn){
$crypted_password = md5($pwd); // �p�X���[�h(md5�ňÍ���)
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // ���ݎ����擾
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����[�U�X�V
#-------------------------------------------------
function USER_UPDATE($uid,$delflg){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("UPDATE MS_MEMBER SET DEL_FLG=%d WHERE USERID='%s'",
mysql_real_escape_string($delflg),
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���p�X���[�h�X�V
#-------------------------------------------------
function PASS_CHANGE($uname,$pwd){
$crypted_password = md5($pwd); // �p�X���[�h(md5�ňÍ���)
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("UPDATE MS_MEMBER SET PASSWORD='%s' WHERE USER_NAME='%s'",
mysql_real_escape_string($crypted_password),
mysql_real_escape_string($uname));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����[�U�폜
#-------------------------------------------------
function USER_DELETE($uid){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("DELETE FROM MS_MEMBER WHERE USERID='%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����[�U���擾
#-------------------------------------------------
function MEMBER_SELECT($uid){
$userlist ="";
$nowtime = date_format(date_create("NOW"), "Y-m-d");
$nowtime = $nowtime." 00:00:00"; // ��r�Ώۂ̓����ɐݒ�
# DB�ڑ�
DB_CONNECT();
# SQL���s(�f�[�^���擾)
$sql=sprintf("SELECT USERID,USER_NAME,USER_KANA,MEMBER_KBN,RANK,DEL_FLG,LOGIN_DATE,TOUROKU_DATE FROM MS_MEMBER ORDER BY TOUROKU_DATE DESC");
$res=mysql_query($sql) or die(mysql_error());
$num = 0; //NO
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// ����敪
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
	// ����敪
	if($row['MEMBER_KBN'] == 0)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>�Ǘ���</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>���</td>\r\n";
	}
	// �����N
	if($row['RANK'] == 0 && $row['MEMBER_KBN'] == 1)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>�r</td>\r\n";
	}
	else if($row['RANK'] == 1 && $row['MEMBER_KBN'] == 1)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>�`</td>\r\n";
	}
	else if($row['RANK'] == 2 && $row['MEMBER_KBN'] == 1)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>�a</td>\r\n";
	}
	else if($row['RANK'] == 3 && $row['MEMBER_KBN'] == 1)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>�b</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>�|</td>\r\n";
	}
	// ������
	if($row['DEL_FLG'] == 0)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>����</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>�L��</td>\r\n";
	}
	// �ŏI���O�C������
	if($row['LOGIN_DATE'] >= $nowtime)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=right nowrap class='num'><font color=red>".$row['LOGIN_DATE']."</font></td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=right nowrap class='num'>".$row['LOGIN_DATE']."</td>\r\n";
	}
	$userlist = $userlist. "<td bgcolor=".$cel_color. " align=right nowrap class='num'>".$row['TOUROKU_DATE']."</td>\r\n";
	// �_���폜
	if($row['DEL_FLG'] == 1 && $uid != $row['USERID'])
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'><input type=checkbox name=r_delflg".$num." value=".$row['USERID']."></td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>�|</td>\r\n";
	}
	// �����폜
	if(UNSCORE_USER_CNT($row['USER_NAME']) && UNTOURNAMENT_USER_CNT($row['USER_NAME']) && $uid != $row['USERID'])
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'><input type=checkbox name=b_delflg".$num." value=".$row['USERID']."></td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>�폜�s��</td>\r\n";
	}
	// ����
	if($row['DEL_FLG'] == 0)
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'><input type=checkbox name=resurreflg".$num." value=".$row['USERID']."></td></tr>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor=".$cel_color. " align=center nowrap class='num'>�|</td></tr>\r\n";
	}
}
// ���[�U�̑������i�폜���̔���p�Ƃ��Ďg�p����j
$userlist = $userlist. "<input type=hidden name=usernum value=".$num.">";
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $userlist;
}
#-------------------------------------------------
#  ���X�R�A�̃��[�U�o�^���擾
#-------------------------------------------------
function UNSCORE_USER_CNT($uname){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$flg = true;
$sql=sprintf("SELECT COUNT(USER_NAME) COUNT FROM UN_SCORE WHERE USER_NAME='%s'",
mysql_real_escape_string($uname));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
if($count > 0)
{
	$flg = false;
}
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $flg;
}
#-------------------------------------------------
#  �������̃X�R�A����
#-------------------------------------------------
function UNSCORE_MONTH_CNT(){
$nowdate = date_format(date_create("NOW"), "Y-m"); // ���݌��擾
$nowdate = $nowdate."-01";
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT COUNT(NO) COUNT FROM UN_SCORE WHERE GAME_DATE >= '%s' AND RANKING=1",
mysql_real_escape_string($nowdate));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $count;
}
#-------------------------------------------------
#  ���v���o�^����
#-------------------------------------------------
function HAIHU_CNT2(){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT COUNT(HAIHU_CODE) COUNT FROM MS_HAIHU");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $count;
}
#-------------------------------------------------
#  ���v�����擾
#-------------------------------------------------
function HAIHU_SELECT($uid,$kbn,$limit_s,$limit_e){
$haihulist ="";
# DB�ڑ�
DB_CONNECT();
# SQL���s(�f�[�^���擾)
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
	// �v���t�@�C���̑������i�폜���̔���p�Ƃ��Ďg�p����j
	$haihulist = $haihulist. "<input type=hidden name=haihunum value=".$num.">";
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $haihulist;
}
#-------------------------------------------------
#  �������΋Ǐ��擾
#-------------------------------------------------
function UN_SCORE_MONTH_SELECT($uid,$kbn,$limit_s,$limit_e){
$scorelist ="";
$nowdate = date_format(date_create("NOW"), "Y-m"); // ���݌��擾
$nowdate = $nowdate."-01";
# DB�ڑ�
DB_CONNECT();
$num = 0; //NO
# SQL���s(�f�[�^���擾)
$sql=sprintf("SELECT RUI_NO,NO,PLAY_KBN,RANKING,USER_NAME,SCORE,GAME_SCORE,LP,LPSCORE,FIGHTCLUB_SCORE,GAME_DATE,USERID FROM UN_SCORE WHERE GAME_DATE >= '%s' ORDER BY NO DESC,RANKING ASC LIMIT %d,%d",
mysql_real_escape_string($nowdate),
mysql_real_escape_string($limit_s),
mysql_real_escape_string($limit_e));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// ���݂�tdcolor��ݒ�
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
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'>����</td>\r\n";
		}
		else
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'>����</td>\r\n";
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
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>��".($row['GAME_SCORE'])*(-1)."</td>\r\n";
	}
	elseif($row['GAME_SCORE'] <= -20)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=red>��".($row['GAME_SCORE'])*(-1)."</font></td>\r\n";
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
// �X�R�A�̑������i�폜���̔���p�Ƃ��Ďg�p����j
if($scorelist != "")
{
	$scorelist = $scorelist. "<input type=hidden name=scorenum value=".$num.">";
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $scorelist;
}
#-------------------------------------------------
#  ���X�R�A�폜
#-------------------------------------------------
function SCORE_DELETE($code){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("DELETE FROM UN_SCORE WHERE RUI_NO=%d",
mysql_real_escape_string($code));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���v���o�^���擾
#-------------------------------------------------
function HAIHU_CNT($code){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$haihu = true;
$sql=sprintf("SELECT COUNT(HAIHU_CODE) COUNT FROM MS_HAIHU WHERE HAIHU_CODE='%s'",
mysql_real_escape_string($code));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
if($count > 0)
{
	$haihu = false;
}
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $haihu;
}
#-------------------------------------------------
#  ���v���o�^
#-------------------------------------------------
function HAIHU_INSERT($uid,$code,$comment){
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // ���ݎ����擾
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("INSERT INTO MS_HAIHU VALUES('%s','%s','%s','%s')",
mysql_real_escape_string($uid),
mysql_real_escape_string($code),
mysql_real_escape_string($comment),
mysql_real_escape_string($nowtime));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���v���폜
#-------------------------------------------------
function HAIHU_DELETE($code){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("DELETE FROM MS_HAIHU WHERE HAIHU_CODE='%s'",
mysql_real_escape_string($code));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �������^�C���p�X���[�h�폜
#-------------------------------------------------
function ONETIMEPWD_DELETE($uid){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("DELETE FROM WK_ONETIME_PWD WHERE USERID='%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �������^�C���p�X���[�h�o�^
#-------------------------------------------------
function ONETIMEPWD_INSERT($uid,$kbn){
$yukotime = date_format(date_create(yukotime." hours"), "Y/m/d H:i:s"); // X���Ԍ�̎擾
//�������镶����̒������w��
$str_len = rand(5,100);
//���̕����񂩂烉���_���ɕ����𔲂��o��
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
for(; $i < $str_len; $i++){
	//�ϐ��ɓ����������񂩂烉���_����1�����𔲂��o���āu$res�v�ɒǋL
	$res .= $chars{mt_rand(0, strlen($chars)-1)};
}
$pwd = $res;
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("INSERT INTO WK_ONETIME_PWD VALUES('%s',%d,'%s','%s')",
mysql_real_escape_string($uid),
mysql_real_escape_string($kbn),
mysql_real_escape_string($pwd),
mysql_real_escape_string($yukotime));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �������^�C���p�X���[�h�o�^���擾
#-------------------------------------------------
function ONETIMEPWD_SELECT($uid){
$result = array();
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT PASSWORD,YUKO_TIME,MEMBER_KBN FROM WK_ONETIME_PWD WHERE USERID='%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$pwd = $row['PASSWORD'];
	$yuko_time = $row['YUKO_TIME'];
	$kbn = $row['MEMBER_KBN'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
array_push($result, $pwd,$yuko_time,$kbn);
# �擾�f�[�^��ԋp
return $result;
}
#-------------------------------------------------
#  ��MAXRUI_NO�擾
#-------------------------------------------------
function MAX_RUINO(){
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $num;
}
#-------------------------------------------------
#  ������MAX_NO_MONTH�擾
#-------------------------------------------------
function MAX_NO_MONTH(){
$nowdate = date_format(date_create("NOW"), "Y-m"); // ���݌��擾
$nowdate = $nowdate."-01";
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $num;
}
#-------------------------------------------------
#  ���݌v�X�R�A�o�^
#-------------------------------------------------
function SCORE_INSERT($rui_no,$no,$playkbn,$rank,$username,$score,$gamescore,$lp,$lpscore,$mfcscore,$userid,$firstscore,$resscore){
$nowtime = date_format(date_create("NOW"), "Y-m-d"); // ���ݓ��t�擾
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���݌v�X�R�A�X�V
#-------------------------------------------------
function SCORE_UPDATE($rui_no,$playkbn,$rank,$username,$score,$gamescore,$lp,$lpscore,$mfcscore,$firstscore,$resscore){
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����[�U���擾(�����N�ݒ�p�j
#-------------------------------------------------
function MEMBER_SELECT_RANK(){
$userlist ="";
# DB�ڑ�
DB_CONNECT();
# SQL���s(�f�[�^���擾)
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
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>���</td>\r\n";
	// �����N
	if($row['RANK'] == 0)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type='radio' name=rank".$num. " checked value=0>�r<input type='radio' name=rank".$num. " value=1>�`<input type='radio' name=rank".$num. " value=2>�a<input type='radio' name=rank".$num. " value=3>�b</td>\r\n";
	}
	else if($row['RANK'] == 1)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type='radio' name=rank".$num. " value=0>�r<input type='radio' name=rank".$num. " checked value=1>�`<input type='radio' name=rank".$num. " value=2>�a<input type='radio' name=rank".$num. " value=3>�b</td>\r\n";
	}
	else if($row['RANK'] == 2)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type='radio' name=rank".$num. " value=0>�r<input type='radio' name=rank".$num. " value=1>�`<input type='radio' name=rank".$num. " checked value=2>�a<input type='radio' name=rank".$num. " value=3>�b</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type='radio' name=rank".$num. " value=0>�r<input type='radio' name=rank".$num. " value=1>�`<input type='radio' name=rank".$num. " value=2>�a<input type='radio' name=rank".$num. " checked value=3>�b</td>\r\n";
	}
	// ������
	if($row['DEL_FLG'] == 0)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>����</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>�L��</td>\r\n";
	}
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['LOGIN_DATE']."</td>\r\n";
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['TOUROKU_DATE']."</td></tr>\r\n";
}
// ���[�U�̑������i�X�V���̔���p�Ƃ��Ďg�p����j
$userlist = $userlist. "<input type=hidden name=usernum value=".$num.">";
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $userlist;
}
#-------------------------------------------------
#  �����[�O�����N�X�V
#-------------------------------------------------
function RANK_UPDATE($user,$rank){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("UPDATE MS_MEMBER SET RANK=%d WHERE USERID='%s'",
mysql_real_escape_string($rank),
mysql_real_escape_string($user));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���X�R�A�o�^���擾��WK_PERFORMANCE�e�[�u���Ɋi�[
#-------------------------------------------------
function SCORE_CNT($d_from,$d_to,$kbn,$c_uid,$k_num){
# DB�ڑ�
DB_CONNECT();
# ���[�U��(���[�N�p)
$wk_user = "";
$cnt_1 = 0;# �P�ʉ�
$cnt_2 = 0;# �Q�ʉ�
$cnt_3 = 0;# �R�ʉ�
$cnt_4 = 0;# �S�ʉ�
$i = 1;
$data_umu = false;
// �敪��2�̏ꍇ�́A�����A������̃g�[�^���̃f�[�^���擾
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
		// ���[�O�����N�擾
		$rank = LEAGUE($wk_user);
		// wk_performance�Ƀf�[�^����
		PERFORMANCE_INSERT($c_uid,$wk_user,$rank,($cnt_1+$cnt_2+$cnt_3+$cnt_4),$k_num,$cnt_1,$cnt_2,$cnt_3,$cnt_4,$sum_score,$sum_lp,$sum_lpscore,$sum_fscore,$d_from,$d_to);
		// �Y����������
		$i = 1;
		$cnt_1 = 0;# �P�ʉ񐔏�����
		$cnt_2 = 0;# �Q�ʉ񐔏�����
		$cnt_3 = 0;# �R�ʉ񐔏�����
		$cnt_4 = 0;# �S�ʉ񐔏�����
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
			// �e�����L���O�̉񐔂�ݒ�
			switch($row['RANKING'])
			{
				//�P�ʂ̏ꍇ
				case 1:
				$cnt_1 = $row['CNT'];
				break;
				//�Q�ʂ̏ꍇ
				case 2:
				$cnt_2 = $row['CNT'];
				break;
				//�R�ʂ̏ꍇ
				case 3:
				$cnt_3 = $row['CNT'];
				break;
				//�S�ʂ̏ꍇ
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
// �f�[�^�����݂���ꍇ�݈̂ȉ��̏������s��
if($data_umu)
{
	// �Ō�̃��[�U�̃f�[�^�������ݏ���
	// ���[�O�����N�擾
	$rank = LEAGUE($wk_user);
	// wk_performance�Ƀf�[�^����
	PERFORMANCE_INSERT($c_uid,$wk_user,$rank,($cnt_1+$cnt_2+$cnt_3+$cnt_4),$k_num,$cnt_1,$cnt_2,$cnt_3,$cnt_4,$sum_score,$sum_lp,$sum_lpscore,$sum_fscore,$d_from,$d_to);
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���������[�O���擾
#-------------------------------------------------
function LEAGUE($uname){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT RANK FROM MS_MEMBER WHERE USER_NAME='%s'",
mysql_real_escape_string($uname));      // ���[�U��;;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$rank = $row['RANK'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $rank;
}
#-------------------------------------------------
#  ���݌v�X�R�A�o�^
#-------------------------------------------------
function PERFORMANCE_INSERT($c_userid,$uname,$rank,$t_num,$k_num,$no1,$no2,$no3,$no4,$gamescore,$lp,$lpscore,$fscore,$from,$to){
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // ���ݎ����擾
if($t_num < $k_num)
{
	$sort_jun = 1;
}
else
{
	$sort_jun = 0;
}
// ���Ϗ���
$avg_jun = ($no1 + ($no2*2) + ($no3*3) + ($no4*4)) / $t_num;
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ��WK_PERFORMANCE�f�[�^�폜
#-------------------------------------------------
function WK_PERFORMANCE_DELETE($uid){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("DELETE FROM WK_PERFORMANCE WHERE C_USERID = '%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}

#-------------------------------------------------
#  �����я��擾
#-------------------------------------------------
function PERFORMANCE_SELECT($uid){
$array_all = array();
$array_s = array();
$array_a = array();
$array_b = array();
$array_c = array();
$res_array = array();
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT WK.USER_NAME,MS.RATING,WK.RANK,WK.AVG_JUN,WK.NUM,WK.GAME_SCORE,WK.NO1,WK.NO2,WK.NO3,WK.NO4 FROM WK_PERFORMANCE WK,MS_MEMBER MS WHERE C_USERID='%s' AND WK.USER_NAME = MS.USER_NAME ORDER BY SORT_JUN,GAME_SCORE DESC",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// �S�̕\���̔z��Ƀf�[�^�i�[
	array_push($array_all, $row['USER_NAME'],$row['NUM'],$row['AVG_JUN'],$row['GAME_SCORE'],$row['RATING'],$row['NO1'],$row['NO2'],$row['NO3'],$row['NO4']);
	// �e�����N�ʂɔz��Ƀf�[�^���i�[
	switch($row['RANK'])
	{
		//�r�̏ꍇ
		case 0:
		array_push($array_s, $row['USER_NAME'],$row['NUM'],$row['AVG_JUN'],$row['GAME_SCORE'],$row['RATING'],$row['NO1'],$row['NO2'],$row['NO3'],$row['NO4']);
		break;
		//�`�̏ꍇ
		case 1:
		array_push($array_a, $row['USER_NAME'],$row['NUM'],$row['AVG_JUN'],$row['GAME_SCORE'],$row['RATING'],$row['NO1'],$row['NO2'],$row['NO3'],$row['NO4']);
		break;
		//�a�̏ꍇ
		case 2:
		array_push($array_b, $row['USER_NAME'],$row['NUM'],$row['AVG_JUN'],$row['GAME_SCORE'],$row['RATING'],$row['NO1'],$row['NO2'],$row['NO3'],$row['NO4']);
		break;
		//�b�̏ꍇ
		case 3:
		array_push($array_c, $row['USER_NAME'],$row['NUM'],$row['AVG_JUN'],$row['GAME_SCORE'],$row['RATING'],$row['NO1'],$row['NO2'],$row['NO3'],$row['NO4']);
		break;
	}
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
// �ԋp�p�̔z����쐬
array_push($res_array,$array_all,$array_s,$array_a,$array_b,$array_c);
# �擾�f�[�^��ԋp
return $res_array;
}
#-------------------------------------------------
#  ���Q�X�g�X�R�A
#-------------------------------------------------
function GUEST_SCORE($from,$to,$kbn){
if($kbn == 102)
{
	$kbn = '100,101';
}
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT ROUND(SUM(GAME_SCORE),1) SUM_SCORE FROM UN_SCORE WHERE PLAY_KBN IN(%s) AND GAME_DATE BETWEEN '%s' AND '%s'",
mysql_real_escape_string($kbn), // �敪;
mysql_real_escape_string($from),// �W�v����(FROM);
mysql_real_escape_string($to)); // �W�v����(TO);
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$score = $row['SUM_SCORE'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
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
# �擾�f�[�^��ԋp
return $res_data;
}
#-------------------------------------------------
#  ���X�R�A�C���Ώۑ΋Ǐ��擾
#-------------------------------------------------
function SCORE_FIX_DATA($uid,$kbn,$syuseino){
$nowmonth = date_format(date_create("NOW"), "Y-m");
$nowmonth = $nowmonth."-01";
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
			$user = $user."<option value=".$row['RUI_NO']." selected>��".$row['NO']."��i".$row['GAME_DATE']."�j\r\n";
		} else {
			$user = $user."<option value=".$row['RUI_NO'].">��".$row['NO']."��i".$row['GAME_DATE']."�j\r\n";
		}
	}
	else
	{
		if($syuseino == $row['RUI_NO'])
		{
			$user = $user."<option value=".$row['RUI_NO']." selected>��".$row['RUI_NO']."��i".$row['GAME_DATE']."�j\r\n";
		} else {
			$user = $user."<option value=".$row['RUI_NO'].">��".$row['RUI_NO']."��i".$row['GAME_DATE']."�j\r\n";
		}
	}
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $user;
}

#-------------------------------------------------
#  �����я��擾
#-------------------------------------------------
function SCORE_FIX_SELECT($num){
$array_all = array();
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT RANKING,USER_NAME,SCORE,FIRST_SCORE,RES_SCORE,GAME_DATE,PLAY_KBN FROM UN_SCORE WHERE RUI_NO=%d ORDER BY RANKING",
mysql_real_escape_string($num));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// �e�����N�ʂɔz��Ƀf�[�^���i�[
	switch($row['RANKING'])
	{
		//�P�ʂ̏ꍇ
		case 1:
			array_push($array_all, $row['PLAY_KBN'],$row['FIRST_SCORE'],$row['RES_SCORE'],$row['GAME_DATE'],$row['USER_NAME'],$row['SCORE']);
			break;
		// �Q�C�R�C�S�ʂ̏ꍇ
		case 2:
		case 3:
		case 4:
			array_push($array_all, $row['PLAY_KBN'],$row['USER_NAME'],$row['SCORE']);
			break;
	}
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $array_all;
}
#-------------------------------------------------
#  �������o�[���擾�i�X�R�A�C���p�j
#-------------------------------------------------
function USERNAME2($player1,$player2,$player3,$player4){
$array_all = array();
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
array_push($array_all, $user1,$user2,$user3,$user4);
# �擾�f�[�^��ԋp
return $array_all;
}
#-------------------------------------------------
#  ���X�R�A�폜
#-------------------------------------------------
function UN_SCORE_DELETE(){
if(score_del_year > 0)
{
	$nowdate = date_format(date_create("NOW"), "Y"); // ���ݔN�擾
	$nowdate = ($nowdate - score_del_year)."01-01";
	# DB�ڑ�
	DB_CONNECT();
	# SQL���s
	$sql=sprintf("DELETE FROM UN_SCORE WHERE GAME_DATE < '%s'",
	mysql_real_escape_string($nowdate));
	$res=mysql_query($sql) or die(mysql_error());
	# DB�ؒf
	DB_DISCONNECT();
}
}
#-------------------------------------------------
#  �����폜
#-------------------------------------------------
function TOURNAMENT_DELETE($id){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("DELETE FROM MS_TOURNAMENT WHERE TOURNAMENT_ID = %d",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
# SQL���s
$sql=sprintf("DELETE FROM UN_TOURNAMENT WHERE TOURNAMENT_ID = %d",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����o�^�`�F�b�N
#-------------------------------------------------
function TOURNAMENT_CNT($tournament){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$cnt = true;
$sql=sprintf("SELECT COUNT(TOURNAMENT_NAME) COUNT FROM MS_TOURNAMENT WHERE TOURNAMENT_NAME = '%s'",
mysql_real_escape_string($tournament));      // ��;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
if($count > 0)
{
	$cnt = false;
}
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $cnt;
}
#-------------------------------------------------
#  �����o�^
#-------------------------------------------------
function TOURNAMENT_INSERT($tournament){
# DB�ڑ�
DB_CONNECT();
$cnt = 0;
$sql=sprintf("SELECT MAX(TOURNAMENT_ID) COUNT FROM MS_TOURNAMENT");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$cnt = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
$cnt++;
# SQL���s
$sql=sprintf("INSERT INTO MS_TOURNAMENT VALUES(%d,'%s',1,null)",
mysql_real_escape_string($cnt),
mysql_real_escape_string($tournament));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����o�^���擾
#-------------------------------------------------
function TOURNAMENT_CNT2($id){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$flg = true;
$sql=sprintf("SELECT COUNT(TOURNAMENT_ID) COUNT FROM UN_TOURNAMENT WHERE TOURNAMENT_ID = %d",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
if($count > 0)
{
	$flg = false;
}
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $flg;
}
#-------------------------------------------------
#  �������擾
#-------------------------------------------------
function TOURNAMENT_SELECT(){
$userlist ="";
# DB�ڑ�
DB_CONNECT();
$num = 0; //NO
# SQL���s(�f�[�^���擾)
$sql=sprintf("SELECT TOURNAMENT_ID,TOURNAMENT_NAME,TOURNAMENT_FLG,CLOSE_DATE FROM MS_TOURNAMENT ORDER BY TOURNAMENT_ID DESC");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$num++;
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['TOURNAMENT_ID']."</td>\r\n";
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['TOURNAMENT_NAME']."</td>\r\n";
	// �����
	if($row['TOURNAMENT_FLG'] == 0)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>�J�ÏI��</td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>�J�Ò�</td>\r\n";
	}
	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=right nowrap class='num'>".$row['CLOSE_DATE']."</td>\r\n";
	// ���I��
	if($row['TOURNAMENT_FLG'] == 1)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type=checkbox name=r_delflg".$num." value=".$row['TOURNAMENT_ID']."></td>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>�|</td>\r\n";
	}
	// �����폜
	//if(TOURNAMENT_CNT2($row['TOURNAMENT_ID']))
	//{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type=checkbox name=b_delflg".$num." value=".$row['TOURNAMENT_ID']."></td>\r\n";
	//}
	//else
	//{
	//	$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>�폜�s��</td>\r\n";
	//}
	// ����
	if($row['TOURNAMENT_FLG'] == 0)
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'><input type=checkbox name=resurreflg".$num." value=".$row['TOURNAMENT_ID']."></td></tr>\r\n";
	}
	else
	{
		$userlist = $userlist. "<td bgcolor='#FFFFFF' align=center nowrap class='num'>�|</td></tr>\r\n";
	}
}
// ���̑������i�폜���̔���p�Ƃ��Ďg�p����j
$userlist = $userlist. "<input type=hidden name=tournamentnum value=".$num.">";
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $userlist;
}
#-------------------------------------------------
#  �����X�V
#-------------------------------------------------
function TOURNAMENT_UPDATE($id,$delflg){
if($delflg == 0)
{
	$nowtime = date_format(date_create("NOW"), "Y-m-d"); // ���ݎ����擾
}
else
{
	$nowtime = null;
}
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("UPDATE MS_TOURNAMENT SET TOURNAMENT_FLG=%d,CLOSE_DATE='%s' WHERE TOURNAMENT_ID=%d",
mysql_real_escape_string($delflg),
mysql_real_escape_string($nowtime),
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����f�[�^�擾�i�v���_�E���\���p�j
#-------------------------------------------------
function TOURNAMENT_SELECT2($id,$flg){
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $tournament;
}
#-------------------------------------------------
#  �����v���[����������̓o�^�`�F�b�N
#-------------------------------------------------
function TOURNAMENT_CHECK($id,$kai,$player1,$player2,$player3,$player4){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$cnt = true;
$sql=sprintf("SELECT COUNT(TOURNAMENT_ID) COUNT FROM UN_TOURNAMENT WHERE TOURNAMENT_ID = %d AND NUM = %d AND USER_NAME IN('%s','%s','%s','%s')",
mysql_real_escape_string($id),       // �h�c;
mysql_real_escape_string($kai),      // ��;
mysql_real_escape_string($player1),  // �v���[���[�P;
mysql_real_escape_string($player2),  // �v���[���[�Q;
mysql_real_escape_string($player3),  // �v���[���[�R;
mysql_real_escape_string($player4)); // �v���[���[�S;
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
if($count > 0)
{
	$cnt = false;
}
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $cnt;
}
#-------------------------------------------------
#  ��TOURNAMENT_MAXRUI_NO�擾
#-------------------------------------------------
function TOURNAMENT_MAX_RUINO(){
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $num;
}
#-------------------------------------------------
#  �����X�R�A�o�^
#-------------------------------------------------
function TOURNAMENT_SCORE_INSERT($rui_no,$id,$num,$playkbn,$rank,$username,$score,$gamescore,$lp,$lpscore,$mfcscore,$userid,$firstscore,$resscore,$comment){
$nowtime = date_format(date_create("NOW"), "Y-m-d"); // ���ݓ��t�擾
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����΋Ǐ��擾
#-------------------------------------------------
function TOURNAMENT_SCORE_SELECT($uid,$kbn,$tournament_id){
$scorelist ="";
# DB�ڑ�
DB_CONNECT();
$num = 0; //NO
# SQL���s(�f�[�^���擾)
$sql=sprintf("SELECT RUI_NO,TOURNAMENT_ID,NUM,PLAY_KBN,RANKING,USER_NAME,SCORE,GAME_SCORE,LP,LPSCORE,FIGHTCLUB_SCORE,GAME_DATE,USERID,COMMENT FROM UN_TOURNAMENT WHERE TOURNAMENT_ID = %d ORDER BY RUI_NO DESC,RANKING ASC",
mysql_real_escape_string($tournament_id));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// ���݂�tdcolor��ݒ�
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
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'>����</td>\r\n";
		}
		else
		{
			$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=center nowrap rowspan=4 class='num'>����</td>\r\n";
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
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'>��".($row['GAME_SCORE'])*(-1)."</td>\r\n";
	}
	elseif($row['GAME_SCORE'] <= -20)
	{
		$scorelist = $scorelist. "<td bgcolor='".$bgcolor."' align=right nowrap class='num'><font color=red>��".($row['GAME_SCORE'])*(-1)."</font></td>\r\n";
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
// ���X�R�A�̑������i�폜���̔���p�Ƃ��Ďg�p����j
$scorelist = $scorelist. "<input type=hidden name=scorenum value=".$num.">";
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $scorelist;
}
#-------------------------------------------------
#  �����X�R�A�폜
#-------------------------------------------------
function TOURNAMENT_SCORE_DELETE($code){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("DELETE FROM UN_TOURNAMENT WHERE RUI_NO=%d",
mysql_real_escape_string($code));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ��MAXNUM�擾
#-------------------------------------------------
function MAXNUM($id){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT MAX(NUM) FROM UN_TOURNAMENT WHERE TOURNAMENT_ID=%d",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
list($num) = mysql_fetch_row($res);
if($num == null){
	$num = 0;
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $num;
}
#-------------------------------------------------
#  ���e���[�U�̐�̍ő吔���擾
#-------------------------------------------------
function USER_MAXNUM($id){
$array_all = array();
$array_usr = array();
$array_num = array();
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT MAX(NUM) NUM,USER_NAME FROM UN_TOURNAMENT WHERE TOURNAMENT_ID=%d GROUP BY USER_NAME ORDER BY SUM(GAME_SCORE) DESC",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	array_push($array_usr, $row['USER_NAME']);
	array_push($array_num, $row['NUM']);
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
array_push($array_all, $array_usr,$array_num);
# �擾�f�[�^��ԋp
return $array_all;
}
#-------------------------------------------------
#  ���e���[�U�̐�̑��X�R�A���擾
#-------------------------------------------------
function USER_TOURNAMENTSCORE($id,$uid){
$array_all = array();
$array_usr = array();
$array_num = array();
$array_score = array();
# DB�ڑ�
DB_CONNECT();
# SQL���s
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
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
array_push($array_all, $array_usr,$array_num,$array_score);
# �擾�f�[�^��ԋp
return $array_all;
}
#-------------------------------------------------
#  �����̃��[�U�o�^���擾
#-------------------------------------------------
function UNTOURNAMENT_USER_CNT($uname){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$flg = true;
$sql=sprintf("SELECT COUNT(USER_NAME) COUNT FROM UN_TOURNAMENT WHERE USER_NAME='%s'",
mysql_real_escape_string($uname));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
if($count > 0)
{
	$flg = false;
}
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $flg;
}
#-------------------------------------------------
#  �����[�U���擾
#-------------------------------------------------
function MYUSERNAME($uid){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT USER_NAME FROM MS_MEMBER WHERE USERID='%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$user = $row['USER_NAME'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $user;
}
#-------------------------------------------------
#  ��WK_CONFRONTATION�f�[�^�폜
#-------------------------------------------------
function WK_CONFRONTATION_DELETE($uid){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("DELETE FROM WK_CONFRONTATION WHERE C_USERID = '%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���X�R�A�o�^���擾��WK_CONFRONTATION�e�[�u���Ɋi�[
#-------------------------------------------------
function CONFRONTATION_CREATE($d_from,$d_to,$kbn,$u_name,$uid){
# DB�ڑ�
DB_CONNECT();
// �敪��2�̏ꍇ�́A�����A������̃g�[�^���̃f�[�^���擾
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
	// ��������RUI_NO�ƂP�O��RUI_NO���Ⴄ�ꍇ�A�����t���O��������
	if($s_ruino != $row['RUI_NO'])
	{
		$winflg = false;
	}
	if($u_name == $row['USER_NAME'])
	{
		$winflg = true;
	}
	// �I�����[�U�ȊO�̃f�[�^����������
	else
	{
		// �����Ă���ꍇ
		if($winflg)
		{
			// wk_confrontation�Ƀf�[�^����
			CONFRONTATION_INSERT($uid,$row['RUI_NO'],$row['USER_NAME'],1,0);
		} else {
			// wk_confrontation�Ƀf�[�^����
			CONFRONTATION_INSERT($uid,$row['RUI_NO'],$row['USER_NAME'],0,1);
		}
	}
	$s_ruino = $row['RUI_NO'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���Ό����o�^
#-------------------------------------------------
function CONFRONTATION_INSERT($c_userid,$rui_no,$uname,$win,$lose){
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // ���ݎ����擾
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("INSERT INTO WK_CONFRONTATION VALUES('%s',%d,'%s',%d,%d,'%s')",
mysql_real_escape_string($c_userid),
mysql_real_escape_string($rui_no),
mysql_real_escape_string($uname),
mysql_real_escape_string($win),
mysql_real_escape_string($lose),
mysql_real_escape_string($nowtime));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���v�����擾
#-------------------------------------------------
function CONFRONTATION_SELECT($uid){
$confrontationlist ="";
# DB�ڑ�
DB_CONNECT();
# SQL���s(�f�[�^���擾)
$sql=sprintf("SELECT USER_NAME,SUM(WIN) WIN,SUM(LOSE) LOSE FROM WK_CONFRONTATION WHERE C_USERID='%s' GROUP BY USER_NAME ORDER BY USER_NAME",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
$num = 0; //NO
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$win_ritu = sprintf("%01.3f",$row['WIN']/($row['WIN']+$row['LOSE'])); // ��r�p
	$win_ritu2 = sprintf("%.3f",$row['WIN']/($row['WIN']+$row['LOSE']));  // �\���p
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
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $confrontationlist;
}
#-------------------------------------------------
#  �����샍�O�o�^
#-------------------------------------------------
function LOG_INSERT($userid,$display,$colflg,$content){
//if(logstate == 1 && $userid != adminusr)
if(logstate == 1)
{
	$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // ���ݎ����擾
	// microtime�̕Ԃ�l��explode�ŕ������ĕʁX�̕ϐ��ɕۑ�
	list($micro, $Unixtime) = explode(" ", microtime());
	$sec = $micro + date_format(date_create("NOW"), "s"); // �b"s"�ƃ}�C�N���b�𑫂�
	$nowtimesec = $sec;

	$host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
	# DB�ڑ�
	DB_CONNECT();
	if(log_day >= 0)
	{
		$log_day = log_day * (-1);
		$del_day = date_format(date_create($log_day."DAYS"), "Y/m/d");
		$del_day = $del_day. " 00:00:00";
		# SQL���s
		$sql=sprintf("DELETE FROM UN_LOG WHERE DAYTIME < '%s'",
		mysql_real_escape_string($del_day));
		$res=mysql_query($sql) or die(mysql_error());
	}
	$uname = MYUSERNAME($userid);
	if($userid == adminusr)
	{
		$uname = "�y�������[�U�z";
	}
	else if($uname == "")
	{
		$uname = "�y���݂��Ȃ����[�U�z";
	}

	# SQL���s
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
	# DB�ؒf
	DB_DISCONNECT();
}
}
#-------------------------------------------------
#  �����O�擾
#-------------------------------------------------
function LOG_SELECT($limit_s,$limit_e){
$loglist ="";
# DB�ڑ�
DB_CONNECT();
# SQL���s(�f�[�^���擾)
$sql=sprintf("SELECT DAYTIME,USER_NAME,DISPLAY,COLFLG,CONTENT,HOST_NAME FROM UN_LOG ORDER BY DAYTIME DESC,DAYTIMESEC DESC LIMIT %d,%d",
mysql_real_escape_string($limit_s),
mysql_real_escape_string($limit_e));
$res=mysql_query($sql) or die(mysql_error());
$num = 0; //NO
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	// ��
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
	// ��
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
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $loglist;
}
#-------------------------------------------------
#  �����O����
#-------------------------------------------------
function LOG_CNT(){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT COUNT(DAYTIME) COUNT FROM UN_LOG");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $count;
}
#-------------------------------------------------
#  ��WK_TOURNAMENTSUB�f�[�^�폜
#-------------------------------------------------
function WK_TOURNAMENTSUB_DELETE($uid){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("DELETE FROM WK_TOURNAMENTSUB WHERE C_USERID = '%s'",
mysql_real_escape_string($uid));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����̍��v�X�R�A�������o���ƂɎZ�o���\�[�g���f�[�^�쐬
#-------------------------------------------------
function TOURNAMENT_TOP($id,$uid){
$num = 1;
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT USER_NAME FROM UN_TOURNAMENT WHERE TOURNAMENT_ID=%d GROUP BY USER_NAME ORDER BY SUM(GAME_SCORE) DESC",
mysql_real_escape_string($id));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$uname = $row['USER_NAME'];
	WK_TOURNAMENTSUB_INSERT($uid,$uname,$num);
	$num++;
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $count;
}
#-------------------------------------------------
#  ���\�[�g���o�^�i�����p�j
#-------------------------------------------------
function WK_TOURNAMENTSUB_INSERT($uid,$uname,$sortjun){
$nowtime = date_format(date_create("NOW"), "Y-m-d G:i:s"); // ���ݎ����擾
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("INSERT INTO WK_TOURNAMENTSUB VALUES('%s',%d,'%s','%s')",
mysql_real_escape_string($uid),
mysql_real_escape_string($sortjun),
mysql_real_escape_string($uname),
mysql_real_escape_string($nowtime));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���Q�X�g�ȊO�̑΋ǎ҂̃J�E���g�����擾
#-------------------------------------------------
function UNSCORE_RESULT($ruino){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT COUNT(USER_NAME) COUNT FROM UN_SCORE WHERE RUI_NO=%d AND PLAY_KBN IN(0,1)",
mysql_real_escape_string($ruino));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $count;
}
#-------------------------------------------------
#  ���ΐ탆�[�U�̎��������擾
#-------------------------------------------------
function GAME_NUM($name,$rui_no){
$num = null;
# DB�ڑ�
DB_CONNECT();
if($name != null)
{
	# SQL���s
	$sql=sprintf("SELECT COUNT(RUI_NO) COUNT FROM UN_SCORE WHERE USER_NAME = '%s' AND RUI_NO <= %d",
	mysql_real_escape_string($name),
	mysql_real_escape_string($rui_no));
	$res=mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($res, MYSQL_BOTH ))
	{
		$num = $row['COUNT'];
	}
	// ���ʃZ�b�g�̉��
	mysql_free_result($res);
}
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $num;
}
#-------------------------------------------------
#  �����[�U�̌��݂̃��[�e�B���O���擾
#-------------------------------------------------
function NOW_RATING($name){
$rate = 0;
# DB�ڑ�
DB_CONNECT();
if($name != null)
{
	# SQL���s
	$sql=sprintf("SELECT RATING FROM MS_MEMBER WHERE USER_NAME = '%s'",
	mysql_real_escape_string($name));
	$res=mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($res, MYSQL_BOTH ))
	{
		$rate = $row['RATING'];
	}
	// ���ʃZ�b�g�̉��
	mysql_free_result($res);
}
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $rate;
}
#-------------------------------------------------
#  ���q�X�V
#-------------------------------------------------
function RATE_UPD($uname,$rate){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("UPDATE MS_MEMBER SET RATING='%s' WHERE USER_NAME='%s'",
mysql_real_escape_string($rate),
mysql_real_escape_string($uname));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���ΐ�҂̖��O�A�v���C�敪���擾�i�q�v�Z�p�j
#-------------------------------------------------
function UNSCORE_SELECTS($ruino){
$data = array();
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT USER_NAME,PLAY_KBN FROM UN_SCORE WHERE RUI_NO=%d ORDER BY RANKING",
mysql_real_escape_string($ruino));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	array_push($data,$row['USER_NAME'],$row['PLAY_KBN']);
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $data;
}
#-------------------------------------------------
#  �����[�U�폜�t���O�X�V
#-------------------------------------------------
function USERFLG_UPDATE($delflg,$before_flg){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("UPDATE MS_MEMBER SET DEL_FLG=%d WHERE DEL_FLG=%d",
mysql_real_escape_string($delflg),
mysql_real_escape_string($before_flg));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �������^�C���p�X���[�h�������[�U�ȊO�폜
#-------------------------------------------------
function ONETIMEPWD_DELETE_ALMOST(){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("DELETE FROM WK_ONETIME_PWD WHERE USERID <> '%s'",
mysql_real_escape_string(adminusr));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����[�e�B���O������
#-------------------------------------------------
function RATING_ALLUPDATE($rate){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("UPDATE MS_MEMBER SET RATING=%d",
mysql_real_escape_string($rate));
$res=mysql_query($sql) or die(mysql_error());
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  ���X�R�A�̃��[�U�o�^���擾
#-------------------------------------------------
function RATECALC_FLG($flg){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT COUNT(CALC_FLG) COUNT FROM UN_RATECALC");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
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
# DB�ؒf
DB_DISCONNECT();
}
#-------------------------------------------------
#  �����[�e�B���O�t���O�擾
#-------------------------------------------------
function RATECALC_FLG_SELECT(){
# DB�ڑ�
DB_CONNECT();
# SQL���s
$flg = false;
$sql=sprintf("SELECT COUNT(CALC_FLG) COUNT FROM UN_RATECALC");
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	$count = $row['COUNT'];
}
// ���ʃZ�b�g�̉��
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
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $flg;
}
#-------------------------------------------------
#  ����ʃX�R�A�������ŏ��N�����A�ő�N�������擾
#-------------------------------------------------
function UNSCORE_YMD_SELECTS(){
$data = array();
# DB�ڑ�
DB_CONNECT();
# SQL���s
$sql=sprintf("SELECT MAX(GAME_DATE) MAX_GAMEDATE,MIN(GAME_DATE) MIN_GAMEDATE FROM UN_SCORE",
mysql_real_escape_string($ruino));
$res=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($res, MYSQL_BOTH ))
{
	array_push($data,$row['MIN_GAMEDATE'],$row['MAX_GAMEDATE']);
}
// ���ʃZ�b�g�̉��
mysql_free_result($res);
# DB�ؒf
DB_DISCONNECT();
# �擾�f�[�^��ԋp
return $data;
}
?>