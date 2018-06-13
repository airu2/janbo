<?php
#┌────────────────────────
#│MahjongscoreProject
#│ haihu.php
#│ Copyright (c) radipo
#│ http://www.radipo.com
#│ ◆改版履歴◆
#│ 2012/02/27 新規作成 by airu
#└────────────────────────
require ('setting.php');
####### 一時格納領域 #######
$p_code = @$_POST['code'];                  # 牌譜コード
$p_comment = @$_POST['comment'];            # コメント
$p_uid2 = @$_POST['uid'];                   # ログインユーザＩＤ
$p_pwd2 = @$_POST['pass'];                  # ログインパスワード
$p_kbn = @$_POST['kbn'];                    # ログイン区分
$limit_e = page_num;
// メニューから遷移された場合は、検索初期値を設定
if(@$_POST['menu7'] != "")
{
	$page_num = 1; # ページ番号
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
<!-- ヘッダ部 -->
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=shift_jis">
<meta http-equiv="content-style-type" content="text/css">
<meta name="viewport" content="width=device-width,user-scalable=yes,initial-scale=1.0,maximum-scale=3.0" />
<link rel="STYLESHEET" type="text/css" href="./css/bbspatio.css">
<style type="text/css">
<!--
body,td,th { font-size:13px;font-family:"MS UI Gothic", Osaka, "ＭＳ Ｐゴシック"; }
a:hover { color:#DD0000 }
.num { font-size:12px; font-family:Verdana,Helvetica,Arial; }
.s1  { font-size:10px; font-family:Verdana,Helvetica,Arial; }
.s2  { font-size:10px; font-family:""MS UI Gothic", Osaka, "ＭＳ Ｐゴシック""; }
-->
</style>

<?php
// 管理者パスワードチェック
$admin_chk = adminpass_check($p_pwd2,$p_uid2);
// ユーザチェック(DB)
$user_chk = USERID_CNT3($p_uid2,$p_pwd2);
// パスワード認証失敗の場合は、エラーとする
if(!$admin_chk && $user_chk == -1)
{
	echo"<img src='./img/error.png' align='middle'> ".err0001;
	echo "<Input type=button value='ログイン画面' onClick=location.href='./login.php'>";
}
else if($user_chk == 0)
{
	LOG_INSERT($p_uid2,"牌譜登録",1,err0015."ユーザＩＤ：".$p_uid2);
	echo"<img src='./img/error.png' align='middle'> ".err0015;
	echo "<Input type=button value='ログイン画面' onClick=location.href='./login.php'>";
}
else
{
	###### ボタンが押された場合の処理 ######
	if (@$_SERVER["REQUEST_METHOD"]=="POST")
	{
		// 登録処理
		if(@$_POST["regit"])
		{
			$err = "";
			$err .= moji_check_ad($p_code,20,20,"牌譜コード");       // 牌譜コードの文字長・入力チェック
			$err .= moji_check_ad($p_comment,1,50,"コメント");       // コメントの文字長・入力チェック
			// エラーでない場合
			if($err == "")
			{
				// 牌譜コード登録チェック
				if(HAIHU_CNT($p_code))
				{
					// 牌譜登録
					HAIHU_INSERT($p_uid2,$p_code,$p_comment);
					LOG_INSERT($p_uid2,"牌譜登録",2,"牌譜コード：".$p_code."　コメント：".$p_comment);
				}
				else
				{
					LOG_INSERT($p_uid2,"牌譜登録",1,err0003."　牌譜コード：".$p_code);
					$err .= "<img src='./img/error.png' align='middle'>".err0003."<BR>";
				}
			}
			###### エラーメッセージ表示 ######
			if($err != "")
			{
				echo $err;
			}
			$limit_s = 0;
			$page_num = 1; # ページ番号
			$first_page_flg = "disabled";
			$num = HAIHU_CNT2();
			$max_page_num = $num / page_num;
			if($max_page_num <= $page_num)
			{
				$last_page_flg = "disabled";
			}
		}
		// 削除処理
		else if(@$_POST["del"])
		{
			$haihudel_flg = false;    // 牌譜削除フラグ
			// file1～fileXまで削除にチェックが入っているものを検索する
			for($i=0; $i<=@$_POST['haihunum'] ;$i++)
			{
				// 削除にチェックが入っている拡張子を削除
				if(@$_POST['haihu'.$i] != "")
				{
					HAIHU_DELETE(@$_POST['haihu'.$i]);
					$haihudel_flg = true;
					LOG_INSERT($p_uid2,"牌譜登録",2,"削除牌譜コード：".@$_POST['haihu'.$i]);
				}
			}
			// 入力チェック(1つも選択されていない場合は、エラーとする)
			if(!$haihudel_flg)
			{
				echo"<img src='./img/error.png' align='middle'> ".err0004;
			}
			$limit_s = 0;
			$page_num = 1; # ページ番号
			$first_page_flg = "disabled";
			$num = HAIHU_CNT2();
			$max_page_num = $num / page_num;
			if($max_page_num <= $page_num)
			{
				$last_page_flg = "disabled";
			}
		}
		// ページ遷移処理
		else if(@$_POST["page_regit1"] || @$_POST["page_regit2"] || @$_POST["page_regit3"] || @$_POST["page_regit4"])
		{
			$num = HAIHU_CNT2();
			$max_page_num = ceil($num / page_num);
			$page_num = @$_POST['pagenum'];
			// 「＜＜」押下時
			if(@$_POST["page_regit1"])
			{
				$page_num = 1; # ページ番号
				$first_page_flg = "disabled";
			}
			// 「＜」押下時
			elseif(@$_POST["page_regit2"])
			{
				$page_num = $page_num-1; # ページ番号
				if($page_num <= 1)
				{
					$first_page_flg = "disabled";
				}
				else
				{
					$first_page_flg = "";
				}
			}
			// 「＞」押下時
			elseif(@$_POST["page_regit3"])
			{
				$page_num = $page_num+1; # ページ番号
				if($max_page_num <= $page_num)
				{
					$last_page_flg = "disabled";
				}
				else
				{
					$last_page_flg = "";
				}
			}
			// 「＞＞」押下時
			elseif(@$_POST["page_regit4"])
			{
				$page_num = $max_page_num; # ページ番号
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
<?php // パスワード認証成功の場合は、フォーム表示
if($admin_chk || ($user_chk != -1 && $user_chk != 0))
{?>
<table width="100%">
<tr>
  <form action="./menu.php" method='post' >
	<Input type=submit value='メニュー画面に戻る'>
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
&nbsp; <b>牌譜登録</b></td>
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
  <th bgcolor="#FFFFFF" width="100">牌譜コード<?php echo hissu ?></th>
  <td><input type="text" name="code" style="ime-mode:disabled" size="30" value="<?php echo $p_code  ?>" maxlength="20"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <th bgcolor="#FFFFFF" width="100">コメント<?php echo hissu ?></th>
  <td><input type="text" name="comment" size="75" value="<?php echo $p_comment  ?>" maxlength="50"></td>
</tr>
</tr>
<tr bgcolor="#FFFFFF">
  <td bgcolor="#FFFFFF"><br></td>
  <td bgcolor="#FFFFFF">
    <input type="submit" name=regit style="font-size:15pt;background:#FF66AA" value="牌譜登録">
    <input type="submit" name=del   style="font-size:15pt;background:#FF66AA" value="選択牌譜削除">&nbsp;&nbsp;
</tr>
</tr></table>
</Td></Tr></Table>

<!-- ########## ヘッダ表示部 ########## -->
<BR>
<table border="0" cellspacing="1" cellpadding="5" width="80%">
<tr bgcolor=<?php echo line_color ?> nowrap>
<td bgcolor=<?php echo line_color ?> nowrap><b>NO</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>牌譜コード</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>コメント</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>登録日時</b></td>
<td bgcolor=<?php echo line_color ?> nowrap><b>削除</b></td>
</tr>
<?php
// 登録されている牌譜コードを表示する
$haihu_list = HAIHU_SELECT($p_uid2,$p_kbn,$limit_s,$limit_e);
echo $haihu_list;
?>
</table></Td></Tr></Table>
<?php }
if($haihu_list !="")
{ ?>
<center>
<input type="submit" name=page_regit1 <?php echo $first_page_flg ?> style="font-size:15pt;background:#99AA33" value="＜＜">
<input type="submit" name=page_regit2 <?php echo $first_page_flg ?> style="font-size:15pt;background:#99AA33" value="＜">
<font face='Century Gothic' size=15pt><?php echo $page_num ?></font>
<input type=hidden name=pagenum value=<?php echo $page_num ?>>
<input type="submit" name=page_regit3 <?php echo $last_page_flg ?> style="font-size:15pt;background:#99AA33" value="＞">
<input type="submit" name=page_regit4 <?php echo $last_page_flg ?> style="font-size:15pt;background:#99AA33" value="＞＞">
</center>
<?php } ?>
</div></body></html>
