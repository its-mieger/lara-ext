# ItsMieger LaraExt - Useful extensions for the Laravel framework
This library adds commonly use functionality to the Laravel PHP framework. It also integrates the
`its-mieger/obj` package (helps to make objects behave like native data types) well to laravel.

 
So far this includes:
* Collection macros
* String macros
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
 
## Helper improvements

### data_get
The `data_get` helper now supports receiving data from getters:

	data_get('getA()', $target);
	data_get('test.getA()', $target);
	
This is also very handy in other methods using `data_get` as many collection methods do:

	$collection->sortBy('getUnitPrice()');
		
