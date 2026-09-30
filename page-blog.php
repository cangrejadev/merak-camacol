<?php
/**
 * Plantilla para mostrar entradas individuales
 */
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;

$context         = Timber::context();
$post            = Timber::get_post();
$context['post'] = $post;

$provider = get_query_var('supplier_slug');
$funder   = get_query_var('funder_slug');
$page     = get_query_var('paged', 1);
$page     = ($page == 0) ? 1 : $page;
$s        = $_GET['search_text'];

if(!empty($provider)) {
	$provider = get_page_by_path($provider, OBJECT, 'supplier');
	if(empty($provider)) {
		Merakcamacol::p404();
		exit();
	}
}

if(!empty($funder)) {
	$funder = get_page_by_path($funder, OBJECT, 'funder');
	if(empty($funder)) {
		Merakcamacol::p404();
		exit();
	}
}

if (post_password_required($post->ID)) {
	Merakcamacol::get_instance()->render('components/single-password.twig', $context);
} else {
	$to = false;
	$context['url'] = '/blog/';
	if(!empty($provider)) {
		$relationship   = 'post_to_supplier';
		$to             = $provider->ID;
		$context['url'] = '/blog/' . get_option('supplier_base', 'supplier') . '/' . $provider->post_name . '/';
	}
	if(!empty($funder)) {
		$relationship           = 'post_to_funder';
		$to                     = $funder->ID;
		$context['funder_slug'] = $funder->post_name;
		$context['url']         = '/blog/' . get_option('funder_base', 'funder') . '/' . $funder->post_name . '/';
	}

	// Posts
	if($to) {
		$args = [
			'post_type' => 'post',
			'paged'     => $page,
			'p2p'       => [
				'relation' => 'AND',
				[
					'type' => $relationship,
					'to'   => $to,
				],
			],
		];

	} else {
		$args = [
			'post_type' => 'post',
			'paged'     => $page,
		];
	}

	//¿Hay texto de búsqueda?
	if($s) {
		$args['s']              = $s;
		$context['search_text'] = $s;
	}
	$context['blog_page'] = true;

	$context['posts'] = Timber::get_posts($args);

	Merakcamacol::get_instance()->render(['templates/blog.twig'], $context);
}
