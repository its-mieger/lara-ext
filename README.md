# ItsMieger LaraExt - Useful extensions for the Laravel framework
This library adds commonly use functionality to the Laravel PHP framework. It also integrates the
`its-mieger/obj` package (helps to make objects behave like native data types) well to laravel.

 
So far this includes:
* Collection macros
* String macros
* Additional helpers
* Improved helper functions

It also includes a helper file for IDE auto completion.

## Installation

After updating composer, add the service provider to the providers array in config/app.php.

This is not required if using automatic package discovery as enabled.

	ItsMieger\LaravelExt\Provider\LaraExtServiceProvider::class,

##Collection macros
Following macros extend the `Illuminate\Support\Collection`:

| Macro 			| Description
|-------------------| ------------
| `diffBy`			| array_diff with custom comparator
| `diffAssocBy`		| associative array_diff with custom comparators
| `diffObj`			| array_diff using object comparision
| `diffAssocObj`	| associative array_diff using object comparision
| `sortCallback`	| sort using custom sort function
| `sortCallbackDesc` | sort in descending order using custom sort function
| `sortObj`			| sort using object comparision
| `sortObjDesc`		| sort in descending order using object comparision
| `compareToValues`		| compares the collection values to another collection 
| `compareToValuesAssoc`	| compares the collection values and keys to another collection
| `maxBy`	| get the item returning the maximum value for specified callback
| `minBy`	| get the item returning the minimum value for specified callback

## String macros
| Macro 			| Description
|-------------------| ------------
| `extract`			| as explode but with fixed result array length, eg.: `[$a, $b] = Str::extract('a:b:c', ':',  2)`
 
## Helpers

| Macro 			| Description
|-------------------| ------------
| `buffer`			| creates a new `FlushingBuffer` instance
| `chunked`			| `array_chunk()` for generators
| `joined`			| joins two collections and passes the tuples to a callback
| `cursor_get`		| `data_get()` for collections
| `db_table`		| gets a model's table name
| `db_connection`	| gets a model's connection name
| `db_field`		| prefixes a model's field name with the table name
 
## Helper improvements

### data_get
The `data_get` helper now supports receiving data from getters:

	data_get('getA()', $target);
	data_get('test.getA()', $target);
	
This is also very handy in other methods using `data_get` as many collection methods do:

	$collection->sortBy('getUnitPrice()');
		
## Additional helpers

### buffer()
Flushing buffers implement buffers with a given size which are automatically flushed using a given
handler function when they are full.

You may create an instance using the `buffer`-helper. It Takes up to three arguments:

	buffer(10, function(data) { /* send data */ });
	buffer(10, function(data) { /* send data */ }, function() { return collect(); });
	
The first argument specifies the buffer size, the second one a handler function which is called
each time the buffer is full. It receives the buffer data as argument. The third one is optional
and acts as resolver for the underlying data structure to use. If omitted a simple array is used.

New items are added to the buffer using the `add()`-method. Usually you want the buffer to flushed
a last time, after all data was added, even if it is not null. To achieve this, simply call the
`flush()`-method to manually flush the buffer:

	$b = buffer(2, function(data) { /* send data */ });
	$b->add(1);
	$b->add(2);
	$b->add(3);
	$b->flush();
	
	
### chunked()
The native `array_chunk()`-function is very useful when dealing with large data that cannot
be processed at once. However it does not solve the problem that you might not even be able
to load all the input data at once. Here the `chunked()`-helper function comes in. See
following example:
	
	$generator = function() { /* generator code */ };
	
	chunked($generator, 500, function($chunk) {
		/* processing code */
	});

	
### joined()
Often you have to join two collections by a given field and want to process the joined
value pairs. The `joined()` helper makes this task really easy:

	joined($collectionA, 'fieldA', $collectionB, 'fieldB.x', function($a, $b) {
		/* do s.th. here */
	});
	
The closure receives the value pairs. By default only the first matching value pair is
processed. But you may specify this by a parameter.

This helper is very flexible. You may pass in generators, closures, any traversables and
even model names:

	joined($collectionA, 'user', User::class, 'username', function($a, $b) {
		/* do s.th. here */
	});
	
This would call `User::whereIn('username', /* .. */)->get()` to receive the right side
collection. If you need more flexibility you can pass a query builder:

	joined($collectionA, 'user', User::where('active', true), 'username', function($a, $b) {
		/* do s.th. here */
	});

### cursor_get()
The `cursor_get()` helper iterates the passed items (cursor, collection, array, ...) and
uses `data_get()` to receive a value for each item which will be returned by the returned
generator. You may also pass a closure as field parameter which returns the value for
each item:

	cursor_get($data, 'x.y');
	
### db_table()

Gets the table name of a given model:

	db_table(User::class);
	
	
### db_connection()

Gets the connection instance to use for a given model:

	db_connection(User::class);
	
### db_field()

Gets the model field prefixed by the model's table name:

	db_field(User::class, 'id');
	// => users.id