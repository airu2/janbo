<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� league_all.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/11/03 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_ymd_month = @$_POST['ymd_month'];        # �N�����i�������j
$p_ymd_setu = @$_POST['ymd_setu'];          # �N�����i�ߌ����j
$search_value = @$_POST['search_value'];    # �����敪
$p_kbn = @$_POST['kbn'];                    # �W�v�敪
$p_num = @$_POST['num'];                    # �����K�萔
$p_uid2 = @$_POST['uid'];                   # ���O�C�����[�U�h�c
$p_pwd2 = @$_POST['pass'];                  # ���O�C���p�X���[�h
$p0_select="";                              # �W�v�敪�i�����j�Z���N�g
$p1_select="";                              # �W�v�敪�i�����j�Z���N�g
$p2_select="";                              # �W�v�敪�i�S�āj�Z���N�g
if($p_num == "")
{
	$p_num = 0;
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
<script type="text/javascript">
<!--
function checks(flg) {
if(flg == 1)
{
	document.myFORM.ymd_setu.disabled = true;
} else {
	document.myFORM.ymd_setu.disabled = false;
}


if(flg == 2)
{
	document.myFORM.ymd_month.disabled = true;
} else {
	document.myFORM.ymd_month.disabled = false;
}

}
//-->
</script>
<?php
// �Ǘ��҃p�X���[�h�`�F�b�N
$admin_chk = adminpass_check($p_pwd2,$p_uid2);
// ���[�U�`�F�b�N(DB)
$user_chk = USERID_CNT3($p_uid2,$p_pwd2);
// �o�^����Ă���΋ǔN�����̍ŏ��ƍő���擾
$score_ymd = UNSCORE_YMD_SELECTS();
// �v���_�E�����j���[�쐬(�N���A�߁j
$ymd_search = ymd_select($score_ymd[0],$score_ymd[1],"");
// ���[�U�`�F�b�N��NG�̏ꍇ�AID,PASSWORD�̃G���A�͋󔒂Ƃ���(��ʌ��J�p)
if(!$admin_chk && !$user_chk)
{
	$p_uid2 = "";
	$p_pwd2 = "";
}
###### �{�^���������ꂽ�ꍇ�̏��� ######
if (@$_SERVER["REQUEST_METHOD"]=="POST")
{
	// ��������
	if(@$_POST["regit"])
	{
		if($p_kbn == 0) { $p0_select="selected"; }
		if($p_kbn == 1) { $p1_select="selected"; }
		else { $p2_select="selected"; }
		$err = "";
		$err .= moji_check_ad($p_num,1,3,"�����K�萔");                     // �����K�萔�̕������E���̓`�F�b�N
		// �G���[�łȂ��ꍇ
		if($err == "")
		{
			// �Q�X�g�����[�U�̏ꍇ�A���[�UID�������s����(IP�A�h���X�𐔒l���j
			// �Q�X�g���[�U�̏ꍇ�A���[�U�����݂��Ȃ����ߕ����A�N�Z�X���A�������Ă��܂�
			// �����ɕs���������\�������邽�߉����s����
			if($p_uid2 == "")
			{
				// IP�A�h���X���擾���ĕϐ��ɃZ�b�g����
				$ipAddress = $_SERVER["REMOTE_ADDR"];
				// IP�A�h���X�𐔒l�Ƃ��Ď擾����ꍇ
				$ipLong = ip2long($ipAddress);
				// �����[�UID���s
				$p_uid2 = "[".$ipLong."]";
			}
			// ��������I�������ꍇ
			if($search_value == 1)
			{
				$p_from_ymd = substr($p_ymd_month,  0, 10);
				$p_to_ymd   = substr($p_ymd_month, 10, 10);
			}
			// ����ȊO�̏ꍇ
			else
			{
				$p_from_ymd = substr($p_ymd_setu,  0, 10);
				$p_to_ymd   = substr($p_ymd_setu, 10, 10);
			}
			// wk_performance�f�[�^�폜
			WK_PERFORMANCE_DELETE($p_uid2);
			// wk_performance�Ƀf�[�^����
			SCORE_CNT($p_from_ymd,$p_to_ymd,$p_kbn,$p_uid2,$p_num);
			// �f�[�^�擾
			$performance_data = PERFORMANCE_SELECT($p_uid2);
			// �Q�X�g�f�[�^�擾
			$guest_score = GUEST_SCORE($p_from_ymd,$p_to_ymd,($p_kbn + 100));
			// �f�[�^�쐬
			create_file($performance_data[0],$performance_data[1],$performance_data[2],$performance_data[3],$performance_data[4],$p_uid2,$p_num,$p_kbn,$p_from_ymd,$p_to_ymd,1);
			if($p_kbn == 0) {$syubetsu="������";}
			else if($p_kbn == 1){ $syubetsu="������";}
			else { $syubetsu="�S��";}
			if($p_uid2 !="")
			{
				LOG_INSERT($p_uid2,"��ʑ΋ǐ��яƉ�i��ʗp�j",0,"�e�q�n�l�F".$p_from_ymd."�@�s�n�F".$p_to_ymd."�@�΋ǎ�ʁF".$syubetsu."�@�����K�萔�F".$p_num);
			}
		}
		###### �G���[���b�Z�[�W�\�� ######
		if($err != "")
		{
			echo $err;
		}
		// ������������POST��������p��
		if($search_value == 1)
		{
			$g1_check = "checked";
			$g2_check = "";
			$ymd_month_useflg = "";
			$ymd_setu_useflg = "disabled";
			// �v���_�E�����j���[�쐬(�N���j
			$ymd_search = ymd_select($score_ymd[0],$score_ymd[1],$p_ymd_month);
		}
		else
		{
			$g1_check = "";
			$g2_check = "checked";
			$ymd_month_useflg = "disabled";
			$ymd_setu_useflg = "";
			// �v���_�E�����j���[�쐬(�N���j
			$ymd_search = ymd_select($score_ymd[0],$score_ymd[1],$p_ymd_setu);
		}
	}
	else
	{
		// �����������������Ƃ���
		$g1_check ="checked";
	}
}
else
{
	// �����������������Ƃ���
	$g1_check ="checked";
}
?>

<title><?php echo boardtitle ?></title></head>
<body background="<?php echo background ?>" bgcolor="<?php echo bgcolor ?>" text="#000000" link="#0000FF" vlink="#800080" alink="#DD0000">
<div id="container">
<?php // �p�X���[�h�F�ؐ����̏ꍇ�́A�t�H�[���\��
if($admin_chk || ($user_chk != -1 && $user_chk != 0) && @$_POST['menu9'] != "")
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
<?php } ?>
<Table border="0" cellspacing="0" cellpadding="0" width="100%">
<Tr bgcolor="<?php echo line_color ?>"><Td bgcolor="<?php echo line_color ?>"  class="td0">
<table border="0" cellspacing="1" cellpadding="5" width="100%">
<tr bgcolor="<?php echo in_bgcolor ?>"><td bgcolor="<?php echo in_bgcolor ?>" nowrap width="100%" class="td0">
<img src="./img/gurafu.png" align="middle">
&nbsp; <b>��ʑ΋ǐ��яƉ�i��ʗp�j</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<input type=hidden name='menu9' value="menu9">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�΋Ǌ���<?php echo hissu ?></th>
  <td>
  <select name="ymd_month" <?php echo $ymd_month_useflg ?>>
  <?php echo $ymd_search[0] ?>
  </select>
  <input type=radio name=search_value value="1"  <?php echo $g1_check ?> onClick="checks(1)">�N������(1��������)
  <br>
  <select name="ymd_setu" <?php echo $ymd_setu_useflg ?>>
  <?php echo $ymd_search[1] ?>
  </select>
  <input type=radio name=search_value value="2"  <?php echo $g2_check ?> onClick="checks(2)">�ߌ���(3��������)
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
�W�v���ԁF<?php if(@$_POST["regit"] && $err =="") { echo $p_from_ymd ?>�@�`�@<?php echo $p_to_ymd; } ?><br>
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
<?php }
// �ߌ�����s��(�������)
if (!@$_POST["regit"])
{
	echo "<script language=javascript>checks(1)</script>";
}
?>
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
		if($performance_data[0][$i] < 0 && $performance_data[0][$i] > -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'>��".($performance_data[0][$i])*(-1)."</td>"; // �X�R�A
		}
		else if($performance_data[0][$i] <= -100)
		{
			echo "<td bgcolor='#FFFFFF' align=center nowrap class='num'><font color=red>��".($performance_data[0][$i])*(-1)."</font></td>"; // �X�R�A
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
		if($performance_data[0][$i] < 0 && $performance_data[0][$i] > -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'>��".($performance_data[0][$i])*(-1)."</td>"; // �X�R�A
		}
		else if($performance_data[0][$i] <= -100)
		{
			echo "<td bgcolor='#AA44AA' align=center nowrap class='num'><font color=red>��".($performance_data[0][$i])*(-1)."</font></td>"; // �X�R�A
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
</form>
</div></body></html>
