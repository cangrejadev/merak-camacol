const { __ } = wp.i18n; // Import __() from wp.i18n
import { Button, Placeholder, ToggleControl, TextControl, PanelBody } from '@wordpress/components';
import { InspectorControls } from "@wordpress/block-editor";
import { registerBlockType } from '@wordpress/blocks';

import ServerSideRender from '@wordpress/server-side-render';

import { arrayMoveImmutable } from 'array-move';

import { Slide } from './components/Slide';

registerBlockType(mrk_block_var.prefix + '/postscarousel', {
	title: __('Posts carousel', mrk_block_var.domain),
	icon: 'image-flip-horizontal',
	category: 'camacol',
	keywords: [
		__('matrix', mrk_block_var.domain),
		__('grid', mrk_block_var.domain),
		__('slider', mrk_block_var.domain),
	],
	attributes: {
		slides: {
			type: 'array',
			default: []
		},
		columns: {
			type: 'number',
			default: 3
		},
		autoplay: {
			default: false,
			type: 'boolean'
		},
		nav: {
			default: false,
			type: 'boolean'
		},
		dots: {
			default: false,
			type: 'boolean'
		},
	},
	edit: (props) => {
		var { slides, columns, autoplay, nav, dots } = props.attributes;

		function setSlide(key, item) {
			const _slides = [];

			slides.forEach(function (slide, index) {
				if (slide.id == key) {
					slide.item = item;
					_slides.push(slide);
				} else {
					_slides.push(slide);
				}
			});
			props.setAttributes({ slides: _slides });
		}

		function addSlide(e) {
			const _slides = [...slides];

			_slides.push({
				id: Math.floor((Math.random() * 899999) + 100000),
				item: undefined,
			});
			props.setAttributes({ slides: _slides });
		}

		function removeSlide(key) {
			const _slides = [];
			slides.forEach(function (slide, index) {
				if (slide.id !== key) {
					_slides.push(slide);
				}
			});
			props.setAttributes({ slides: _slides });
		}

		function moveUpSlide(key) {
			let pos = false;
			slides.forEach(function (slide, index) {
				if (slide.id == key) {
					pos = index;
				}
			});

			if (pos >= 1) props.setAttributes({ slides: arrayMoveImmutable(slides, pos, pos - 1) });
		}

		function moveDownSlide(key) {
			let pos = false;
			slides.forEach(function (slide, index) {
				if (slide.id == key) {
					pos = index;
				}
			});

			if (pos < slides.length - 1) props.setAttributes({ slides: arrayMoveImmutable(slides, pos, pos + 1) });
		}

		// Caja lateral
		const inspectorcontrol = <InspectorControls>
			<PanelBody
				title={__('Posts carousel', mrk_block_var.domain)}
				initialOpen={true}
			>
				<TextControl
					label={__('Columns', mrk_block_var.domain)}
					type="number"
					min={1}
					max={8}
					value={columns}
					onChange={(newValue) => {
						props.setAttributes({ columns: parseInt(newValue) });
					}}
				/>
				<ToggleControl
					label={__("Autoplay", mrk_block_var.domain)}
					checked={autoplay}
					onChange={function (newValue) {
						props.setAttributes({
							autoplay: !autoplay
						});
					}}
				/>
				<ToggleControl
					label={__("Display arrows", mrk_block_var.domain)}
					checked={nav}
					onChange={function (newValue) {
						props.setAttributes({
							nav: !nav
						});
					}}
				/>
				<ToggleControl
					label={__("Display dots", mrk_block_var.domain)}
					checked={dots}
					onChange={function (newValue) {
						props.setAttributes({
							dots: !dots
						});
					}}
				/>
			</PanelBody>
		</InspectorControls>;

		var _slides = [];

		slides.forEach((slide) => {
			_slides.push(<li key={slide.id}>
				<Slide
					id={slide.id}
					item={slide.item}
					onUpdateData={setSlide}
					onRemoveSlide={removeSlide}
					onMoveUpSlide={moveUpSlide}
					onMoveDownSlide={moveDownSlide}
				/>
			</li>);
		});

		return <>
			{inspectorcontrol}
			<Placeholder
				className={props.className}
				icon="images-alt2"
				label={__('Posts Carousel', mrk_block_var.domain)}>
				<div className='content'>
					<ul className="slides">
						{_slides}
					</ul>
					<Button className="add_slide"
						icon="plus"
						label={__('Add Slide', mrk_block_var.domain)}
						onClick={addSlide}
					/>
				</div>
			</Placeholder>
		</>;
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function (props) {
		const attributes = props.attributes;

		return <div className={props.className}></div>;
	}
});

// wp.blocks.registerBlockStyle(
// 	mrk_block_var.prefix + '/postscarousel',
// 	[
// 		{
// 			name: 'dos-columnas',
// 			label: __('Two columns', mrk_block_var.domain),
// 		}
// 	]
// );
