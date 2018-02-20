(function($, undefined) {

$.nette.ext({
	success: function (payload, status, jqXHR, settings) {
		$('input[data-phone]').each(function () {
			$(this).val($(this).data('phone')).hide();
		});
	}
});

})(jQuery);
