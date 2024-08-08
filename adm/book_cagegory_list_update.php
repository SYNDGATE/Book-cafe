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

if ($act_button === "선택수정") {

    auth_check_menu($auth, $sub_menu, 'w');

    for ($i=0; $i<$post_count_chk; $i++) {

        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
	    $id = isset($_POST['id'][$k]) ? clean_xss_tags($_POST['id'][$k], 1, 1) : '';
        $mb_id = isset($_POST['mb_id'][$k]) ? clean_xss_tags($_POST['mb_id'][$k], 1, 1) : '';
        $book_sortted = isset($_POST['book_sortted'][$k]) ? clean_xss_tags($_POST['book_sortted'][$k], 1, 1) : '';
  
        $sql = " update g5_book_cagegory
						set
					  mb_id				= '$mb_id',
					  book_sortted		= '$book_sortted'
	
                  where id            = '$id'  ";
        sql_query($sql);

    }

} else if ($act_button === "선택삭제") {

    auth_check_menu($auth, $sub_menu, 'd');


    for ($i=0; $i<$post_count_chk; $i++) {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
	    $id = isset($_POST['id'][$k]) ? clean_xss_tags($_POST['id'][$k], 1, 1) : '';

		$sql = " delete from g5_book_cagegory where id = '$id' ";
	    sql_query($sql);

    }


}

goto_url('./book_cagegory.php?'.$qstr);