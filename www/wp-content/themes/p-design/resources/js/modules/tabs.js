$('.tab-link').on('click', function (e) {
	$this = $(this),
	$tab = $this.attr('data-tab');

	e.preventDefault();

	$('.tab-link').not($this).removeClass('active');
	$this.addClass('active');

	$('.tab').not('#' + $tab).removeClass('active');
	$('#' + $tab).addClass('active');
});