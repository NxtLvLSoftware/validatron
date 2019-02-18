<?php

/**
 * Repeats.php – validatron
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

use nxtlvlsoftware\validatron\IValidatable;
use nxtlvlsoftware\validatron\Rule;
use nxtlvlsoftware\validatron\traits\Validatable;

class Repeats extends Rule implements IValidatable {
	use Validatable;

	/** @var int|null */
	protected $min = null;

	/** @var int|null */
	protected $max = null;

	/**
	 * Limit the minimum and maximum number of times the nested value can be repeated.
	 *
	 * @param int|null $min
	 * @param int|null $max
	 *
	 * @return \nxtlvlsoftware\validatron\rule\Repeats
	 */
	public function limit(?int $min = null, ?int $max = null) : Repeats {
		$this->min = $min;
		$this->max = $max;
		return $this;
	}

	/**
	 * Set the maximum number of times the nested value can be repeated.
	 *
	 * @param int|null $max
	 *
	 * @return \nxtlvlsoftware\validatron\rule\Repeats
	 */
	public function max(?int $max) : Repeats {
		$this->max = $max;
		return $this;
	}

	/**
	 * Sets the minimum number of times the nested value should be repeated.
	 *
	 * @param int|null $min
	 *
	 * @return \nxtlvlsoftware\validatron\rule\Repeats
	 */
	public function min(?int $min) : Repeats {
		$this->min = $min;
		return $this;
	}

	/**
	 * @param mixed $value
	 *
	 * @throws \nxtlvlsoftware\validatron\exception\ValidationFailedException
	 */
	public function validate(&$value) : void {
		// TODO: Implement validate() method.
	}

	/**
	 * @return \nxtlvlsoftware\validatron\IValidatable
	 */
	public function validatable() : IValidatable {
		return $this;
	}

}