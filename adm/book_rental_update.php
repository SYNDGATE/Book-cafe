<?php
$sub_menu = "600500";
include_once('./_common.php');

if ($w == "u" || $w == "d")
    check_demo();

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

$sql_common = "   book_mb_id		= '{$_POST['book_mb_id']}',
				  book_id			= '{$_POST['book_id']}',
				  book_name			= '{$_POST['book_name']}',
				  book_hp			= '{$_POST['book_hp']}',
			      book_book_number	= '{$_POST['book_book_number']}',
			      book_book_name	= '{$_POST['book_book_name']}',
			      book_outbook		= '{$_POST['book_outbook']}',
			      book_inbook		= '{$_POST['book_inbook']}',
			      book_ing			= '{$_POST['book_ing']}' ";

if ($w == "a")
{
    check_demo();
	$post_count_chk = (isset($_POST['chk']) && is_array($_POST['chk'])) ? count($_POST['chk']) : 0;
	$chk = (isset($_POST['chk']) && is_array($_POST['chk'])) ? $_POST['chk'] : array();
	$act_button = isset($_POST['act_button']) ? strip_tags($_POST['act_button']) : '';

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
        $book_mb_id = isset($_POST['book_mb_id'][$k]) ? clean_xss_tags($_POST['book_mb_id'][$k], 1, 1) : '';
        $book_hp = isset($_POST['book_hp'][$k]) ? clean_xss_tags($_POST['book_hp'][$k], 1, 1) : '';
        $book_outbook = isset($_POST['book_outbook'][$k]) ? clean_xss_tags($_POST['book_outbook'][$k], 1, 1) : '';
        $book_inbook = isset($_POST['book_inbook'][$k]) ? clean_xss_tags($_POST['book_inbook'][$k], 1, 1) : '';
        $book_ing = isset($_POST['book_ing'][$k]) ? clean_xss_tags($_POST['book_ing'][$k], 1, 1) : '';
		$book_book_number = isset($_POST['book_book_number'][$k]) ? clean_xss_tags($_POST['book_book_number'][$k], 1, 1) : '';

        $sql = " update g5_book_rental
						set
				      book_hp		= '$book_hp',
					  book_outbook		= '$book_outbook',
					  book_inbook		= '$book_inbook',
					  book_ing		= '$book_ing'
                  where id            = '$id'  ";
        sql_query($sql);
		//회원에 대여 수량 표시
		$sqlct = " select count(*) as cnt from g5_book_rental where book_mb_id = '{$book_mb_id}' and book_ing ='대여중' ";
		$rowsqlct = sql_fetch($sqlct);
		$sqlcts = " update g5_book_member
						set book_rental = '{$rowsqlct['cnt']}'
		            where book_mb_id = '{$book_mb_id}' ";
	    sql_query($sqlcts);

		//도서 리스트에 표시 book_ing  대여 분실 파손
		if($book_ing == "반납") {
			$sqlings = " update g5_book_table
							set book_ing = ''
			            where mb_id = '{$book_book_number}' ";
		    sql_query($sqlings);
		}
		else {
			$sqlings = " update g5_book_table
							set book_ing = '{$book_ing}'
			            where mb_id = '{$book_book_number}' ";
		    sql_query($sqlings);
			}
    }

} else if ($act_button === "선택삭제") {

    auth_check_menu($auth, $sub_menu, 'd');


    for ($i=0; $i<$post_count_chk; $i++) {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
	    $id = isset($_POST['id'][$k]) ? clean_xss_tags($_POST['id'][$k], 1, 1) : '';
        $book_mb_id = isset($_POST['book_mb_id'][$k]) ? clean_xss_tags($_POST['book_mb_id'][$k], 1, 1) : '';

		$sql = " delete from g5_book_rental where id = '$id' ";
	    sql_query($sql);

		//회원에 대여 수량 표시
		$sqlct = " select count(*) as cnt from g5_book_rental where book_mb_id = '{$book_mb_id}' and book_ing ='대여' ";
		$rowsqlct = sql_fetch($sqlct);
		$sqlcts = " update g5_book_member
						set book_rental = '{$rowsqlct['cnt']}'
		            where book_mb_id = '{$book_mb_id}' ";
	    sql_query($sqlcts);


		$book_book_number = isset($_POST['book_book_number'][$k]) ? clean_xss_tags($_POST['book_book_number'][$k], 1, 1) : '';
		$sqlings = " update g5_book_table
						set book_ing = ''
			            where mb_id = '{$book_book_number}' ";
		sql_query($sqlings);

    }
}

goto_url('./book_rental.php?'.$qstr);

}

	check_admin_token();
if ($w == "")
{

//g5_book_renta
	//회원에 대여 수량 표시
	$sqlctv = " select count(*) as cnt from g5_book_rental where book_book_number = '{$_POST['book_book_number']}' and book_ing ='대여중' ";
	$rowsqlctv = sql_fetch($sqlctv);
	if($rowsqlctv['cnt']) alert("현재 대여 책입니다.");


    $sqldd = " select * from `g5_book_member` where book_mb_id = '{$_POST['book_mb_id']}' ";
    $coc = sql_fetch($sqldd);
	//신규회원등록
	if (!$coc['id']) {
		if($_POST['book_hp'])
			$ck1 = explode("-", $_POST['book_hp']);
			else $ck1[2] = date(ymd);

		$book_mb_id = $_POST['book_name']."-".$ck1[2];

	    $sqldz = " select * from g5_book_member where book_mb_id = '$book_mb_id' ";
	    $coz = sql_fetch($sqldz);
	    if($coz['id'])
		    $book_mb_id = $_POST['book_name']."-".mktime() ;

	    $sqlv = " insert g5_book_member
	                set book_mb_id		= '{$book_mb_id}',
						book_name		= '{$_POST['book_name']}',
					    book_hp			= '{$_POST['book_hp']}',
						datetime = '".G5_TIME_YMDHIS."'
					";
	   sql_query($sqlv);

		$sql_common = "   book_mb_id		= '{$book_mb_id}',
					      book_id			= '{$_POST['book_id']}',
						  book_name			= '{$_POST['book_name']}',
					      book_hp			= '{$_POST['book_hp']}',
					      book_book_number	= '{$_POST['book_book_number']}',
					      book_book_name	= '{$_POST['book_book_name']}',
					      book_outbook		= '{$_POST['book_outbook']}',
					      book_inbook		= '{$_POST['book_inbook']}',
					      book_ing			= '{$_POST['book_ing']}' ";
	}


    $sql = " insert g5_book_rental
                set $sql_common
				 , datetime = '".G5_TIME_YMDHIS."'
				";
    sql_query($sql);

	//회원에 대여 수량 표시
	$sqlct = " select count(*) as cnt from g5_book_rental where book_mb_id = '{$book_mb_id}' and book_ing ='대여' ";
	$rowsqlct = sql_fetch($sqlct);
	$sqlcts = " update g5_book_member
					set book_rental = '{$rowsqlct['cnt']}'
		         where book_mb_id = '{$book_mb_id}' ";
    sql_query($sqlcts);
}
else if ($w == "u")
{
	$id = $_POST['id'];
    $sql = " update g5_book_rental
                set $sql_common
              where id = '{$id}' ";
    sql_query($sql);

}
else if ($w == "d")
{
    $sql = " delete from g5_book_rental where id = '$id' ";
    sql_query($sql);
}

goto_url('./book_rental.php', false);