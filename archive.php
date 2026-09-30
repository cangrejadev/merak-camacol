<?php
/**
 * Plantilla de archivo
 */
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;
use Timber\Term;

$templates = ['templates/archive.twig', 'templates/index.twig'];

$context = Timber::context();

//Set term into context
$context['term'] = false;
if ($term = get_queried_object()) {
	$context['term'] = Timber::get_term($term);
}

$context['title'] = __('Archive', MRK_D);
if (is_day()) {
	$context['title'] = __('Archive', MRK_D) . ': ' . get_the_date('D M Y');
} elseif (is_month()) {
	$context['title'] = __('Archive', MRK_D) . ': ' . get_the_date('M Y');
} elseif (is_year()) {
	$context['title'] = __('Archive', MRK_D) . ': ' . get_the_date('Y');
} elseif (is_tag()) {
	$context['title'] = single_tag_title('', false);
} elseif (is_category()) {
	$context['title'] = single_cat_title('', false);
	array_unshift($templates, 'archive-' . $context['term']->slug . '.twig');
} elseif (is_tax()) {
	$context['title'] = single_term_title('', false);
	array_unshift($templates, 'taxonomy-' . $context['term']->slug . '.twig');
} elseif (is_post_type_archive()) {
	$context['title'] = post_type_archive_title('', false);
	array_unshift($templates, 'archive-' . get_post_type() . '.twig');
}

$context['posts'] = Timber::get_posts($wp_query);

Merakcamacol::get_instance()->render($templates, $context);
