<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� pass_change.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/05/03 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_userid = @$_POST['c_userid'];            # ���[�U�h�c
$p_pwd = @$_POST['c_pwd'];                  # �p�X���[�h
$p_name = @$_POST['player'];                # ���O
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
	LOG_INSERT($p_uid2,"�p�X���[�h�ύX",1,err0015."���[�U�h�c�F".$p_uid2);
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
			 $err .= moji_check($p_pwd,1,25,"�p�X���[�h");      // �p�X���[�h�̕������E���̓`�F�b�N
			 // �������[�U��o�^����ꍇ���G���[�Ƃ���
			 if($p_userid == adminusr)
			 {
			 	$err .="<img src='./img/error.png' align='middle'>".err0002."<BR>";
			 }
			 # �G���[���������Ȃ������ꍇ�AMS_MEMBER�e�[�u���Ƀf�[�^���X�V
			 if($err == "")
			 {
		 		PASS_CHANGE($p_name,$p_pwd);
		 		LOG_INSERT($p_uid2,"�p�X���[�h�ύX",2,"�p�X���[�h�ύX���[�U�F".$p_name);

			 }

			 ###### �G���[���b�Z�[�W�\�� ######

			 if($err != ""){
			 	echo "<table>".$err."</table>";
			 }
			 ###### �f�[�^�o�^���b�Z�[�W�\�� ######
			 else
			 {
			 	$info = msg0005;
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
	}
}
// ���[�U�o�^���b�Z�[�W���ݒ肳��Ă��Ȃ��ꍇ�A�t�H�[����ʂ�\������
if($info == "")
{
	$user = USERNAME();
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
<img src="./img/keys.png" align="middle">
&nbsp; <b>�p�X���[�h�ύX</b></td>
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
  <td><select name="player"><?php echo $user ?></select></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�p�X���[�h<?php echo hissu ?></th>
  <td><input type="password" name="c_pwd" size="50" value="<?php echo $p_pwd  ?>" maxlength="25"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="�p�X���[�h�ύX"> &nbsp;&nbsp;
</tr>
</tr></table>

</Td></Tr></Table><br><br>
</form>
</div></body></html>
<?php } } ?>