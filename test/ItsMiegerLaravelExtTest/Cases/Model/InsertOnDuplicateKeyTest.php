<?php /** @noinspection SqlNoDataSourceInspection */
	/** @noinspection SqlDialectInspection */

	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 25.10.18
	 * Time: 14:02
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Model;


	use Carbon\Carbon;
	use Illuminate\Database\Eloquent\Model;
	use ItsMieger\LaravelExt\Model\InsertOnDuplicateKey;
	use ItsMiegerLaravelExtTest\Cases\TestCase;

	class InsertOnDuplicateKeyTest extends TestCase
	{
		/**
		 * @var UserTest
		 */
		private $user;

		public function setUp() {
			parent::setUp();
			$this->user = new UserTest();
		}

		/**
		 * Call protected/private method of a class.
		 *
		 * @param object &$object Instantiated object that we will run method on.
		 * @param string $methodName Method name to call
		 * @param array $parameters Array of parameters to pass into method.
		 *
		 * @return mixed Method return.
		 * @throws \ReflectionException
		 */
		public function invokeMethod(&$object, $methodName, array $parameters = []) {
			$reflection = new \ReflectionClass(get_class($object));
			$method     = $reflection->getMethod($methodName);
			$method->setAccessible(true);

			return $method->invokeArgs($object, $parameters);
		}

		public function getDataForInsert() {
			return [
				['id' => 1, 'email' => 'user1@email.com', 'name' => 'User One'],
				['id' => 2, 'email' => 'user2@email.com', 'name' => 'User Two'],
				['id' => 3, 'email' => 'user3@email.com', 'name' => 'User Three'],
			];
		}

		public function testGetTableName() {
			$this->assertEquals('test_user_table', UserTest::getTableName());
		}

		public function testGetTablePrefix() {
			$this->assertEquals('prefix_', UserTest::getTablePrefix());
		}

		public function testGetPrimaryKey() {
			$this->assertEquals('uuid', UserTest::getPrimaryKey());
		}
		// test private functions

		/**
		 * @expectedException \InvalidArgumentException
		 * @throws \ReflectionException
		 */
		public function testGetColumnListEmptyDataException() {
			$data = [];
			$this->invokeMethod($this->user, 'getColumnList', [$data]);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testGetColumnList() {
			$data     = $this->getDataForInsert();
			$expected = '`id`,`email`,`name`';
			$result   = $this->invokeMethod($this->user, 'getColumnList', [$data[0]]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testBuildValuesList() {
			$value    = [
				'id',
				'email',
				'name'
			];
			$expected = '`id` = VALUES(`id`), `email` = VALUES(`email`), `name` = VALUES(`name`)';
			$result   = $this->invokeMethod($this->user, 'buildValuesList', [$value]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testBuildValuesListAssociativeArray() {
			$value    = [
				'id'      => 'id + 1',
				'counter' => 'counter + 2',
			];
			$expected = 'id = id + 1, counter = counter + 2';
			$result   = $this->invokeMethod($this->user, 'buildValuesList', [$value]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testInLineFieldsDataArraySimple() {
			$data     = [
				['id' => 1, 'email' => 'user1@email.com', 'name' => 'User One']
			];
			$expected = [1, 'user1@email.com', 'User One'];
			$result   = $this->invokeMethod($this->user, 'inLineFieldsDataArray', [$data]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testBuildQuestionMarks() {
			$data     = $this->getDataForInsert();
			$expected = '(?,?,?), (?,?,?), (?,?,?)';
			$result   = $this->invokeMethod($this->user, 'buildQuestionMarks', [$data]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testInLineFieldsDataArrayThreeRows() {
			$data     = $this->getDataForInsert();
			$expected = [
				1, 'user1@email.com', 'User One',
				2, 'user2@email.com', 'User Two',
				3, 'user3@email.com', 'User Three',
			];
			$result   = $this->invokeMethod($this->user, 'inLineFieldsDataArray', [$data]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testBuildInsertOnDuplicateSqlSimple() {
			$data     = [
				['id' => 1, 'email' => 'user1@email.com', 'name' => 'User One']
			];
			$expected = 'INSERT INTO `prefix_test_user_table`(`id`,`email`,`name`) VALUES
(?,?,?)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `email` = VALUES(`email`), `name` = VALUES(`name`)';
			$result   = $this->invokeMethod($this->user, 'buildInsertOnDuplicateSql', [$data]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testBuildInsertOnDuplicateSqlMultiple() {
			$data     = $this->getDataForInsert();
			$expected = 'INSERT INTO `prefix_test_user_table`(`id`,`email`,`name`) VALUES
(?,?,?), (?,?,?), (?,?,?)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `email` = VALUES(`email`), `name` = VALUES(`name`)';
			$result   = $this->invokeMethod($this->user, 'buildInsertOnDuplicateSql', [$data]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testBuildInsertOnDuplicateSqlMultipleWithUpdateColumn() {
			$data     = $this->getDataForInsert();
			$expected = 'INSERT INTO `prefix_test_user_table`(`id`,`email`,`name`) VALUES
(?,?,?), (?,?,?), (?,?,?)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`)';
			$result   = $this->invokeMethod($this->user, 'buildInsertOnDuplicateSql', [$data, ['name']]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testUpdatedColumnIsAssociativeArray() {
			$data     = $this->getDataForInsert();
			$expected = 'INSERT INTO `prefix_test_user_table`(`id`,`email`,`name`) VALUES
(?,?,?), (?,?,?), (?,?,?)
ON DUPLICATE KEY UPDATE counter = counter + 1';
			$result   = $this->invokeMethod($this->user, 'buildInsertOnDuplicateSql', [$data, ['counter' => 'counter + 1']]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testBuildInsertIgnoreSqlSimple() {
			$data     = [
				['id' => 1, 'email' => 'user1@email.com', 'name' => 'User One']
			];
			$expected = 'INSERT IGNORE INTO `prefix_test_user_table`(`id`,`email`,`name`) VALUES
(?,?,?)';
			$result   = $this->invokeMethod($this->user, 'buildInsertIgnoreSql', [$data]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testBuildInsertIgnoreSqlMulitple() {
			$data     = $this->getDataForInsert();
			$expected = 'INSERT IGNORE INTO `prefix_test_user_table`(`id`,`email`,`name`) VALUES
(?,?,?), (?,?,?), (?,?,?)';
			$result   = $this->invokeMethod($this->user, 'buildInsertIgnoreSql', [$data]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testBuildReplaceSqlSimple() {
			$data     = [
				['id' => 1, 'email' => 'user1@email.com', 'name' => 'User One']
			];
			$expected = 'REPLACE INTO `prefix_test_user_table`(`id`,`email`,`name`) VALUES
(?,?,?)';
			$result   = $this->invokeMethod($this->user, 'buildReplaceSql', [$data]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testBuildReplaceSqlMulitple() {
			$data     = $this->getDataForInsert();

			$expected = 'REPLACE INTO `prefix_test_user_table`(`id`,`email`,`name`) VALUES
(?,?,?), (?,?,?), (?,?,?)';
			$result   = $this->invokeMethod($this->user, 'buildReplaceSql', [$data]);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testInLineFieldsDataArray() {
			$rows     = [
				['id' => 1, 'email' => '1@email.com'],
				['id' => 2, 'email' => '2@email.com'],
			];
			$result   = $this->invokeMethod($this->user, 'inLineFieldsDataArray', [$rows]);
			$expected = [
				1,
				'1@email.com',
				2,
				'2@email.com',
			];
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testInLineFieldsDataArray_Casts() {
			$d1 = ['x' => 56];
			$d2 = ['x' => 'fg'];

			$rows     = [
				['id' => 1, 'data' => $d1],
				['id' => 2, 'data' => $d2],
			];
			$result   = $this->invokeMethod($this->user, 'inLineFieldsDataArray', [$rows]);
			$expected = [
				1,
				json_encode($d1),
				2,
				json_encode($d2),
			];
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testInLineFieldsDataArray_Mutators() {

			$rows     = [
				['id' => 1, 'first_name' => 'CHRIS'],
				['id' => 2, 'first_name' => 'KARIN'],
			];
			$result   = $this->invokeMethod($this->user, 'inLineFieldsDataArray', [$rows]);
			$expected = [
				1,
				'chris',
				2,
				'karin',
			];
			$this->assertEquals($expected, $result);
		}

		/**
		 * @throws \ReflectionException
		 */
		public function testInLineFieldsDataArray_Dates() {

			$ts = Carbon::now();

			$model = new UserTest();

			$rows     = [
				['id' => 1, 'test_date' => $ts],
				['id' => 2, 'test_date' => $ts],
			];
			$result   = $this->invokeMethod($this->user, 'inLineFieldsDataArray', [$rows]);
			$expected = [
				1,
				$model->fromDateTime($ts),
				2,
				$model->fromDateTime($ts),
			];
			$this->assertEquals($expected, $result);
		}


	}

	/**
	 * Class UserTest.
	 *
	 * A user class for testing purpose.
	 */
	class UserTest extends Model
	{
		use InsertOnDuplicateKey;
		protected $table = 'test_user_table';
		protected $primaryKey = 'uuid';

		protected $casts = [
			'data' => 'array'
		];

		protected $dates = [
			'test_date'
		];

		/**
		 * Override this method for unit test because we don't have a table connection.
		 *
		 * @return string
		 */
		public static function getTablePrefix() {
			return 'prefix_';
		}

		/**
		 * Set the user's first name.
		 *
		 * @param  string $value
		 * @return void
		 */
		public function setFirstNameAttribute($value) {
			$this->attributes['first_name'] = strtolower($value);
		}
	}