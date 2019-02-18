<?php

/**
 * ValueList.php – validatron
 *
 * Copyright (C) 2019 Jack Noordhuis
 *
 * @author Jack Noordhuis
 *
 * This is free and unencumbered software released into the public domain.
 *
 * Anyone is free to copy, modify, publish, use, compile, sell, or
 * distribute this software, either in source code form or as a compiled
 * binary, for any purpose, commercial or non-commercial, and by any means.
 *
 * In jurisdictions that recognize copyright laws, the author or authors
 * of this software dedicate any and all copyright interest in the
 * software to the public domain. We make this dedication for the benefit
 * of the public at large and to the detriment of our heirs and
 * successors. We intend this dedication to be an overt act of
 * relinquishment in perpetuity of all present and future rights to this
 * software under copyright law.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * For more information, please refer to <http://unlicense.org/>
 *
 */

declare(strict_types=1);

namespace nxtlvlsoftware\validatron;

use InvalidArgumentException;
use function array_key_exists;
use function explode;
use function is_array;

class ValueList {

	/** @var array */
	protected $values;

	/**
	 * @param array $values
	 */
	public function __construct(array $values = []) {
		$this->values = $values;
	}

	/**
	 * Check if a value exists using dot-notation.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has(string $key) : bool {
		$value = $this->values;
		foreach(explode(".", $key) as $part) {
			if(!is_array($value) or !array_key_exists($part, $value)) {
				return false;
			}

			$value = $value[$part];
		}

		return true;
	}

	/**
	 * Retrieve a value using dot-notation.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get(string $key) {
		$value = $this->values;
		foreach(explode(".", $key) as $part) {
			if(!is_array($value) or !array_key_exists($part, $value)) {
				throw new InvalidArgumentException("Attempted to retrieve undefined index " . $key);
			}

			$value = $value[$part];
		}

		return $value;
	}

	/**
	 * @return array
	 */
	public function all() : array {
		return $this->values;
	}

	/**
	 * Set a value using dot-notation.
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return \nxtlvlsoftware\validatron\ValueList
	 */
	public function set(string $key, $value) : ValueList {
		$parts = explode(".", $key);
		$ref = &$this->values;

		foreach($parts as $i => $part) {
			if($i < count($parts) - 1 and (!isset($ref[$part]) or !is_array($ref[$part]))) {
				$ref[$part] = [];
			}

			$ref = &$ref[$part];
		}

		$ref = $value;
		return $this;
	}

}