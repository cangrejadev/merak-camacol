const { __ } = wp.i18n;
import { PanelBody, CheckboxControl, Toolbar } from '@wordpress/components';
import { registerBlockType, createBlock } from '@wordpress/blocks';
import { InspectorControls, InnerBlocks, BlockControls, useBlockProps } from "@wordpress/block-editor";
import { RichText } from '@wordpress/block-editor';

registerBlockType(mrk_block_var.prefix + '/accordion', {
	title: __('Accordion', mrk_block_var.domain),
	icon: 'editor-kitchensink',
	category: 'camacol',
	parent: [mrk_block_var.prefix + '/accordions'],
	keywords: [
		__('Accordion', mrk_block_var.domain),
		__('Commuter', mrk_block_var.domain),
	],
	attributes: {
		title: {
			type: 'string',
		},
		subtitle: {
			type: 'string',
		},
		unfolded: {
			type: 'bool',
			default: false
		}
	},
	edit: (props) => {
		const { clientId } = props;
		var { title, subtitle, unfolded } = props.attributes;
		const { select, dispatch } = wp.data;
		const parentBlockId = select('core/block-editor').getBlockHierarchyRootClientId(clientId);
		const parentBlock = select('core/block-editor').getBlocksByClientId(parentBlockId)[0];
		const blockProps = useBlockProps.save();

		// Agregar elemento
		const addElement = function () {
			// Crear bloque
			const block = createBlock(mrk_block_var.prefix + '/accordion');
			const siblingBlocks = parentBlock.innerBlocks;
			let pos = 0;
			siblingBlocks.forEach((el, index) => {
				if (el.clientId == clientId) pos = index;
			});

			// Insert the block
			dispatch('core/block-editor').insertBlocks(block, pos + 1, parentBlockId);

		};

		// Barra de botones
		const toolbarControls = [
			{
				icon: 'plus-alt',
				label: __('Add accordion', mrk_block_var.domain),
				onClick: () => addElement()
			}
		];

		const template = [["core/paragraph", { content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi consequat bibendum justo, vel commodo massa congue id. In eget nisi tristique, porta est vitae, tincidunt dui. Sed ultrices faucibus nunc, auctor commodo sapien sagittis eu. Quisque feugiat est pellentesque risus sodales, vehicula tincidunt elit faucibus." }]];
		const inspectorcontrol = <InspectorControls>
			<PanelBody
				title={__('Accordion', mrk_block_var.domain)}
				initialOpen={true}
			>
				<CheckboxControl
					label={__('Start unfolded', mrk_block_var.domain)}
					checked={unfolded}
					onChange={
						function (newUnfolded) {
							props.setAttributes({
								unfolded: newUnfolded
							});
						}
					}
				/>
			</PanelBody>
		</InspectorControls>;

		return <>
			{inspectorcontrol}
			<BlockControls>
				<Toolbar controls={toolbarControls} />
			</BlockControls>
			<div data-unfolded={unfolded}>
				<RichText
					className='title'
					tagName='h3'
					value={title}
					placeholder={__('Title', mrk_block_var.domain)}
					onChange={(newValue) => {
						props.setAttributes({ title: newValue });
					}}
				/>
				<RichText
					className='subtitle'
					tagName='h4'
					value={subtitle}
					placeholder={__('Subtitle', mrk_block_var.domain)}
					onChange={(newValue) => {
						props.setAttributes({ subtitle: newValue });
					}}
				/>
				<div className="text">
					<InnerBlocks template={template} />
				</div>
			</div>
		</>;
	},
	save: (props) => {
		const { clientId } = props;
		var { title, subtitle, unfolded } = props.attributes;
		const blockProps = useBlockProps.save();
		const lbl_subtitle = (subtitle != '' && subtitle !== undefined) ? <span className='subtitle'>{subtitle}</span> : '';

		return <div {...blockProps}
			data-id={clientId}
			data-unfolded={(unfolded) ? 'true' : 'false'}>
			<div className="accordion__header">
				<h3 className="accordion__title">
					{title}
					{lbl_subtitle}
					<i class="fa-solid fa-chevron-right"></i>
				</h3>
			</div>
			<div
				data-id={clientId}
				className="accordion__content">
				<InnerBlocks.Content />
			</div>
		</div>
	}
});
