<?php
/**
 * Plantilla para página sencilla de autor.
 */
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;

$context          = Timber::context();
$context['posts'] = Timber::get_posts($wp_query);
if (isset($wp_query->query_vars['author'])) {
	$author            = Timber::get_user($wp_query->query_vars['author']);
	$context['author'] = $author;
	$context['title']  = 'Author Archives: ' . $author->name();
}
$templates = ['templates/archive.twig', 'templates/index.twig'];
Merakcamacol::get_instance()->render($templates, $context);
