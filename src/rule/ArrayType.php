<?php

/**
 * ArrayType.php – validatron
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
use function is_array;

class ArrayType extends Type {

	public const NOT_ARRAY = "NOT_ARRAY";

	protected function messages(TemplateList $container) : void {
		$container
			->template(self::NOT_ARRAY, '${key} must be an array.');
	}

	/**
	 * Set the value to be casted to the type being check if it is safe.
	 *
	 * NOTE: This override is here for documentation and code completion purposes.
	 *
	 * @param bool $doCast
	 *
	 * @return \nxtlvlsoftware\validatron\rule\ArrayType|\nxtlvlsoftware\validatron\rule\Type
	 */
	public function cast(bool $doCast = true) : ArrayType {
		return parent::cast($doCast);
	}

	/**
	 * Validate a value is an array type with a cast option.
	 *
	 * @param mixed $value
	 *
	 * @throws \nxtlvlsoftware\validatron\exception\ValidationFailedException
	 */
	public function validate(&$value) : void {
		if(!$this->cast and !is_array($value)) {
			$this->error(self::NOT_ARRAY); // value isn't an array and we were asked not to cast
		}

		$value = (array) $value;
	}

}