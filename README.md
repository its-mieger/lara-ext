# ItsMieger LaraExt - Useful extensions for the Laravel framework
This library adds commonly use functionality to the Laravel PHP framework. It also integrates the
`its-mieger/obj` package (helps to make objects behave like native data types) well to laravel.

It also includes a helper file for IDE auto completion.
 
So far this includes:
* Collection macros

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
