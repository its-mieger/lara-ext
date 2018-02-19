<?php
	use Illuminate\Support\Collection;
	use ItsMieger\Obj\Obj;

	Collection::macro('diffBy', function ($items, callable $compareFn) {
		return new static(array_udiff($this->items, $this->getArrayableItems($items), $compareFn));
	});

	Collection::macro('diffAssocBy', function ($items, callable $valueCompareFunc, callable $keyCompareFunc = null) {
		if ($keyCompareFunc)
			return new static(array_udiff_uassoc($this->items, $this->getArrayableItems($items), $valueCompareFunc, $keyCompareFunc));
		else
			return new static(array_udiff_assoc($this->items, $this->getArrayableItems($items), $valueCompareFunc));
	});

	Collection::macro('diffObj', function ($items) {
		return new static(array_udiff($this->items, $this->getArrayableItems($items), [Obj::class, 'compare']));
	});

	Collection::macro('diffAssocObj', function ($items, callable $keyCompareFunc = null) {
		if ($keyCompareFunc)
			return new static(array_udiff_uassoc($this->items, $this->getArrayableItems($items), [Obj::class, 'compare'], $keyCompareFunc));
		else
			return new static(array_udiff_assoc($this->items, $this->getArrayableItems($items), [Obj::class, 'compare']));
	});

	Collection::macro('sortCallback', function (callable $compareFn, $keepKeys = false, $desc = false) {
		$items = $this->items;

		if ($desc) {
			$comparator = function ($a, $b) use ($compareFn) {
				return -1 * call_user_func($compareFn, $a, $b);
			};
		}
		else {
			$comparator = $compareFn;
		}

		// sort
		$keepKeys ? uasort($items, $comparator) : usort($items, $comparator);


		return new static($items);
	});

	Collection::macro('sortCallbackDesc', function (callable $compareFn, $keepKeys = false) {
		return $this->sortCallback($compareFn, $keepKeys, true);
	});

	Collection::macro('sortObj', function ($keepKeys = false, $desc = false) {
		$items = $this->items;

		if ($desc) {
			$comparator = function ($a, $b) {
				return -1 * Obj::compare($a, $b);
			};
		}
		else {
			$comparator = [Obj::class, 'compare'];
		}

		// sort
		$keepKeys ? uasort($items, $comparator) : usort($items, $comparator);


		return new static($items);
	});

	Collection::macro('sortObjDesc', function ($keepKeys = false) {
		return $this->sortObj($keepKeys, true);
	});

	Collection::macro('compareToValues', function ($items) {

		$items = $this->getArrayableItems($items);

		$missingA = collect();
		foreach($this->items as $value) {
			$found = false;
			foreach($items as $value2) {
				if (Obj::equal($value, $value2)) {
					$found = true;
					break;
				}
			}

			if (!$found)
				$missingA->push($value);
		}

		$missingB = collect();
		foreach($items as $value) {
			$found = false;
			foreach ($this->items as $value2) {
				if (Obj::equal($value, $value2)) {
					$found = true;
					break;
				}
			}

			if (!$found)
				$missingB->push($value);
		}

		if ($missingA->count() == 0 && $missingB->count() == 0)
			return 0;
		if (($res = (count($this->items) <=> count($items))))
			return $res;


		$missingA = $missingA->sortObjDesc(true);
		$missingB = $missingB->sortObjDesc(true);

		while ($missingA->count() > 0 && $missingB->count() > 0) {
			if ($res = Obj::compare($missingA->shift(), $missingB->shift()))
				return $res;
		}

		if (($res = ($missingA->count() <=> $missingB->count())))
			return $res * -1;

		throw new RuntimeException('Inconsistent object compare detected');
	});

	Collection::macro('compareToValuesAssoc', function ($items) {

		$items = $this->getArrayableItems($items);

		$missingA = collect();
		foreach($this->items as $key => $value) {
			if (!array_key_exists($key, $items)) {
				$missingA->put($key, $value);
				break;
			}


			if (!Obj::equal($value, $items[$key])) {
				$missingA->put($key, $value);
				break;
			}
		}

		$missingB = collect();
		foreach($items as $key => $value) {
			if (!array_key_exists($key, $this->items)) {
				$missingB->put($key, $value);
				break;

			}
			if (!Obj::equal($value, $this->items[$key])) {
				$missingB->put($key, $value);
				break;
			}
		}


		if ($missingA->count() == 0 && $missingB->count() == 0)
			return 0;
		if (($res = (count($this->items) <=> count($items))))
			return $res;


		$missingA = $missingA->sortObjDesc(true);
		$missingB = $missingB->sortObjDesc(true);
		$keysA = $missingA->keys()->all();
		$keysB = $missingB->keys()->all();


		// compare missing values
		while ($missingA->count() > 0 && $missingB->count() > 0) {
			if ($res = Obj::compare($missingA->shift(), $missingB->shift()))
				return $res;
		}

		// compare missing keys
		while (count($keysA) > 0 && count($keysB) > 0) {
			if ($res = ((string)array_shift($keysA) <=> (string)array_shift($keysB)))
				return $res;
		}

		if (($res = ($missingA->count() <=> $missingB->count())))
			return $res * -1;

		throw new RuntimeException('Inconsistent object compare detected');
	});