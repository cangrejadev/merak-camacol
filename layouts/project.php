<?php
/* Template Name: Project layout
 * Template Post Type: page
 * Design to display project.
 */

 use Merakcamacol\Init as Merakcamacol;
 use Timber\Timber;
 
 $context         = Timber::context();
 $post            = Timber::get_post();
 $context['post'] = $post;
 
 if (post_password_required($post->ID)) {
	 Merakcamacol::get_instance()->render('components/single-password.twig', $context);
 } else {
	 Merakcamacol::get_instance()->render(['templates/layout-hero.twig'], $context);
 }
