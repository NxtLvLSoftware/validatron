<?php

/**
 * IValidatable.php – validatron
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

/**
 * Interface implemented by all classes that can perform validation of a collection.
 */
interface IValidatable {

	/**
	 * Creates a new collection of rules for a required key.
	 *
	 * @param string $key
	 *
	 * @return \nxtlvlsoftware\validatron\Collection
	 */
	public function required(string $key) : Collection;

	/**
	 * Creates a new collection of rules for an optional key.
	 *
	 * @param string $key
	 * @param        $default
	 *
	 * @return \nxtlvlsoftware\validatron\Collection
	 */
	public function optional(string $key, $default) : Collection;

	/**
	 * Creates a new collection of rules for repeating nested values.
	 *
	 * @param string|null $key
	 * @param int|null    $min
	 * @param int|null    $max
	 *
	 * @return \nxtlvlsoftware\validatron\Collection
	 */
	public function repeats(?string $key = null, ?int $min = null, ?int $max = null) : Collection;

}