<?php
	/**
	 * A helper file for Laravel 5, to provide autocomplete information to your IDE
	 *
	 */

	namespace {
		exit("This file should not be included, only analyzed by your IDE");
	}

	namespace Illuminate\Support {

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
	}