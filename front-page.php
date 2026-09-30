<?php
/**
 * Plantilla de la página de inicio.
 */
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;
use Timber\Post;

$context         = Timber::context();
$post            = Timber::get_post();
$context['post'] = $post;
Merakcamacol::get_instance()->render(['templates/front-page.twig', 'templates/page.twig'], $context);
