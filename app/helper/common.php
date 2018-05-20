<?php

// ARRAY
/**
 * Return an array with values from $data matched with $default by keys
 *
 * @var array $data
 * @var array $default
 * @since 1.0
 */
function array_bind($data, $default) {
	return array_merge($default, array_intersect_key($data, $default));
}

/**
 * Merge sub arrays
 *
 * @var array $data
 * @since 1.0
 */
function array_merge_sub($data) {
	return array_unique(call_user_func_array('array_merge', $data));
}
// ARRAY