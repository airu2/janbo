<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� tournament_search.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/05/05 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_uid2 = @$_POST['uid'];                   # ���O�C�����[�U�h�c
$p_pwd2 = @$_POST['pass'];                  # ���O�C���p�X���[�h
$p_id = @$_POST['tournament'];              # ���h�c
// �����擾�i�v���_�E���\���p�j
$tournament = TOURNAMENT_SELECT2($p_id,0);
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
<title><?php echo boardtitle ?></title></head>
<body background="<?php echo background ?>" bgcolor="<?php echo bgcolor ?>" text="#000000" link="#0000FF" vlink="#800080" alink="#DD0000">
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
	LOG_INSERT($p_uid2,"�΋ǐ��яƉ�i���΋ǁj",1,err0015."���[�U�h�c�F".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='���O�C�����' onClick=location.href='./login.php'>";
}
else
{
	###### �{�^���������ꂽ�ꍇ�̏��� ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{
		// ��������
		if(@$_POST["regit"])
		{
			$err = "";
			// �g�쐬�̂��߁A�ő���MAX�l���擾
			$max_num = MAXNUM($p_id);
			// �e���[�U�̐�̍ő吔���擾
			$max_array_data = USER_MAXNUM($p_id);
			// WK_TOURNAMENTSUB�f�[�^�폜
			WK_TOURNAMENTSUB_DELETE($p_uid2);
			// ���̍��v�X�R�A�������o���ƂɎZ�o���\�[�g���f�[�^�쐬
			TOURNAMENT_TOP($p_id,$p_uid2);
			// �e���[�U�̐�̑��X�R�A���擾
			$array_data = USER_TOURNAMENTSCORE($p_id,$p_uid2);
			if($max_num == 0)
			{
				LOG_INSERT($p_uid2,"�΋ǐ��яƉ�i���΋ǁj",1,err0021."�@���h�c�F".$p_id);
				$err .= "<img src='./img/error.png' align='middle'>".err0021."<BR>";
			}
			else
			{
				LOG_INSERT($p_uid2,"�΋ǐ��яƉ�i���΋ǁj",0,"���h�c�F".$p_id."�̃f�[�^�擾");
			}
			// ���I���`�F�b�N�i��1���쐬����Ă��Ȃ��Ƃ��󂪑I���ł��Ă��܂��̂Ń`�F�b�N�j
			if($p_id == "")
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0022."<BR>";
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
&nbsp; <b>�΋ǐ��яƉ�i���΋ǁj</b></td>
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
  <th bgcolor="#FFFFFF" width="100">���<?php echo hissu ?></th>
  <td>
  <select name="tournament">
  <?php echo $tournament ?>
  </select>
    </td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="���яƉ�">
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## �w�b�_�\���� ########## -->
<BR>
�����с�
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<?php if($max_num > 0) { ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǎ�</b></td>
<?php for($i=1; $i<=$max_num; $i++) { ?>
<td bgcolor=<?php echo line_color ?> nowrap><b><?php echo mb_convert_kana($i,N,"shift_jis")."���"?></b></td>
<?php } ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>���v</b></td>
<?php } ?>
</tr>
<?php
// �������ꂽ���̑΋ǌ��ʂ�\������
$k=0;
for($i = 0; $i < count($max_array_data[0]);)
{
	$sum_score = 0;
	echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$max_array_data[0][$i]."</td>"; // �΋ǎ�
	for($j = 0; $j < $max_array_data[1][$i]; $j++)
	{
		if($max_array_data[0][$i] == $array_data[0][$k] && $j+1 == $array_data[1][$k])
		{
			if($array_data[2][$k] < 0 && $array_data[2][$k] > -20)
			{
				echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>��".($array_data[2][$k])*(-1)."</td>"; // �΋ǃX�R�A
			}
			else if($array_data[2][$k] <= -20)
			{
				echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>��".($array_data[2][$k])*(-1)."</font></td>"; // �΋ǃX�R�A
			}
			else
			{
				echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$array_data[2][$k]."</td>"; // �΋ǃX�R�A
			}
			$sum_score = $sum_score + $array_data[2][$k];
			$k++;
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'></td>"; // �΋ǃX�R�A(�󕶎�)
		}
		if($max_array_data[0][$i] != $array_data[0][$k])
		{
			for($l = $max_array_data[1][$i]; $l < $max_num; $l++)
			{
				echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'></td>"; // �΋ǃX�R�A(�󕶎�)
			}
		}
	}
	if($sum_score < 0 && $sum_score > -20)
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>��".($sum_score)*(-1)."</font></td></tr>\r\n"; // ���v
	}
	else if($sum_score <= -20)
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>��".($sum_score)*(-1)."</font></td></tr>\r\n"; // ���v
	}
	else
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$sum_score."</td></tr>\r\n"; // ���v
	}
	$i++;
}
?>
</form></table></Td></Tr></Table>
<?php } ?>
</div></body></html>
