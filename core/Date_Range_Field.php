<?php
namespace Carbon_Field_Date_Range;

use Carbon_Fields\Field\Field;
use Carbon_Fields\Value_Set\Value_Set;

/**
 * Date picker field class.
 */
class Date_Range_Field extends Field {

	/**
	 * {@inheritDoc}
	 */
	protected $allowed_attributes = array( 'placeholder' );

	/**
	 * The storage format for use in PHP
	 *
	 * @var string
	 */
	protected $storage_format = 'Y-m-d';

	/**
	 * The expected input format for use in PHP
	 *
	 * @var string
	 */
	protected $input_format_php = 'Y-m-d';

	/**
	 * The expected input format for use in Flatpickr JS
	 *
	 * @var string
	 */
	protected $input_format_js = 'Y-m-d';

	/**
	 * Picker options.
	 *
	 * @var array
	 */
	protected $picker_options = array(
		'allowInput' => true,
		'mode'       => 'range',
	);

	public function __construct( $type, $name, $label ) {
		$this->set_value_set( new Value_Set( Value_Set::TYPE_MULTIPLE_PROPERTIES, array( 'from' => '', 'to' => '' ) ) );
		parent::__construct( $type, $name, $label );
	}

	/**
	 * Prepare the field type for use.
	 * Called once per field type when activated.
	 *
	 * @static
	 * @access public
	 *
	 * @return void
	 */
	public static function field_type_activated() {
		$dir = \Carbon_Field_Date_Range\DIR . '/languages/';
		$locale = get_locale();
		$path = $dir . $locale . '.mo';
		load_textdomain( 'carbon-field-date-range', $path );
	}

	/**
	 * Enqueue scripts and styles in admin.
	 * Called once per field type.
	 *
	 * @static
	 * @access public
	 *
	 * @return void
	 */
	public static function admin_enqueue_scripts() {
		$root_uri = \Carbon_Fields\Carbon_Fields::directory_to_url( \Carbon_Field_Date_Range\DIR );

		// Enqueue field styles.
		wp_enqueue_style( 'carbon-field-date-range', $root_uri . '/build/bundle.css' );

		// Enqueue field scripts.
		wp_enqueue_script( 'carbon-field-date-range', $root_uri . '/build/bundle.js', array( 'carbon-fields-core' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	public function set_value_from_input( $input ) {
		if ( ! isset( $input[ $this->get_name() ] ) ) {
			$this->clear_value();
			return $this;
		}

		$value_set = array(
			'from' => '',
			'to'   => '',
		);
		$dates = explode( 'to', $input[ $this->get_name() ] );

		$from_date = \DateTime::createFromFormat( $this->input_format_php, trim( $dates[0] ) );
		$value_set['from'] = is_a( $from_date, 'DateTime' ) ? $from_date->format( $this->storage_format ) : '';
		if ( isset( $dates[1] ) ) {
			$to_date = \DateTime::createFromFormat( $this->input_format_php, trim( $dates[1] ) );
			$value_set['to'] = is_a( $to_date, 'DateTime' ) ? $to_date->format( $this->storage_format ) : '';
		}

		$value_set[ Value_Set::VALUE_PROPERTY ] = $value_set['from'];
		if ( ! empty( $value_set['to'] ) ) {
			$value_set[ Value_Set::VALUE_PROPERTY ] .= ' to ' . $value_set['to'];
		}

		$this->set_value( $value_set );

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function to_json( $load ) {
		$field_data = parent::to_json( $load );

		$value_set = $this->get_value();
		$value     = '';
		if ( ! empty( $value_set['from'] ) || ! empty( $value_set['to'] ) ) {
			$from_date = \DateTime::createFromFormat( $this->storage_format, $value_set['from'] );
			$value = is_a( $from_date, 'DateTime' ) ? $from_date->format( $this->input_format_php ) : '';
			if ( isset( $value_set['to'] ) ) {
				$to_date = \DateTime::createFromFormat( $this->storage_format, $value_set['to'] );
				$value .= is_a( $to_date, 'DateTime' ) ? ' to ' . $to_date->format( $this->input_format_php ) : '';
			}
		}

		$field_data = array_merge( $field_data, array(
			'value' => $value,
			'storage_format' => $this->get_storage_format(),
			'picker_options' => array_merge( $this->get_picker_options(), array(
				'dateFormat' => $this->input_format_js,
			) ),
		) );

		return $field_data;
	}

	/**
	 * Get storage format
	 *
	 * @return string
	 */
	public function get_storage_format() {
		return $this->storage_format;
	}

	/**
	 * Set storage format
	 *
	 * @param  string $storage_format
	 * @return self   $this
	 */
	public function set_storage_format( $storage_format ) {
		$this->storage_format = $storage_format;
		return $this;
	}

	/**
	 * Get the expected input format in php and js variants
	 *
	 * @return array
	 */
	public function get_input_format( $php_format, $js_format ) {
		$this->input_format_php = $php_format;
		$this->input_format_js = $js_format;
		return $this;
	}

	/**
	 * Set a format for use on the front-end in both PHP and Flatpickr formats
	 * The formats should produce identical results (i.e. they are translations of each other)
	 *
	 * @param  string $php_format
	 * @param  string $js_format
	 * @return self   $this
	 */
	public function set_input_format( $php_format, $js_format ) {
		$this->input_format_php = $php_format;
		$this->input_format_js = $js_format;
		return $this;
	}

	/**
	 * Returns the picker options.
	 *
	 * @return array
	 */
	public function get_picker_options() {
		return $this->picker_options;
	}

	/**
	 * Set datepicker options
	 *
	 * @param  array $options
	 * @return self  $this
	 */
	public function set_picker_options( $options ) {
		$this->picker_options = array_replace( $this->picker_options, $options );
		return $this;
	}
}
