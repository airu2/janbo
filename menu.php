<?php
#��������������������������������������������������
#�� MahjongscoreProject
#�� menu.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/02/19 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_pwd = @$_POST['pass'];              # �p�X���[�h
$p_uid = @$_POST['uid'];               # ���[�U�h�c
$p_job = @$_POST['job'];               # ��ʑJ�ړ��e
?>
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=shift_jis">
<meta http-equiv="content-style-type" content="text/css">
<meta name="viewport" content="width=device-width,user-scalable=yes,initial-scale=1.0,maximum-scale=3.0" />
<link rel="STYLESHEET" type="text/css" href="./css/bbspatio.css">
<style type="text/css">
<!--
body,td,th { font-size:13px;	font-family:"MS UI Gothic", Osaka, "�l�r �o�S�V�b�N"; }
-->
</style>
<title>���j���[</title></head>
<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" >
<link rel="icon" href="./favicon.ico" >
<body background="<?php echo background ?>" bgcolor="<?php echo bgcolor ?>" text="#000000" link="#0000FF" vlink="#0000FF" alink="#0000FF">
<?php
###### �ԐM����{�^���������ꂽ�ꍇ�̏��� ######
if($p_job != "back")
{
	// �Ǘ��҃p�X���[�h�`�F�b�N
	$admin_chk = adminpass_check($p_pwd,$p_uid);
	// ���[�U�`�F�b�N(DB)
	$user_chk = USERID_CNT($p_uid,$p_pwd);
	if($admin_chk || $user_chk != -1)
	{
		// �����^�C���p�X���[�h�f�[�^�폜
		ONETIMEPWD_DELETE($p_uid);
		// �����^�C���p�X���[�h�f�[�^�o�^
		ONETIMEPWD_INSERT($p_uid,$user_chk);
	}
}
else
{
	// �������[�U�̏ꍇ�́A�������[�U�̐ݒ���ēx�s��
	if($p_uid == adminusr)
	{
		$admin_chk = true;
		$user_chk = -1;
	}
}
// �����^�C���p�X���[�h�f�[�^�擾
$usr_data = ONETIMEPWD_SELECT($p_uid);
if (@$_SERVER["REQUEST_METHOD"]=="POST")
{ //�|�X�g�Ŕ�΂���Ă�����ȉ�������

	// �p�X���[�h�F�؎��s�̏ꍇ�́A�G���[�Ƃ���
	if(!$admin_chk && $user_chk == -1)
	{
		LOG_INSERT($p_uid,"���j���[",1,err0001."�@���̓��[�U�h�c�F".$p_uid."�@���̓p�X���[�h�F".$p_pwd);
		echo"<img src='./img/error.png' align='middle'> ".err0001;
		echo "<Input type=button value='���O�C�����' onClick=location.href='./login.php'>";
	}
 	else {
		// ���[�g�v�Z���j���[�\���i�������[�U�̂݁j
		if($admin_chk)
		{
			echo "<div align='center'>";
			echo "�������e��I�����Ă��������B";
			echo "<p>";
			echo "<Table border='0' cellspacing='0' cellpadding='0' width='320'>";
			echo "<Tr><Td bgcolor='".line_color."'>";
			echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
			echo "<tr bgcolor='".line_color."'>";
			echo "<td bgcolor='".in_bgcolor."' align='center'>";
			echo "�I��";
			echo "</td>";
			echo "<td bgcolor='".in_bgcolor."' width='100%'>";
			echo "&nbsp; �������[�U�p";
			echo "</td>";
			echo "</tr>";
			// ���j���[�P�Q�F���[�g�v�Z
			echo "<form action='./rate_calc.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu0' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; ���[�g�v�Z";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			echo "</table>";
			echo "</Td></Tr></Table>";
			echo "</div>";
		}
		// �Ǘ��҃p�X���[�h�`�F�b�N�Ő������ꍇ�A���j���[��\������
		if(($user_chk == 0 && $usr_data[2] == 0) || $admin_chk)
		{
			echo "<div align='center'>";
			if(!$admin_chk)
			{
				echo "�������e��I�����Ă��������B�@(".version.")";
			}
			echo "<p>";
			echo "<Table border='0' cellspacing='0' cellpadding='0' width='320'>";
			echo "<Tr><Td bgcolor='".line_color."'>";
			echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
			echo "<tr bgcolor='".line_color."'>";
			echo "<td bgcolor='".in_bgcolor."' align='center'>";
			echo "�I��";
			echo "</td>";
			echo "<td bgcolor='".in_bgcolor."' width='100%'>";
			echo "&nbsp; �Ǘ��p";
			echo "</td>";
			echo "</tr>";
			// ���j���[�O�F���O
			echo "<form action='./log.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu0' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; ���샍�O����";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// ���j���[�P�F���[�U�o�^
			echo "<form action='./user.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu1' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; ���[�U�o�^";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// ���j���[�Q�F�p�X���[�h�ύX
			echo "<form action='./pass_change.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu2' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; �p�X���[�h�ύX";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// ���j���[�R�F���[�O�����N�ݒ�
			echo "<form action='./ranksetting.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu3' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; ���[�O�����N�ݒ�";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// ���j���[�S�F���쐬
			echo "<form action='./tournament.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu4' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; ���쐬";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// ���j���[�P�Q�F���яƉ�i��ʑ΋ǁj
			echo "<form action='./league_all_admin.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu12' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; ��ʑ΋ǐ��яƉ�i�Ǘ��p�j";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			echo "</table>";
			echo "</Td></Tr></Table>";
			echo "</div>";
		}
		if($user_chk == 0 || $user_chk == 1)
		{
			echo "<div align='center'>";
			if($user_chk == 1)
			{
				echo "�������e��I�����Ă��������B�@(".version.")";
			}
			echo "<p>";
			echo "<Table border='0' cellspacing='0' cellpadding='0' width='320'>";
			echo "<Tr><Td bgcolor='".line_color."'>";
			echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
			echo "<tr bgcolor='".line_color."'>";
			echo "<td bgcolor='".in_bgcolor."' align='center'>";
			echo "�I��";
			echo "</td>";
			echo "<td bgcolor='".in_bgcolor."' width='100%'>";
			echo "&nbsp; ��ʃX�R�A�֘A";
			echo "</td>";
			echo "</tr>";
			// ���j���[�T�F�΋Ǐ����́i��ʑ΋ǁj
			echo "<form action='./score.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu5' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; �΋Ǐ����́i��ʑ΋ǁj";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='kbn' value=$usr_data[2]>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// ���j���[�X�F���яƉ�i��ʑ΋ǁj
			echo "<form action='./league_all.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu9' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; ��ʑ΋ǐ��яƉ�i��ʗp�j";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// ���j���[�P�P�F���ڑΌ�����
			echo "<form action='./directconfrontation.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu11' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; ���ڑΌ����ʏƉ�i��ʑ΋ǁj";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "<input type=hidden name='u_kbn' value=$usr_data[2]>";
			echo "</form>";
			echo "</table>";
			echo "</Td></Tr></Table>";
			echo "</div>";
			echo "<div align='center'>";
			echo "<p>";
			echo "<Table border='0' cellspacing='0' cellpadding='0' width='320'>";
			echo "<Tr><Td bgcolor='".line_color."'>";
			echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
			echo "<tr bgcolor='".line_color."'>";
			echo "<td bgcolor='".in_bgcolor."' align='center'>";
			echo "�I��";
			echo "</td>";
			echo "<td bgcolor='".in_bgcolor."' width='100%'>";
			echo "&nbsp; ���X�R�A�֘A";
			echo "</td>";
			echo "</tr>";
			// ���j���[�U�F�΋Ǐ����́i���΋ǁj
			echo "<form action='./tournament_score.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu6' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; �΋Ǐ����́i���΋ǁj";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='kbn' value=$usr_data[2]>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			// ���j���[�P�O�F���яƉ�i���΋ǁj
			echo "<form action='./tournament_search.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu10' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; ���΋ǐ��яƉ�";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			echo "</table>";
			echo "</Td></Tr></Table>";
			echo "</div>";
			echo "<div align='center'>";
			echo "<p>";
			echo "<Table border='0' cellspacing='0' cellpadding='0' width='320'>";
			echo "<Tr><Td bgcolor='".line_color."'>";
			echo "<table border='0' cellspacing='1' cellpadding='5' width='100%'>";
			echo "<tr bgcolor='".line_color."'>";
			echo "<td bgcolor='".in_bgcolor."' align='center'>";
			echo "�I��";
			echo "</td>";
			echo "<td bgcolor='".in_bgcolor."' width='100%'>";
			echo "&nbsp; ���̑�";
			echo "</td>";
			echo "</tr>";
			// �v���o�^���j���[�\������ꍇ
			if(haihu_touroku_flg == 1)
			{
				// ���j���[�V�F�v��������
				echo "<form action='./haihu.php' method='post'>";
				echo "<tr bgcolor='#FFFFFF'>";
				echo "<td bgcolor='#FFFFFF' align='center'>";
				echo "<input type='submit' name='menu7' value='�I��'>";
				echo "</td>";
				echo "<td bgcolor='#FFFFFF' width='100%'>";
				echo "&nbsp; �v��������";
				echo "</td>";
				echo "</tr>";
				echo "<input type=hidden name='kbn' value=$usr_data[2]>";
				echo "<input type=hidden name='uid' value=$p_uid>";
				echo "<input type=hidden name='pass' value=$usr_data[0]>";
				echo "</form>";
			}
			// ���j���[�W�F�������X�R�A�C��
			echo "<form action='./score_fix.php' method='post'>";
			echo "<tr bgcolor='#FFFFFF'>";
			echo "<td bgcolor='#FFFFFF' align='center'>";
			echo "<input type='submit' name='menu8' value='�I��'>";
			echo "</td>";
			echo "<td bgcolor='#FFFFFF' width='100%'>";
			echo "&nbsp; �΋Ǐ��C���i��ʑ΋ǂ̂݁j";
			echo "</td>";
			echo "</tr>";
			echo "<input type=hidden name='kbn' value=$usr_data[2]>";
			echo "<input type=hidden name='uid' value=$p_uid>";
			echo "<input type=hidden name='pass' value=$usr_data[0]>";
			echo "</form>";
			echo "</table>";
			echo "</Td></Tr></Table>";
			echo "</div>";
		}
 	}

}
if(@$_POST['login'] == "���O�C��" && $user_chk != -1)
{
	// ���O�C����������
	LOGIN_DATE($p_uid);
	$uname = MYUSERNAME($p_uid);
	LOG_INSERT($p_uid,"���j���[",0,$uname."�l�����O�C�����܂����B");
}
?>
</body>