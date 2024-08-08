<?php
$sub_menu = "600500";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');



//book
if (!sql_query("select count(*) as cnt from g5_book_cagegory",false)) { // attendance 테이블이 없다면 생성
    $sql_table = "create table g5_book_cagegory (           
      id int(11) NOT NULL auto_increment,
      mb_id varchar(50) NOT NULL default '',
      book_sortted varchar(255) NOT NULL default '',
      book_wr_1  varchar(255) NOT NULL default '',
      book_wr_2  varchar(255) NOT NULL default '',
      book_wr_3  varchar(255) NOT NULL default '',
      book_wr_4  varchar(255) NOT NULL default '',
      book_wr_5  varchar(255) NOT NULL default '',
      datetime datetime NOT NULL default '0000-00-00 00:00:00',
      PRIMARY KEY  (id),
      KEY id (mb_id,datetime)
        )";
    sql_query($sql_table, false);

	sql_query( "ALTER TABLE `g5_book_cagegory` CHANGE mb_id  mb_id  VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_cagegory` CHANGE book_sortted book_sortted VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_cagegory` CHANGE `book_wr_1` `book_wr_1` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_cagegory` CHANGE `book_wr_2` `book_wr_2` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_cagegory` CHANGE `book_wr_3` `book_wr_3` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");
	sql_query( "ALTER TABLE `g5_book_cagegory` CHANGE `book_wr_4` `book_wr_4` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");	
	sql_query( "ALTER TABLE `g5_book_cagegory` CHANGE `book_wr_5` `book_wr_5` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''; ");

}


$sql_common = " from g5_book_cagegory ";

if ($stx) {
	$sql_search = " where (1) ";

    $sql_search .= " and ( ";
    switch ($sfl) {
        case "book_sortted" :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.book_site" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "id";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '도서 분류코드 관리';
include_once('./admin.head.php');

$colspan = 4;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">생성된 도서분류 목록수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="mb_id"<?php echo get_selected($sfl, "mb_id", true); ?>>분류코드</option>
    <option value="book_sortted"<?php echo get_selected($sfl, "book_sortted", true); ?>>도서분류</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" value="검색" class="btn_submit">
</form>


<form name="fboardlist" id="fboardlist" action="./book_cagegory_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo isset($token) ? $token : ''; ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">게시판 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col"><?php echo subject_sort_link('mb_id') ?>분류코드</a></th>
        <th scope="col"><?php echo subject_sort_link('book_sortted') ?>도서분류</a></th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $one_update = '<a href="./book_cagegory_form.php?w=u&amp;id='.$row['id'].'&amp;'.$qstr.'" class="btn btn_03">상세수정</a>';
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['book_subjec']) ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
			<input type="hidden" name="id[<?php echo $i ?>]" value="<?php echo $row['id'] ?>">
        </td>
        <td>
            <label for="book_subjec_<?php echo $i; ?>" class="sound_only">분류코드<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="mb_id[<?php echo $i ?>]" value="<?php echo get_text($row['mb_id']) ?>" id="mb_id<?php echo $i ?>" required class="required tbl_input book_subject full_input" size="10">
        </td>
 	     <td>
            <label for="book_sortted_<?php echo $i; ?>" class="sound_only">도서분류 <strong class="sound_only"> 필수</strong></label>
            <input type="text" name="book_sortted[<?php echo $i ?>]" value="<?php echo get_text($row['book_sortted']) ?>" id="book_subjec_<?php echo $i ?>" class="tbl_input book_sortted full_input" size="10">
        </td>
        </td>
        <td class="td_mng td_mng_m">
            <?php echo $one_update ?>
        </td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn_02 btn">
    <?php if ($is_admin == 'super') { ?>
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_02 btn">
    <?php } ?>
    <?php if ($is_admin == 'super') { ?>
    <a href="./book_cagegory_db_update.php" id="bo_add" class="btn_01 btn">도서관리 DB 정리</a>
    <?php } ?>
    <?php if ($is_admin == 'super') { ?>
    <a href="./book_cagegory_form.php" id="bo_add" class="btn_01 btn">분류코드 추가</a>
    <?php } ?>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
function fboardlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once('./admin.tail.php');