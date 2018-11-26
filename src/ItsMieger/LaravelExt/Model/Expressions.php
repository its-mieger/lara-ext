<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 26.11.18
	 * Time: 11:31
	 */

	namespace ItsMieger\LaravelExt\Model;


	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Query\Expression;

	/**
	 * Common SQL expression generation
	 * @package ItsMieger\LaravelExt\Model
	 */
	trait Expressions
	{
		use Identifiers;


		/**
		 * Generates a SQL sum() expression
		 * @param Expression|string $expr The expression to pass as argument. If a string is passed, it will be interpreted as column name of the model's table
		 * @param string|null $alias The alias to use. If omitted and a column is passed, the column name is used as alias.
		 * @return Expression The expression
		 */
		public static function sumExpr($expr, $alias = null) {

			return static::functionExpr('sum', $expr, $alias);
		}

		/**
		 * Generates a SQL avg() expression
		 * @param Expression|string $expr The expression to pass as argument. If a string is passed, it will be interpreted as column name of the model's table
		 * @param string|null $alias The alias to use. If omitted and a column is passed, the column name is used as alias.
		 * @return Expression The expression
		 */
		public static function avgExpr($expr, $alias = null) {

			return static::functionExpr('avg', $expr, $alias);
		}

		/**
		 * Generates a SQL min() expression
		 * @param Expression|string $expr The expression to pass as argument. If a string is passed, it will be interpreted as column name of the model's table
		 * @param string|null $alias The alias to use. If omitted and a column is passed, the column name is used as alias.
		 * @return Expression The expression
		 */
		public static function minExpr($expr, $alias = null) {

			return static::functionExpr('min', $expr, $alias);
		}

		/**
		 * Generates a SQL max() expression
		 * @param Expression|string $expr The expression to pass as argument. If a string is passed, it will be interpreted as column name of the model's table
		 * @param string|null $alias The alias to use. If omitted and a column is passed, the column name is used as alias.
		 * @return Expression The expression
		 */
		public static function maxExpr($expr, $alias = null) {

			return static::functionExpr('max', $expr, $alias);
		}

		/**
		 * Generates a SQL lower() expression
		 * @param Expression|string $expr The expression to pass as argument. If a string is passed, it will be interpreted as column name of the model's table
		 * @param string|null $alias The alias to use. If omitted and a column is passed, the column name is used as alias.
		 * @return Expression The expression
		 */
		public static function lowerExpr($expr, $alias = null) {

			return static::functionExpr('lower', $expr, $alias);
		}

		/**
		 * Generates a SQL upper() expression
		 * @param Expression|string $expr The expression to pass as argument. If a string is passed, it will be interpreted as column name of the model's table
		 * @param string|null $alias The alias to use. If omitted and a column is passed, the column name is used as alias.
		 * @return Expression The expression
		 */
		public static function upperExpr($expr, $alias = null) {

			return static::functionExpr('upper', $expr, $alias);
		}


		/**
		 * Generates a SQL count() expression
		 * @param Expression|Expression[]|string|string[] $expressions The expression(s) to pass as argument(s). Strings will be interpreted as column names of the model's table. '*' will be used as wildcard for all columns
		 * @param string|null $alias The alias to use. If omitted and a single column is passed, the column name is used as alias.
		 * @param bool $distinct True if to count distinct values only
		 * @return Expression The expression
		 */
		public static function countExpr($expressions = '*', $alias = null, $distinct = false) {
			$modelClass = get_called_class();
			/** @var Model $model */
			$model      = new $modelClass;

			$connection = $model->getConnection();

			if ($expressions == '*') {
				$expressions = [$connection->raw($expressions)];
			}


			return static::functionExpr('count', $expressions, $alias, ($distinct ? $connection->raw('DISTINCT') : null));
		}

		/**
		 * Generates a SQL count(DISTINCT ...) expression
		 * @param Expression|Expression[]|string|string[] $expressions The expression(s) to pass as argument(s). Strings will be interpreted as column names of the model's table
		 * @param string|null $alias The alias to use. If omitted and a single column is passed, the column name is used as alias.
		 * @return Expression The expression
		 */
		public static function countDistinctExpr($expressions, $alias = null) {

			return static::countExpr($expressions, $alias, true);
		}

		/**
		 * Generates a SQL function expression
		 * @param string $function The raw function name
		 * @param Expression|Expression[]|string|string[] $arguments The expression(s) to pass as argument(s). Strings will be interpreted as column names of the model's table
		 * @param string|null $alias The alias to use. If omitted and a single column is passed, the column name is used as alias.
		 * @param Expression|null $preArgSQL SQL expression to add before argument list, e.g. "DISTINCT"
		 * @return Expression The expression
		 */
		public static function functionExpr(string $function, $arguments, string $alias = null, Expression $preArgSQL = null) {
			$modelClass = get_called_class();
			/** @var Model $model */
			$model = new $modelClass;

			// make alias the same as field name, if single field passed
			if (!$alias && !is_array($arguments) && !($arguments instanceof Expression))
				$alias = $arguments;

			// make arguments array
			if (!is_array($arguments))
				$arguments = [$arguments];

			// convert field names to expressions
			$arguments = array_map(function($value) {

				// convert strings to field names
				if (!($value instanceof Expression))
					$value = static::fieldRaw($value);

				return (string)$value;

			}, $arguments);

			// build SQL fragments
			$preArgSQL = $preArgSQL ? (string)$preArgSQL . ' ' : '';
			$argsSql   = implode(', ', $arguments);
			$aliasSql  = $alias ? ' AS ' . static::quoteIdentifier($alias) : '';

			return $model->getConnection()->raw( "$function($preArgSQL$argsSql)$aliasSql");
		}

	}