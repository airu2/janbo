<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� user.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/02/21 �V�K�쐬 by airu
#�� 2012/05/05 ���[�U�����폜���̂��݃t�@�C���폜�Ή� by airu (#1)
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_userid = @$_POST['c_userid'];            # ���[�U�h�c
$p_pwd = @$_POST['c_pwd'];                  # �p�X���[�h
$p_name = @$_POST['c_name'];                # ���O
$p_kana = @$_POST['c_kana'];                # �J�i
$p_kbn = @$_POST['c_kbn'];                  # �敪
$p_uid2 = @$_POST['uid'];                   # ���O�C�����[�U�h�c
$p_pwd2 = @$_POST['pass'];                  # ���O�C���p�X���[�h
?>
<!-- �w�b�_�� -->
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=shift_jis">
<meta http-equiv="content-style-type" content="text/css">
<meta name="viewport" content="width=device-width,user-scalable=yes,initial-scale=1.0,maximum-scale=3.0" />
<link rel="STYLESHEET" type="text/css" href="./css/bbspatio.css">
<style type="text/css">
<!--
body,td,th { font-size:13px;font-family:"MS UI Gothic", Osaka, "�l�r �o�S�V�b�N"; }
a:hover { color:#DD0000 }
.num { font-size:12px; font-family:Verdana,Helvetica,Arial; }
.s1  { font-size:10px; font-family:Verdana,Helvetica,Arial; }
.s2  { font-size:10px; font-family:""MS UI Gothic", Osaka, "�l�r �o�S�V�b�N""; }
-->
</style>

<?php
// �Ǘ��҃p�X���[�h�`�F�b�N
$admin_chk = adminpass_check($p_pwd2,$p_uid2);
// ���[�U�`�F�b�N(DB)
$user_chk = USERID_CNT3($p_uid2,$p_pwd2);
// �p�X���[�h�F�؎��s�̏ꍇ�́A�G���[�Ƃ���
if(!$admin_chk && $user_chk == -1)
{
	echo"<img src='./img/error.png' align='middle'> ".err0001;
	echo "<Input type=button value='���O�C�����' onClick=location.href='./login.php'>";
}
else if($user_chk == 0)
{
	LOG_INSERT($p_uid2,"���[�U�o�^",1,err0015."���[�U�h�c�F".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='���O�C�����' onClick=location.href='./login.php'>";
}
else
{
	###### ���[�U�쐬�{�^���������ꂽ�ꍇ�̏��� ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{ //�|�X�g�Ŕ�΂���Ă�����ȉ�������

		if(@$_POST["regit"])
		{
			 $err = ""; # �G���[�o�͗̈��������
			 $info = "";# �f�[�^�o�^���b�Z�[�W�̈揉����

			 ###### �������E�K�{���̓`�F�b�N ######
			 $err .= moji_check($p_userid,1,50,"���[�U�h�c");   // ���[�U�h�c�̕������E���̓`�F�b�N
			 $err .= moji_check($p_pwd,1,25,"�p�X���[�h");      // �p�X���[�h�̕������E���̓`�F�b�N
			 $err .= moji_check($p_name,1,25,"���O");           // ���O�̕������E���̓`�F�b�N
			 $err .= moji_check($p_kana,0,25,"�J�i");           // �J�i�̕������E���̓`�F�b�N
			 // �������[�U��o�^����ꍇ���G���[�Ƃ���
			 if($p_userid == adminusr)
			 {
			 	$err .="<img src='./img/error.png' align='middle'>".err0002."<BR>";
			 }
			 # �G���[���������Ȃ������ꍇ�AMS_MEMBER�e�[�u���Ƀf�[�^������
			 if($err == "")
			 {
			 	// ���[�U���o�^����Ă��Ȃ������ꍇ�A���[�U�o�^����
			 	if(USERID_CNT2($p_userid,$p_name))
			 	{
			 		USER_INSERT($p_userid,$p_pwd,$p_name,$p_kana,$p_kbn);
			 		LOG_INSERT($p_uid2,"���[�U�o�^",2,"�쐬���[�U�h�c�F".$p_userid."�@���[�U���F".$p_name."�@�敪�F".$p_kbn);
			 	}
			 	else
			 	{   // ���[�U���o�^����Ă����ꍇ�A�G���[�Ƃ���
			 		LOG_INSERT($p_uid2,"���[�U�o�^",1,err0002."���̓��[�U�h�c�F".$p_userid."�@���̓��[�U���F".$p_name);
			 		$err .="<img src='./img/error.png' align='middle'>".err0002."<BR>";
			 	}
			 }

			 ###### �G���[���b�Z�[�W�\�� ######
			 if($err != ""){
			 echo "<table>".$err."</table>";}
			 ###### �f�[�^�o�^���b�Z�[�W�\�� ######
			 else
			 {
			 	$info = msg0001;
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
			 	echo "<Input type=submit value='���j���[��ʂɖ߂�'>";
			 	echo "</form>";
			 }
		}
		// ���[�U�폜�E�����{�^���������ꂽ�ꍇ
		elseif(@$_POST["del"])
		{
			$b_delflg = false;    // �����폜�t���O
			$r_delflg = false;    // �_���폜�t���O
			$resurreflg = false;  // �����t���O
			// b_delflg1�`b_delflgX�܂ō폜�Ƀ`�F�b�N�������Ă�����̂���������
			for($i=0; $i<=@$_POST['usernum'] ;$i++)
			{
				// �폜�Ƀ`�F�b�N�������Ă��郆�[�U�𕨗��폜
				if(@$_POST['b_delflg'.$i] != "")
				{
					USER_DELETE(@$_POST['b_delflg'.$i]);
					// #1 START
					// �����^�C���p�X���[�h�f�[�^�폜
					ONETIMEPWD_DELETE(@$_POST['b_delflg'.$i]);
					// wk_performance�f�[�^�폜
					WK_PERFORMANCE_DELETE(@$_POST['b_delflg'.$i]);
					// wk_confrontation�f�[�^�폜
					WK_CONFRONTATION_DELETE(@$_POST['b_delflg'.$i]);
					// wk_tounamentsub�f�[�^�폜
					WK_TOURNAMENTSUB_DELETE(@$_POST['b_delflg'.$i]);
					if(file_exists(c_filedir.'Result_'.@$_POST['b_delflg'.$i].'.html'))
					{
						// �W�v���ʃt�@�C���폜
						unlink(c_filedir.'Result_'.@$_POST['b_delflg'.$i].'.html');
					}
					// #1 END
					$b_delflg = true;
					LOG_INSERT($p_uid2,"���[�U�o�^",2,"�����폜���[�U�h�c�F".@$_POST['b_delflg'.$i]);
				}
				// �폜�Ƀ`�F�b�N�������Ă��郆�[�U��_���폜
				if(@$_POST['r_delflg'.$i] != "")
				{
					USER_UPDATE(@$_POST['r_delflg'.$i],0);
					$r_delflg = true;
					LOG_INSERT($p_uid2,"���[�U�o�^",2,"�_���폜���[�U�h�c�F".@$_POST['r_delflg'.$i]);
				}
				// �����Ƀ`�F�b�N�������Ă��郆�[�U�𕜊�
				if(@$_POST['resurreflg'.$i] != "")
				{
					USER_UPDATE(@$_POST['resurreflg'.$i],1);
					$resurreflg = true;
					LOG_INSERT($p_uid2,"���[�U�o�^",2,"�������[�U�h�c�F".@$_POST['resurreflg'.$i]);
				}
			}
			// ���̓`�F�b�N(1���I������Ă��Ȃ��ꍇ�́A�G���[�Ƃ���)
			if(!$b_delflg && !$r_delflg && !$resurreflg)
			{
				echo"<img src='./img/error.png' align='middle'> ".err0014;
			}
		}
	}
}
// ���[�U�o�^���b�Z�[�W���ݒ肳��Ă��Ȃ��ꍇ�A�t�H�[����ʂ�\������
if($info == "")
{
?>

<!-- HTML�{�̕� -->

<title><?php echo boardtitle ?></title></head>
<body background="<?php echo background ?>" bgcolor="<?php echo bgcolor ?>" text="#000000" link="#0000FF" vlink="#800080" alink="#DD0000">
<div id="container">
<?php // �p�X���[�h�F�ؐ����̏ꍇ�́A�t�H�[���\��
if($admin_chk || ($user_chk != -1 && $user_chk != 0))
{?>
<table width="100%">
<tr>
  <form action="./menu.php" method='post' >
	<Input type=submit value='���j���[��ʂɖ߂�'>
	<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
	<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
	<input type=hidden name='job' value="back">
  </form>
</tr></table>
<Table border="0" cellspacing="0" cellpadding="0" width="100%">
<Tr bgcolor="<?php echo line_color ?>"><Td bgcolor="<?php echo line_color ?>"  class="td0">
<table border="0" cellspacing="1" cellpadding="5" width="100%">
<tr bgcolor="<?php echo in_bgcolor ?>"><td bgcolor="<?php echo in_bgcolor ?>" nowrap width="100%" class="td0">
<img src="./img/users.png" align="middle">
&nbsp; <b>���[�U�o�^�t�H�[��</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">���[�U�h�c<?php echo hissu ?></th>
  <td><input type="text" name="c_userid" size="50" value="<?php echo $p_userid  ?>" maxlength="25" style="ime-mode:disabled"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�p�X���[�h<?php echo hissu ?></th>
  <td><input type="password" name="c_pwd" size="50" value="<?php echo $p_pwd  ?>" maxlength="25"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">���O<?php echo hissu ?></th>
  <td><input type="text" name="c_name" size="50" value="<?php echo $p_name  ?>" maxlength="25"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�J�i</th>
  <td><input type="text" name="c_kana" size="50" value="<?php echo $p_kana  ?>" maxlength="25"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�敪</th>
  <td><select name="c_kbn">
<option value=0>�Ǘ���
<option value=1 selected>���
</select></td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="���[�U�쐬"> &nbsp;&nbsp;
    <input type="submit" name=del style="font-size:15pt;background:#FF66AA" value="���[�U�폜�E����"> &nbsp;&nbsp;
</tr>
</tr></table>

</Td></Tr></Table><br><br>
�����[�U�ꗗ��
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>NO</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���[�UID</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���[�U��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���[�U�J�i</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>����敪</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�������[�O</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>������</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�ŏI���O�C������</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���[�U�o�^����</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�_���폜</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�����폜</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>����</b></td>
</tr>
<?php
// �o�^����Ă��郆�[�U��\������
$member_list = MEMBER_SELECT($p_uid2);
echo $member_list;
} ?>
</form>
</div></body></html>
<?php } ?>