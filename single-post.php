<?php
/**
 * Plantilla para mostrar entradas individuales
 */
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;

$context         = Timber::context();
$post            = Timber::get_post();
$context['post'] = $post;

// Incluir las categorías en el body_class
$clases = explode(' ', $context['body_class']);
foreach ($post->categories() as $item) {
	$clases[] = sprintf('cat-%s', $item->slug);
}
array_unique($clases);
$context['body_class'] = implode(' ', $clases);

// Entradas de la categoría
$args = [
	'posts_per_page' => 4,
	'paged' => get_query_var('page', 1)
];
$context['posts'] = Timber::get_posts($args);

if (post_password_required($post->ID)) {
	Merakcamacol::get_instance()->render('components/single-password.twig', $context);
} else {
	Merakcamacol::get_instance()->render(['templates/single-' . $post->ID . '.twig', 'templates/single-' . $post->post_type . '.twig', 'templates/single.twig'], $context);
}
