<?php
#-------------------------------------------------
#  ���͕����`�F�b�N(���͕���,�ŏ�������,�ő啶����,���̓t�B�[���h)
#-------------------------------------------------
function moji_check($str,$minlength,$maxlength,$field) {
$errmsg = "";
// �ŏ�������,�ő啶�����`�F�b�N
if(mb_strlen($str,"sjis") > $maxlength || mb_strlen($str,"sjis") < $minlength)
{
    $errmsg = "<img src='./img/error.png' align='middle'>".$field."�̓��͕������́A".$minlength."�����ȏ�".$maxlength."�����ȓ��œ��͂��Ă��������B<BR>";
}
return $errmsg;
}
#-------------------------------------------------
#  ���͕����`�F�b�N(���͕���,�ŏ�����,�ő吔��,���̓t�B�[���h)
#-------------------------------------------------
function num_check($str,$minnum,$maxnum,$field) {
$errmsg = "";
// �͈̓`�F�b�N
if($str > $maxnum || $str < $minnum)
{
	$errmsg = "<img src='./img/error.png' align='middle'>".$field."�̓��͔͈͂́A".$minnum."�`".$maxnum."�̊Ԃœ��͂��Ă��������B<BR>";
}
return $errmsg;
}
#-------------------------------------------------
#  ���͕����`�F�b�N(���͕���,�ŏ�������,�ő啶����,���̓t�B�[���h)(�Ǘ��҃��j���[��p)
#-------------------------------------------------
function moji_check_ad($str,$minlength,$maxlength,$field) {
$errmsg = "";
// �ŏ�������,�ő啶�����`�F�b�N
if(mb_strlen($str,"sjis") > $maxlength || mb_strlen($str,"sjis") < $minlength)
{
	$errmsg = "<img src='./img/error.png' align='middle'>".$field."�̓��͕������́A".$minlength."�����ȏ�".$maxlength."�����ȓ��œ��͂��Ă��������B<BR>";
}
return $errmsg;
}
#-------------------------------------------------
#  �z�X�g���擾
#-------------------------------------------------
function hostname() {
$ip = getenv("REMOTE_ADDR");
$host = getenv("REMOTE_HOST");
if ($host == null || $host == $ip)
$host = @gethostbyaddr($ip);
return $host;
}

#-------------------------------------------------
#  ���͉\�����`�F�b�N(���͕���,�p�^�[��,���̓t�B�[���h)
#-------------------------------------------------
function match_check($str,$pettern,$field) {
$errmsg = "";
switch($pettern){
    case 0: // �p�^�[��0:A-Z,a-z,0-9�̂�
    if(preg_match("/[^A-Za-z0-9]/",$str)){
	$errmsg = "<img src='./img/error.png' align='middle'>".$field."�Ɏg�p�ł��镶���́A[A-Z,a-z,0-9]�݂̂ł�<BR>";
    }
    break;
    case 1: // �p�^�[��1:0-9�̂�
    if(preg_match("/[^0-9]/",$str)){
	$errmsg = "<img src='./img/error.png' align='middle'>".$field."�Ɏg�p�ł��镶���́A[0-9]�݂̂ł�<BR>";
    }
    break;
    case 2: // �p�^�[��2:���[���A�h���X�`�F�b�N�p
    if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)) {
    $errmsg = "<img src='./img/error.png' align='middle'>".$field."���s���ł��B<BR>";
    }
    break;
    case 3: // �p�^�[��3:�����`�F�b�N�p
    if (!preg_match('/^(\d\d\d\d)\/(\d\d)\/(\d\d) (\d\d):(\d\d)$/', $str)) {
    $errmsg = "<img src='./img/error.png' align='middle'>".$field."���s���ł��B<BR>";
    }
    break;

}
return $errmsg;
}
#-------------------------------------------------
#  �Ǘ��҃p�X���[�h�`�F�b�N(�`�F�b�N�Ώە�����)
#-------------------------------------------------
function adminpass_check($chk_pwd,$chk_uid) {
# �}�b�`���Ȃ��ꍇ�́A�G���[�Ƃ���
if (adminpwd != $chk_pwd || adminusr != $chk_uid)
{
	$chk_kekka = false;
}
else
{
	$chk_kekka = true;
}
return $chk_kekka;
}
#-------------------------------------------------
#  �z��i�[�i�J���}��؂�̕�����j
#-------------------------------------------------
function add_array($str) {
if($str != "")
{
	$array = array();
	$str_data = explode( ",", $str);
	for( $i = 0; $i < count( $str_data ); $i++ ) {
		array_push($array, trim( $str_data[ $i ]));
	}
}
return $array;
}
#-------------------------------------------------
#  �z�����_�l���Z�o
#-------------------------------------------------
function firstscore_num($p1_score,$p2_score,$p3_score,$p4_score,$first_score) {
$array = array();
$p1_origin_flg = false;
$p2_origin_flg = false;
$p3_origin_flg = false;
$p4_origin_flg = false;
$num = 0;
if($p1_score >= $first_score)
{
	$num++;
	$p1_origin_flg = true;
}
if($p2_score >= $first_score)
{
	$num++;
	$p2_origin_flg = true;
}
if($p3_score >= $first_score)
{
	$num++;
	$p3_origin_flg = true;
}
if($p4_score >= $first_score)
{
	$num++;
	$p4_origin_flg = true;
}
array_push($array, $p1_origin_flg);
array_push($array, $p2_origin_flg);
array_push($array, $p3_origin_flg);
array_push($array, $p4_origin_flg);
array_push($array, $num);
return $array;
}
#-------------------------------------------------
#  �␳�l�ԋp
#-------------------------------------------------
function hosei($flg,$num) {
$hosei = 0;
if($num == 0)
{
	$hosei = -3;
}
elseif($num == 1 && $flg)
{
	$hosei = 6;
}
elseif($num == 1 && !$flg)
{
	$hosei = -3;
}
elseif($num == 3 && $flg)
{
	$hosei = 2;
}
elseif($num == 3 && !$flg)
{
	$hosei = -6;
}
return $hosei;
}
#-------------------------------------------------
#  �W�v�敪�ԋp
#-------------------------------------------------
function sum_kbn($flg) {
$kbn = "";
if($flg == 0)
{
	$kbn = "������";
}
elseif($flg == 1)
{
	$kbn = "������";
}
elseif($flg == 2)
{
	$kbn = "�S��";
}
return $kbn;
}
#-------------------------------------------------
#  �W�v���ʃt�@�C���쐬
#-------------------------------------------------
function create_file($array_all,$array_s,$array_a,$array_b,$array_c,$uid,$p_num,$kbn,$from,$to,$create_kbn) {
$html_file = 'Result_'.$uid.'.html'; // �o��html�t�@�C����
$fp = @fopen(c_filedir.$html_file, 'w');
// �o�͓��e
$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>�W�v���ԁF".$from. "�`".$to."</div>";
$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>�W�v�敪�F".$kbn."�i".sum_kbn($kbn)."�j</div>";
$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>�S��</div>";
$output_moji = $output_moji."<table style='border:solid 1px; border-color:#0000ff'>";
$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."�΋ǎҖ�";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."�΋ǐ�";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."���Ϗ���";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."���_";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."���[�e�B���O";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."1�ʉ�";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."2�ʉ�";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."3�ʉ�";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."4�ʉ�";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
$output_moji = $output_moji."�A�Η�";
$output_moji = $output_moji."</td>";
$output_moji = $output_moji."</tr>";
for($i = 0; $i < count($array_all);)
{
	$rentai_rate = 0;
	$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
	if($p_num <= $array_all[$i+1])
	{
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."</td>"; // �΋ǎ�
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."</td>"; // �΋ǐ�
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."</td>"; // ���Ϗ���
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".score_minus($array_all[$i])."</td>"; // �X�R�A
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."</td>"; // ���[�e�B���O
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."�i".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-4]*100))."%�j</td>"; // �P��
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."�i".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-5]*100))."%�j</td>"; // �Q��
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."�i".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-6]*100))."%�j</td>"; // �R��
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_all[$i]."�i".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-7]*100))."%�j</td>"; // �S��
		// �P�ʂƂQ�ʂ̉񐔂�0���傫���ꍇ(0�̏ꍇ��0�ŏ��Z���邽�߃G���[�΍�j
		if(($array_all[$i-3]+$array_all[$i-2]) > 0)
		{
			$rentai_rate = round(($array_all[$i-3]+$array_all[$i-2])/$array_all[$i-7]*100,1);
		}
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$rentai_rate."%</td></tr>\r\n"; // �A�Η�

		$i++;
	}
	else
	{
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."</td>"; // �΋ǎ�
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."</td>"; // �΋ǐ�
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."</td>"; // ���Ϗ���
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".score_minus($array_all[$i])."</td>"; // �X�R�A
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."</td>"; // ���[�e�B���O
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."�i".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-4]*100))."%�j</td>"; // �P��
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."�i".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-5]*100))."%�j</td>"; // �Q��
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."�i".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-6]*100))."%�j</td>"; // �R��
		$i++;
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_all[$i]."�i".sprintf("%01.3f", ($array_all[$i]/$array_all[$i-7]*100))."%�j</td>"; // �S��
		// �P�ʂƂQ�ʂ̉񐔂�0���傫���ꍇ(0�̏ꍇ��0�ŏ��Z���邽�߃G���[�΍�j
		if(($array_all[$i-3]+$array_all[$i-2]) > 0)
		{
			$rentai_rate = round(($array_all[$i-3]+$array_all[$i-2])/$array_all[$i-7]*100,1);
		}
		$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$rentai_rate."%</td></tr>\r\n"; // �A�Η�
		$i++;
	}
}
$output_moji = $output_moji."</table>";
if($create_kbn == 0)
{
	// �r���[�O
	$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>�r���[�O</div>";
	$output_moji = $output_moji."<table style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."�΋ǎҖ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."�΋ǐ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���Ϗ���";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���_";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���[�e�B���O";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."1�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."2�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."3�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."4�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."</tr>";
	for($i = 0; $i < count($array_s);)
	{
		$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
		if($p_num <= $array_s[$i+1])
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // �΋ǎ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // �΋ǐ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // ���Ϗ���
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".score_minus($array_s[$i])."</td>"; // �X�R�A
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // ���[�e�B���O
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // �P��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // �Q��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td>"; // �R��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_s[$i]."</td></tr>\r\n"; // �S��
			$i++;
		}
		else
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // �΋ǎ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // �΋ǐ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // ���Ϗ���
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".score_minus($array_s[$i])."</td>"; // �X�R�A
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // ���[�e�B���O
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // �P��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // �Q��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td>"; // �R��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_s[$i]."</td></tr>\r\n"; // �S��
			$i++;
		}
	}
	$output_moji = $output_moji."</table>";
	// �`���[�O
	$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>�`���[�O</div>";
	$output_moji = $output_moji."<table style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."�΋ǎҖ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."�΋ǐ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���Ϗ���";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���_";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���[�e�B���O";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."1�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."2�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."3�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."4�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."</tr>";
	for($i = 0; $i < count($array_a);)
	{
		$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
		if($p_num <= $array_a[$i+1])
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // �΋ǎ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // �΋ǐ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // ���Ϗ���
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".score_minus($array_a[$i])."</td>"; // �X�R�A
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // ���[�e�B���O
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // �P��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // �Q��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td>"; // �R��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_a[$i]."</td></tr>\r\n"; // �S��
			$i++;
		}
		else
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // �΋ǎ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // �΋ǐ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // ���Ϗ���
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".score_minus($array_a[$i])."</td>"; // �X�R�A
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // ���[�e�B���O
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // �P��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // �Q��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td>"; // �R��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_a[$i]."</td></tr>\r\n"; // �S��
			$i++;
		}
	}
	$output_moji = $output_moji."</table>";
	// �a���[�O
	$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>�a���[�O</div>";
	$output_moji = $output_moji."<table style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."�΋ǎҖ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."�΋ǐ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���Ϗ���";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���_";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���[�e�B���O";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."1�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."2�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."3�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."4�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."</tr>";
	for($i = 0; $i < count($array_b);)
	{
		$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
		if($p_num <= $array_b[$i+1])
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // �΋ǎ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // �΋ǐ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // ���Ϗ���
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".score_minus($array_b[$i])."</td>"; // �X�R�A
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // ���[�e�B���O
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // �P��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // �Q��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td>"; // �R��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_b[$i]."</td></tr>\r\n"; // �S��
			$i++;
		}
		else
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // �΋ǎ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // �΋ǐ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // ���Ϗ���
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".score_minus($array_b[$i])."</td>"; // �X�R�A
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // ���[�e�B���O
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // �P��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // �Q��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td>"; // �R��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_b[$i]."</td></tr>\r\n"; // �S��
			$i++;
		}
	}
	$output_moji = $output_moji."</table>";
	// �b���[�O
	$output_moji = $output_moji."<div style='font-weight:bold; margin-top:10px'>�b���[�O</div>";
	$output_moji = $output_moji."<table style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."�΋ǎҖ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."�΋ǐ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���Ϗ���";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���_";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."���[�e�B���O";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."1�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."2�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."3�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff; background-color:#ccffcc'>";
	$output_moji = $output_moji."4�ʉ�";
	$output_moji = $output_moji."</td>";
	$output_moji = $output_moji."</tr>";
	for($i = 0; $i < count($array_c);)
	{
		$output_moji = $output_moji."<tr style='border:solid 1px; border-color:#0000ff'>";
		if($p_num <= $array_c[$i+1])
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // �΋ǎ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // �΋ǐ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // ���Ϗ���
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".score_minus($array_c[$i])."</td>"; // �X�R�A
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // ���[�e�B���O
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // �P��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // �Q��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td>"; // �R��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#0000ff'>".$array_c[$i]."</td></tr>\r\n"; // �S��
			$i++;
		}
		else
		{
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // �΋ǎ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // �΋ǐ�
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // ���Ϗ���
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".score_minus($array_c[$i])."</td>"; // �X�R�A
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // ���[�e�B���O
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // �P��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // �Q��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td>"; // �R��
			$i++;
			$output_moji = $output_moji."<td style='border:solid 1px; border-color:#AA44AA; background-color:#AA44AA'>".$array_c[$i]."</td></tr>\r\n"; // �S��
			$i++;
		}
	}
}
$output_moji = $output_moji."</table>";
fwrite($fp, $output_moji);
fclose($fp);
}
#-------------------------------------------------
#  �Q�[���X�R�A�ԋp
#-------------------------------------------------
function reckoning($gamescore) {

// �؂�̂�
if(calc_num == 1)
{
	$gamescore = floor($gamescore);
}
// �؂�グ
if(calc_num == 2)
{
	$gamescore = ceil($gamescore);
}
// �l�̌ܓ�
if(calc_num == 3)
{
	$gamescore = round($gamescore);
}
return $gamescore;

}
#-------------------------------------------------
#  ���ƕ��ςq�擾�i�Q�X�g���Ή��j
#-------------------------------------------------
function avg_rating($name,$avg_rate) {

// �v���[���[���Q�X�g�̏ꍇ�́Anull�ݒ�
if($name == null)
{
	$avg_rate = null;
}
return $avg_rate;

}
#-------------------------------------------------
#  �X�R�A���\��
#-------------------------------------------------
function score_minus($score) {
if($score < 0)
{
	$score = "��".$score * (-1);
}
return $score;
}
#-------------------------------------------------
#  ��{���i���[�g�v�Z�p�j
#-------------------------------------------------
function kihon_sc($juni) {
$kihon = 0;
// �P��
if($juni == 1)
{
	$kihon = 30;
}
// �Q��
else if($juni == 2)
{
	$kihon = 10;
}
// �R��
else if($juni == 3)
{
	$kihon = -10;
}
// �S��
else if($juni == 4)
{
	$kihon = -30;
}
return $kihon;

}
#-------------------------------------------------
#  ��ʃX�R�A�΋ǌ������e�v���_�E���쐬
#-------------------------------------------------
function ymd_select($min_ymd,$max_ymd,$value_select) {
$select_ymd=array();
$next_year_flg=false;
$st_flg = true;
$chk_flg ="";
// ���j���[�P�F�N��
for($i=date_format(date_create($min_ymd), "Y"); $i<=date_format(date_create($max_ymd), "Y"); $i++)
{
	if(!$next_year_flg)
	{
		$st_month = date_format(date_create($min_ymd), "m");
	}
	else
	{
		$st_month = 1;
	}
	if($i == date_format(date_create($max_ymd), "Y") && date_format(date_create($max_ymd), "m") < 12)
	{
		$ed_month = date_format(date_create($max_ymd), "m");
	}
	else
	{
		$ed_month = 12;
	}
	for($j=$st_month; $j<=$ed_month; $j++)
	{
		if(date_format(date_create($min_ymd),"Ymd") < date_format(date_create($i."/".$j."/01"),"Ymd"))
		{
			$st_ymd = $i."/".sprintf("%02d",$j)."/01";
		}
		else
		{
			$st_ymd = date_format(date_create($min_ymd),"Y/m/d");
		}
		if(date_format(date_create($max_ymd),"Ymd") > date_format(date_create($i."/".$j."/01"),"Ymt"))
		{
			$ed_ymd = date_format(date_create($i."/".$j."/01"),"Y/m/t");
		}
		else
		{
			$ed_ymd = date_format(date_create($max_ymd),"Y/m/d");
		}
		if($value_select == $st_ymd.$ed_ymd)
		{
			$chk_flg = "selected";
		}
		else if(date_format(date_create($st_ymd), "Ym") <= date_format(date_create("NOW"), "Ym") &&
		   date_format(date_create($ed_ymd), "Ym") >= date_format(date_create("NOW"), "Ym") && $value_select == "")
		{
			$chk_flg = "selected";
		}
		else
		{
			$chk_flg = "";
		}
		// �N�����j���[�쐬
		$str_month = $str_month."<option value='".$st_ymd.$ed_ymd."' ".$chk_flg.">".$i."�N".$j."��(".$st_ymd."�`".$ed_ymd.")\r\n";
	}
	$next_year_flg = true;
}
array_push($select_ymd, $str_month);
// ���j���[�Q�F��
$next_year_flg = false;
for($i=date_format(date_create($min_ymd), "Y"); $i<=date_format(date_create($max_ymd), "Y"); $i++)
{
	if(!$next_year_flg)
	{
		$st_month = date_format(date_create($min_ymd), "m");
	}
	else
	{
		$st_month = 1;
	}
	if($i == date_format(date_create($max_ymd), "Y") && date_format(date_create($max_ymd), "m") < 12)
	{
		$ed_month = date_format(date_create($max_ymd), "m");
	}
	else
	{
		$ed_month = 12;
	}
	for($j=$st_month; $j<=$ed_month; $j++)
	{
		if($j == 1 || $j == 4 || $j == 7 || $j == 10)
		{
			$st_flg = true;
		}
		if(date_format(date_create($min_ymd),"Ymd") < date_format(date_create($i."/".$j."/01"),"Ymd") && $st_flg)
		{
			$st_ymd = $i."/".sprintf("%02d",$j)."/01";
			$st_flg = false;
		}
		else if(date_format(date_create($min_ymd),"Ymd") >= date_format(date_create($i."/".$j."/01"),"Ymd"))
		{
			$st_ymd = date_format(date_create($min_ymd),"Y/m/d");
			if(date_format(date_create($min_ymd),"m") >= 1 && date_format(date_create($min_ymd),"m") <= 3)
			{
				$j = 3;
			}
			else if(date_format(date_create($min_ymd),"m") >= 4 && date_format(date_create($min_ymd),"m") <= 6)
			{
				$j = 6;
			}
			else if(date_format(date_create($min_ymd),"m") >= 7 && date_format(date_create($min_ymd),"m") <= 9)
			{
				$j = 9;
			}
			else if(date_format(date_create($min_ymd),"m") >= 10 && date_format(date_create($min_ymd),"m") <= 12)
			{
				$j = 12;
			}
		}
		if(date_format(date_create($max_ymd),"Ymd") > date_format(date_create($i."/".$j."/01"),"Ymt"))
		{
			$ed_ymd = date_format(date_create($i."/".$j."/01"),"Y/m/t");
		}
		else
		{
			$ed_ymd = date_format(date_create($max_ymd),"Y/m/d");
			if(date_format(date_create($max_ymd),"m") >= 1 && date_format(date_create($max_ymd),"m") <= 3)
			{
				$j = 3;
			}
			else if(date_format(date_create($max_ymd),"m") >= 4 && date_format(date_create($max_ymd),"m") <= 6)
			{
				$j = 6;
			}
			else if(date_format(date_create($max_ymd),"m") >= 7 && date_format(date_create($max_ymd),"m") <= 9)
			{
				$j = 9;
			}
			else if(date_format(date_create($max_ymd),"m") >= 10 && date_format(date_create($max_ymd),"m") <= 12)
			{
				$j = 12;
			}
		}
		if($value_select == $st_ymd.$ed_ymd)
		{
			$chk_flg = "selected";
		}
		else if(date_format(date_create($st_ymd), "Ym") <= date_format(date_create("NOW"), "Ym") &&
		        date_format(date_create($ed_ymd), "Ym") >= date_format(date_create("NOW"), "Ym") && $value_select == "")
		{
			$chk_flg = "selected";
		}
		else
		{
			$chk_flg = "";
		}
		switch ($j){
		case 3:
			// �߃��j���[�쐬
			$str_setu = $str_setu."<option value='".$st_ymd.$ed_ymd."' ".$chk_flg.">".($i-1)."�N�x4��(".$st_ymd."�`".$ed_ymd.")\r\n";
			$st_flg = false;
		break;
		case 6:
			// �߃��j���[�쐬
			$str_setu = $str_setu."<option value='".$st_ymd.$ed_ymd."' ".$chk_flg.">".$i."�N�x1��(".$st_ymd."�`".$ed_ymd.")\r\n";
			$st_flg = false;
		break;
		case 9:
			// �߃��j���[�쐬
			$str_setu = $str_setu."<option value='".$st_ymd.$ed_ymd."' ".$chk_flg.">".$i."�N�x2��(".$st_ymd."�`".$ed_ymd.")\r\n";
			$st_flg = false;
		break;
		case 12:
			// �߃��j���[�쐬
			$str_setu = $str_setu."<option value='".$st_ymd.$ed_ymd."' ".$chk_flg.">".$i."�N�x3��(".$st_ymd."�`".$ed_ymd.")\r\n";
			$st_flg = false;
		break;
		}
	}
	$next_year_flg = true;

}
array_push($select_ymd, $str_setu);
return $select_ymd;

}
?>
