<?php
$sub_menu = "600100";
include_once('./_common.php');

check_demo();

$post_count_chk = (isset($_POST['chk']) && is_array($_POST['chk'])) ? count($_POST['chk']) : 0;
$chk = (isset($_POST['chk']) && is_array($_POST['chk'])) ? $_POST['chk'] : array();
$act_button = isset($_POST['act_button']) ? strip_tags($_POST['act_button']) : '';
$book = (isset($_POST['book']) && is_array($_POST['book'])) ? $_POST['book'] : array();

if (! $post_count_chk) {
    alert($act_button." 하실 항목을 하나 이상 체크하세요.");
}


check_admin_token();

if ($act_button == "선택수정") {

    auth_check_menu($auth, $sub_menu, 'w');

    for ($i=0; $i<$post_count_chk; $i++) {

        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
	    $id = isset($_POST['id'][$k]) ? clean_xss_tags($_POST['id'][$k], 1, 1) : '';
        $mb_id = isset($_POST['mb_id'][$k]) ? clean_xss_tags($_POST['mb_id'][$k], 1, 1) : '';
        $book_subject = isset($_POST['book_subject'][$k]) ? clean_xss_tags($_POST['book_subject'][$k], 1, 1) : '';
        $book_day = isset($_POST['book_day'][$k]) ? clean_xss_tags($_POST['book_day'][$k], 1, 1) : '';
        $book_site = isset($_POST['book_site'][$k]) ? clean_xss_tags($_POST['book_site'][$k], 1, 1) : '';
        $book_sponsor = isset($_POST['book_sponsor'][$k]) ? clean_xss_tags($_POST['book_sponsor'][$k], 1, 1) : '';
        $book_cagegory = isset($_POST['book_cagegory'][$k]) ? clean_xss_tags($_POST['book_cagegory'][$k], 1, 1) : '';
        $book_sortted = isset($_POST['book_sortted'][$k]) ? clean_xss_tags($_POST['book_sortted'][$k], 1, 1) : '';
        $book_authors_name = isset($_POST['book_authors_name'][$k]) ? clean_xss_tags($_POST['book_authors_name'][$k], 1, 1) : '';


        $sql = " update g5_book_table
						set
					  mb_id				= '$mb_id',
					  book_subject		= '$book_subject',
				      book_day			= '$book_day',
				      book_site			= '$book_site',
				      book_sponsor		= '$book_sponsor',
					  book_cagegory     = '$book_cagegory',
					  book_sortted		= '$book_sortted',
				      book_authors_name = '$book_authors_name'
                  where id            = '$id'  ";
        sql_query($sql);

    }

} else if ($act_button == "선택삭제") {

    auth_check_menu($auth, $sub_menu, 'd');


    for ($i=0; $i<$post_count_chk; $i++) {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
	    $id = isset($_POST['id'][$k]) ? clean_xss_tags($_POST['id'][$k], 1, 1) : '';

		$sql = " delete from g5_book_table where id = '$id' ";
	    sql_query($sql);
	 echo $i."=>".$sql."<BR>";


    }

}

goto_url('./book_list.php?'.$qstr);