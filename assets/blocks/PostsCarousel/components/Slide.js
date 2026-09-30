/**
 * WordPress dependencies
 */
import React from 'react';
import Select from 'react-select';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { Component } = wp.element;
import { Button } from '@wordpress/components';

const DEFAULT_MIN_ITEMS = 1;
const DEFAULT_MAX_ITEMS = 10;

export class Slide extends Component {
	constructor(props) {
		super(...arguments);
		this.props = props;

		this.state = {
			item: this.props.item,
			options: []
		};

		this.handleSelectChange = this.handleSelectChange.bind(this);
		this.getOptionsAsync = this.getOptionsAsync.bind(this);
		this.handleRemoveSlide = this.handleRemoveSlide.bind(this);
		this.handleMoveUpSlide = this.handleMoveUpSlide.bind(this);
		this.handleMoveDownSlide = this.handleMoveDownSlide.bind(this);

		this.getOptionsAsync('');
		if (this.state.item !== undefined) {
			this.handleSelectChange(this.props.item);
		}
	}

	getOptionsAsync(search) {
		var me = this;
		wp.apiFetch({
			path: "/" + mrk_block_var.prefix + "/v1/sugerencias?q=" + search
		}).then(data => {
			const posts = data.items;
			const answer = (posts === undefined) ? [] : posts.map((item) => {
				return {
					value: item.id,
					label: item.name
				}
			})

			if (me.state.item) {
				//Buscar si no estÃ¡ la opciÃ³n inicial
				var disponible = false;
				jQuery.each(answer, function (index, item) {
					if (item.value == me.state.item.value) {
						disponible = true;
					}
				});

				//Si no estÃ¡ disponible, anexarla al inicio
				if (!disponible) {
					answer.push(me.state.item);
				}
			}

			me.setState({
				options: answer
			})
		});
	}

	handleSelectChange(item) {
		const key = this.props.id;
		this.setState({
			item: item
		});

		this.props.onUpdateData(key, item);
	}

	handleRemoveSlide(e) {
		const key = this.props.id;
		this.props.onRemoveSlide(key);
	}

	handleMoveUpSlide(e) {
		const key = this.props.id;
		this.props.onMoveUpSlide(key);
	}

	handleMoveDownSlide(e) {
		const key = this.props.id;
		this.props.onMoveDownSlide(key);
	}

	render() {
		const options = this.state.options;
		const item = this.state.item;

		return <div class="slide">
			<Select className="select"
				value={item}
				placeholder={__('Select...', mrk_block_var.domain)}
				onChange={item => {
					this.handleSelectChange(item)
				}}
				options={options}
				onInputChange={(value) => {
					this.getOptionsAsync(value)
				}} />
			<Button
				icon="arrow-up-alt2"
				label={__('Move up', mrk_block_var.domain)}
				onClick={this.handleMoveUpSlide}
			/>
			<Button
				icon="arrow-down-alt2"
				label={__('Move down', mrk_block_var.domain)}
				onClick={this.handleMoveDownSlide}
			/>
			<Button
				icon="no"
				label={__('Remove slide', mrk_block_var.domain)}
				onClick={this.handleRemoveSlide}
			/>
		</div>;
	}
}

