<?php

/**
 * IntType.php – validatron
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

namespace nxtlvlsoftware\validatron\rule;

use nxtlvlsoftware\validatron\TemplateList;
use function ctype_digit;
use function filter_var;
use function is_int;
use function is_numeric;
use const FILTER_VALIDATE_INT;
use const PHP_INT_MAX;
use const PHP_INT_MIN;

class IntType extends Type {

	/** @var int|null */
	protected $min = null;

	/** @var int|null */
	protected $max = null;

	/** @var bool */
	protected $strict = false;

	public const NOT_IN_RANGE = "NOT_IN_RANGE";
	public const NOT_INT = "NOT_INT", NOT_INTEGER = "NOT_INT";

	protected function messages(TemplateList $container) : void {
		$container
			->parameter("min", (string) ($this->min ?? PHP_INT_MIN))
			->parameter("max", (string) ($this->max ?? PHP_INT_MAX))
			->template(self::NOT_IN_RANGE, '${key} must be within the range of ${min} to ${max}.')
			->template(self::NOT_INT, '${key} must be an integer.');
	}

	/**
	 * Limit the minimum and maximum number of times the nested value can be repeated.
	 *
	 * @param int|null $min
	 * @param int|null $max
	 *
	 * @return \nxtlvlsoftware\validatron\rule\IntType
	 */
	public function limit(?int $min = null, ?int $max = null) : IntType {
		$this->min = $min;
		$this->max = $max;
		return $this;
	}

	/**
	 * Set the maximum number of times the nested value can be repeated.
	 *
	 * @param int|null $max
	 *
	 * @return \nxtlvlsoftware\validatron\rule\IntType
	 */
	public function max(?int $max) : IntType {
		$this->max = $max;
		return $this;
	}

	/**
	 * Sets the minimum number of times the nested value should be repeated.
	 *
	 * @param int|null $min
	 *
	 * @return \nxtlvlsoftware\validatron\rule\IntType
	 */
	public function min(?int $min) : IntType {
		$this->min = $min;
		return $this;
	}

	/**
	 * Disallow string values that look like floats.
	 *
	 * @param bool $strictCheck
	 *
	 * @return \nxtlvlsoftware\validatron\rule\IntType
	 */
	public function strict(bool $strictCheck = true) : IntType {
		$this->strict = $strictCheck;
		return $this;
	}

	/**
	 * Set the value to be casted to the type being check if it is safe.
	 *
	 * NOTE: This override is here for documentation and code completion purposes.
	 *
	 * @param bool $doCast
	 *
	 * @return \nxtlvlsoftware\validatron\rule\IntType|\nxtlvlsoftware\validatron\rule\Type
	 */
	public function cast(bool $doCast = true) : IntType {
		return parent::cast($doCast);
	}

	/**
	 * Validate a value is of an integer type with cast and strict checking options.
	 *
	 * @param mixed $value
	 *
	 * @throws \nxtlvlsoftware\validatron\exception\ValidationFailedException
	 */
	public function validate(&$value) : void {
		if(!$this->cast and !is_int($value)) {
			$this->error(self::NOT_INT); // value isn't an int and we were asked not to cast
		} elseif(!is_int($value)) {
			if(($filtered = filter_var($value, FILTER_VALIDATE_INT)) === false and !is_numeric($value)) {
				$this->error(self::NOT_INT); // value can't be safely cast to int
			} elseif($this->strict) {
				if(!ctype_digit($value)) {
					$this->error(self::NOT_INT); // value contains non-integer characters (floats)
				}
			}
		}

		if(isset($filtered)) {
			if($filtered === false) {
				$value = (int) $value;
			} else {
				$value = $filtered;
			}
		} else {
			$value = (int) $value;
		}

		if(!$this->withinUpperLimit($value) or !$this->withinLowerLimit($value)) {
			$this->error(self::NOT_IN_RANGE);
		}
	}

	/**
	 * Check if an integer is within the specified upper limit.
	 *
	 * @param int $value
	 *
	 * @return bool
	 */
	private function withinUpperLimit(int $value) : bool {
		if($this->max !== null and $value > $this->max) {
			return false;
		}

		return true;
	}

	/**
	 * Check if an integer is within the specified lower limit.
	 *
	 * @param int $value
	 *
	 * @return bool
	 */
	private function withinLowerLimit(int $value) : bool {
		if($this->min !== null and $value < $this->min) {
			return false;
		}

		return true;
	}

}