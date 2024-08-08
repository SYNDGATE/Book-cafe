<?php
$sub_menu = "600200";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check_menu($auth, $sub_menu, 'w');

$html_title = '도서 회원 관리';

if ($w == '') {
 
} else if ($w == 'u') {

    $html_title .= " 수정";
    $readonly = " readonly";

    $sql = " select * from `g5_book_member` where id = '$id' ";
    $co = sql_fetch($sql);
    if (!$co['id'])
        alert('등록된 자료가 없습니다.');
}


$g5['title'] = $html_title;
include_once ('./admin.head.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$pg_anchor = '<ul class="anchor">
    <li><a href="#anc_bo_basic">기본 설정</a></li>
    <li><a href="#anc_bo_extra">메모필드</a></li>
</ul>';

?>

<form name="fboardform" id="fboardform" action="./book_member_update.php" onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="id" value="<?php echo $id ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

<section id="anc_bo_basic">
    <h2 class="h2_frm">도서 회원 추가</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>회원 기본 등록</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
<?php if($w == "u") {?>
        <tr>
            <th scope="row"><label for="book_mb_id">아이디<strong class="sound_only">필수</strong></label></th>
            <td colspan="2">
                <input type="text" name="book_mb_id" value="<?php echo get_text($co['book_mb_id']) ?>" id="book_mb_id" disabled class="frm_input" size="80" maxlength="120">  (Ex: 특별한 경우만 수정해주세요)
            </td>
        </tr>
<?php } ?>
        <tr>
            <th scope="row"><label for="book_name">회원이름<strong class="sound_only">필수</strong></label></th>
            <td colspan="2">
                <input type="text" name="book_name" value="<?php echo get_text($co['book_name']) ?>" id="book_name " required class="required frm_input" size="80" maxlength="120">
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="book_hp">회원HP<strong class="sound_only">필수</strong></label></th>
            <td colspan="2">
                <input type="text" name="book_hp" value="<?php echo get_text($co['book_hp']) ?>" id="book_hp" required class="required frm_input" size="80" maxlength="120"> (ex: 010-1234-4567)
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="book_tel">집전화</label></th>
            <td colspan="2">
                <input type="text" name="book_tel" value="<?php echo get_text($co['book_tel']) ?>" id="book_tel  " class="frm_input" size="80" maxlength="120">
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="book_email">이메일</label></th>
            <td colspan="2">
                <input type="text" name="book_email" value="<?php echo get_text($co['book_email']) ?>" id="id="mb_email"  " class="frm_input email" size="80" maxlength="120">
            </td>
        </tr>
    <tr>
        <th scope="row">주소</th>
        <td colspan="3" class="td_addr_line">
            <label for="mb_zip" class="sound_only">우편번호</label>
            <input type="text" name="mb_zip" value="<?php echo $co['mb_zip']; ?>" id="mb_zip" class="frm_input readonly" size="5" maxlength="6">
            <button type="button" class="btn_frmline" onclick="win_zip('fboardform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
            <input type="text" name="mb_addr1" value="<?php echo $co['mb_addr1'] ?>" id="mb_addr1" class="frm_input readonly" size="60">
            <label for="mb_addr1">기본주소</label><br>
            <input type="text" name="mb_addr2" value="<?php echo $co['mb_addr2'] ?>" id="mb_addr2" class="frm_input" size="60">
            <label for="mb_addr2">상세주소</label>
            <br>
            <input type="text" name="mb_addr3" value="<?php echo $co['mb_addr3'] ?>" id="mb_addr3" class="frm_input" size="60">
            <label for="mb_addr3">참고항목</label>
            <input type="hidden" name="mb_addr_jibeon" value="<?php echo $co['mb_addr_jibeon']; ?>"><br>
        </td>
    </tr>
        <tr>
            <th scope="row"><label for="book_memo">메모</label></th>
            <td colspan="2">
			<textarea name="book_memo" id="book_memo"><?php echo $co['book_memo'] ?></textarea>
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
    <a href="./book_member.php" class="btn btn_02">목록</a>
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