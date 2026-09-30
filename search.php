<?php
/**
 * Página de resultados
 */
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;

$templates = ['templates/pages/search.twig'];

$context          = Timber::context();
$context['title'] = sprintf(__('Search results for %s', MRK_D), get_search_query());
$context['search'] = get_search_query();
$context['posts'] = Timber::get_posts($wp_query);

Merakcamacol::get_instance()->render($templates, $context);
