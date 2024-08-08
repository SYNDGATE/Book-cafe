<?php
$sub_menu = "600500";
include_once('./_common.php');

if ($w == "u" || $w == "d")
    check_demo();

if ($w == 'd')
    auth_check_menu($auth, $sub_menu, "d");
else
    auth_check_menu($auth, $sub_menu, "w");

check_admin_token();

$sevs = "";
	if($sst)
	   $sevs .= "&sst=".$_POST['sst'];
	if($sod)
	   $sevs .= "&sod=".$_POST['sod'];
	if($sfl)
	   $sevs .= "&sfl=".$_POST['sfl'];
	if($stx)
	   $sevs .= "&stx=".$_POST['stx'];
	if($page)
	   $sevs .= "&page=".$_POST['page'];

$sql_common = "   mb_id				= '{$_POST['mb_id']}',
			      book_sortted		= '{$_POST['book_sortted']}',
			      book_wr_1			= '{$_POST['book_wr_1']}',
			      book_wr_2			= '{$_POST['book_wr_2']}',
			      book_wr_3			= '{$_POST['book_wr_3']}',
			      book_wr_4			= '{$_POST['book_wr_4']}',
			      book_wr_5			= '{$_POST['book_wr_5']}' ";

if ($w == "")
{
    $sql = " insert g5_book_cagegory
                set $sql_common
				 , datetime = '".G5_TIME_YMDHIS."'
				";
    sql_query($sql);
}
else if ($w == "u")
{
	$id = $_POST['id'];
    $sql = " update g5_book_cagegory
                set $sql_common
              where id = '{$id}' ";
    sql_query($sql);

echo $sql;


	goto_url('./book_cagegory_form.php?w=u&id='.$id.'&'.$sevs, false);

}
else if ($w == "d")
{
    $sql = " delete from g5_book_cagegory where id = '$id' ";
    sql_query($sql);
}

goto_url('./book_cagegory.php', false);