<?php
/**
 * Plantilla para mostrar entradas individuales
 */
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;

$context         = Timber::context();
$post            = Timber::get_post();
$context['post'] = $post;

// Primeras 4 entradas del blog
$args = $args = [
	'post_type' => 'post',
	'posts_per_page' => 4,
	'p2p'       => [
		'relation' => 'AND',
		[
			'type' => 'post_to_supplier',
			'to'   => $post->id,
		],
	],
];
$context['blog'] = Timber::get_posts($args);

if (post_password_required($post->ID)) {
	Merakcamacol::get_instance()->render('components/single-password.twig', $context);
} else {
	Merakcamacol::get_instance()->render(['templates/templates/single-' . $post->ID . '.twig', 'templates/templates/single-' . $post->post_type . '.twig', 'templates/templates/single.twig'], $context);
}
