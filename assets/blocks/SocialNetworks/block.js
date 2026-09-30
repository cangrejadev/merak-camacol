const { __ } = wp.i18n;
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from "@wordpress/block-editor";
import ServerSideRender from '@wordpress/server-side-render';

registerBlockType(mrk_block_var.prefix + '/socialnetworks', {
	title: __('Social Networks', mrk_block_var.domain),
	icon: 'share',
	category: 'camacol',
	keywords: [
		__('Social', mrk_block_var.domain),
		__('Network', mrk_block_var.domain),
	],
	
	edit: (props) => {
		return <div className={props.className}>
			<ServerSideRender
				block={mrk_block_var.prefix + '/socialnetworks'}
				attributes={props.attributes}
			/>
		</div>;
	},
	save: (props) => {
		// Para mantener la visual del sitio en caso de que este plugin
		// sea desactivado se recomienda incluir el HTML.
		// Puede ser similar al twig. Lo importante es que incluya el
		// div de apertura con la clase y un contenido real.
		const blockProps = useBlockProps.save();
		return <div {...blockProps}>
			<p>Social networks</p>
		</div>;
	},
});

// Evitar guardar dos veces
// var socialnetworks_save_twice = false;

// Acciones al guardar 
// subscribe(() => {
// 	const isSavingPost = select('core/editor').isSavingPost();
// 	const isAutosavingPost = select('core/editor').isAutosavingPost();

// 	// Si es una acción de guardar y no un autosave
// 	if (!isAutosavingPost && isSavingPost) {
// 		// Evitamos doble llamado
// 		if (socialnetworks_save_twice) {
// 			// Obtener el contenido y extraer los bloques
// 			const content = wp.blocks.parse(select('core/editor').getEditedPostContent('format'));
// 			content.forEach(element => {
// 				// Si es un bloque basic
// 				if (element.name == mrk_block_var.prefix + '/socialnetworks') {
// 					// Ejecutar las acciones
// 				}
// 			})
// 		}
// 		socialnetworks_save_twice = !socialnetworks_save_twice;
// 		return;
// 	}
// });
