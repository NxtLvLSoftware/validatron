<?php

/**
 * BoolTypeTest.php – validatron
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

namespace nxtlvlsoftware\tests\validatron\rule;

use nxtlvlsoftware\tests\validatron\TestCase;
use nxtlvlsoftware\validatron\Validator;

class BoolTypeTest extends TestCase {

	/** @var \nxtlvlsoftware\validatron\Validator */
	protected $validator;

	protected function setUp() : void {
		$this->validator = new Validator();
	}

	/**
	 * Make sure a plain bool passes.
	 */
	public function testNormalBool() : void {
		$this->validator
			->required("test_bool")->bool()->new()
			->required("test_bool_false")->bool();

		$result = $this->validator->validate([
			"test_bool" => true,
			"test_bool_false" => false,
		]);

		$this->assertTrue($result->valid());
	}

	/**
	 * Make sure a non-bool isn't cast to bool by default.
	 */
	public function testNoDefaultBoolCast() : void {
		$this->validator
			->required("test_bool")->bool()->new()
			->required("test_bool_false")->bool();

		$result = $this->validator->validate([
			"test_bool" => "yes",
			"test_bool_false" => "no",
		]);

		$this->assertFalse($result->valid());
	}

	/**
	 * Make sure a non-bool value is casted to bool when told to.
	 */
	public function testBoolCasts() : void {
		$this->validator
			->required("test_bool")->bool()->cast()->new()
			->required("test_bool_false")->bool()->cast();

		$result = $this->validator->validate([
			"test_bool" => "yes",
			"test_bool_false" => "no",
		]);

		$this->assertTrue($result->valid());
	}

}