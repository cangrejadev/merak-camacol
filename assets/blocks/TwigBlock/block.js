const { __ } = wp.i18n;
import { Toolbar, Button, Placeholder } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import { PlainText, useBlockProps, BlockControls } from "@wordpress/block-editor";
import { useState } from '@wordpress/element'
import ServerSideRender from '@wordpress/server-side-render';

const symfonyLogo = wp.element.createElement(
	"svg",
	{
		width: 20,
		height: 20,
		viewBox: "0 0 20 20.000177"
	},
	wp.element.createElement("g", {
		style: {
			transform: "matrix(0.17830874,0,0,0.17830874,-7.8074265,-5.0584406)"
		},
	},
		[
			wp.element.createElement("path", {
				style: {
					strokeWidth: 2,
					strokeOpacity: 1,
				},
				d:
					"m 124.389,49.119 c -5.697,0.195 -10.67,3.34 -14.373,7.68 -4.1,4.765 -6.824,10.411 -8.791,16.18 -3.514,-2.882 -6.223,-6.611 -11.864,-8.233 -4.359,-1.253 -8.936,-0.737 -13.146,2.399 -1.992,1.489 -3.367,3.738 -4.02,5.859 -1.692,5.498 1.778,10.396 3.354,12.151 l 3.447,3.691 c 0.709,0.725 2.422,2.613 1.584,5.319 -0.9,2.947 -4.451,4.85 -8.092,3.731 -1.627,-0.499 -3.963,-1.71 -3.439,-3.413 0.215,-0.699 0.715,-1.225 0.984,-1.821 0.244,-0.521 0.363,-0.907 0.438,-1.14 0.665,-2.169 -0.245,-4.994 -2.57,-5.713 -2.171,-0.666 -4.391,-0.138 -5.252,2.655 -0.977,3.174 0.543,8.935 8.681,11.441 9.535,2.935 17.597,-2.259 18.742,-9.026 0.721,-4.239 -1.195,-7.392 -4.701,-11.441 l -2.859,-3.163 c -1.73,-1.729 -2.324,-4.677 -0.533,-6.942 1.512,-1.912 3.664,-2.726 7.191,-1.768 5.15,1.396 7.443,4.969 11.271,7.851 -1.578,5.187 -2.613,10.392 -3.547,15.059 l -0.574,3.481 c -2.736,14.352 -4.826,22.235 -10.256,26.76 -1.094,0.779 -2.658,1.943 -5.014,2.027 -1.238,0.037 -1.637,-0.814 -1.654,-1.186 -0.027,-0.865 0.703,-1.264 1.188,-1.652 0.727,-0.396 1.824,-1.053 1.748,-3.156 -0.078,-2.484 -2.137,-4.639 -5.111,-4.541 -2.229,0.075 -5.625,2.171 -5.497,6.011 0.131,3.967 3.827,6.938 9.401,6.75 2.979,-0.102 9.633,-1.312 16.188,-9.105 7.631,-8.935 9.766,-19.175 11.372,-26.671 l 1.793,-9.897 c 0.992,0.119 2.059,0.2 3.217,0.228 9.504,0.201 14.256,-4.72 14.328,-8.302 0.049,-2.167 -1.42,-4.302 -3.479,-4.251 -1.471,0.041 -3.32,1.022 -3.762,3.057 -0.436,1.995 3.023,3.798 0.32,5.553 -1.92,1.242 -5.361,2.116 -10.209,1.407 l 0.881,-4.872 c 1.799,-9.238 4.018,-20.6 12.436,-20.878 0.615,-0.029 2.857,0.026 2.91,1.512 0.014,0.493 -0.109,0.623 -0.689,1.757 -0.592,0.884 -0.814,1.64 -0.785,2.504 0.08,2.356 1.873,3.908 4.471,3.818 3.473,-0.116 4.469,-3.496 4.412,-5.233 -0.146,-4.085 -4.449,-6.665 -10.14,-6.477 z"
			})
		]
	)
);

registerBlockType(mrk_block_var.prefix + '/twigblock', {
	title: __('Twig Block', mrk_block_var.domain),
	icon: { src: symfonyLogo },
	category: 'camacol',
	keywords: [
		__('Twig', mrk_block_var.domain),
		__('Format', mrk_block_var.domain),
		__('Label', mrk_block_var.domain),
	],
	attributes: {
		text: {
			type: 'string',
			default: ''
		}
	},
	supports: {
		anchor: true,
		typography: {
			textAlign: true,
		},
	},
	edit: (props) => {
		var {
			text
		} = props.attributes;
		const [preview, setPreview] = useState(false);

		// Barra de botones
		const toolbarControls = <BlockControls>
			<Toolbar>
				<Button
					isPressed={!preview}
					onClick={() => { setPreview(false) }}
				>{__('Twig', mrk_block_var.domain)}</Button>
				<Button
					isPressed={preview}
					onClick={() => { setPreview(true) }}
				>{__('Preview', mrk_block_var.domain)}</Button>
			</Toolbar>
		</BlockControls>;

		if (preview) {
			return <>
				{toolbarControls}
				<ServerSideRender
					block={mrk_block_var.prefix + '/twigblock'}
					attributes={props.attributes}
				/>
			</>;
		}

		return <>
			{toolbarControls}
			<Placeholder
				icon={symfonyLogo}
				label={__('Twig code', mrk_block_var.domain)}>
				<PlainText
					value={text}
					placeholder={__('Write here the twig code', mrk_block_var.domain)}
					onChange={(newValue) => {
						props.setAttributes({ text: newValue });
					}}
				/>
			</Placeholder>
		</>;
	},
	save: (props) => {
		// Para mantener la visual del sitio en caso de que este plugin
		// sea desactivado se recomienda incluir el HTML.
		// Puede ser similar al twig. Lo importante es que incluya el
		// div de apertura con la clase y un contenido real.
		const blockProps = useBlockProps.save();
		return <div {...blockProps}>
			{props.attributes.text}
		</div>;
	},
});
