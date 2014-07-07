Missing-Math
============

PHP's missing math functions - mean(), median(), variance(), covariance(), correlation(), stddev(), pv(), npv(), and more.


##Functions

####`String mean(array $values)`

**Alias:** `avg()`

**Parameters:**
 * `Array $values` - Array of numeric values.

**Returns:** `String` Arithmetic average of the given values.

####`String median(array $values)`

**Parameters:**
 * `Array $values` - Array of numeric values

**Returns:** `String` Median value of the given values, or null if it cannot be computed.


####`String sumxy(array $x_values, array $y_values)`

**Parameters:**
 * `Array $x_values` - Array of numeric values of x.
 * `Array $y_values` - Array of numeric values of y.

**Returns:** `String` Sum of the products of the values.


####`String sos(array $values, $values2 = null)`

**Parameters:**
 * `Array $values` - Array of numeric values.
 * `Null|Array|String $values2` - Optional - If null, simply sums the square of each array value. If given a scalar value, sums the squares of the difference between each array value and that given by $values2 (good for explained/regression SS). If given an array, sums the squares of the difference between betweeen each array value and the value in $values2 with matching key (good for residual SS).

**Returns:** `String` Sum of squares.


####`String variance(array $values, $is_sample = false)`

Variance

####`String stddev(array $a, $is_sample = false)`

Standard deviation

Alias: `stdev()`

####`String covariance(array $x_values, array $y_values)`

Covariance

Alias: `covar()`

####`String correlation(array $x_values, array $y_values)`

Correlation

Alias: `correl()`

####`String pv($cashflow, $rate, $period = 0)`

Present value

####`String npv(array $cashflows, $rate)`

Net present value

####`String weighted_avg(array $values, array $weights)`

Weighted average

####`String pct($portion, $total)`

Percent

####`String pct_change($current, $previous)`

Percent change

####`Array pct_change_array($values)`

Array values percent change 


