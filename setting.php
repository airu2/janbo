<?php
#-------------------------------------------------
#  ��include
#-------------------------------------------------
include ('function.php');
include('db/db_access.php');
#-------------------------------------------------
#  ��DB���
#-------------------------------------------------
const hostname = 'localhost';  # �z�X�g��
const dbname = 'pokemon';        # DB��
const dbuser = 'pokemon';      # DB�ڑ����[�U
const dbpass = 'pokemon';      # DB�p�X���[�h
#-------------------------------------------------
#  �����b�Z�[�W�i�ʏ�n�j
#-------------------------------------------------
const msg0001 = '���[�U��o�^���܂����B';
const msg0002 = '���[�O�����N��ݒ肵�܂����B';
const msg0003 = '�X�R�A�����X�V���܂����B';
const msg0004 = '����o�^���܂����B';
const msg0005 = '�p�X���[�h���X�V���܂����B';
const msg0006 = '���[�g���X�V���܂����B';
#-------------------------------------------------
#  �����b�Z�[�W�i�G���[�n�j
#-------------------------------------------------
const err0001 = '�p�X���[�h�Ɍ�肪����܂��B�F�؂ł��܂���B';
const err0002 = '���[�U�͊��ɓo�^����Ă��܂��B';
const err0003 = '�v���R�[�h�͊��ɓo�^����Ă��܂��B';
const err0004 = '�폜�Ώۂ̔v�����I������Ă��܂���B';
const err0005 = '1,2,3,4�ʂ̃X�R�A���ʂɖ���������܂��B';
const err0006 = '�S�l�̍��v�X�R�A���z�����_�̂S�l���̃X�R�A�𒴂��Ă��܂��B';
const err0007 = '1,2,3,4�ʂ̃X�R�A�́A100�_�P�ʂœ��͂��Ă��������B';
const err0008 = '�폜�Ώۂ̃X�R�A���I������Ă��܂���B';
const err0009 = '�ΐ�҂��d�����Ă��܂��B';
const err0010 = '�e�q�n�l�܂��͂s�n�̓��t�Ɍ�肪����܂��B';
const err0011 = '�e�q�n�l���s�n�ɂȂ��Ă��܂��B';
const err0012 = '�����K�萔�������^�ł͂���܂���B';
const err0013 = '�����K�萔��0�����ɂȂ��Ă��܂��B';
const err0014 = '�폜�܂��͕����Ώۂ̃��[�U���I������Ă��܂���B';
const err0015 = '�p�X���[�h�̗L�������؂�ł��B�ēx���O�C�����Ă��������B';
const err0016 = '�X�R�A�C�����\�ȃf�[�^�����݂��܂���B';
const err0017 = '���͊��ɓo�^����Ă��܂��B';
const err0018 = '�폜�܂��͕����Ώۂ̑��I������Ă��܂���B';
const err0019 = '���1�ȏ�999�ȓ��œ��͂��Ă��������B';
const err0020 = '�����̓�����Ɋ��ɓo�^����Ă��郆�[�U�����܂��B';
const err0021 = '�Y���f�[�^�����݂��܂���B';
const err0022 = '����I�����Ă��������B';
const err0023 = '�z�����_�����_�ƂȂ��Ă��܂��B';
const err0024 = '���[�e�B���O���ŐV���ʂł͂���܂���B�Ǘ��҂ɘA�����Ă��������B���̂ق��̏W�v���ʂɂ͖�肠��܂���B';
#-------------------------------------------------
#  ���ݒ���1
#-------------------------------------------------
const boardtitle = '�����X�R�A�Ǘ��y�{�Ԋ��z'; # �f����
const hissu = '<font color = red>*</font>'; # �K�{
#-------------------------------------------------
#  ���ݒ���2
#-------------------------------------------------
const adminusr = 'admin'; # �Ǘ��҃��[�U��
const adminpwd = '19830206'; # �Ǘ��҃p�X���[�h
const background = './img/kp3.gif'; # �w�i�摜
const toppage = './menu.php'; # TOP_URL
const bgcolor = '#F0F0F0';
const in_bgcolor = '#DCDCED';
//const line_color = '#FF33BB';// �e�X�g��
const line_color = '#8080C0';// �{�Ԋ�
const c_filedir = './output/'; # �t�@�C���o�͐�f�B���N�g��
const yukotime = 336; # �����^�C���p�X���[�h�L������
const haihu_touroku_flg = 0; # �v���o�^�t���O�i0:�g�p���Ȃ� 1:�g�p����j
const score_del_year = 5; # �X�R�A�폜�^�C�~���O�iX�N�O�̃f�[�^���폜�j0:������
const guest_use_flg = 1; # �Q�X�g�g�p�t���O�i0:�g�p���Ȃ� 1:�g�p����j
const page_num = 10; # �P�y�[�W������̍ő�\����
const logstate = 1; # ���샍�O�i0:�Ƃ�Ȃ��A1:�Ƃ�j
const host_disp = 0; # ���샍�OHOST�\���i0:�\�����Ȃ��A1:�\������j
const log_day = 1; # ���샍�O�ۑ����i0:�����̂݁A-1:�������A1�`�FN���ԕۑ��j
const logdisp = 30; # �P�y�[�W������̃��O�ő�\����
const mfcscore_disp = 0; #�����i����y���|�C���g�\��(0:�\�����Ȃ��A1:�\������j
const calc_num = 0; # �Q�[���X�R�A�Z�o���@�i0:�����_�A1:�؂�̂āA2:�؂�グ�A3:�l�̌ܓ��j
const version = 'V1.5'; # �X�R�A�Ǘ��̃o�[�W����
?>



