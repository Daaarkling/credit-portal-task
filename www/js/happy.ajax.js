(function($, undefined) {

$.nette.ext({
	success: function (payload, status, jqXHR, settings) {
		window.happy.reset();
	}
});

})(jQuery);
