<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� log.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/05/22 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_uid2 = @$_POST['uid'];                   # ���O�C�����[�U�h�c
$p_pwd2 = @$_POST['pass'];                  # ���O�C���p�X���[�h
$limit_e = logdisp;
// ���j���[����J�ڂ��ꂽ�ꍇ�́A���������l��ݒ�
if(@$_POST['menu0'] != "")
{
	$page_num = 1; # �y�[�W�ԍ�
	$limit_s = 0;
	$first_page_flg = "disabled";
	$num = LOG_CNT();
	$max_page_num = $num / logdisp;
	if($max_page_num <= 1)
	{
		$last_page_flg = "disabled";
	}
	else if($max_page_num > 1)
	{
		$last_page_flg = "";
	}
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
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='���O�C�����' onClick=location.href='./login.php'>";
}
else
{
	###### �{�^���������ꂽ�ꍇ�̏��� ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{
		if(@$_POST["regit"])
		{
			$limit_s = 0;
			$page_num = 1; # �y�[�W�ԍ�
			$first_page_flg = "disabled";
			$num = LOG_CNT();
			$max_page_num = $num / logdisp;
			if($max_page_num <= $page_num)
			{
				$last_page_flg = "disabled";
			}
		}
		// �y�[�W�J�ڏ���
		if(@$_POST["page_regit1"] || @$_POST["page_regit2"] || @$_POST["page_regit3"] || @$_POST["page_regit4"])
		{
			$num = LOG_CNT();
			$max_page_num = ceil($num / logdisp);
			$page_num = @$_POST['pagenum'];
			// �u�����v������
			if(@$_POST["page_regit1"])
			{
				$page_num = 1; # �y�[�W�ԍ�
				$first_page_flg = "disabled";
			}
			// �u���v������
			elseif(@$_POST["page_regit2"])
			{
				$page_num = $page_num-1; # �y�[�W�ԍ�
				if($page_num <= 1)
				{
					$first_page_flg = "disabled";
				}
				else
				{
					$first_page_flg = "";
				}
			}
			// �u���v������
			elseif(@$_POST["page_regit3"])
			{
				$page_num = $page_num+1; # �y�[�W�ԍ�
				if($max_page_num <= $page_num)
				{
					$last_page_flg = "disabled";
				}
				else
				{
					$last_page_flg = "";
				}
			}
			// �u�����v������
			elseif(@$_POST["page_regit4"])
			{
				$page_num = $max_page_num; # �y�[�W�ԍ�
				$last_page_flg = "disabled";
			}
			$limit_s = ($page_num -1)*logdisp;
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
	<input type=hidden name='job' value="back">
  </form>
</tr></table>
<Table border="0" cellspacing="0" cellpadding="0" width="100%">
<Tr bgcolor="<?php echo line_color ?>"><Td bgcolor="<?php echo line_color ?>"  class="td0">
<table border="0" cellspacing="1" cellpadding="5" width="100%">
<tr bgcolor="<?php echo in_bgcolor ?>"><td bgcolor="<?php echo in_bgcolor ?>" nowrap width="100%" class="td0">
<img src="./img/camera.png" align="middle">
&nbsp; <b>���샍�O</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type=hidden name='kbn' value=<?php echo $p_kbn ?>>
<input type="hidden" name="mode" value="regist">
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="���O�X�V"> &nbsp;&nbsp;
</tr><br><br><br>
<!-- ########## �w�b�_�\���� ########## -->
<BR>
<table border="0" cellspacing="1" cellpadding="5" width="100%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>����</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���샆�[�U</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>��ʖ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>������e�i�G���[�܁j</b></td>
<?php if(host_disp == 1) { ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>�z�X�g��</b></td>
<?php } ?>
</tr>
<?php
// �o�^����Ă��郍�O��\������
$log_list = LOG_SELECT($limit_s,$limit_e);
echo $log_list;
?>
</table></Td></Tr></Table>
<?php }
if($log_list !="")
{ ?>
<center>
<input type="submit" name=page_regit1 <?php echo $first_page_flg ?> style="font-size:15pt;background:#99AA33" value="����">
<input type="submit" name=page_regit2 <?php echo $first_page_flg ?> style="font-size:15pt;background:#99AA33" value="��">
<font face='Century Gothic' size=15pt><?php echo $page_num ?></font>
<input type=hidden name=pagenum value=<?php echo $page_num ?>>
<input type="submit" name=page_regit3 <?php echo $last_page_flg ?> style="font-size:15pt;background:#99AA33" value="��">
<input type="submit" name=page_regit4 <?php echo $last_page_flg ?> style="font-size:15pt;background:#99AA33" value="����">
</center>
<?php } ?>
</div></body></html>
