<?php
if (!defined('_INDEX_')) define('_INDEX_', true);
include_once("../../../../../common.php");

$sql_common = "{$write_table}";

$category_option = '';
if ($board['bo_use_category']) {
    $ca_name = "";
    if (isset($write['ca_name']))
        $ca_name = $write['ca_name'];
    $category_option = get_category_option($bo_table, $ca_name);
    $is_category = true;
}
?>
<link rel="stylesheet" href="<?php echo $board_skin_url;?>/pop/pop.css">

<section id="bo_w">
  <h2 class="sound_only"><?php echo $g5['title'] ?></button></h2>
  <form name="fwrite" id="fwrite" action="<?php echo $board_skin_url;?>/write_pop.php" onsubmit="return fwrite_submit(this);" method="post"  autocomplete="off" style="width:100%">
    <input type="hidden" name="w" value="up">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
	<input type="hidden" name="token" value="" id="token">
    <section id="anc_cf_basic">
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <colgroup>
                    <col class="grid_1">
                    <col class="grid_2">
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="row"><label for="cf_title">기간</label></th>
                        <td><?php echo $write['wr_1'] ?> ~ <?php echo $write['wr_2'] ?></td>
                    </tr>
					<?php if($write['ca_name']) { ?>
                    <tr>
                        <th scope="row"><label for="cf_title">카테고리</label></th>
                        <td><select name="ca_name" id="pop_select" required>
								<option value="">분류를 선택하세요</option>
								  <?php echo $category_option ?>
							</select>
						</td>
                    </tr>
					<?php }?>
                    <tr>
                        <th scope="row"><label for="cf_title">제목</label></th>
                        <td><?php echo $write['wr_subject'] ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_title">내용</label></th>
                        <td><?php echo $write['wr_content'] ?></td>
                    </tr>
				</tbody>
				</table>
		</div>
    <div class="pop_submit">  
	  <button onclick="closePopup()" class="custom-btn btn-5 btn">닫기</button>
    </div>
  </form>

<script>
 $('button').click(function(event){
    event.preventDefault(); 
  });
  
	if (window == window.top) {
  		alert("접근 권한이 없습니다.");
		window.close();
	}

	function closePopup() {
          window.parent.document.getElementById('popup').style.display = 'none';
		  window.parent.document.getElementById('overlay').style.display = 'none';
     }
</script>
</section>

