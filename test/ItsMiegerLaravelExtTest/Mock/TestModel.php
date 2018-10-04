<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 04.10.18
	 * Time: 12:59
	 */

	namespace ItsMiegerLaravelExtTest\Mock;


	use Illuminate\Database\Eloquent\Model;

	class TestModel extends Model
	{

		protected $table = 'test_table';
	}