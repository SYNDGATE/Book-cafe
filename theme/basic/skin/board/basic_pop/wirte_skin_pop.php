<?php
// 제작 : 카이루  - 2024.8.15
// 팝업 관련 내용 광복절 버전

include_once('../../../../../common.php');
add_stylesheet('<link rel="stylesheet" href="' . $board_skin_url . '/style.css">', 0);
if($w == "up" ) {
   $sql = " update {$write_table}
                set  wr_subject = '{$wr_subject}',
					 wr_content = '{$wr_content}'
              where wr_id = '$wr_id' ";
	// xss 대비용
	$sql = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $sql);
	
    sql_query($sql);

	$redirect_url = $board_skin_url.'/wirte_skin_pop.php?bo_table='.$bo_table.'&w=u&wr_id='.$wr_id.'&page='.$page;
	goto_url($redirect_url);
}
?>
<link rel="stylesheet" href="<?php echo $board_skin_url;?>/style.css">

<section id="bo_w">
  <h2 class="sound_only"><?php echo $g5['title'] ?></button></h2>
  <form name="fwrite" id="fwrite" action="<?php echo $board_skin_url;?>/wirte_skin_pop.php";onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="w" value="up">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
	<input type="hidden" name="token" value="" id="token">
    <section id="anc_cf_basic">
        <?php echo $pg_anchor ?>

        <div class="tbl_frm01 tbl_wrap">
            <table>
                <colgroup>
                    <col class="grid_1">
                    <col class="grid_2">
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="row"><label for="cf_title">제목</label></th>
                        <td><input type="text" name="wr_subject" value="<?php echo $write['wr_subject'] ?>" id="wr_subject" required class="frm_input full_input required" size="50" maxlength="255" placeholder="제목"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_title">내용</label></th>
                        <td><textarea name="wr_content" id="wr_content"><?php echo $write['wr_content'] ; ?></textarea>
						</td>
                    </tr>
				</tbody>
				</table>
		</div>
    <div class="pop_submit">  
	  <button type="submit" id="btn_submit" accesskey="s" class="custom-btn btn-1 btn">저장</button>
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

    <?php if ($write_min || $write_max) { ?>
      // 글자수 제한
      var char_min = parseInt(<?php echo $write_min; ?>); // 최소
      var char_max = parseInt(<?php echo $write_max; ?>); // 최대
      check_byte("wr_content", "char_count");

      $(function() {
        $("#wr_content").on("keyup", function() {
          check_byte("wr_content", "char_count");
        });
      });

    <?php } ?>
    function html_auto_br(obj) {
      if (obj.checked) {
        result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
        if (result)
          obj.value = "html2";
        else
          obj.value = "html1";
      } else
        obj.value = "";
    }

    function fwrite_submit(f) {
   
      var subject = "";
  
      if (subject) {
        alert("제목에 금지단어('" + subject + "')가 포함되어있습니다");
        f.wr_subject.focus();
        return false;
      }

      if (content) {
        alert("내용에 금지단어('" + content + "')가 포함되어있습니다");
        if (typeof(ed_wr_content) != "undefined")
          ed_wr_content.returnFalse();
        else
          f.wr_content.focus();
        return false;
      }

      <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  
      ?>
         document.getElementById("btn_submit").disabled = "disabled";
      return true;
    }

		function closePopup() {
            window.parent.document.getElementById('popup').style.display = 'none';
        }
  </script>
</section>

