<?php
/**
 * Plantilla de error 404
 */
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;

$site = get_option('site');

$p404 = (isset($site['error_page'])) ?? $site['error_page'];

if (is_numeric($p404) && $p404) {
	$p404 = Timber::get_post($p404);
} else {
	$p404 = false;
}

$context = Timber::context();

if (!$p404) {
	Merakcamacol::get_instance()->render('templates/page404.twig', $context);
} else {
	$context['post'] = $p404;
	if ($p404->_wp_page_template != '') {
		global $post;
		$post = $p404;
		require $p404->_wp_page_template;
	} else {
		Merakcamacol::get_instance()->render(['templates/page-' . $p404->post_name . '.twig', 'templates/page.twig'], $context);
	}
}
