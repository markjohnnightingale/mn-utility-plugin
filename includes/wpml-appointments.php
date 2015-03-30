<?php // Fix WPML with Appointments+

function wpml_fix_ajax_install() {
	global $sitepress;
	if(defined('DOING_AJAX') && DOING_AJAX && isset($_REQUEST['action']) && isset($_REQUEST['lang']) ){
		// remove WPML legacy filter, as it is not doing its job for ajax calls
		remove_filter('locale', array($sitepress, 'locale'));
		add_filter('locale', 'wpml_ajax_fix_locale');
		function wpml_ajax_fix_locale($locale){
			global $sitepress;
			// simply return the locale corresponding to the "lang" parameter in the request
			return $sitepress->get_locale($_REQUEST['lang']);
		}
	}
}
add_action('plugins_loaded', 'wpml_fix_ajax_install');

function app_my_language_filter () {
	global $sitepress;
	$lang = $sitepress->get_current_language();
	?>
<script>
;(function ($) {
$.ajaxSetup({
	beforeSend: function (jqxhr, settings) {
		settings.data += '&lang=<?php echo esc_js($lang); ?>';
	}
});
})(jQuery);
</script>
	<?php
}
add_action('wp_footer', 'app_my_language_filter');
