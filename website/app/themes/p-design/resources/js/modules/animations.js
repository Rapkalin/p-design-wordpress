$(function() {
	if ($('.page-l-equipe').length > 0) {
		var animationAboutScrolled = false;

		$(document).scroll(function() {
			if (!animationAboutScrolled) {
				var $scroll = $(window).scrollTop(),
					$homeAbout = $('#team-about').offset().top,
					$windowHeight = $(window).height();

				if ($scroll > ($homeAbout - $windowHeight / 2)) {
					$('#about-mask').addClass('active');
					animationAboutScrolled = true
				}
			}
		});
	}

})