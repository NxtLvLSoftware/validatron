<?php

/**
 * FloatType.php – validatron
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
use function filter_var;
use function is_float;
use function is_numeric;
use function is_string;
use const FILTER_VALIDATE_FLOAT;
use const PHP_FLOAT_MAX;
use const PHP_FLOAT_MIN;

class FloatType extends Type {

	public const NOT_IN_RANGE = "NOT_IN_RANGE";
	public const NOT_FLOAT = "NOT_FLOAT";

	/** @var float|null */
	protected $min = null;

	/** @var float|null */
	protected $max = null;

	protected function messages(TemplateList $container) : void {
		$container
			->parameter("min", (string) ($this->min ?? PHP_FLOAT_MIN))
			->parameter("max", (string) ($this->max ?? PHP_FLOAT_MAX))
			->template(self::NOT_IN_RANGE, '${key} must be within the range of ${min} to ${max}.')
			->template(self::NOT_FLOAT, '${key} must be a float.');
	}

	/**
	 * Limit the minimum and maximum number of times the nested value can be repeated.
	 *
	 * @param float|null $min
	 * @param float|null $max
	 *
	 * @return \nxtlvlsoftware\validatron\rule\FloatType
	 */
	public function limit(?float $min = null, ?float $max = null) : FloatType {
		$this->min = $min;
		$this->max = $max;
		return $this;
	}

	/**
	 * Set the maximum number of times the nested value can be repeated.
	 *
	 * @param float|null $max
	 *
	 * @return \nxtlvlsoftware\validatron\rule\FloatType
	 */
	public function max(?float $max) : FloatType {
		$this->max = $max;
		return $this;
	}

	/**
	 * Sets the minimum number of times the nested value should be repeated.
	 *
	 * @param float|null $min
	 *
	 * @return \nxtlvlsoftware\validatron\rule\FloatType
	 */
	public function min(?float $min) : FloatType {
		$this->min = $min;
		return $this;
	}

	/**
	 * Validate a value is of a float type with a cast option.
	 *
	 * @param mixed $value
	 *
	 * @throws \nxtlvlsoftware\validatron\exception\ValidationFailedException
	 */
	public function validate(&$value) : void {
		if(!$this->cast and !is_float($value)) {
			$this->error(self::NOT_FLOAT); // value isn't an float and we were asked not to cast
		} elseif(is_string($value)) {
			if(($filtered = filter_var($value, FILTER_VALIDATE_FLOAT)) === false and !is_numeric($value)) {
				$this->error(self::NOT_FLOAT); // value can't be safely cast to float
			}
		}

		$value = (isset($filtered) ? $filtered : (float) $value);

		if(!$this->withinUpperLimit($value) or !$this->withinLowerLimit($value)) {
			$this->error(self::NOT_IN_RANGE);
		}
	}

	/**
	 * Check if a float is within the specified upper limit.
	 *
	 * @param float $value
	 *
	 * @return bool
	 */
	private function withinUpperLimit(float $value) : bool {
		if($this->max !== null and $value > $this->max) {
			return false;
		}

		return true;
	}

	/**
	 * Check if a float is within the specified lower limit.
	 *
	 * @param float $value
	 *
	 * @return bool
	 */
	private function withinLowerLimit(float $value) : bool {
		if($this->min !== null and $value < $this->min) {
			return false;
		}

		return true;
	}

}