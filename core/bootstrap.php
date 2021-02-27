<?php

use Carbon_Fields\Carbon_Fields;
use Carbon_Field_Date_Range\Date_Range_Field;

define( 'Carbon_Field_Date_Range\\VERSION', '1.0.0' );
define( 'Carbon_Field_Date_Range\\DIR', dirname( __DIR__ ) );

Carbon_Fields::extend( Date_Range_Field::class, function( $container ) {
	return new Date_Range_Field(
		$container['arguments']['type'],
		$container['arguments']['name'],
		$container['arguments']['label']
	);
} );
