$('.rgpd-button').on('click', function () {
	Cookies.set('rgpd', '1', { expires: 365 });
	$('#rgpd').fadeOut();
});