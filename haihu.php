<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� haihu.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/02/27 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_code = @$_POST['code'];                  # �v���R�[�h
$p_comment = @$_POST['comment'];            # �R�����g
$p_uid2 = @$_POST['uid'];                   # ���O�C�����[�U�h�c
$p_pwd2 = @$_POST['pass'];                  # ���O�C���p�X���[�h
$p_kbn = @$_POST['kbn'];                    # ���O�C���敪
$limit_e = page_num;
// ���j���[����J�ڂ��ꂽ�ꍇ�́A���������l��ݒ�
if(@$_POST['menu7'] != "")
{
	$page_num = 1; # �y�[�W�ԍ�
	$limit_s = 0;
	$first_page_flg = "disabled";
	$num = HAIHU_CNT2();
	$max_page_num = $num / page_num;
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
	LOG_INSERT($p_uid2,"�v���o�^",1,err0015."���[�U�h�c�F".$p_uid2);
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
			$err = "";
			$err .= moji_check_ad($p_code,20,20,"�v���R�[�h");       // �v���R�[�h�̕������E���̓`�F�b�N
			$err .= moji_check_ad($p_comment,1,50,"�R�����g");       // �R�����g�̕������E���̓`�F�b�N
			// �G���[�łȂ��ꍇ
			if($err == "")
			{
				// �v���R�[�h�o�^�`�F�b�N
				if(HAIHU_CNT($p_code))
				{
					// �v���o�^
					HAIHU_INSERT($p_uid2,$p_code,$p_comment);
					LOG_INSERT($p_uid2,"�v���o�^",2,"�v���R�[�h�F".$p_code."�@�R�����g�F".$p_comment);
				}
				else
				{
					LOG_INSERT($p_uid2,"�v���o�^",1,err0003."�@�v���R�[�h�F".$p_code);
					$err .= "<img src='./img/error.png' align='middle'>".err0003."<BR>";
				}
			}
			###### �G���[���b�Z�[�W�\�� ######
			if($err != "")
			{
				echo $err;
			}
			$limit_s = 0;
			$page_num = 1; # �y�[�W�ԍ�
			$first_page_flg = "disabled";
			$num = HAIHU_CNT2();
			$max_page_num = $num / page_num;
			if($max_page_num <= $page_num)
			{
				$last_page_flg = "disabled";
			}
		}
		// �폜����
		else if(@$_POST["del"])
		{
			$haihudel_flg = false;    // �v���폜�t���O
			// file1�`fileX�܂ō폜�Ƀ`�F�b�N�������Ă�����̂���������
			for($i=0; $i<=@$_POST['haihunum'] ;$i++)
			{
				// �폜�Ƀ`�F�b�N�������Ă���g���q���폜
				if(@$_POST['haihu'.$i] != "")
				{
					HAIHU_DELETE(@$_POST['haihu'.$i]);
					$haihudel_flg = true;
					LOG_INSERT($p_uid2,"�v���o�^",2,"�폜�v���R�[�h�F".@$_POST['haihu'.$i]);
				}
			}
			// ���̓`�F�b�N(1���I������Ă��Ȃ��ꍇ�́A�G���[�Ƃ���)
			if(!$haihudel_flg)
			{
				echo"<img src='./img/error.png' align='middle'> ".err0004;
			}
			$limit_s = 0;
			$page_num = 1; # �y�[�W�ԍ�
			$first_page_flg = "disabled";
			$num = HAIHU_CNT2();
			$max_page_num = $num / page_num;
			if($max_page_num <= $page_num)
			{
				$last_page_flg = "disabled";
			}
		}
		// �y�[�W�J�ڏ���
		else if(@$_POST["page_regit1"] || @$_POST["page_regit2"] || @$_POST["page_regit3"] || @$_POST["page_regit4"])
		{
			$num = HAIHU_CNT2();
			$max_page_num = ceil($num / page_num);
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
			$limit_s = ($page_num -1)*page_num;
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
<img src="./img/cd_add.png" align="middle">
&nbsp; <b>�v���o�^</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type=hidden name='kbn' value=<?php echo $p_kbn ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�v���R�[�h<?php echo hissu ?></th>
  <td><input type="text" name="code" style="ime-mode:disabled" size="30" value="<?php echo $p_code  ?>" maxlength="20"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�R�����g<?php echo hissu ?></th>
  <td><input type="text" name="comment" size="75" value="<?php echo $p_comment  ?>" maxlength="50"></td>
</tr>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="�v���o�^">
    <input type="submit" name=del   style="font-size:15pt;background:#FF66AA" value="�I��v���폜">&nbsp;&nbsp;
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## �w�b�_�\���� ########## -->
<BR>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>NO</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�v���R�[�h</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�R�����g</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�o�^����</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�폜</b></td>
</tr>
<?php
// �o�^����Ă���v���R�[�h��\������
$haihu_list = HAIHU_SELECT($p_uid2,$p_kbn,$limit_s,$limit_e);
echo $haihu_list;
?>
</table></Td></Tr></Table>
<?php }
if($haihu_list !="")
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
