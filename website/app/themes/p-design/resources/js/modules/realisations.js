$(".home-realisation-item").on("click", function () {
	if ($(this).hasClass("active")) {
		return
	}

	$(".home-realisation-item").not($(this)).removeClass("active")
	$(this).addClass("active")
	$(".home-realisation-cover").css("background-image", "url(" + $(this).attr("data-image") + ")")
})

$(function () {
	$image = $(".home-realisation-item").first()
	$(".home-realisation-cover").css("background-image", "url(" + $image.attr("data-image") + ")")
})

$(".scrollto").on("click", function (e) {
	e.preventDefault()
	const href = $(this).attr("href")
	$("html,body").animate(
		{
			scrollTop: $(href).offset().top - 100,
		},
		"slow"
	)
})
