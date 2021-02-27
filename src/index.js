/**
 * External dependencies.
 */
import { registerFieldType } from '@carbon-fields/core';

/**
 * Internal dependencies.
 */
import './style.scss';
import DateRangeField from './main';

registerFieldType( 'date_range', DateRangeField );
