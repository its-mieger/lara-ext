<?php
	/**
	 * A helper file for Laravel 5, to provide autocomplete information to your IDE
	 *
	 */

	namespace {
		exit("This file should not be included, only analyzed by your IDE");
	}

	namespace Illuminate\Support {

		use ArrayAccess;
		use Generator;
		use Iterator;
		use IteratorAggregate;
		use Traversable;

		class Collection
		{

			/**
			 * Get the items in the collection that are not present in the given items. It uses a custom comparator function
			 *
			 * @param mixed $items The items
			 * @param callable $compareFn The comparator function
			 * @return static
			 */
			public function diffBy($items, callable $compareFn) {

			}

			/**
			 * Get the items in the collection whose keys and values are not present in the given items. It uses custom comparator functions
			 *
			 * @param mixed $items The items
			 * @param callable $valueCompareFunc The value compare function
			 * @param callable|null $keyCompareFunc The key compare function. If not passed no custom function is used for key comparision
			 * @return static
			 */
			public function diffAssocBy($items, callable $valueCompareFunc, callable $keyCompareFunc = null) {

			}

			/**
			 * Get the items in the collection values are not present in the given items. It uses ITSMieger object comparision
			 * @param mixed $items The items
			 * @return static
			 */
			public function diffObj($items) {

			}

			/**
			 * Get the items in the collection values are not present in the given items. It uses ITSMieger object comparision
			 * @param mixed $items The items
			 * @param callable|null $keyCompareFunc The key compare function. If not passed no custom function is used for key comparision
			 * @return static
			 */
			public function diffAssocObj($items, callable $keyCompareFunc = null) {

			}

			/**
			 * Sort the collection using given comparator function
			 * @param callable $compareFn The comparator function
			 * @param bool $keepKeys True to maintain keys
			 * @param bool $desc True to sort descending. Else false.
			 * @return static
			 */
			public function sortCallback(callable $compareFn, $keepKeys = false, $desc = false) {

			}

			/**
			 * Sort the collection in descending order using given comparator function
			 * @param callable $compareFn The comparator function
			 * @param bool $keepKeys True to maintain keys
			 * @return static
			 */
			public function sortCallbackDesc(callable $compareFn, $keepKeys = false) {

			}


			/**
			 * Sort the collection using ITSMieger object comparision
			 * @param bool $desc True to sort descending. Else false.
			 * @param bool $keepKeys True to maintain keys
			 * @return static
			 */
			public function sortObj($keepKeys = false, $desc = false) {

			}

			/**
			 * Sort the collection in descending order using ITSMieger object comparision
			 * @param bool $keepKeys True to maintain keys
			 * @return static
			 */
			public function sortObjDesc($keepKeys = false) {

			}

			/**
			 * Checks if the collection holds the same values as the given items. It uses ITSMieger object comparision
			 *
			 * If differences are detected, the following comparision rules are applied in given order:
			 * 1. The set with less items is considered to be the lesser one
			 * 2. The set missing the greater item is considered to be the lesser one
			 * 3. The set missing more items than the other is considered to be the lesser one
			 *
			 * @param mixed $items The items
			 * @return int 1 if a value of the collection is not in given items. -1 if a value in the given items is not in collection. Else 0
			 */
			public function compareToValues($items) {

			}

			/**
			 * Checks if the collection holds the same key-value pairs as the given items. It uses ITSMieger object comparision
			 *
			 * If differences are detected, the following comparision rules are applied in given order:
			 * 1. The set with less items is considered to be the lesser one
			 * 2. The set missing the greater item is considered to be the lesser one
			 * 3. The set missing the greater key is considered to be the lesser one
			 * 4. The set missing more items is considered to be the lesser one
			 *
			 * @param mixed $items The items
			 * @return int 1 if a value of the collection is not in given items. -1 if a value in the given items is not in collection. Else 0
			 */
			public function compareToValuesAssoc($items) {

			}

			/**
			 * Gets the item which returns the maximum value for specified callback. It uses ITSMieger object comparision to compare callback values
			 * @param callable|string $callback
			 * @return mixed The item with maximum value
			 */
			public function maxBy($callback) {

			}

			/**
			 * Gets the item which returns the minimum value for specified callback. It uses ITSMieger object comparision to compare callback values
			 * @param callable|string $callback
			 * @return mixed The item with minimum value
			 */
			public function minBy($callback) {

			}
		}

		class Str {
			/**
			 * Extracts values using a given delimiter. The resulting array will always have the given length
			 * @param string $haystack The haystack
			 * @param string $delimiter The delimiter
			 * @param int $length The number of elements in the return array. If positive the resulting array is left aligned. If negative the resulting array is right aligned.
			 * @param null $defaultValue The default value for the array elements
			 * @param bool $appendOverflowing If true, any overflowing values are appended to the last value using the original delimiter
			 * @return string[] The return array
			 */
			public static function extract($haystack, $delimiter, $length, $defaultValue = null, $appendOverflowing = false) : array {

			}

		}

		/**
		 * Creates a buffer which is automatically flushed if it is full.
		 * @param int $size The buffer size. You may pass 0 or a negative value if the buffer should not be flushed automatically.
		 * @param callable $flushHandler Handler function which will be called on flush and receive the buffer contents as first parameter
		 * @param callable $collectionResolver Resolver for the underlying collection. This is called each time an empty collection is initialized and must return an
		 * empty collection instance. If omitted an array is used as underlying collection. Also a function name or a class name which is resolved via service container may be passed.
		 * @return \ItsMieger\LaravelExt\Util\FlushingBuffer
		 */
		function buffer($size, callable $flushHandler, $collectionResolver = null) { }

		/**
		 * Calls the given handler with chunks of the provided data. The last chunk's size may by less than the
		 * specified chunk size. This function "streams" the data using a flushing buffer internally.
		 * @param \Iterator|IteratorAggregate|ArrayAccess|[]|\Closure $data The source data
		 * @param int $size The chunk size
		 * @param callable $flushHandler Handler function which will receive the chunk data
		 * @param callable|string $collectionResolver Resolver for the underlying collection. This is called each time an empty collection is initialized and must return an
		 * empty collection instance. If omitted an array is used as underlying collection. Also a function name or a class name which is resolved via service container may be passed.
		 */
		function chunked($data, $size, callable $flushHandler, $collectionResolver = null) { }

		/**
		 * Calls the given callback with values of left and right collection joined by given field values. NULL values are never treated as equal.
		 * @param \Closure|Iterator|IteratorAggregate|[] $left The left side collection
		 * @param string $leftField The left side field to use for joining
		 * @param \Closure|Iterator|IteratorAggregate|[]|string|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $right The right side collection. This may also be
		 * a model class name or query builder instance for which queries are automatically executed
		 * @param string $rightField The right field to use for joining
		 * @param callable $callback The callback to be called with joined items. Will receive the left side item as first argument and the right side item as second
		 * @param bool $all True to return all matching right side items for each left side item. Else only the first matching item will be returned
		 */
		function joined($left, $leftField, $right, $rightField, callable $callback, $all = false) { }

		/**
		 * Iterates the given cursor and gets and generates an item for each element using "dot" notation
		 * @param Traversable|Iterator|IteratorAggregate|[]|ArrayAccess|Generator|function $cursor The data
		 * @param string|\Closure $field The field in "dot" notation or a closure returning the field value
		 * @param mixed $default The default value. This has no effect if a closure is passed as field
		 * @return Generator The generator
		 */
		function cursor_get($cursor, $field, $default = null) { }

		/**
		 * Gets the model's table name
		 * @param string|\Illuminate\Database\Eloquent\Model The model
		 * @return string The table name
		 */
		function db_table($model) { }

		/**
		 * Gets the model's table name for use in raw SQL queries
		 * @param string|\Illuminate\Database\Eloquent\Model The model
		 * @return string The table name wrapped i
		 */
		function db_table_raw($model) { }

		/**
		 * Gets the model's connection
		 * @param string|\Illuminate\Database\Eloquent\Model $model The model
		 * @return \Illuminate\Database\Connection The connection
		 */
		function db_connection($model) { }

		/**
		 * Gets the model's field name prefixed with the table name
		 * @param string|\Illuminate\Database\Eloquent\Model $model The model
		 * @param string $field The model field name
		 * @return string The model field, eg. "table.field"
		 */
		function db_field($model, string $field) { }

		/**
		 * Gets the model's field name prefixed with the table name for use in raw SQL expressions
		 * @param string|\Illuminate\Database\Eloquent\Model $model The model
		 * @param string $field The model field name
		 * @return string The model field, eg. "`table`.`field`"
		 */
		function db_field_raw($model, string $field) { }
	}