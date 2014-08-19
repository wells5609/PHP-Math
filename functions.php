<?php
/**
 * General mathematical functions.
 */

bcscale('10');
 
function math_array_sum($array, &$count = null) {
	$sum = '0';
	$count = '0';
	foreach($array as $value) {
		if (is_numeric($value)) {
			$sum = bcadd($sum, (string) $value);
			$count = bcadd($count, '1');
		}
	}
	return $sum;
}

function math_count($array) {
	$c = '0';
	foreach($array as $value) {
		if (is_numeric($value)) {
			$c = bcadd($c, '1');
		}
	}
	return $c;
}

/**
 * Calculate mean (simple arithmetic average).
 *
 * @param array $values
 * @return string Mean
 */
function mean(array $values) {
	$sum = math_array_sum($values, $n);
	return bcdiv($sum, $n);
}

/**
 * Calculate median.
 * 
 * @param array $values
 * @return string Median value
 */
function median(array $values) {
	$values = array_values(array_map('strval', $values));
	sort($values, SORT_NUMERIC);
	$n = count($values);
	// exact median
	if (isset($values[$n/2])) {
		return $values[$n/2];
	}
	// average of two middle values
	$m1 = ($n-1)/2;
	$m2 = ($n+1)/2;
	if (isset($values[$m1]) && isset($values[$m2])) {
		return bcdiv(bcadd($values[$m1], $values[$m2]), '2');
	}
	// best guess
	$mrnd = (int) round($n/2, 0);
	if (isset($values[$mrnd])) {
		return $values[$mrnd];
	}
	return null;
}

/**
 * Calculate the sum of products.
 * 
 * @param array $x_values
 * @param array $y_values
 * @return string Sum of products.
 */
function sumxy(array $x_values, array $y_values) {
	$sum = '0';
	foreach($x_values as $i => $x) {
		if (isset($y_values[$i])) {
			$sum = bcadd($sum, bcmul($x, $y_values[$i]));
			#$sum += $x * $y_values[$i];
		}
	}
	return (string) $sum;
}

/**
 * Compute the sum of squares.
 *
 * @param array $values An array of values.
 * @param null|scalar|array $values2 If null is given, squares each array value.
 * If given a scalar value, squares the difference between each array value and
 * the one given in $values2 (good for explained/regression SS).
 * If given an array, squares the difference between betweeen each array value
 * and the value in $values2 with matching key (good for residual SS).
 * @return string Sum of all da squares.
 */
function sos(array $values, $values2 = null) {
	if (isset($values2) && ! is_array($values2)) {
		$values2 = array_fill_keys(array_keys($values), $values2);
	}
	$sum = '0';
	foreach ($values as $i => $val) {
		if (! isset($values2)) {
			$sum = bcadd($sum, bcpow($val, '2'));
			#$sum += pow($val, 2);
		} else if (isset($values2[$i])) {
			$sum = bcadd($sum, bcpow(bcsub($val, $values2[$i]), '2'));
			#$sum += pow($val - $values2[$i], 2);
		}
	}
	return (string) $sum;
}

/**
 * Calculate variance.
 *
 * @param array $values
 * @param boolean $is_sample Default false.
 * @return string Variance of the values.
 */
function variance(array $values, $is_sample = false) {
	if ($is_sample) {
		// = SOS(r) / (COUNT(s) - 1)
		return bcdiv(sos($values, mean($values)), bcsub(math_count($values), '1'));
	}
	return covariance($values, $values);
}

/**
 * Compute standard deviation.
 *
 * @param array $a The array of data to find the standard deviation for.
 * Note that all values of the array will be cast to float.
 * @param bool $is_sample [Optional] Indicates if $a represents a sample of the
 * population (otherwise its the population); Defaults to false.
 * @return string|bool The standard deviation or false on error.
 */
function stddev(array $a, $is_sample = false) {
	if (math_count($a) < 2) {
		trigger_error("The array has too few elements", E_USER_NOTICE);
		return false;
	}
	return bcsqrt(variance($a, $is_sample));
}

/**
 * Calculate covariance.
 *
 * @param array $x_values Dependent variable values.
 * @param array $y_values Independent variable values.
 * @return string Covariance of x and y.
 */
function covariance(array $x_values, array $y_values) {
	
	$l = bcdiv(sumxy($x_values, $y_values), math_count($x_values));
	$r = bcmul(mean($x_values), mean($y_values));
	
	return bcsub($l, $r);
	
	#return sumxy($x_values, $y_values)/math_count($x_values) - mean($x_values)*mean($y_values);
}

/**
 * Compute correlation.
 * 
 * @param array $x_values
 * @param array $y_values
 * @return string Correlation
 */
function correlation(array $x_values, array $y_values) {
	
	$sdxy = bcmul(stddev($x_values, true), stddev($y_values, true));
	
	return bcdiv(covariance($x_values, $y_values), $sdxy);
	
	#return covariance($x_values, $y_values) / (stddev($x_values, true)*stddev($y_values, true));
}

/**
 * Returns the present value of a cashflow.
 *
 * @param int|float|string $cashflow Numeric quantity of currency.
 * @param float|string $rate Discount rate
 * @param int|float|string $period A number representing time period in which the
 * cash flow occurs. e.g. for an annual cashflow, start a 0 and increase by 1
 * each year (e.g. [Year] 0, [Year] 1, ...)
 * @return string Present value of the cash flow.
 */
function pv($cashflow, $rate, $period = 0) {
	if ($period < 1) {
		return (string) $cashflow;
	}
	
	return bcdiv($cashflow, bcpow(bcadd($rate, '1'), $period));
	
	#return $cashflow / pow(1 + $rate, $period);
}

/**
 * Returns the Net Present Value of a series of cashflows.
 *
 * @param array $cashflows Indexed array of cash flows.
 * @param number $rate Discount rate applied.
 * @return string NPV of $cashflows discounted at $rate.
 */
function npv(array $cashflows, $rate) {
	$npv = "0.0";
	foreach ($cashflows as $index => $cashflow) {
		$npv += pv($cashflow, $rate, $index);
	}
	return (string) $npv;
}

/**
 * Returns the weighted average of a series of values.
 *
 * @param array $values Indexed array of values.
 * @param array $weights Indexed array of weights corresponding to each value.
 * @return string Weighted average of values.
 */
function weighted_avg(array $values, array $weights) {
	if (count($values) !== count($weights)) {
		trigger_error("Must pass the same number of weights and values.");
		return null;
	}
	$weighted_sum = "0.0";
	foreach ($values as $i => $val) {
		$weighted_sum += $val * $weights[$i];
	}
	return strval($weighted_sum/array_sum($weights));
}

/** ========================================
	Percentages
 ======================================== */

/**
 * Returns the % of an amount of the total.
 *
 * e.g. for operating margin, use operating income as 1st arg, revenue as 2nd.
 * e.g. for capex as a % of sales, use capex as 1st arg, revenue as 2nd.
 *
 * @param number $portion An amount, a portion of the total.
 * @param number $total The total amount.
 * @return string %
 */
function pct($portion, $total) {
	return strval($portion/$total);
}

/**
 * Returns the % change between two values.
 *
 * @param number $current The current value.
 * @param number $previous The previous value.
 * @return string Percent change from previous to current.
 */
function pct_change($current, $previous) {
	return strval(($current - $previous) / $previous);
}

/**
 * Convert an array of values to % change.
 *
 * @param array $values Raw values ordered from oldest to newest.
 * @return array Array of the % change between values.
 */
function pct_change_array(array $values) {
	$pcts = array();
	$keys = array_keys($values);
	$vals = array_values($values);
	foreach ($vals as $i => $value) {
		if (0 !== $i) {
			$prev = $vals[$i-1];
			if (0 == $prev) {
				$pcts[$i] = '0';
			} else {
				$pcts[$i] = strval(($value-$prev)/$prev);
			}
		}
	}
	array_shift($keys);
	return array_combine($keys, $pcts);
}

/** ========================================
	Aliases
 ======================================== */

/**
 * Arithmetic average.
 */
function avg(array $values) {
	return strval(array_sum($values)/count($values));
}

/**
 * Covariance
 */
function covar(array $xvals, array $yvals) {
	 return covariance($xvals, $yvals); 
}

/**
 * Standard deviation
 */
function stdev(array $values, $is_sample = false) {
	 return stddev($values, $is_sample); 
}

/**
 * Correlation
 */
function correl(array $x_values, array $y_values) {
	 return correlation($x_values, $y_values); 
}
