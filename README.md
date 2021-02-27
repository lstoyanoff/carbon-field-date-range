# Carbon Field Date Range

Provides the ability to select a date range and saves it in the database in custom format.

## Usage

```php
Field::make( 'date_range', 'date', __( 'Select Date Range', 'crb' ) ),
```

## Value Format

```php
array(
	'value' => '2021-02-28 to 2021-03-03',
	'from'  => '2021-02-28',
	'to'    => '2021-03-03',
)
```
