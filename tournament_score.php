<?php
#��������������������������������������������������
#��MahjongscoreProject
#�� tournament_score.php
#�� Copyright (c) radipo
#�� http://www.radipo.com
#�� �����ŗ�����
#�� 2012/05/04 �V�K�쐬 by airu
#��������������������������������������������������
require ('setting.php');
####### �ꎞ�i�[�̈� #######
$p_1playername = @$_POST['player1'];            # �P�ʃv���[���[��
$p_2playername = @$_POST['player2'];            # �Q�ʃv���[���[��
$p_3playername = @$_POST['player3'];            # �R�ʃv���[���[��
$p_4playername = @$_POST['player4'];            # �S�ʃv���[���[��
$p_1score = @$_POST['score1st'];                # �P�ʃX�R�A
$p_2score = @$_POST['score2st'];                # �Q�ʃX�R�A
$p_3score = @$_POST['score3st'];                # �R�ʃX�R�A
$p_4score = @$_POST['score4st'];                # �S�ʃX�R�A
$p_kbn = @$_POST['kbn2'];                       # �v���C�敪(0:������A1:������)
$p_firstscore = @$_POST['1stscore'];            # ���_(�X�R�A�v�Z���Ɏg�p)
$p_resscore = @$_POST['resscore'];              # �Ԃ��_(�X�R�A�v�Z���Ɏg�p)
$p_comment = @$_POST['comment'];                # �R�����g

$p_uid2 = @$_POST['uid'];                   # ���O�C�����[�U�h�c
$p_pwd2 = @$_POST['pass'];                  # ���O�C���p�X���[�h
$p_kbn2 = @$_POST['kbn'];                   # ���O�C���敪
$p_id = @$_POST['tournament'];              # ���h�c
$p_sid = @$_POST['s_tournament'];           # ���h�c�i�����p�j
$p_kai = @$_POST['kai'];                    # ��
$p_hanei = @$_POST['hanei'];                # ��ʑ΋ǂɔ��f

$fscore_25000 = "selected";
$rscore_30000 = "selected";
$hancyan_select = "selected";
// ���[�U���擾�i�v���_�E���\���p�j
$user = USERNAME2($p_1playername,$p_2playername,$p_3playername,$p_4playername);
// �����擾�i�v���_�E���\���p�j
$tournament = TOURNAMENT_SELECT2($p_id,1);
// �����\���p
$s_tournament = TOURNAMENT_SELECT2($p_sid,0);
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
	LOG_INSERT($p_uid2,"�΋Ǐ����́i���΋ǁj",1,err0015."���[�U�h�c�F".$p_uid2);
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
			// ���[�U���擾�i�v���_�E���\���p�j
			$user = USERNAME2($p_1playername,$p_2playername,$p_3playername,$p_4playername);
			// �����擾�i�v���_�E���\���p�j
			$tournament = TOURNAMENT_SELECT2($p_id,1);
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
			$err .= moji_check_ad($p_comment,0,50,"�R�����g");       // �R�����g�̕������E���̓`�F�b�N
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
			// ��`�F�b�N
			if($p_kai > 999 || $p_kai < 1)
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0019."<BR>";
			}
			// �����œ���ɓ����v���[���[�������ꍇ���G���[�Ƃ���
			if(!TOURNAMENT_CHECK($p_id,$p_kai,$p_1playername,$p_2playername,$p_3playername,$p_4playername))
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0020."<BR>";
			}
			// ���I���`�F�b�N�i��1���쐬����Ă��Ȃ��Ƃ��󂪑I���ł��Ă��܂��̂Ń`�F�b�N�j
			if($p_id == "")
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0022."<BR>";
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
				// TOURNAMENT_MAXRUI_NO�擾
				$to_max_rui_no = TOURNAMENT_MAX_RUINO();
				// ���X�R�A�o�^
				TOURNAMENT_SCORE_INSERT($to_max_rui_no,$p_id,$p_kai,$p_kbn,1,$p_1playername,$p_1score,$score_p1,$lpscore_1p,$lpscore_1p*10,$mfcscore_1p,$p_uid2,$p_firstscore,$p_resscore,$p_comment);
				TOURNAMENT_SCORE_INSERT($to_max_rui_no,$p_id,$p_kai,$p_kbn,2,$p_2playername,$p_2score,$score_p2,$lpscore_2p,$lpscore_2p*10,$mfcscore_2p,$p_uid2,$p_firstscore,$p_resscore,$p_comment);
				TOURNAMENT_SCORE_INSERT($to_max_rui_no,$p_id,$p_kai,$p_kbn,3,$p_3playername,$p_3score,$score_p3,$lpscore_3p,$lpscore_3p*10,$mfcscore_3p,$p_uid2,$p_firstscore,$p_resscore,$p_comment);
				TOURNAMENT_SCORE_INSERT($to_max_rui_no,$p_id,$p_kai,$p_kbn,4,$p_4playername,$p_4score,$score_p4,$lpscore_4p,$lpscore_4p*10,$mfcscore_4p,$p_uid2,$p_firstscore,$p_resscore,$p_comment);
				if($p_kbn == 0) {$syubetsu="������";}
				else{ $syubetsu="������";}
				LOG_INSERT($p_uid2,"�΋Ǐ����́i���΋ǁj",2,"�݌vNo�F".$to_max_rui_no."�@�΋ǎ�ʁF".$syubetsu."�@���h�c�F".$p_id."�@��F".mb_convert_kana($p_kai,N,"shift_jis")."���");
				LOG_INSERT($p_uid2,"�΋Ǐ����́i���΋ǁj",2,"�P�ʁF".$p_1playername."�@".mb_convert_kana($p_1score,A,"shift_jis")."�_�@�Q�ʁF".$p_2playername."�@".mb_convert_kana($p_2score,A,"shift_jis")."�_�@�R�ʁF".$p_3playername."�@".mb_convert_kana($p_3score,A,"shift_jis")."�_�@�S�ʁF".$p_4playername."�@".mb_convert_kana($p_4score,A,"shift_jis")."�_");
				$score_list2 = TOURNAMENT_SCORE_SELECT($p_uid2,$p_kbn2,$p_id);
				// ��ʑ΋ǂɔ��f����ꍇ�́AUN_SCORE�ɓo�^
				if($p_hanei == 1)
				{
					// MAXRUI_NO�擾
					$max_rui_no = MAX_RUINO();
					// ����MAX_NO�擾
					$max_no = MAX_NO_MONTH();
					// X�N�O�̃X�R�A�f�[�^�폜
					UN_SCORE_DELETE();
					// �݌v�X�R�A�o�^
					SCORE_INSERT($max_rui_no,$max_no,$p_kbn,1,$p_1playername,$p_1score,$score_p1,$lpscore_1p,$lpscore_1p*10,$mfcscore_1p,$p_uid2,$p_firstscore,$p_resscore);
					SCORE_INSERT($max_rui_no,$max_no,$p_kbn,2,$p_2playername,$p_2score,$score_p2,$lpscore_2p,$lpscore_2p*10,$mfcscore_2p,$p_uid2,$p_firstscore,$p_resscore);
					SCORE_INSERT($max_rui_no,$max_no,$p_kbn,3,$p_3playername,$p_3score,$score_p3,$lpscore_3p,$lpscore_3p*10,$mfcscore_3p,$p_uid2,$p_firstscore,$p_resscore);
					SCORE_INSERT($max_rui_no,$max_no,$p_kbn,4,$p_4playername,$p_4score,$score_p4,$lpscore_4p,$lpscore_4p*10,$mfcscore_4p,$p_uid2,$p_firstscore,$p_resscore);
					LOG_INSERT($p_uid2,"�΋Ǐ����́i��ʑ΋ǁj",2,"�݌vNo�F".$max_rui_no."�@�΋ǎ�ʁF".$syubetsu."�@���h�c�F".$p_id."�@��F".mb_convert_kana($p_kai,N,"shift_jis")."���");
					LOG_INSERT($p_uid2,"�΋Ǐ����́i��ʑ΋ǁj",2,"�P�ʁF".$p_1playername."�@".mb_convert_kana($p_1score,A,"shift_jis")."�_�@�Q�ʁF".$p_2playername."�@".mb_convert_kana($p_2score,A,"shift_jis")."�_�@�R�ʁF".$p_3playername."�@".mb_convert_kana($p_3score,A,"shift_jis")."�_�@�S�ʁF".$p_4playername."�@".mb_convert_kana($p_4score,A,"shift_jis")."�_");
					// �����[�e�B���O�Ή��� start
					// �X�R�A�o�^�Ώۂ̃����o�[�l���Z�o�i�Q�X�g�ȊO�j
					$score_member_cnt = UNSCORE_RESULT($max_rui_no);
					// �X�R�A�o�^�Ώۂ̃����o�[�l���i�Q�X�g�ȊO�j��2�ȏ�̏ꍇ�A�ȉ��̏������s��
					if($score_member_cnt >= 2)
					{
						// �P�`�S�ʂ܂ł̃����o�[�̎������Z�o�i�Q�X�g�ȊO�j=>�Q�X�g�̏ꍇ��null��ݒ�
						$p1_name=null; // �P�ʃv���[���[��������
						$p2_name=null; // �Q�ʃv���[���[��������
						$p3_name=null; // �R�ʃv���[���[��������
						$p4_name=null; // �S�ʃv���[���[��������
						$game_num =array(); // �P�ʁ`�S�ʂ̃v���[���[�̌��݂̎�����
						$now_rate =array(); // �P�ʁ`�S�ʂ̃v���[���[�̌��݂̂q
						$other_avg_rate =array(); // �P�ʁ`�S�ʂ̃v���[���[�̑��Ƃ̂q
						if($t_kbn1 == 0 || $t_kbn1 == 1) {
							$p1_name = $p_1playername;
						}
						if($t_kbn2 == 0 || $t_kbn2 == 1) {
							$p2_name = $p_2playername;
						}
						if($t_kbn3 == 0 || $t_kbn3 == 1) {
							$p3_name = $p_3playername;
						}
						if($t_kbn4 == 0 || $t_kbn4 == 1) {
							$p4_name = $p_4playername;
						}
						array_push($game_num, GAME_NUM($p1_name,$max_rui_no),GAME_NUM($p2_name,$max_rui_no),GAME_NUM($p3_name,$max_rui_no),GAME_NUM($p4_name,$max_rui_no));
						// �P�`�S�ʂ܂ł̊e�v���[���[�̌��݂̂q���擾
						array_push($now_rate, NOW_RATING($p1_name),NOW_RATING($p2_name),NOW_RATING($p3_name),NOW_RATING($p4_name));
						// �e���ʂ̃����o�[�ɑ΂��Ă̑��ƕ��ςq���Z�o =>�Q�X�g�̏ꍇ��null��ݒ�
						array_push($other_avg_rate, avg_rating($p1_name,($now_rate[1]+$now_rate[2]+$now_rate[3])/$score_member_cnt),avg_rating($p2_name,($now_rate[0]+$now_rate[2]+$now_rate[3])/$score_member_cnt),avg_rating($p3_name,($now_rate[0]+$now_rate[1]+$now_rate[3])/$score_member_cnt),avg_rating($p4_name,($now_rate[0]+$now_rate[1]+$now_rate[2])/$score_member_cnt));
						// �P�ʂ̍ŐV�q���擾
						if($other_avg_rate[0] != null)
						{
							if($game_num[0] > 400){	$game_num[0] = 400;	} // ��������400�����Ă���ꍇ�͋����I��400�ɂ���
							$new_rate1 = floor($now_rate[0]+(1-$game_num[0]*0.002)*(kihon_sc(1)+($other_avg_rate[0]-$now_rate[0])/40));
						}
						// �Q�ʂ̍ŐV�q���擾
						if($other_avg_rate[1] != null)
						{
							if($game_num[1] > 400){ $game_num[1] = 400;	} // ��������400�����Ă���ꍇ�͋����I��400�ɂ���
							$new_rate2 = floor($now_rate[1]+(1-$game_num[1]*0.002)*(kihon_sc(2)+($other_avg_rate[1]-$now_rate[1])/40));
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
							LOG_INSERT($p_uid2,"�΋Ǐ����́i��ʑ΋ǁj",2,$p1_name."�@���݂̎������F".mb_convert_kana($game_num[0],A,"shift_jis")."�@�X�V�O���[�g�F".mb_convert_kana($now_rate[0],A,"shift_jis")."�@�X�V�ヌ�[�g�F".mb_convert_kana($new_rate1,A,"shift_jis")."�@�i��".mb_convert_kana($max_rui_no,A,"shift_jis")."��j");
						}
						// �Q�ʂ̍ŐV�q���X�V
						if($other_avg_rate[1] != null)
						{
							RATE_UPD($p2_name,$new_rate2);
							LOG_INSERT($p_uid2,"�΋Ǐ����́i��ʑ΋ǁj",2,$p2_name."�@���݂̎������F".mb_convert_kana($game_num[1],A,"shift_jis")."�@�X�V�O���[�g�F".mb_convert_kana($now_rate[1],A,"shift_jis")."�@�X�V�ヌ�[�g�F".mb_convert_kana($new_rate2,A,"shift_jis")."�@�i��".mb_convert_kana($max_rui_no,A,"shift_jis")."��j");
						}
						// �R�ʂ̍ŐV�q���X�V
						if($other_avg_rate[2] != null)
						{
							RATE_UPD($p3_name,$new_rate3);
							LOG_INSERT($p_uid2,"�΋Ǐ����́i��ʑ΋ǁj",2,$p3_name."�@���݂̎������F".mb_convert_kana($game_num[2],A,"shift_jis")."�@�X�V�O���[�g�F".mb_convert_kana($now_rate[2],A,"shift_jis")."�@�X�V�ヌ�[�g�F".mb_convert_kana($new_rate3,A,"shift_jis")."�@�i��".mb_convert_kana($max_rui_no,A,"shift_jis")."��j");
						}
						// �S�ʂ̍ŐV�q���X�V
						if($other_avg_rate[3] != null)
						{
							RATE_UPD($p4_name,$new_rate4);
							LOG_INSERT($p_uid2,"�΋Ǐ����́i��ʑ΋ǁj",2,$p4_name."�@���݂̎������F".mb_convert_kana($game_num[3],A,"shift_jis")."�@�X�V�O���[�g�F".mb_convert_kana($now_rate[3],A,"shift_jis")."�@�X�V�ヌ�[�g�F".mb_convert_kana($new_rate4,A,"shift_jis")."�@�i��".mb_convert_kana($max_rui_no,A,"shift_jis")."��j");
						}
					}
					// �����[�e�B���O�Ή��� end
				}
			}
			###### �G���[���b�Z�[�W�\�� ######
			if($err != "")
			{
				$score_list2 = TOURNAMENT_SCORE_SELECT($p_uid2,$p_kbn2,$p_id);
				echo $err;

			}
		}
		// �폜����
		else if(@$_POST["del"])
		{
			$scoredel_flg = false;    // �X�R�A�폜�t���O
			// score1�`scoreX�܂ō폜�Ƀ`�F�b�N�������Ă�����̂���������
			for($i=0; $i<=@$_POST['scorenum'] ;$i++)
			{
				// �폜�Ƀ`�F�b�N�������Ă���g���q���폜
				if(@$_POST['score'.$i] != "")
				{
					TOURNAMENT_SCORE_DELETE(@$_POST['score'.$i]);
					$scoredel_flg = true;
					LOG_INSERT($p_uid2,"�΋Ǐ����́i���΋ǁj",2,"�폜�݌vNo�F".@$_POST['score'.$i]);
				}
				$i=$i+3;
			}
			$score_list2 = TOURNAMENT_SCORE_SELECT($p_uid2,$p_kbn2,$p_sid);
			// ���̓`�F�b�N(1���I������Ă��Ȃ��ꍇ�́A�G���[�Ƃ���)
			if(!$scoredel_flg)
			{
				echo"<img src='./img/error.png' align='middle'> ".err0008;
			}
		}
		// ��������
		else if(@$_POST["search"])
		{
			// ���I���`�F�b�N�i��1���쐬����Ă��Ȃ��Ƃ��󂪑I���ł��Ă��܂��̂Ń`�F�b�N�j
			if($p_sid == "")
			{
				$err .= "<img src='./img/error.png' align='middle'>".err0022."<BR>";
			}
			###### �G���[���b�Z�[�W�\�� ######
			if($err != "")
			{
				$score_list2 = TOURNAMENT_SCORE_SELECT($p_uid2,$p_kbn2,$p_id);
				echo $err;

			}
			else
			{
				$score_list2 = TOURNAMENT_SCORE_SELECT($p_uid2,$p_kbn2,$p_sid);
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
&nbsp; <b>�΋Ǐ����́i���΋ǁj</b></td>
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
  <th bgcolor="#FFFFFF" width="100">�P��<?php echo hissu ?><img src='./img/gold.png'></th>
  <td>
  <select name="player1">
  <?php echo $user[0] ?>
  </select>
  �ŏI�_���F<input type="text" name="score1st" size="10" value="<?php echo $p_1score  ?>"  maxlength="7" style="ime-mode:disabled">
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�Q��<?php echo hissu ?></th>
  <td>
  <select name="player2">
  <?php echo $user[1] ?>
  </select>
  �ŏI�_���F<input type="text" name="score2st" size="10" value="<?php echo $p_2score  ?>"  maxlength="7" style="ime-mode:disabled">
  </td>

</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�R��<?php echo hissu ?></th>
  <td>
  <select name="player3">
  <?php echo $user[2] ?>
  </select>
  �ŏI�_���F<input type="text" name="score3st" size="10" value="<?php echo $p_3score  ?>"  maxlength="7" style="ime-mode:disabled">
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">�S��<?php echo hissu ?></th>
  <td>
  <select name="player4">
  <?php echo $user[3] ?>
  </select>
  �ŏI�_���F<input type="text" name="score4st" size="10" value="<?php echo $p_4score  ?>"  maxlength="7" style="ime-mode:disabled">
  </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">���<?php echo hissu ?></th>
  <td>
  <select name="tournament">
  <?php echo $tournament ?>
  </select>
    </td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">��<?php echo hissu ?></th>
  <td>
  ��<input type="text" name="kai" size="5" value="<?php echo $p_kai  ?>"  maxlength="3" style="ime-mode:disabled">���
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
  <th bgcolor="#FFFFFF" width="100">�R�����g</th>
  <td><input type="text" name="comment" size="75" value="<?php echo $p_comment  ?>" maxlength="50"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="120">��ʑ΋ǌ��ʔ��f</th>
  <td>
  <input type=checkbox  name="hanei" value=1>���f����@���I�������ꍇ�ō폜���K�v�ȏꍇ�́A�΋Ǐ����́i��ʑ΋ǁj���폜���Ă��������B
    </td>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="���ʓo�^">
    <input type="submit" name=del   style="font-size:15pt;background:#FF66AA" value="���ʍ폜">&nbsp;&nbsp;
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## �w�b�_�\���� ########## -->
<BR>
�����̑΋ǌ��ʁ�
<select name="s_tournament">
<?php echo $s_tournament ?>
</select>
<input type="submit" name=search style="font-size:15pt;background:#FFFF22" value="���ʌ���">
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>NO</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>���ID</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋ǎ��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>����</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�v���[���[</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�ŏI�_��</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�Q�[���X�R�A</b></td>
<!-- <td bgcolor=<?php echo line_color ?> nowrap><b>���[�O�|�C���g</b></td> -->
<?php if(mfcscore_disp == 1) { ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>�i����y���|�C���g</b></td>
<?php } ?>
<td bgcolor=<?php echo line_color ?> nowrap><b>�R�����g</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�΋Ǔ��t</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>�폜</b></td>
</tr>
<?php
// �������ꂽ���̑΋ǌ��ʂ�\������
echo $score_list2;
?>
</form></table></Td></Tr></Table>
<?php } ?>
</div></body></html>
