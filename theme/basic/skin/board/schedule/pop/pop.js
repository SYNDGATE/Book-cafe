// 팝업 리스트
function openPopup(id) {
    document.getElementById('popup').style.display = 'block';
	document.getElementById('overlay').style.display = 'block';
	
	const frameElement = document.getElementsByClassName('pops')[0]; //class 값 찾아서 수정
	const src = frameElement.getAttribute('src') + '&wr_id='+ id; // 내용 수정
	frameElement.setAttribute('src', src);
	return false;
	}

	document.addEventListener("DOMContentLoaded", function () {
	  const frames = document.querySelectorAll(".frame");
	  frames.forEach((frame) => {
	    const src = frame.dataset.src;
	    const iframe = document.createElement("iframe");
	    iframe.src = src;
		iframe.classList.add("pops");
	    frame.appendChild(iframe);
	  });
});	 