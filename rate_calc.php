<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� rate_calc.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/08/04 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_uid2 = @$_POST['uid'];                   # ���O�C�����[�U�h�c
$p_pwd2 = @$_POST['pass'];                  # ���O�C���p�X���[�h
$p_confirm = @$_POST['CONFIRM'];            # �m�F���b�Z�[�W
?>
<!-- �w�b�_�� -->
<html lang="ja">
<head>
<script type="text/javascript">
<!--
 function sendconfirm(){

     if(confirm("�ŐV���[�g���v�Z���܂����H \n �y�d�v�z���O�C�����[�U�����Ȃ����ēx���m�F�������B\n ���O�C�����[�U������ꍇ�A�����I�ɐؒf����܂��B\n �P�ǂ��ƂɌv�Z���邽�ߏ����Ɏ��Ԃ�������ꍇ������܂��B\n ��������������܂ő��̑���i�u���E�U�A��ʑJ�ړ��j�� \n �s��Ȃ��ł��������B\n ��L�����ł���������������ꍇ�́A�n�j�������Ă��������B"))
     {
     	document.forms[1].CONFIRM.value=1;//�n�j�̏ꍇ
     	return true;
     }
     else
     {
         document.forms[1].CONFIRM.value=0;//�L�����Z���̏ꍇ
         return false;
     }

 }
//-->
 </script>
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
	if($p_confirm == 1)
	{
		###### �ŐV���[�g�v�Z�{�^���������ꂽ�ꍇ�̏��� ######
		if (@$_SERVER["REQUEST_METHOD"]=="POST")
		{ //�|�X�g�Ŕ�΂���Ă�����ȉ�������

			if(@$_POST["regit"])
			{
				$info = "";# �f�[�^�o�^���b�Z�[�W�̈揉����
				$score_update_cnt = 0; // ���[�g�X�V����
				// �e�����o�[��DELFLG��1(�L��)��99(�����e��)�ɕύX(���O�C���j�~�P)
				USERFLG_UPDATE(99,1);
				// ONETIME_PWD�e�[�u�����f�[�^�폜(�������[�U�̂ݗL��)(���O�C���j�~�Q)
				ONETIMEPWD_DELETE_ALMOST();
				// �v���[���[�̃��[�g������������
				RATING_ALLUPDATE(1500);
				// MAXRUI_NO�擾
				$max_rui_no = MAX_RUINO();
				// ��1��`���݂܂ł̃��[�g���v�Z
				for($i=1; $i<=$max_rui_no ;$i++)
				{
					// �X�R�A�o�^�Ώۂ̃����o�[�l���Z�o�i�Q�X�g�ȊO�j
					$score_member_cnt = UNSCORE_RESULT($i);
					// �X�R�A�o�^�Ώۂ̃����o�[�l���i�Q�X�g�ȊO�j��2�ȏ�̏ꍇ�A�ȉ��̏������s��
					if($score_member_cnt >= 2)
					{
						// �P�`�S�ʂ܂ł̃����o�[�̎������Z�o�i�Q�X�g�ȊO�j=>�Q�X�g�̏ꍇ��null��ݒ�
						$p1_name=null; // �P�ʃv���[���[��������
						$p2_name=null; // �Q�ʃv���[���[��������
						$p3_name=null; // �R�ʃv���[���[��������
						$p4_name=null; // �S�ʃv���[���[��������
						$play_info=null; // �v���C���i�ΐ�ҁA���ʁj
						$game_num =array(); // �P�ʁ`�S�ʂ̃v���[���[�̌��݂̎�����
						$now_rate =array(); // �P�ʁ`�S�ʂ̃v���[���[�̌��݂̂q
						$other_avg_rate =array(); // �P�ʁ`�S�ʂ̃v���[���[�̑��Ƃ̂q
						$play_info = UNSCORE_SELECTS($i);
						$score_member_cnt--;
						if($play_info[1] == 0 || $play_info[1] == 1) {
							$p1_name = $play_info[0];
						}
						if($play_info[3] == 0 || $play_info[3] == 1) {
							$p2_name = $play_info[2];
						}
						if($play_info[5] == 0 || $play_info[5] == 1) {
							$p3_name = $play_info[4];
						}
						if($play_info[7] == 0 || $play_info[7] == 1) {
							$p4_name = $play_info[6];
						}
						array_push($game_num, GAME_NUM($p1_name,$i),GAME_NUM($p2_name,$i),GAME_NUM($p3_name,$i),GAME_NUM($p4_name,$i));
						// �P�`�S�ʂ܂ł̊e�v���[���[�̌��݂̂q���擾
						array_push($now_rate, NOW_RATING($p1_name),NOW_RATING($p2_name),NOW_RATING($p3_name),NOW_RATING($p4_name));
						// �e���ʂ̃����o�[�ɑ΂��Ă̑��ƕ��ςq���Z�o =>�Q�X�g�̏ꍇ��null��ݒ�
						array_push($other_avg_rate, avg_rating($p1_name,($now_rate[1]+$now_rate[2]+$now_rate[3])/$score_member_cnt),avg_rating($p2_name,($now_rate[0]+$now_rate[2]+$now_rate[3])/$score_member_cnt),avg_rating($p3_name,($now_rate[0]+$now_rate[1]+$now_rate[3])/$score_member_cnt),avg_rating($p4_name,($now_rate[0]+$now_rate[1]+$now_rate[2])/$score_member_cnt));
						// �P�ʂ̍ŐV�q���擾
						if($other_avg_rate[0] != null)
						{
							if($game_num[0] > 400){ $game_num[0] = 400; } // ��������400�����Ă���ꍇ�͋����I��400�ɂ���
							$new_rate1 = floor($now_rate[0]+(1-$game_num[0]*0.002)*(kihon_sc(1)+($other_avg_rate[0]-$now_rate[0])/40));
							//LOG_INSERT($p_uid2,"���[�g�X�V",2,$p1_name."floor(".$now_rate[0]."+(".kihon_sc(1)."-((".$other_avg_rate[0]."-".$now_rate[0].")/300))*(1+((400-".$game_num[0].")/100))))");
							//LOG_INSERT($p_uid2,"���[�g�X�V",2,$score_member_cnt);


						}
						// �Q�ʂ̍ŐV�q���擾
						if($other_avg_rate[1] != null)
						{
							if($game_num[1] > 400){	$game_num[1] = 400;	} // ��������400�����Ă���ꍇ�͋����I��400�ɂ���
							$new_rate2 = floor($now_rate[1]+(1-$game_num[1]*0.002)*(kihon_sc(2)+($other_avg_rate[1]-$now_rate[1])/40));
							//LOG_INSERT($p_uid2,"���[�g�X�V",2,$p2_name."floor(".$now_rate[1]."+(".kihon_sc(2)."-((".$other_avg_rate[1]."-".$now_rate[1].")/300))*(1+((400-".$game_num[1].")/100))))");
							//LOG_INSERT($p_uid2,"���[�g�X�V",2,$score_member_cnt);
						}
						// �R�ʂ̍ŐV�q���擾
						if($other_avg_rate[2] != null)
						{
							if($game_num[2] > 400){	$game_num[2] = 400;	} // ��������400�����Ă���ꍇ�͋����I��400�ɂ���
							$new_rate3 = floor($now_rate[2]+(1-$game_num[2]*0.002)*(kihon_sc(3)+($other_avg_rate[2]-$now_rate[2])/40));
						}
						// �S�ʂ̍ŐV�q���擾
						if($other_avg_rate[3] != null)
						{
							if($game_num[3] > 400){	$game_num[3] = 400;	} // ��������400�����Ă���ꍇ�͋����I��400�ɂ���
							$new_rate4 = floor($now_rate[3]+(1-$game_num[3]*0.002)*(kihon_sc(4)+($other_avg_rate[3]-$now_rate[3])/40));
						}
						// �P�ʂ̍ŐV�q���X�V
						if($other_avg_rate[0] != null)
						{
							RATE_UPD($p1_name,$new_rate1);
							$score_update_cnt++;
							LOG_INSERT($p_uid2,"���[�g�X�V",2,$p1_name."�@���݂̎������F".mb_convert_kana($game_num[0],A,"shift_jis")."�@�X�V�O���[�g�F".mb_convert_kana($now_rate[0],A,"shift_jis")."�@�X�V�ヌ�[�g�F".mb_convert_kana($new_rate1,A,"shift_jis")."�@�i��".mb_convert_kana($i,A,"shift_jis")."��j");
						}
						// �Q�ʂ̍ŐV�q���X�V
						if($other_avg_rate[1] != null)
						{
							RATE_UPD($p2_name,$new_rate2);
							$score_update_cnt++;
							LOG_INSERT($p_uid2,"���[�g�X�V",2,$p2_name."�@���݂̎������F".mb_convert_kana($game_num[1],A,"shift_jis")."�@�X�V�O���[�g�F".mb_convert_kana($now_rate[1],A,"shift_jis")."�@�X�V�ヌ�[�g�F".mb_convert_kana($new_rate2,A,"shift_jis")."�@�i��".mb_convert_kana($i,A,"shift_jis")."��j");
						}
						// �R�ʂ̍ŐV�q���X�V
						if($other_avg_rate[2] != null)
						{
							RATE_UPD($p3_name,$new_rate3);
							$score_update_cnt++;
							LOG_INSERT($p_uid2,"���[�g�X�V",2,$p3_name."�@���݂̎������F".mb_convert_kana($game_num[2],A,"shift_jis")."�@�X�V�O���[�g�F".mb_convert_kana($now_rate[2],A,"shift_jis")."�@�X�V�ヌ�[�g�F".mb_convert_kana($new_rate3,A,"shift_jis")."�@�i��".mb_convert_kana($i,A,"shift_jis")."��j");
						}
						// �S�ʂ̍ŐV�q���X�V
						if($other_avg_rate[3] != null)
						{
							RATE_UPD($p4_name,$new_rate4);
							$score_update_cnt++;
							LOG_INSERT($p_uid2,"���[�g�X�V",2,$p4_name."�@���݂̎������F".mb_convert_kana($game_num[3],A,"shift_jis")."�@�X�V�O���[�g�F".mb_convert_kana($now_rate[3],A,"shift_jis")."�@�X�V�ヌ�[�g�F".mb_convert_kana($new_rate4,A,"shift_jis")."�@�i��".mb_convert_kana($i,A,"shift_jis")."��j");
						}
					}
				}
				// �X�R�A�X�V���������O�ɏo��
				LOG_INSERT($p_uid2,"���[�g�X�V",2,"���[�g�X�V����".mb_convert_kana($score_update_cnt,A,"shift_jis")."��");
				// ���[�g�v�Z�t���O�X�V
				RATECALC_FLG(1);
				// �e�����o�[��DELFLG��99(�����e��)��1(�L��)�ɕύX
				USERFLG_UPDATE(1,99);
				###### �f�[�^�o�^���b�Z�[�W�\�� ######
			 	$info = msg0006;
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
// �ŐV���[�g�v�Z���b�Z�[�W���ݒ肳��Ă��Ȃ��ꍇ�A�t�H�[����ʂ�\������
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
<img src="./img/chart.gif" align="middle">
&nbsp; <b>�ŐV���[�g�X�V</b></td>
</tr></table></Td></Tr></Table>
<form action="#" method="post" name="myFORM" enctype="multipart/form-data" onSubmit="return sendconfirm()">
<input type="hidden" name="CONFIRM" value="" />
	<input type=hidden name='uid' value=<?php echo $p_uid2 ?>>
	<input type=hidden name='pass' value=<?php echo $p_pwd2 ?>>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="�ŐV���[�g�X�V"> &nbsp;&nbsp;
</tr><br><br><br>
</form>
<?php } ?>
</div></body></html>
<?php } ?>