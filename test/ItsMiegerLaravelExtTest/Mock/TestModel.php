<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 04.10.18
	 * Time: 12:59
	 */

	namespace ItsMiegerLaravelExtTest\Mock;


	use Illuminate\Database\Eloquent\Model;
	use ItsMieger\LaravelExt\Model\Identifiers;

	class TestModel extends Model
	{
		use Identifiers;

		protected $table = 'test_table';
	}