/**
 * jQuery TinyWatermark Plugin v3.1.0
 *   http://plugins.jquery.com/project/TinyWatermark
 *
 * Copyright (c) 2010 buzoganylaszlo
 *
 * $Id: jquery.tinywatermark.js 6 2015-07-08 07:07:06Z PragmaMx $
 */

(function($) {
	$.fn.watermark = function(c, t) {
		var e = function(e) {
			var i = $(this);
			if (!i.val()) {
				var w = t || i.attr('title'), $c = $($("<div />").append(i.clone()).html().replace(/type=\"?password\"?/, 'type="text"')).val(w).addClass(c);
				i.replaceWith($c);
				$c.focus(function() {
					$c.replaceWith(i); setTimeout(function() {i.focus();}, 1);
				})
				.change(function(e) {
					i.val($c.val()); $c.val(w); i.val() && $c.replaceWith(i);
				})
				.closest('form').submit(function() {
					$c.replaceWith(i);
				});
			}
		};
		return $(this).on('blur change', e).change();
	};
})(jQuery);