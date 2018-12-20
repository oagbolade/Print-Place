$(function () {
	$(".mandatory").click(function () {
		let theId = $(this).attr("id");
		let subMandatory = document.querySelectorAll("#"+theId+" .sub-mandatory")[0];
		if (subMandatory.style.display == "none") {
			subMandatory.style.display = "block";
		}else{
			subMandatory.style.display = "none";
		}
	})
})