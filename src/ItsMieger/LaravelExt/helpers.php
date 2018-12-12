<?php
	if (!function_exists('buffer')) {
		/**
		 * Creates a buffer which is automatically flushed if it is full.
		 * @param int $size The buffer size. You may pass 0 or a negative value if the buffer should not be flushed automatically.
		 * @param callable $flushHandler Handler function which will be called on flush and receive the buffer contents as first parameter
		 * @param callable $collectionResolver Resolver for the underlying collection. This is called each time an empty collection is initialized and must return an
		 * empty collection instance. If omitted an array is used as underlying collection. Also a function name or a class name which is resolved via service container may be passed.
		 * @return \ItsMieger\LaravelExt\Util\FlushingBuffer
		 */
		function buffer($size, callable $flushHandler, $collectionResolver = null) {
			return app(\ItsMieger\LaravelExt\Util\FlushingBuffer::class, [
				'size'               => $size,
				'flushHandler'       => $flushHandler,
				'collectionResolver' => $collectionResolver,
			]);
		}
	}

	if (!function_exists('chunked')) {

		/**
		 * Calls the given handler with chunks of the provided data. The last chunk's size may by less than the
		 * specified chunk size. This function "streams" the data using a flushing buffer internally.
		 * @param \Iterator|IteratorAggregate|ArrayAccess|[]|\Closure $data The source data
		 * @param int $size The chunk size
		 * @param callable $flushHandler Handler function which will receive the chunk data
		 * @param callable|string $collectionResolver Resolver for the underlying collection. This is called each time an empty collection is initialized and must return an
		 * empty collection instance. If omitted an array is used as underlying collection. Also a function name or a class name which is resolved via service container may be passed.
		 */
		function chunked($data, $size, callable $flushHandler, $collectionResolver = null) {

			// setup buffer
			$buffer = app(\ItsMieger\LaravelExt\Util\FlushingBuffer::class, [
				'size'               => $size,
				'flushHandler'       => $flushHandler,
				'collectionResolver' => $collectionResolver,
			]);

			// "stream" to buffer
			foreach(value($data) as $curr) {
				$buffer->add($curr);
			}

			// flush the rest
			$buffer->flush();
		}
	}

	if (!function_exists('chunked_generator')) {

		/**
		 * Returns the a generator yielding the generated items from the given callback. The callback receives the data in chunks of given size
		 * @param \Traversable|array|\Closure $data The data to split into chunks. May also be a closure returning the data
		 * @param int $size The buffer size. You may pass 0 or a negative value if the buffer should not be flushed automatically.
		 * @param callable $generator Handler function which will be called for each chunk. It will receive buffer contents as first parameter and is expected to return a generator.
		 * @param callable $collectionResolver Resolver for the underlying collection. This is called each time an empty collection is initialized and must return an
		 * empty collection instance. If omitted an array is used as underlying collection.
		 * @return Generator
		 */
		function chunked_generator($data, $size, callable $generator, $collectionResolver = null) {

			yield from (new \ItsMieger\LaravelExt\Util\ChunkedGenerator($data, $size, $generator, $collectionResolver))->consume();
		}
	}

	if (!function_exists('joined')) {

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
		function joined($left, $leftField, $right, $rightField, callable $callback, $all = false) {

			$canFlip = true;

			$left  = value($left);
			$right = value($right);

			// generators can not be read multiple times, so collect all items first
			if ($left instanceof Generator)
				$left = collect($left);


			// collect left field values
			$leftFieldValues = [];
			foreach ($left as $index => $curr) {
				$currVal = data_get($curr, $leftField) ;

				switch (gettype($currVal)) {
					case 'string':
					case 'integer':
						break;
					default:
						$canFlip = false;
				}

				$leftFieldValues[$index] = $currVal;
			}


			// retrieve right items
			if (is_string($right)) {
				$right = forward_static_call_array([$right, 'whereIn'], [$rightField, $leftFieldValues]);
			}
			else {
				// convert to collection so we can perform whereIn
				if (is_array($right) || $right instanceof Traversable)
					$right = collect($right);

				// call object function
				$right = call_user_func_array([$right, 'whereIn'], [$rightField, $leftFieldValues]);
			}

			// if query builder was returned, perform a query
			if ($right instanceof \Illuminate\Database\Query\Builder || $right instanceof \Illuminate\Database\Eloquent\Builder)
				$right = $right->get();


			// collect right field values
			$rightFieldValues = [];
			foreach ($right as $index => $curr) {
				$currVal = data_get($curr, $rightField) ;

				if ($currVal === null)
					continue;

				switch (gettype($currVal)) {
					case 'string':
					case 'integer':
						break;
					default:
						$canFlip = false;
				}


				$rightFieldValues[$index] = $currVal;
			}


			// execute callback with values
			if (!$all && $canFlip) {
				// if the right values array can be flipped and not all values should be passed, we can use faster method

				// flip (reversed to keep first item)
				$rightFieldValues = array_flip(array_reverse($rightFieldValues, true));

				foreach ($left as $index => $leftItem) {

					$leftValue = $leftFieldValues[$index];
					if ($leftValue !== null)
						$rightKey = $rightFieldValues[$leftValue] ?? null;
					else
						$rightKey = null;


					call_user_func_array($callback, [$leftItem, ($rightKey !== null ? $right[$rightKey] : null)] ?? null);
				}
			}
			else if ($all) {
				// here we return all right side items

				foreach ($left as $index => $leftItem) {
					$leftValue = $leftFieldValues[$index];

					if ($leftValue !== null) {

						// find all matching values from the right side
						$currRightFieldValues = array_filter($rightFieldValues, function ($rightValue) use ($leftValue) {
							return $rightValue == $leftValue;
						});
					}
					else {
						$currRightFieldValues = [];
					}

					if ($currRightFieldValues) {
						foreach ($currRightFieldValues as $currRightKey => $v) {
							call_user_func_array($callback, [$leftItem, $right[$currRightKey]]);
						}
					}
					else {
						// call at least once for left item
						call_user_func_array($callback, [$leftItem, null]);
					}
				}
			}
			else {
				// we only return the first right side item for each left side item

				foreach ($left as $index => $leftItem) {
					$leftValue = $leftFieldValues[$index];
					if ($leftValue !== null)
						$rightKey = array_search($leftFieldValues[$index], $rightFieldValues);
					else
						$rightKey = false;

					call_user_func_array($callback, [$leftItem, ($rightKey !== false ? $right[$rightKey] : null)]);
				}
			}

		}
	}

	if (!function_exists('cursor_get')) {

		/**
		 * Iterates the given cursor and gets and generates an item for each element using "dot" notation
		 * @param Traversable|Iterator|IteratorAggregate|[]|ArrayAccess|Generator|function $cursor The data
		 * @param string|\Closure $field The field in "dot" notation or a closure returning the field value
		 * @param mixed $default The default value. This has no effect if a closure is passed as field
		 * @return Generator The generator
		 */
		function cursor_get($cursor, $field, $default = null) {

			$fieldClosure = $field instanceof \Closure;

			// "stream" to buffer
			foreach (value($cursor) as $index => $curr) {
				yield $fieldClosure ? $field($curr, $index) : data_get($curr, $field, $default);
			}
		}
	}

	if (!function_exists('db_quote_identifier')) {

		/**
		 * Gets the given identifier for use in raw SQL expressions
		 * @param null|string|\Illuminate\Database\Connection|\Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Model $connection The connection for which to get identifier for
		 * @param string $identifier The identifier name. May contain multiple segments separated by '.'
		 * @return string The wrapped identifier eg. "`table`.`field`" or "`table`"
		 */
		function db_quote_identifier(string $identifier, $connection = null) {
			if (is_string($connection) || is_null($connection))
				$connection = \DB::connection($connection);
			elseif ($connection instanceof \Illuminate\Database\Eloquent\Relations\Relation)
				$connection = $connection->getModel()->getConnection();
			elseif ($connection instanceof \Illuminate\Database\Eloquent\Model)
				$connection = $connection->getConnection();


			return $connection->getQueryGrammar()->wrap($identifier);
		}
	}

	if (!function_exists('iterator_for')) {

		/**
		 * Function which creates an iterator for the given value. Iterators are returned as they are, for arrays an ArrayIterator is returned and all other values
		 * are returned as the first item of an array iterator, if they are not null. Null will return an EmptyIterator
		 * @param Iterator|array|mixed $value The value
		 * @return Iterator The iterator for the value
		 */
		function iterator_for($value) {
			if ($value instanceof \Iterator) {
				return $value;
			}
			elseif (is_array($value)) {
				return new \ArrayIterator($value);
			}
			elseif ($value !== null) {
				return new \ArrayIterator([$value]);
			}
			else {
				return new \EmptyIterator();
			}
		}
	}