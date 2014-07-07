Missing-Math
============

PHP's missing math functions - mean(), median(), variance(), covariance(), correlation(), stddev(), pv(), npv(), and more.


##Functions

####`mean(array $values)`

**Parameters:**
 * `Array $values` - Array of numeric values.

**Returns:** `String` Arithmetic average of the given values.

**Alias:** `avg()`

####`median(array $values)`

**Parameters:**
 * `Array $values` - Array of numeric values

**Returns:** `String` Median value of the given values, or null if it cannot be computed.

####`sumxy(array $x_values, array $y_values)`

**Parameters:**
 * `Array $x_values` - Array of numeric values of x.
 * `Array $y_values` - Array of numeric values of y.

**Returns:** `String` Sum of the products of the values.

####`sos(array $values, $values2 = null)`

**Parameters:**
 * `Array $values` - Array of numeric values.
 * `Null|Array|String $values2` - Optional - If null, simply sums the square of each array value. If given a scalar value, sums the squares of the difference between each array value and that given by $values2 (good for explained/regression SS). If given an array, sums the squares of the difference between betweeen each array value and the value in $values2 with matching key (good for residual SS).

**Returns:** `String` Sum of squares.

####`variance(array $values, $is_sample = false)`

**Returns:** `String` Variance

####`stddev(array $a, $is_sample = false)`

**Returns:** `String` Standard deviation

**Alias:** `stdev()`

####`covariance(array $x_values, array $y_values)`

**Returns:** `String` Covariance

**Alias:** `covar()`

####`correlation(array $x_values, array $y_values)`

**Returns:** `String` Correlation

**Alias:** `correl()`

####`pv($cashflow, $rate, $period = 0)`

**Returns:** `String` Present value

####`npv(array $cashflows, $rate)`

**Returns:** `String` Net present value

####`weighted_avg(array $values, array $weights)`

**Returns:** `String` Weighted average

####`pct($portion, $total)`

**Returns:** `String` Percent

####`pct_change($current, $previous)`

**Returns:** `String` Percent change

####`pct_change_array($values)`

**Returns:** `Array` Array values percent change 


