<?php
include_once('./_common.php');

include_once(G5_PATH.'/head.sub.php');

$sql_common = " from g5_book_table ";

//xss 공격 방어
$stx = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $stx);
$stx = preg_replace('#([^\p{L}]|^)(?:javascript|jar|applescript|vbscript|vbs|wscript|jscript|behavior|mocha|livescript|view-source)\s*:(?:.*?([/\\\;()\'">]|$))#ius','$1$2', $stx);

if ($stx) {
	$sql_search = " where (1) ";

    $sql_search .= " and ( ";
    switch ($sfl) {
        case "book_subject" :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
        case "a.book_site" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";

// 인기검색어
    $field = explode('||', trim($sfl));
	$search_str[0] =  $stx;
    insert_popular($field, $search_str[0]);

}

if (!$sst) {
    $sst  = "id";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '도서 관리';

$colspan = 6;
?>
<link rel="stylesheet" href="<?php echo G5_URL;?>/book/book.css?ver=210618">
<div class="logobook">
	<?php
	    $logo_img = G5_DATA_PATH."/common/logo_img.jpg";
		if (file_exists($logo_img))
		 { ?>
		 <a href="../"><img src="<?php echo G5_DATA_URL; ?>/common/logo_img.jpg" alt="로고"></a>
		<?php } ?>
</div>


<div class="psearch">
	<form name="fsearch" id="fsearch" class="local_sch01 local_sch tbl_font" method="get">
	<label for="sfl" class="sound_only">검색대상</label>
	<div class="local_ov01">
	    <span class="btn_ov01"><span class="ov_txt">도서목록수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개  <?php echo $listall ?></span></span>
	</div>
	<span class="ovtop_txt"><?php echo $config['cf_title']; ?> 도서정보 검색</span>
	<select name="sfl" id="sfl">
	    <option value="book_subject"<?php echo get_selected($sfl, "book_subject", true); ?>>도서명</option>
	    <option value="mb_id"<?php echo get_selected($sfl, "mb_id", true); ?>>분류번호</option>
	    <option value="book_site"<?php echo get_selected($sfl, "book_site"); ?>>장소</option>
	    <option value="book_sortted"<?php echo get_selected($sfl, "book_sortted"); ?>>도서분류</option>
	    <option value="book_authors_name"<?php echo get_selected($sfl, "book_authors_name"); ?>>작가명</option>
	</select>
	<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
	<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
	<input type="submit" value="검색" class="btn_submit">
	</form>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col"><?php echo subject_sort_link('mb_id') ?>분류번호</a></th>
		<th scope="col"><?php echo subject_sort_link('book_subject') ?>도서명</a></th>
        <th scope="col"><?php echo subject_sort_link('book_site', '', 'desc') ?>장소</a></th>
        <th scope="col"><?php echo subject_sort_link('book_sortted') ?>도서분류</a></th>
        <th scope="col"><?php echo subject_sort_link('book_authors_name') ?>작가명</a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
	    <td class="grid_2">
            <?php echo get_text($row['mb_id']) ?>
        </td>
        <td>
            <?php echo get_text($row['book_subject']) ?>
        </td>
		<td class="grid_5">
            <?php if($row['book_ing'] == "대여중") echo "현재 대여중"; else echo get_text($row['book_site']);?>
        </td>
		     <td class="grid_7">
            <?php echo get_text($row['book_sortted']) ?>
        </td>
		     <td class="grid_6">
            <?php echo get_text($row['book_authors_name']) ?>
        </td>
 
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>

	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>
</div>
<?php
include_once(G5_PATH.'/tail.sub.php');