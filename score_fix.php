<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� score_fix.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/04/13 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_1playername = @$_POST['player1'];            # �P�ʃv���[���[��
$p_2playername = @$_POST['player2'];            # �Q�ʃv���[���[��
$p_3playername = @$_POST['player3'];            # �R�ʃv���[���[��
$p_4playername = @$_POST['player4'];            # �S�ʃv���[���[��
$p_1score = @$_POST['score1'];                  # �P�ʃX�R�A
$p_2score = @$_POST['score2'];                  # �Q�ʃX�R�A
$p_3score = @$_POST['score3'];                  # �R�ʃX�R�A
$p_4score = @$_POST['score4'];                  # �S�ʃX�R�A
$p_guest1 =  @$_POST['guest1'];                 # �Q�X�g�P
$p_guest2 =  @$_POST['guest2'];                 # �Q�X�g�Q
$p_guest3 =  @$_POST['guest3'];                 # �Q�X�g�R
$p_guest4 =  @$_POST['guest4'];                 # �Q�X�g�S
$p_kbn = @$_POST['kbn2'];                       # �v���C�敪(0:������A1:������)
$p_firstscore = @$_POST['1stscore'];            # ���_(�X�R�A�v�Z���Ɏg�p)
$p_resscore = @$_POST['resscore'];              # �Ԃ��_(�X�R�A�v�Z���Ɏg�p)

$p_uid2 = @$_POST['uid'];                   # ���O�C�����[�U�h�c
$p_pwd2 = @$_POST['pass'];                  # ���O�C���p�X���[�h
$p_kbn2 = @$_POST['kbn'];                   # ���O�C���敪
$p_syuseino = @$_POST['syuseino'];          # �C���Ώ�
$g1_check = "";   # �Q�X�g�P�`�F�b�N�Ȃ����
$g2_check = "";   # �Q�X�g�Q�`�F�b�N�Ȃ����
$g3_check = "";   # �Q�X�g�R�`�F�b�N�Ȃ����
$g4_check = "";   # �Q�X�g�S�`�F�b�N�Ȃ����
$p1_useflg = "";  # �ΐ�҂P�I���\���
$p2_useflg = "";  # �ΐ�҂Q�I���\���
$p3_useflg = "";  # �ΐ�҂R�I���\���
$p4_useflg = "";  # �ΐ�҂S�I���\���
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
<script type="text/javascript">
<!--
function checks(guest_player) {
// �P��
if(guest_player ==1)
{
	if(document.myFORM.guest1.checked)
	{
		document.myFORM.player1.disabled = true;
	} else {
		document.myFORM.player1.disabled = false;
	}
}
//�Q��
if(guest_player ==2)
{
	if(document.myFORM.guest2.checked)
	{
		document.myFORM.player2.disabled = true;
	} else {
		document.myFORM.player2.disabled = false;
	}
}
//�R��
if(guest_player ==3)
{
	if(document.myFORM.guest3.checked)
	{
		document.myFORM.player3.disabled = true;
	} else {
		document.myFORM.player3.disabled = false;
	}
}
//�S��
if(guest_player ==4)
{
	if(document.myFORM.guest4.checked)
	{
		document.myFORM.player4.disabled = true;
	} else {
		document.myFORM.player4.disabled = false;
	}
}
}
//-->
</script>
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
	LOG_INSERT($p_uid2,"�΋Ǐ��C���i��ʑ΋ǁj",1,err0015."���[�U�h�c�F".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='���O�C�����' onClick=location.href='./login.php'>";
}
else
{
	###### �{�^���������ꂽ�ꍇ�̏��� ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{
		// �C������
		if(@$_POST["syusei"])
		{
			$tonpu_select = "";
			$hancyan_select = "";
			$fscore_20000 = "";
			$fscore_25000 = "";
			$fscore_26000 = "";
			$fscore_27000 = "";
			$fscore_30000 = "";
			$rscore_25000 = "";
			$rscore_30000 = "";
			$rscore_35000 = "";
			$fix_list = SCORE_FIX_SELECT($p_syuseino);
			$p_1score = $fix_list[5];  // �P�ʃX�R�A
			$p_2score = $fix_list[8];  // �Q�ʃX�R�A
			$p_3score = $fix_list[11];  // �R�ʃX�R�A
			$p_4score = $fix_list[14]; // �S�ʃX�R�A
			// �΋ǎ��
			if($fix_list[0] == 0 || $fix_list[0] == 100)
			{
				$tonpu_select = "selected";
			} else {
				$hancyan_select = "selected";
			}
			// �z�����_
			if($fix_list[1] == 20000)
			{
				$fscore_20000 = "selected";
			}
			else if($fix_list[1] == 25000){
				$fscore_25000 = "selected";
			}
			else if($fix_list[1] == 26000){
				$fscore_26000 = "selected";
			}
			else if($fix_list[1] == 27000){
				$fscore_27000 = "selected";
			}
			else if($fix_list[1] == 30000){
				$fscore_30000 = "selected";
			}
			// ���_
			if($fix_list[2] == 25000)
			{
				$rscore_25000 = "selected";
			}
			else if($fix_list[2] == 30000)
			{
				$rscore_30000 = "selected";
			}
			else
			{
				$rscore_35000 = "selected";
			}
			// �΋ǋ敪�i�P�ʁj
			if($fix_list[0] == 100 || $fix_list[0] == 101)
			{
				$g1_check ="checked";
				$p1_useflg = "disabled";
			}
			// �΋ǋ敪�i�Q�ʁj
			if($fix_list[6] == 100 || $fix_list[6] == 101)
			{
				$g2_check ="checked";
				$p2_useflg = "disabled";
			}
			// �΋ǋ敪�i�R�ʁj
			if($fix_list[9] == 100 || $fix_list[9] == 101)
			{
				$g3_check ="checked";
				$p3_useflg = "disabled";
			}
			// �΋ǋ敪�i�S�ʁj
			if($fix_list[12] == 100 || $fix_list[12] == 101)
			{
				$g4_check ="checked";
				$p4_useflg = "disabled";
			}
			// ���[�U���擾�i�v���_�E���\���p�j
			$user = USERNAME2($fix_list[4],$fix_list[7],$fix_list[10],$fix_list[13]);
			LOG_INSERT($p_uid2,"�΋Ǐ��C���i��ʑ΋ǁj",0,"�X�R�A�C���Ώ�No�F".$p_syuseino);
		}
		// �X�V����
		if(@$_POST["regit"])
		{
			$err = "";
			$tonpu_select = "";
			$hancyan_select = "";
			$fscore_20000 = "";
			$fscore_25000 = "";
			$fscore_26000 = "";
			$fscore_27000 = "";
			$fscore_30000 = "";
			$rscore_25000 = "";
			$rscore_30000 = "";
			$rscore_35000 = "";
			// �΋ǎ��
			if($p_kbn == 0)
			{
				$tonpu_select = "selected";
			} else {
				$hancyan_select = "selected";
			}
			// �z�����_
			if($p_firstscore == 20000)
			{
				$fscore_20000 = "selected";
			}
			else if($p_firstscore == 25000){
				$fscore_25000 = "selected";
			}
			else if($p_firstscore == 26000){
				$fscore_26000 = "selected";
			}
			else if($p_firstscore == 27000){
				$fscore_27000 = "selected";
			}
			else {
				$fscore_30000 = "selected";
			}
			// ���_
			if($p_resscore == 25000)
			{
				$rscore_25000 = "selected";
			}
			else if($p_resscore == 30000){
				$rscore_30000 = "selected";
			}
			else {
				$rscore_35000 = "selected";
			}
			$t_kbn1 = $p_kbn;
			$t_kbn2 = $p_kbn;
			$t_kbn3 = $p_kbn;
			$t_kbn4 = $p_kbn;
			// �P�ʂ̐l���Q�X�g�̏ꍇ
			if($p_guest1 == 1)
			{
				$t_kbn1 = $t_kbn1 + 100; // �敪+100���Q�X�g�敪�Ƃ���
				$p_1playername = "�Q�X�g�P";
				$g1_check = "checked";
				$p1_useflg = "disabled";
			}
			// �Q�ʂ̐l���Q�X�g�̏ꍇ
			if($p_guest2 == 1)
			{
				$t_kbn2 = $t_kbn2 + 100; // �敪+100���Q�X�g�敪�Ƃ���
				$p_2playername = "�Q�X�g�Q";
				$g2_check = "checked";
				$p2_useflg = "disabled";
			}
			// �R�ʂ̐l���Q�X�g�̏ꍇ
			if($p_guest3 == 1)
			{
				$t_kbn3 = $t_kbn3 + 100; // �敪+100���Q�X�g�敪�Ƃ���
				$p_3playername = "�Q�X�g�R";
				$g3_check = "checked";
				$p3_useflg = "disabled";
			}
			// �S�ʂ̐l���Q�X�g�̏ꍇ
			if($p_guest4 == 1)
			{
				$t_kbn4 = $t_kbn4 + 100; // �敪+100���Q�X�g�敪�Ƃ���
				$p_4playername = "�Q�X�g�S";
				$g4_check = "checked";
				$p4_useflg = "disabled";
			}
			// ���[�U���擾�i�v���_�E���\���p�j
			$user = USERNAME2($p_1playername,$p_2playername,$p_3playername,$p_4playername);
			// �K�{���̓`�F�b�N
			$err .= moji_check_ad($p_1score,1,7,"�P�ʃX�R�A");
			$err .= moji_check_ad($p_2score,1,7,"�Q�ʃX�R�A");
			$err .= moji_check_ad($p_3score,1,7,"�R�ʃX�R�A");
			$err .= moji_check_ad($p_4score,1,7,"�S�ʃX�R�A");
			// �͈̓`�F�b�N
			$err .= num_check($p_1score,-300000,400000,"�P�ʃX�R�A");
			$err .= num_check($p_2score,-300000,400000,"�Q�ʃX�R�A");
			$err .= num_check($p_3score,-300000,400000,"�R�ʃX�R�A");
			$err .= num_check($p_4score,-300000,400000,"�S�ʃX�R�A");
			// 1,2,3,4�ʃX�R�A���̓`�F�b�N(1�� < 2�ʓ��̓_���ɂȂ��Ă��Ȃ����`�F�b�N)
			if($p_1score < $p_2score || $p_2score < $p_3score || $p_3score < $p_4score)
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0005."<BR>";
			}
			// �S�l�̍��v�X�R�A�����_�~�S���傫���ꍇ���G���[�Ƃ���
			if(($p_1score + $p_2score + $p_3score + $p_4score) > ($p_firstscore * 4))
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0006."<BR>";
			}
			// �P�O�O�_�P�ʂœ��͂���Ă��Ȃ��ꍇ���G���[�Ƃ���
			if($p_1score % 100 != 0 || $p_2score % 100 != 0 || $p_3score % 100 != 0 || $p_4score % 100 != 0)
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0007."<BR>";
			}
			// �ΐ�ҏd���`�F�b�N
			if($p_1playername == $p_2playername || $p_1playername == $p_3playername || $p_1playername == $p_4playername ||
					$p_2playername == $p_3playername || $p_2playername == $p_4playername || $p_3playername == $p_4playername)
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0009."<BR>";
			}
			// �z�����_�����_�̏ꍇ���G���[�Ƃ���
			if($p_firstscore > $p_resscore)
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0023."<BR>";
			}
			// �G���[�łȂ��ꍇ
			if($err == "")
			{
				// �S�ʃX�R�A�Z�o
				$score_p4 = reckoning(($p_4score - $p_resscore) / 1000);
				// �R�ʃX�R�A�Z�o
				$score_p3 = reckoning(($p_3score - $p_resscore) / 1000);
				// �Q�ʃX�R�A�Z�o
				$score_p2 = reckoning(($p_2score - $p_resscore) / 1000);
				// �P�ʃX�R�A�Z�o
				$score_p1 = ($score_p4 + $score_p3 + $score_p2) * (-1);
				// �z�����_�l���Z�o
				$score_list = firstscore_num($p_1score,$p_2score,$p_3score,$p_4score,$p_firstscore);
				// �P�ʂ̃��[�O�X�R�A�Z�o
				$lpscore_1p = 10;
				$mfcscore_1p = 4;
				if($score_list[0])
				{
					$lpscore_1p = $lpscore_1p +3 +(($p_1score - $p_firstscore) / 1000);
				}
				else
				{
					$lpscore_1p = $lpscore_1p -3 +(($p_1score - $p_firstscore) / 1000);
				}

				$lpscore_1p = $lpscore_1p +hosei($score_list[0],$score_list[4]);
				$mfcscore_1p = $mfcscore_1p + (($p_1score - $p_firstscore) / 10000);
				// �Q�ʂ̃��[�O�X�R�A�Z�o
				$lpscore_2p = 5;
				$mfcscore_2p = 3;
				if($score_list[1])
				{
					$lpscore_2p = $lpscore_2p +3 +(($p_2score - $p_firstscore) / 1000);
				}
				else
				{
					$lpscore_2p = $lpscore_2p -3 +(($p_2score - $p_firstscore) / 1000);
				}
				$lpscore_2p = $lpscore_2p +hosei($score_list[1],$score_list[4]);
				$mfcscore_2p = $mfcscore_2p + (($p_2score - $p_firstscore) / 10000);
				// �R�ʂ̃��[�O�X�R�A�Z�o
				$lpscore_3p = -5;
				$mfcscore_3p = 2;
				if($score_list[2])
				{
					$lpscore_3p = $lpscore_3p +3 +(($p_3score - $p_firstscore) / 1000);
				}
				else
				{
					$lpscore_3p = $lpscore_3p -3 +(($p_3score - $p_firstscore) / 1000);
				}
				$lpscore_3p = $lpscore_3p +hosei($score_list[2],$score_list[4]);
				$mfcscore_3p = $mfcscore_3p + (($p_3score - $p_firstscore) / 10000);
				// �S�ʂ̃��[�O�X�R�A�Z�o
				$lpscore_4p = -10;
				$mfcscore_4p = 1;
				if($score_list[3])
				{
					$lpscore_4p = $lpscore_4p +3 +(($p_4score - $p_firstscore) / 1000);
				}
				else
				{
					$lpscore_4p = $lpscore_4p -3 +(($p_4score - $p_firstscore) / 1000);
				}
				$lpscore_4p = $lpscore_4p +hosei($score_list[3],$score_list[4]);
				$mfcscore_4p = $mfcscore_4p + (($p_4score - $p_firstscore) / 10000);
				// �݌v�X�R�A�X�V
				SCORE_UPDATE($p_syuseino,$t_kbn1,1,$p_1playername,$p_1score,$score_p1,$lpscore_1p,$lpscore_1p*10,$mfcscore_1p,$p_firstscore,$p_resscore);
				SCORE_UPDATE($p_syuseino,$t_kbn2,2,$p_2playername,$p_2score,$score_p2,$lpscore_2p,$lpscore_2p*10,$mfcscore_2p,$p_firstscore,$p_resscore);
				SCORE_UPDATE($p_syuseino,$t_kbn3,3,$p_3playername,$p_3score,$score_p3,$lpscore_3p,$lpscore_3p*10,$mfcscore_3p,$p_firstscore,$p_resscore);
				SCORE_UPDATE($p_syuseino,$t_kbn4,4,$p_4playername,$p_4score,$score_p4,$lpscore_4p,$lpscore_4p*10,$mfcscore_4p,$p_firstscore,$p_resscore);
				// ���[�g�v�Z�t���O�X�V
				RATECALC_FLG(0);
				if($t_kbn1 == 0 || $t_kbn1 == 100) {$syubetsu="������";}
				else{ $syubetsu="������";}
				LOG_INSERT($p_uid2,"�΋Ǐ��C���i��ʑ΋ǁj",2,"�݌vNo�F".$p_syuseino."�@�΋ǎ�ʁF".$syubetsu);
				LOG_INSERT($p_uid2,"�΋Ǐ��C���i��ʑ΋ǁj",2,"�P�ʁF".$p_1playername."�@".mb_convert_kana($p_1score,A,"shift_jis")."�_�@�Q�ʁF".$p_2playername."�@".mb_convert_kana($p_2score,A,"shift_jis")."�_�@�R�ʁF".$p_3playername."�@".mb_convert_kana($p_3score,A,"shift_jis")."�_�@�S�ʁF".$p_4playername."�@".mb_convert_kana($p_4score,A,"shift_jis")."�_");

			}
			###### �G���[���b�Z�[�W�\�� ######
			 echo "<table>".$err."</table>";
			 if($err != ""){
			 }
			 ###### �f�[�^�o�^���b�Z�[�W�\�� ######
			 else
			 {
			 	$info = msg0003;
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
?>

<div id="container">
<?php // �p�X���[�h�F�ؐ����̏ꍇ�́A�t�H�[���\��
if($admin_chk || ($user_chk != -1 && $user_chk != 0))
{
	$fix_data = SCORE_FIX_DATA($p_uid2,$p_kbn2,$p_syuseino);
?>
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
<img src="./img/money.png" align="middle">
&nbsp; <b><?php if ($p_kbn2 == 1 ){ echo "�����΋Ǐ��C�� "; } else { echo "�S�΋Ǐ��C��"; } ?></b></td>
</tr></table></Td></Tr></Table>
<?php
if($fix_data == "")
{
	echo"<img src='./img/error.png' align='middle'> ".err0016;
} else { ?>
<form action="#" method="post" name="myFORM2" enctype="multipart/form-data">
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type=hidden name='kbn' value=<?php echo $p_kbn2 ?>>
<input type="hidden" name="mode" value="regist">
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�C���΋�<?php echo hissu ?></th>
  <td>
  <select name="syuseino">
  <?php echo SCORE_FIX_DATA($p_uid2,$p_kbn2,$p_syuseino); ?>
  </select>
    </td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=syusei style="font-size:15pt;background:#FF66AA" value="�C��">
</tr>
</tr></table>
</Td></Tr></Table></form>

<?php
// �C������
if(@$_POST["syusei"]|| (@$_POST["regit"] && $err !=""))
{ ?>
<!-- ########## �w�b�_�\���� ########## -->
<form action="#" method="post" name="myFORM" enctype="multipart/form-data">
<BR>
<center>
<img src='./img/download.png' align='center'>
</center>
<BR>
<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<input type=hidden name='kbn' value=<?php echo $p_kbn2 ?>>
<input type="hidden" name=syuseino value=<?php echo $p_syuseino ?>>
<Table cellspacing="0" cellpadding="0" width="100%" border="0">
<Tr><Td bgcolor="<?php echo line_color ?>">
<table cellspacing="1" cellpadding="5" width="100%" border="0">
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�P��<?php echo hissu ?><img src='./img/gold.png'></th>
  <td>
  <select name="player1" <?php echo $p1_useflg ?>>
  <?php echo $user[0] ?>
  </select>
  �ŏI�_���F<input type="text" name="score1" size="10" value="<?php echo $p_1score  ?>"  maxlength="7" style="ime-mode:disabled">
  <input type=checkbox name=guest1 value="1"  <?php echo $g1_check ?> onClick="checks(1)">�Q�X�g�Ƃ��Ďg�p����
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�Q��<?php echo hissu ?></th>
  <td>
  <select name="player2" <?php echo $p2_useflg ?>>
  <?php echo $user[1] ?>
  </select>
  �ŏI�_���F<input type="text" name="score2" size="10" value="<?php echo $p_2score  ?>"  maxlength="7" style="ime-mode:disabled">
  <input type=checkbox name=guest2 value="1"  <?php echo $g2_check ?> onClick="checks(2)">�Q�X�g�Ƃ��Ďg�p����
  </td>

</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�R��<?php echo hissu ?></th>
  <td>
  <select name="player3" <?php echo $p3_useflg ?>>
  <?php echo $user[2] ?>
  </select>
  �ŏI�_���F<input type="text" name="score3" size="10" value="<?php echo $p_3score  ?>"  maxlength="7" style="ime-mode:disabled">
  <input type=checkbox name=guest3 value="1"  <?php echo $g3_check ?> onClick="checks(3)">�Q�X�g�Ƃ��Ďg�p����
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�S��<?php echo hissu ?></th>
  <td>
  <select name="player4" <?php echo $p4_useflg ?>>
  <?php echo $user[3] ?>
  </select>
  �ŏI�_���F<input type="text" name="score4" size="10" value="<?php echo $p_4score  ?>"  maxlength="7" style="ime-mode:disabled">
  <input type=checkbox name=guest4 value="1"  <?php echo $g4_check ?> onClick="checks(4)">�Q�X�g�Ƃ��Ďg�p����
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�΋ǎ��<?php echo hissu ?></th>
  <td>
  <select name="kbn2">
  <option value=0 <?php echo $tonpu_select ?>>������
  <option value=1 <?php echo $hancyan_select ?>>������
  </select>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�z�����_<?php echo hissu ?></th>
  <td>
  <select name="1stscore">
  <option value=20000 <?php echo $fscore_20000 ?>>�Q�O�O�O�O
  <option value=25000 <?php echo $fscore_25000 ?>>�Q�T�O�O�O
  <option value=26000 <?php echo $fscore_26000 ?>>�Q�U�O�O�O
  <option value=27000 <?php echo $fscore_27000 ?>>�Q�V�O�O�O
  <option value=30000 <?php echo $fscore_30000 ?>>�R�O�O�O�O
  </select>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">���_<?php echo hissu ?></th>
  <td>
  <select name="resscore">
  <option value=25000 <?php echo $rscore_25000 ?>>�Q�T�O�O�O
  <option value=30000 <?php echo $rscore_30000 ?>>�R�O�O�O�O
  <option value=35000 <?php echo $rscore_35000 ?>>�R�T�O�O�O
  </select>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="�΋ǌ��ʍX�V">�@���΋Ǔ��́A�X�V����܂���B
</tr>
</tr></table>
</Td></Tr></Table>
<?php } } } } ?></form>
</div></body></html>
