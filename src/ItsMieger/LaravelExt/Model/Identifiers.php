<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 26.11.18
	 * Time: 10:33
	 */

	namespace ItsMieger\LaravelExt\Model;


	trait Identifiers
	{

		/**
		 * Gets the model's table name
		 * @return string The table name
		 */
		public static function table() {
			$modelClass = get_called_class();
			$model      = new $modelClass;

			return $model->getTable();
		}

		/**
		 * Gets the model's table name for use in raw SQL expressions
		 * @return string The table name
		 */
		public static function tableRaw() {
			$modelClass = get_called_class();
			$model = new $modelClass;

			return static::quoteIdentifier($model->getTable());
		}

		/**
		 * Gets the model's field name prefixed with the table name
		 * @param string $field The model field name
		 * @return string The model field, eg. "table.field"
		 */
		public static function field(string $field) {
			$modelClass = get_called_class();
			$model      = new $modelClass;

			return "{$model->getTable()}.{$field}";
		}

		/**
		 * Gets the model's field name prefixed with the table name for use in raw SQL expressions
		 * @param string $field The model field name
		 * @return string The model field, eg. "`table`.`field`"
		 */
		public static function fieldRaw(string $field) {
			$modelClass = get_called_class();
			$model      = new $modelClass;

			return static::quoteIdentifier("{$model->getTable()}.{$field}");
		}

		/**
		 * Gets the given identifier for use in raw SQL expressions of the model's database connection
		 * @param string $identifier The identifier name. May contain multiple segments separated by '.'
		 * @return string The wrapped identifier eg. "`table`.`field`" or "`table`"
		 */
		public static function quoteIdentifier(string $identifier) {
			$modelClass = get_called_class();
			$model      = new $modelClass;

			return $model->getConnection()->getQueryGrammar()->wrap($identifier);
		}


	}