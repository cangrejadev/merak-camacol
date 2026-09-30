<?php
/**
 * Plantilla para visualizar páginas simples.
 */
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;
use Timber\Post;

$context         = Timber::context();
$post            = Timber::get_post();
$a               = $post->heading_desktop;
$context['post'] = $post;

if (post_password_required($post->ID)) {
	Merakcamacol::get_instance()->render('components/single-password.twig', $context);
} else {
	Merakcamacol::get_instance()->render(['templates/page-' . $post->post_name . '.twig', 'templates/page.twig'], $context);
}
