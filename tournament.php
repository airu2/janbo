<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� tournament.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/05/03 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_tournament = @$_POST['c_tournament'];    # ��
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
	LOG_INSERT($p_uid2,"���쐬",1,err0015."���[�U�h�c�F".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='���O�C�����' onClick=location.href='./login.php'>";
}
else
{
	###### ���쐬�{�^���������ꂽ�ꍇ�̏��� ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{ //�|�X�g�Ŕ�΂���Ă�����ȉ�������

		if(@$_POST["regit"])
		{
			 $err = ""; # �G���[�o�͗̈��������
			 $info = "";# �f�[�^�o�^���b�Z�[�W�̈揉����

			 ###### �������E�K�{���̓`�F�b�N ######
			 $err .= moji_check($p_tournament,1,100,"��");   // ���̕������E���̓`�F�b�N
			 # �G���[���������Ȃ������ꍇ�AMS_MEMBER�e�[�u���Ƀf�[�^������
			 if($err == "")
			 {
			 	// ���o�^����Ă��Ȃ������ꍇ�A���o�^����
			 	if(TOURNAMENT_CNT($p_tournament))
			 	{
			 		TOURNAMENT_INSERT($p_tournament);
			 		LOG_INSERT($p_uid2,"���쐬",2,"���F".$p_tournament);
			 	}
			 	else
			 	{   // ���o�^����Ă����ꍇ�A�G���[�Ƃ���
			 		$err .="<img src='./img/error.png' align='middle'>".err0017."<BR>";
			 		LOG_INSERT($p_uid2,"���쐬",1,err0017."�@���͑��F".$p_tournament);
			 	}
			 }

			 ###### �G���[���b�Z�[�W�\�� ######
			 if($err != ""){
			 echo "<table>".$err."</table>";}
			 ###### �f�[�^�o�^���b�Z�[�W�\�� ######
			 else
			 {
			 	$info = msg0004;
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
		// ���폜�E�����{�^���������ꂽ�ꍇ
		elseif(@$_POST["del"])
		{
			$b_delflg = false;    // �����폜�t���O
			// b_delflg1�`b_delflgX�܂ō폜�Ƀ`�F�b�N�������Ă�����̂���������
			for($i=0; $i<=@$_POST['tournamentnum'] ;$i++)
			{
				// �폜�Ƀ`�F�b�N�������Ă�����𕨗��폜
				if(@$_POST['b_delflg'.$i] != "")
				{
					TOURNAMENT_DELETE(@$_POST['b_delflg'.$i]);
					$b_delflg = true;
					LOG_INSERT($p_uid2,"���쐬",2,"�����폜���h�c�F".@$_POST['b_delflg'.$i]);
				}
				// �폜�Ƀ`�F�b�N�������Ă������_���폜
				if(@$_POST['r_delflg'.$i] != "")
				{
					TOURNAMENT_UPDATE(@$_POST['r_delflg'.$i],0);
					$r_delflg = true;
					LOG_INSERT($p_uid2,"���쐬",2,"���I���h�c�F".@$_POST['r_delflg'.$i]);
				}
				// �����Ƀ`�F�b�N�������Ă�����𕜊�
				if(@$_POST['resurreflg'.$i] != "")
				{
					TOURNAMENT_UPDATE(@$_POST['resurreflg'.$i],1);
					$resurreflg = true;
					LOG_INSERT($p_uid2,"���쐬",2,"�������h�c�F".@$_POST['resurreflg'.$i]);
				}

			}
			// ���̓`�F�b�N(1���I������Ă��Ȃ��ꍇ�́A�G���[�Ƃ���)
			if(!$b_delflg && !$r_delflg && !$resurreflg)
			{
				echo"<img src='./img/error.png' align='middle'> ".err0018;
			}
		}
	}
}
// ���o�^���b�Z�[�W���ݒ肳��Ă��Ȃ��ꍇ�A�t�H�[����ʂ�\������
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
<img src="./img/troffi.png" align="middle">
&nbsp; <b>���쐬</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">��<?php echo hissu ?></th>
  <td><input type="text" name="c_tournament" size="120" value="<?php echo $p_tournament  ?>" maxlength="100"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="���쐬"> &nbsp;&nbsp;
    <input type="submit" name=del style="font-size:15pt;background:#FF66AA" value="���I���E�폜�E����"> &nbsp;&nbsp;
</tr>
</tr></table>

</Td></Tr></Table><br><br>
�����ꗗ��
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>���h�c</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�����</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���I����</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���I��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�����폜</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>����</b></td>
</tr>
<?php
// �o�^����Ă������\������
$tournament_list = TOURNAMENT_SELECT();
echo $tournament_list;
} ?>
</form>
</div></body></html>
<?php } ?>