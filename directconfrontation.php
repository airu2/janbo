<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� directconfrontation.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/05/20 �V�K�쐬 by candy
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_from_y = @$_POST['from_y'];              # FROM(�N)
$p_from_m = @$_POST['from_m'];              # FROM(��)
$p_from_d = @$_POST['from_d'];              # FROM(��)
$p_to_y = @$_POST['to_y'];                  # TO(�N)
$p_to_m = @$_POST['to_m'];                  # TO(��)
$p_to_d = @$_POST['to_d'];                  # TO(��)
$p_kbn = @$_POST['kbn'];                    # �W�v�敪
$p_uid2 = @$_POST['uid'];                   # ���O�C�����[�U�h�c
$p_pwd2 = @$_POST['pass'];                  # ���O�C���p�X���[�h
$p_ukbn = @$_POST['u_kbn'];                 # ���O�C���敪�i0:�Ǘ��ҁA1:��ʁj
$playername = @$_POST['player'];            # �v���[���[��
$p0_select="";                              # �W�v�敪�i�����j�Z���N�g
$p1_select="";                              # �W�v�敪�i�����j�Z���N�g
$p2_select="";                              # �W�v�敪�i�S�āj�Z���N�g
// ���j���[����J�ڂ��ꂽ�ꍇ�́A���������l��ݒ�
if(@$_POST['menu11'] != "")
{
	$p_from_y = date("Y");                  # FROM(�N)
	$p_from_m = date("n");                  # FROM(��)
	$p_from_d = 1;                          # FROM(��)
	$p_to_y = date("Y");                    # TO(�N)
	$p_to_m = date("n");                    # TO(��)
	$p_to_d = date("j");                    # TO(��)
}
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
	LOG_INSERT($p_uid2,"���ڑΌ����ʏƉ�i��ʑ΋ǁj",1,err0015."���[�U�h�c�F".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='���O�C�����' onClick=location.href='./login.php'>";
}
else
{
	###### �{�^���������ꂽ�ꍇ�̏��� ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{
		// �o�^����
		if(@$_POST["regit"])
		{
			if($p_kbn == 0) { $p0_select="selected"; }
			if($p_kbn == 1) { $p1_select="selected"; }
			else { $p2_select="selected"; }
			$err = "";
			$err .= moji_check_ad($p_from_y,1,4,"�W�v���ԁi�e�q�n�l�i�N�j�j");  // �e�q�n�l�i�N�j�̕������E���̓`�F�b�N
			$err .= moji_check_ad($p_from_m,1,2,"�W�v���ԁi�e�q�n�l�i���j�j");  // �e�q�n�l�i���j�̕������E���̓`�F�b�N
			$err .= moji_check_ad($p_from_d,1,2,"�W�v���ԁi�e�q�n�l�i���j�j");  // �e�q�n�l�i���j�̕������E���̓`�F�b�N
			$err .= moji_check_ad($p_to_y,1,4,"�W�v���ԁi�s�n�i�N�j�j");        // �s�n�i�N�j�̕������E���̓`�F�b�N
			$err .= moji_check_ad($p_to_m,1,2,"�W�v���ԁi�s�n�i���j�j");        // �s�n�i���j�̕������E���̓`�F�b�N
			$err .= moji_check_ad($p_to_d,1,2,"�W�v���ԁi�s�n�i���j�j");        // �s�n�i���j�̕������E���̓`�F�b�N
			if($err == "")
			{
				// ���t�^�`�F�b�N
				if(!checkdate($p_from_m, $p_from_d, $p_from_y) || !checkdate($p_to_m, $p_to_d, $p_to_y))
				{
					$err .= "<img src='./img/error.png' align='middle'>".err0010."<BR>";
				}
			}
			// ���t(���Ԏw��`�F�b�N)
			if($err == "")
			{
				if (strtotime($p_from_y."-".$p_from_m."-".$p_from_d) > strtotime($p_to_y."-".$p_to_m."-".$p_to_d))
				{
					$err .= "<img src='./img/error.png' align='middle'>".err0011."<BR>";
				}
			}
			// �G���[�łȂ��ꍇ
			if($err == "")
			{
				if($p_kbn == 0) {$syubetsu="������";}
				else if($p_kbn == 1) {$syubetsu="������";}
				else{ $syubetsu="�S��";}
				LOG_INSERT($p_uid2,"���ڑΌ�����",0,"�e�q�n�l�F".($p_from_y."/".$p_from_m."/".$p_from_d)."�@�s�n�F".($p_to_y."/".$p_to_m."/".$p_to_d)."�@�W�v�敪�F".$syubetsu."�@�����Ώۃ��[�U�F".$playername);
				// wk_confrontation�f�[�^�폜
				WK_CONFRONTATION_DELETE($p_uid2);
				// wk_confrontation�Ƀf�[�^����
				CONFRONTATION_CREATE(($p_from_y."/".$p_from_m."/".$p_from_d),($p_to_y."/".$p_to_m."/".$p_to_d),$p_kbn,$playername,$p_uid2);
				// �f�[�^�擾
				$confrontation_data = CONFRONTATION_SELECT($p_uid2);
			}
			###### �G���[���b�Z�[�W�\�� ######
			if($err != "")
			{
				echo $err;
			}
		}
	}

}
?>

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
	<input type=hidden name='u_kbn' value=<?php echo $p_ukbn ?>>
	<input type=hidden name='job' value="back">
  </form>
</tr></table>
<Table border="0" cellspacing="0" cellpadding="0" width="100%">
<Tr bgcolor="<?php echo line_color ?>"><Td bgcolor="<?php echo line_color ?>"  class="td0">
<table border="0" cellspacing="1" cellpadding="5" width="100%">
<tr bgcolor="<?php echo in_bgcolor ?>"><td bgcolor="<?php echo in_bgcolor ?>" nowrap width="100%" class="td0">
<img src="./img/id_card_view.png" align="middle">
&nbsp; <b>���ڑΌ����ʏƉ�i��ʑ΋ǁj</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type=hidden name='u_kbn' value=<?php echo $p_ukbn ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�����Ώۃ��[�U<?php echo hissu ?></th>
  <td>
  <?php if($p_ukbn == 1)  {
  $myuser = MYUSERNAME($p_uid2);
  echo $myuser ?>
  <input type=hidden name=player value=<?php echo $myuser ?>>
 <?php } else { ?>
 <select name="player" >
  <?php  $member = USERNAME2($playername,"","","");
  echo $member[0]; ?>
  </select>
  <?php } ?>
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�΋Ǔ��t<?php echo hissu ?></th>
  <td><input type="text" name="from_y" style="ime-mode:disabled" value="<?php echo $p_from_y  ?>" maxlength="4" size="6">/
  <input type="text" name="from_m" style="ime-mode:disabled" value="<?php echo $p_from_m  ?>" maxlength="2" size="3">/
  <input type="text" name="from_d" style="ime-mode:disabled" value="<?php echo $p_from_d  ?>" maxlength="2" size="3">�@�`�@
  <input type="text" name="to_y" style="ime-mode:disabled" value="<?php echo $p_to_y  ?>" maxlength="4" size="6">/
  <input type="text" name="to_m" style="ime-mode:disabled" value="<?php echo $p_to_m  ?>" maxlength="2" size="3">/
  <input type="text" name="to_d" style="ime-mode:disabled" value="<?php echo $p_to_d  ?>" maxlength="2" size="3">
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�W�v�敪<?php echo hissu ?></th>
  <td><select name="kbn">
  <option value=2 <?php echo $p2_select ?>>�S��
  <option value=0 <?php echo $p0_select ?>>������
  <option value=1 <?php echo $p1_select ?>>������
  </select></td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="���ڑΌ����ʏƉ�">&nbsp;&nbsp;
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## �w�b�_�\���� ########## -->
<BR>
<table border="0" cellspacing="1" cellpadding="5" width="50%">
�W�v���ԁF<?php if(@$_POST["regit"] && $err =="") { echo $p_from_y."/".$p_from_m."/".$p_from_d ?>�@�`�@<?php echo $p_to_y."/".$p_to_m."/".$p_to_d; } ?><br>
�W�v�敪�F<?php if(@$_POST["regit"] && $err =="") { echo $p_kbn."�i".sum_kbn($p_kbn)."�j"; } ?><br>
</table>
<table border="0" cellspacing="1" cellpadding="5" width="50%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǎ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǐ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�s</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>����</b></td>
</tr>
<?php echo $confrontation_data ?>
</table></Td></Tr></Table>
</form>
<?php } ?>
</div></body></html>
