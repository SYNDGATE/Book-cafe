<?php
$sub_menu = "600500";
include_once('./_common.php');

if ($w == "u" || $w == "d")
    check_demo();

if ($w == "a")
{
    check_demo();
	$post_count_chk = (isset($_POST['chk']) && is_array($_POST['chk'])) ? count($_POST['chk']) : 0;
	$chk = (isset($_POST['chk']) && is_array($_POST['chk'])) ? $_POST['chk'] : array();
	$act_button = isset($_POST['act_button']) ? strip_tags($_POST['act_button']) : '';
	$book = (isset($_POST['book']) && is_array($_POST['book'])) ? $_POST['book'] : array();

	if (!$post_count_chk) {
	    alert($act_button." 하실 항목을 하나 이상 체크하세요.");
	}

	check_admin_token();

	if ($act_button === "선택수정") {

	    auth_check_menu($auth, $sub_menu, 'w');

	    for ($i=0; $i<$post_count_chk; $i++) {

        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
	    $id = isset($_POST['id'][$k]) ? clean_xss_tags($_POST['id'][$k], 1, 1) : '';
        $book_name = isset($_POST['book_name'][$k]) ? clean_xss_tags($_POST['book_name'][$k], 1, 1) : '';
        $book_hp = isset($_POST['book_hp'][$k]) ? clean_xss_tags($_POST['book_hp'][$k], 1, 1) : '';
        $book_email = isset($_POST['book_email'][$k]) ? clean_xss_tags($_POST['book_email'][$k], 1, 1) : '';

        $sql = " update g5_book_member
						set
					  book_name		= '$book_name',
				      book_hp		= '$book_hp',
					  book_email    ='$book_email'
                  where id            = '$id'  ";
        sql_query($sql);
    }

} else if ($act_button === "선택삭제") {

    auth_check_menu($auth, $sub_menu, 'd');


    for ($i=0; $i<$post_count_chk; $i++) {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
	    $id = isset($_POST['id'][$k]) ? clean_xss_tags($_POST['id'][$k], 1, 1) : '';

		$sql = " delete from g5_book_member where id = '$id' ";
	    sql_query($sql);

    }
}

goto_url('./book_member.php?'.$qstr);

}
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

$sql_common = "   book_name			= '{$_POST['book_name']}',
			      book_hp			= '{$_POST['book_hp']}',
			      book_tel			= '{$_POST['book_tel']}',
			      mb_zip			= '{$_POST['mb_zip']}',
			      mb_addr1			= '{$_POST['mb_addr1']}',
			      mb_addr2			= '{$_POST['mb_addr2']}',
			      mb_addr3			= '{$_POST['mb_addr3']}',
			      book_email		= '{$_POST['book_email']}',
			      book_memo			= '{$_POST['book_memo']}',
			      book_wr_1			= '{$_POST['book_wr_1']}',
			      book_wr_2			= '{$_POST['book_wr_2']}',
			      book_wr_3			= '{$_POST['book_wr_3']}',
			      book_wr_4			= '{$_POST['book_wr_4']}',
			      book_wr_5			= '{$_POST['book_wr_5']}' ";

if ($w == "")
{
	if($_POST['book_hp'])
			$ck1 = explode("-", $_POST['book_hp']);
		else $ck1[2] = date(ymd);

	$book_mb_id = $_POST['book_name']."-".$ck1[2];

    $sqld = " select * from g5_book_member where book_mb_id = '$book_mb_id' ";
    $co = sql_fetch($sqld);

    if($co['id'])
        $book_mb_id = $_POST['book_name']."-".mktime() ;

    $sql = " insert g5_book_member
                set book_mb_id		= '{$book_mb_id}',
					$sql_common
				 , datetime = '".G5_TIME_YMDHIS."'
				";
    sql_query($sql);

}
else if ($w == "u")
{
	$id = $_POST['id'];
    $sql = " update g5_book_member
                set 
				$sql_common
              where id = '{$id}' ";
    sql_query($sql);
	goto_url('./book_member_form.php?w=u&id='.$id.'&'.$sevs, false);

}
else if ($w == "d")
{
    $sql = " delete from g5_book_member where id = '$id' ";
    sql_query($sql);
}

goto_url('./book_member.php', false);