<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� league_all.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#��  2012/04/01 �V�K�쐬 by airu
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
$p_num = @$_POST['num'];                    # �����K�萔
$p_uid2 = @$_POST['uid'];                   # ���O�C�����[�U�h�c
$p_pwd2 = @$_POST['pass'];                  # ���O�C���p�X���[�h
$p0_select="";                              # �W�v�敪�i�����j�Z���N�g
$p1_select="";                              # �W�v�敪�i�����j�Z���N�g
$p2_select="";                              # �W�v�敪�i�S�āj�Z���N�g

// ���j���[����J�ڂ��ꂽ�ꍇ�́A���������l��ݒ�
if(@$_POST['menu12'] != "")
{
	$p_num = 0;                                        # �����K�萔
	$p_from_y = date_format(date_create("NOW"), "Y");  # FROM(�N)
	$p_from_m = date_format(date_create("NOW"), "n");  # FROM(��)
	$p_from_d = 1;                                     # FROM(��)
	$p_to_y = date_format(date_create("NOW"), "Y");    # TO(�N)
	$p_to_m = date_format(date_create("NOW"), "n");    # TO(��)
	$p_to_d = date_format(date_create("NOW"), "j");    # TO(��)
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
.s2  { font-size:10px; font-family:"MS UI Gothic", Osaka, "�l�r �o�S�V�b�N""; }
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
	LOG_INSERT($p_uid2,"��ʑ΋ǐ��яƉ�i�Ǘ��p�j",1,err0015."���[�U�h�c�F".$p_uid2);
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
			$err .= moji_check_ad($p_num,1,3,"�����K�萔");                     // �����K�萔�̕������E���̓`�F�b�N
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
				if(date_format(date_create($p_from_y."/".$p_from_m."/".$p_from_d),"Ymd") > date_format(date_create($p_to_y."/".$p_to_m."/".$p_to_d),"Ymd"))
				{
					$err .= "<img src='./img/error.png' align='middle'>".err0011."<BR>";
				}
			}
			// �K�萔�̐����^�`�F�b�N
			if($err == "")
			{
				if (!is_numeric($p_num))
				{
					$err .= "<img src='./img/error.png' align='middle'>".err0012."<BR>";
				}
			}
			// �K�萔��0�����`�F�b�N
			if($err == "")
			{
				if ($p_num < 0)
				{
					$err .= "<img src='./img/error.png' align='middle'>".err0013."<BR>";
				}
			}
			// �G���[�łȂ��ꍇ
			if($err == "")
			{
				// wk_performance�f�[�^�폜
				WK_PERFORMANCE_DELETE($p_uid2);
				// wk_performance�Ƀf�[�^����
				SCORE_CNT(($p_from_y."/".$p_from_m."/".$p_from_d),($p_to_y."/".$p_to_m."/".$p_to_d),$p_kbn,$p_uid2,$p_num);
				// �f�[�^�擾
				$performance_data = PERFORMANCE_SELECT($p_uid2);
				// �Q�X�g�f�[�^�擾
				$guest_score = GUEST_SCORE(($p_from_y."/".$p_from_m."/".$p_from_d),($p_to_y."/".$p_to_m."/".$p_to_d),($p_kbn + 100));
				// �f�[�^�쐬
				create_file($performance_data[0],$performance_data[1],$performance_data[2],$performance_data[3],$performance_data[4],$p_uid2,$p_num,$p_kbn,($p_from_y."/".$p_from_m."/".$p_from_d),($p_to_y."/".$p_to_m."/".$p_to_d),0);
				if($p_kbn == 0) {$syubetsu="������";}
				else if($p_kbn == 1){ $syubetsu="������";}
				else { $syubetsu="�S��";}
				LOG_INSERT($p_uid2,"��ʑ΋ǐ��яƉ�i�Ǘ��p�j",0,"�e�q�n�l�F".($p_from_y."/".$p_from_m."/".$p_from_d)."�@�s�n�F".($p_to_y."/".$p_to_m."/".$p_to_d)."�@�΋ǎ�ʁF".$syubetsu."�@�����K�萔�F".$p_num);
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
	<input type=hidden name='job' value="back">
  </form>
</tr></table>
<Table border="0" cellspacing="0" cellpadding="0" width="100%">
<Tr bgcolor="<?php echo line_color ?>"><Td bgcolor="<?php echo line_color ?>"  class="td0">
<table border="0" cellspacing="1" cellpadding="5" width="100%">
<tr bgcolor="<?php echo in_bgcolor ?>"><td bgcolor="<?php echo in_bgcolor ?>" nowrap width="100%" class="td0">
<img src="./img/gurafu.png" align="middle">
&nbsp; <b>��ʑ΋ǐ��яƉ�i�Ǘ��p�j</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�΋Ǔ��t<?php echo hissu ?></th>
  <td><input type="text" name="from_y" style="ime-mode:disabled" value="<?php echo $p_from_y  ?>" maxlength="4" size="6" >/
  <input type="text" name="from_m" style="ime-mode:disabled" value="<?php echo $p_from_m  ?>" maxlength="2" size="3" >/
  <input type="text" name="from_d" style="ime-mode:disabled" value="<?php echo $p_from_d  ?>" maxlength="2" size="3" >�@�`�@
  <input type="text" name="to_y" style="ime-mode:disabled" value="<?php echo $p_to_y  ?>" maxlength="4" size="6" >/
  <input type="text" name="to_m" style="ime-mode:disabled" value="<?php echo $p_to_m  ?>" maxlength="2" size="3" >/
  <input type="text" name="to_d" style="ime-mode:disabled" value="<?php echo $p_to_d  ?>" maxlength="2" size="3" >
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
  <th bgcolor="#FFFFFF" width="100">�����K�萔<?php echo hissu ?></th>
  <td><input type="text" name="num" style="ime-mode:disabled" value="<?php echo $p_num  ?>" maxlength="3" size="5">�������K�萔���w�肵�Ȃ��ꍇ�́A0����͂��Ă��������B�����K�萔�����̏ꍇ�͏W�v�㎇�ŕ\������܂��B</td>
</tr>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="���яƉ�">&nbsp;&nbsp;
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## �w�b�_�\���� ########## -->
<BR>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
�W�v���ԁF<?php if(@$_POST["regit"] && $err =="") { echo $p_from_y."/".$p_from_m."/".$p_from_d ?>�@�`�@<?php echo $p_to_y."/".$p_to_m."/".$p_to_d; } ?><br>
�W�v�敪�F<?php if(@$_POST["regit"] && $err =="") { echo $p_kbn."�i".sum_kbn($p_kbn)."�j"; } ?><br>
�t�@�C���F<?php if(@$_POST["regit"] && $err =="") { echo "<a href='./output/Result_".$p_uid2.".html'>Result_".$p_uid2.".html</a>"; }?><br>
<?php if(!RATECALC_FLG_SELECT()) { echo "<font color=red><b>".err0024."</b></font>"; } ?><br><br>
</table>
<?php if(guest_use_flg == 1) { ?>
<Table cellspacing="0" cellpadding="0" width="20%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th width=100 bgcolor="#FFFFFF" >�Q�X�g�X�R�A</th>
  <td><center><?php echo $guest_score ?></center></td>
</tr>
</tr></table>
</Td></Tr></Table><br>
<?php } ?>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<b>�S��</b>�@�����[�e�B���O�́A����`���݂܂ł̋L�^�Ȃ̂Ŋ��Ԏw��͊֌W����܂���B
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǎ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǐ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���Ϗ���</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�X�R�A</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���[�e�B���O</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�P��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�Q��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�R��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�S��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�A�Η�</b></td>
</tr>
<?php
for($i = 0; $i < count($performance_data[0]);)
{
	$rentai_rate = 0;
	if($p_num <= $performance_data[0][$i+1])
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �΋ǎ�
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �΋ǐ�
		$i++;
		if($performance_data[0][$i] <= 2)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // ���Ϗ���
		}
		elseif($performance_data[0][$i] >= 3)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // ���Ϗ���
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // ���Ϗ���
		}
		$i++;
		if($performance_data[0][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // �X�R�A
		}
		else if($performance_data[0][$i] >= 100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // �X�R�A
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �X�R�A
		}
		$i++;
		if($performance_data[0][$i] <= 999)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // ���[�e�B���O
		}
		else if($performance_data[0][$i] >= 1700)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // ���[�e�B���O
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // ���[�e�B���O
		}
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �P��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �Q��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �R��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �S��
		if(($performance_data[0][$i-3]+$performance_data[0][$i-2]) > 0)
		{
			$rentai_rate = round(($performance_data[0][$i-3]+$performance_data[0][$i-2])/$performance_data[0][$i-7]*100,1);
		}
		if($rentai_rate >= 50)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$rentai_rate."</font></td></tr>\r\n"; // �A�Η�
		}
		else if($rentai_rate < 20)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$rentai_rate."</font></td></tr>\r\n"; // �A�Η�
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$rentai_rate."</td></tr>\r\n"; // �A�Η�
		}
		$i++;
	}
	// �K�萔����
	else
	{
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �΋ǎ�
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �΋ǐ�
		$i++;
		if($performance_data[0][$i] <= 2)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // ���Ϗ���
		}
		elseif($performance_data[0][$i] >= 3)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // ���Ϗ���
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // ���Ϗ���
		}
		$i++;
		if($performance_data[0][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // �X�R�A
		}
		else if($performance_data[0][$i] >= 100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // �X�R�A
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �X�R�A
		}
		$i++;
			if($performance_data[0][$i] <= 999)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[0][$i]."</font></td>"; // ���[�e�B���O
		}
		else if($performance_data[0][$i] >= 1700)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[0][$i]."</font></td>"; // ���[�e�B���O
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // ���[�e�B���O
		}
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �P��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �Q��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �R��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[0][$i]."</td>"; // �S��
		if(($performance_data[0][$i-3]+$performance_data[0][$i-2]) > 0)
		{
			$rentai_rate = round(($performance_data[0][$i-3]+$performance_data[0][$i-2])/$performance_data[0][$i-7]*100,1);
		}
		if($rentai_rate >= 50)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$rentai_rate."</font></td></tr>\r\n"; // �A�Η�
		}
		else if($rentai_rate < 20)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$rentai_rate."</font></td></tr>\r\n"; // �A�Η�
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$rentai_rate."</td></tr>\r\n"; // �A�Η�
		}
		$i++;
	}
}
?>
</table></Td></Tr></Table>
<br><br>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<b>�r���[�O</b>
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǎ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǐ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���Ϗ���</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�X�R�A</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���[�e�B���O</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�P��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�Q��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�R��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�S��</b></td>
</tr>
<?php
for($i = 0; $i < count($performance_data[1]);)
{
	if($p_num <= $performance_data[1][$i+1])
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �΋ǎ�
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �΋ǐ�
		$i++;
		if($performance_data[1][$i] <= 2)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // ���Ϗ���
		}
		elseif($performance_data[1][$i] >= 3)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // ���Ϗ���
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // ���Ϗ���
		}
		$i++;
		if($performance_data[1][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // �X�R�A
		}
		else if($performance_data[1][$i] >= 100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // �X�R�A
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �X�R�A
		}
		$i++;
			if($performance_data[1][$i] <= 999)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // ���[�e�B���O
		}
		else if($performance_data[1][$i] >= 1700)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // ���[�e�B���O
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // ���[�e�B���O
		}
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �P��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �Q��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �R��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[1][$i]."</td></tr>\r\n"; // �S��
		$i++;
	}
	else
	{
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �΋ǎ�
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �΋ǐ�
		$i++;
		if($performance_data[1][$i] <= 2)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // ���Ϗ���
		}
		elseif($performance_data[1][$i] >= 3)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // ���Ϗ���
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // ���Ϗ���
		}
		$i++;
		if($performance_data[1][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // �X�R�A
		}
		else if($performance_data[1][$i] >= 100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // �X�R�A
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �X�R�A
		}
		$i++;
			if($performance_data[1][$i] <= 999)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[1][$i]."</font></td>"; // ���[�e�B���O
		}
		else if($performance_data[1][$i] >= 1700)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[1][$i]."</font></td>"; // ���[�e�B���O
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // ���[�e�B���O
		}
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �P��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �Q��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td>"; // �R��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[1][$i]."</td></tr>\r\n"; // �S��
		$i++;
	}
}
?>
</table></Td></Tr></Table>
<br><br>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<b>�`���[�O</b>
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǎ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǐ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���Ϗ���</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�X�R�A</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���[�e�B���O</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�P��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�Q��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�R��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�S��</b></td>
</tr>
<?php
for($i = 0; $i < count($performance_data[2]);)
{
	if($p_num <= $performance_data[2][$i+1])
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �΋ǎ�
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �΋ǐ�
		$i++;
		if($performance_data[2][$i] <= 2)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // ���Ϗ���
		}
		elseif($performance_data[2][$i] >= 3)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // ���Ϗ���
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // ���Ϗ���
		}
		$i++;
		if($performance_data[2][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // �X�R�A
		}
		else if($performance_data[2][$i] >= 100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // �X�R�A
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �X�R�A
		}
		$i++;
			if($performance_data[2][$i] <= 999)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // ���[�e�B���O
		}
		else if($performance_data[2][$i] >= 1700)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // ���[�e�B���O
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // ���[�e�B���O
		}
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �P��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �Q��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �R��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[2][$i]."</td></tr>\r\n"; // �S��
		$i++;
	}
	else
	{
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �΋ǎ�
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �΋ǐ�
		$i++;
		if($performance_data[2][$i] <= 2)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // ���Ϗ���
		}
		elseif($performance_data[2][$i] >= 3)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // ���Ϗ���
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // ���Ϗ���
		}
		$i++;
		if($performance_data[2][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // �X�R�A
		}
		else if($performance_data[2][$i] >= 100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // �X�R�A
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �X�R�A
		}
		$i++;
			if($performance_data[2][$i] <= 999)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[2][$i]."</font></td>"; // ���[�e�B���O
		}
		else if($performance_data[2][$i] >= 1700)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[2][$i]."</font></td>"; // ���[�e�B���O
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // ���[�e�B���O
		}
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �P��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �Q��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td>"; // �R��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[2][$i]."</td></tr>\r\n"; // �S��
		$i++;
	}
}
?>
</table></Td></Tr></Table>
<br><br>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<b>�a���[�O</b>
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǎ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǐ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���Ϗ���</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�X�R�A</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���[�e�B���O</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�P��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�Q��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�R��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�S��</b></td>
</tr>
<?php
for($i = 0; $i < count($performance_data[3]);)
{
	if($p_num <= $performance_data[3][$i+1])
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �΋ǎ�
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �΋ǐ�
		$i++;
		if($performance_data[3][$i] <= 2)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // ���Ϗ���
		}
		elseif($performance_data[3][$i] >= 3)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // ���Ϗ���
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // ���Ϗ���
		}
		$i++;
		if($performance_data[3][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // �X�R�A
		}
		else if($performance_data[3][$i] >= 100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // �X�R�A
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �X�R�A
		}
		$i++;
			if($performance_data[3][$i] <= 999)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // ���[�e�B���O
		}
		else if($performance_data[3][$i] >= 1700)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // ���[�e�B���O
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // ���[�e�B���O
		}
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �P��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �Q��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �R��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[3][$i]."</td></tr>\r\n"; // �S��
		$i++;
	}
	else
	{
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �΋ǎ�
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �΋ǐ�
		$i++;
		if($performance_data[3][$i] <= 2)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // ���Ϗ���
		}
		elseif($performance_data[3][$i] >= 3)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // ���Ϗ���
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // ���Ϗ���
		}
		$i++;
		if($performance_data[3][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // �X�R�A
		}
		else if($performance_data[3][$i] >= 100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // �X�R�A
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �X�R�A
		}
		$i++;
			if($performance_data[3][$i] <= 999)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[3][$i]."</font></td>"; // ���[�e�B���O
		}
		else if($performance_data[3][$i] >= 1700)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[3][$i]."</font></td>"; // ���[�e�B���O
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // ���[�e�B���O
		}
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �P��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �Q��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td>"; // �R��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[3][$i]."</td></tr>\r\n"; // �S��
		$i++;
	}
}
?>
</table></Td></Tr></Table>
<br><br>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<b>�b���[�O</b>
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǎ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǐ�</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���Ϗ���</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�X�R�A</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���[�e�B���O</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�P��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�Q��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�R��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�S��</b></td>
</tr>
<?php
for($i = 0; $i < count($performance_data[4]);)
{
	if($p_num <= $performance_data[4][$i+1])
	{
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �΋ǎ�
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �΋ǐ�
		$i++;
		if($performance_data[4][$i] <= 2)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // ���Ϗ���
		}
		elseif($performance_data[4][$i] >= 3)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // ���Ϗ���
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // ���Ϗ���
		}
		$i++;
		if($performance_data[4][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // �X�R�A
		}
		else if($performance_data[4][$i] >= 100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // �X�R�A
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �X�R�A
		}
		$i++;
			if($performance_data[4][$i] <= 999)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // ���[�e�B���O
		}
		else if($performance_data[4][$i] >= 1700)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // ���[�e�B���O
		}
		else
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // ���[�e�B���O
		}
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �P��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �Q��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �R��
		$i++;
		echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>".$performance_data[4][$i]."</td></tr>\r\n"; // �S��
		$i++;
	}
	else
	{
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �΋ǎ�
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �΋ǐ�
		$i++;
		if($performance_data[4][$i] <= 2)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // ���Ϗ���
		}
		elseif($performance_data[4][$i] >= 3)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // ���Ϗ���
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // ���Ϗ���
		}
		$i++;
		if($performance_data[4][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // �X�R�A
		}
		else if($performance_data[4][$i] >= 100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // �X�R�A
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �X�R�A
		}
		$i++;
			if($performance_data[4][$i] <= 999)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>".$performance_data[4][$i]."</font></td>"; // ���[�e�B���O
		}
		else if($performance_data[4][$i] >= 1700)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=blue>".$performance_data[4][$i]."</font></td>"; // ���[�e�B���O
		}
		else
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // ���[�e�B���O
		}
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �P��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �Q��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td>"; // �R��
		$i++;
		echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>".$performance_data[4][$i]."</td></tr>\r\n"; // �S��
		$i++;
	}
}
?>
</table></Td></Tr></Table>
</form>
<?php } ?>
</div></body></html>
