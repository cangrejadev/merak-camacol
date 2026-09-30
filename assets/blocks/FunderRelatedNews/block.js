const { __ } = wp.i18n;
import { PanelBody, TextControl, Toolbar } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, BlockControls, useBlockProps } from "@wordpress/block-editor";
import { RichText } from '@wordpress/block-editor';
import { useState, } from '@wordpress/element'
import ServerSideRender from '@wordpress/server-side-render';
// import { select, subscribe } from '@wordpress/data'; // Requeridos para el evento al guardar el bloque

registerBlockType(mrk_block_var.prefix + '/funderrelatednews', {
	title: __('Funder Related News', mrk_block_var.domain),
	icon: 'archive',
	category: 'camacol',
	keywords: [
		__('News', mrk_block_var.domain),
		__('Related', mrk_block_var.domain),
	],
	attributes: {},
	supports: {
		anchor: true,
	},
	edit: (props) => {
		return <div className={props.className}>
			<p>{__('Related news', mrk_block_var.domain)}</p>
		</div>;
	},
	save: (props) => {
		// Para mantener la visual del sitio en caso de que este plugin
		// sea desactivado se recomienda incluir el HTML.
		// Puede ser similar al twig. Lo importante es que incluya el
		// div de apertura con la clase y un contenido real.
		const blockProps = useBlockProps.save();
		return <div {...blockProps}>
			<p>Related news</p>
		</div>;
	},
});
