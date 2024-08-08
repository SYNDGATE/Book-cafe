<?php
$sub_menu = "600500";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check_menu($auth, $sub_menu, 'w');



$html_title = '도서관리';

if ($w == '') {
 
} else if ($w == 'u') {

    $html_title .= " 수정";
    $readonly = " readonly";

    $sql = " select * from g5_book_cagegory where id = '$id' ";
    $co = sql_fetch($sql);
    if (!$co['id'])
        alert('등록된 자료가 없습니다.');
}


$g5['title'] = $html_title;
include_once ('./admin.head.php');

$pg_anchor = '<ul class="anchor">
    <li><a href="#anc_bo_basic">기본 설정</a></li>
    <li><a href="#anc_bo_extra">메모필드</a></li>
</ul>';

?>

<form name="fboardform" id="fboardform" action="./book_cagegory_update.php" onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="id" value="<?php echo $id ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

<section id="anc_bo_basic">
    <h2 class="h2_frm">도서 추가</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>도서명 기본 등록</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="mb_id ">분류코드<strong class="sound_only">필수</strong></label></th>
            <td colspan="2">
                <input type="text" name="mb_id" value="<?php echo get_text($co['mb_id']) ?>" id="mb_id" required class="required frm_input" size="80" maxlength="120">
            </td>
        </tr>
		
        <tr>
            <th scope="row"><label for="book_sortted ">도서분류<strong class="sound_only">필수</strong></label></th>
            <td colspan="2">
                <input type="text" name="book_sortted" value="<?php echo get_text($co['book_sortted']) ?>" id="book_sortted " class="frm_input" size="80" maxlength="120">
            </td>
        </tr>

		 </tbody>
        </table>
    </div>
</section>



<section id="anc_bo_extra">
    <h2 class="h2_frm">메모필드 설정</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시판 메모필드 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <?php for ($i=1; $i<=5; $i++) { ?>
        <tr>
            <th scope="row">메모필드<?php echo $i ?></th>
            <td class="td_extra">
                 <input type="text" name="book_wr_<?php echo $i ?>" value="<?php echo get_text($co['book_wr_'.$i]) ?>" id="book_wr_<?php echo $i ?>" class="frm_input extra-value-input">
            </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
</section>
<div class="btn_fixed_top">
    <a href="./book_cagegory.php" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn btn_submit" accesskey="s">
</div>

</form>

<script>
var captcha_chk = false;

function use_captcha_check(){
    $.ajax({
        type: "POST",
        url: g5_admin_url+"/ajax.use_captcha.php",
        data: { admin_use_captcha: "1" },
        cache: false,
        async: false,
        dataType: "json",
        success: function(data) {
        }
    });
}

function frmcontentform_check(f)
{
    errmsg = "";
    errfld = "";


    check_field(f.book_sortted, "도서분류을 입력하세요.");


    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }
    
    if( captcha_chk ) {
        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>
    }

    return true;
}
</script>

<?php
include_once ('./admin.tail.php');