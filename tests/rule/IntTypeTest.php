<?php

/**
 * IntTypeTest.php – validatron
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

class IntTypeTest extends TestCase {

	/** @var \nxtlvlsoftware\validatron\Validator */
	protected $validator;

	protected function setUp() : void {
		$this->validator = new Validator();
	}

	/**
	 * Make sure a plain int passes.
	 */
	public function testNormalInt() : void {
		$this->validator
			->required("test_int")->int();

		$result = $this->validator->validate([
			"test_int" => 1337
		]);

		$this->assertTrue($result->valid());
	}

	/**
	 * Make sure a non-int isn't cast to int by default.
	 */
	public function testNoDefaultIntCast() : void {
		$this->validator
			->required("test_int")->int();

		$result = $this->validator->validate([
			"test_int" => 13.37
		]);

		$this->assertFalse($result->valid());
	}

	/**
	 * Make sure a non-int value is casted to int when told to.
	 */
	public function testIntCasts() : void {
		$this->validator
			->required("test_int")->int()->cast();

		$result = $this->validator->validate([
			"test_int" => 13.37
		]);

		$this->assertTrue($result->valid());
		$this->assertEquals(13, $result->corrected()->get('test_int'));
	}

	/**
	 * Make sure integers within the bounds of the specified limit pass.
	 */
	public function testIntInBounds() : void {
		$this->validator
			->required("test_int_upper")->int()->max(10)->new()
			->required("test_int_lower")->int()->min(-1);

		$result = $this->validator->validate([
			"test_int_upper" => 10,
			"test_int_lower" => 0,
		]);

		$this->assertTrue($result->valid());
	}

	/**
	 * Make sure integers out of the specified bounds fail.
	 */
	public function testIntOutOfBounds() : void {
		$this->validator
			->required("test_int_upper")->int()->max(10)->new()
			->required("test_int_lower")->int()->min(-1);

		$result = $this->validator->validate([
			"test_int_upper" => 11,
			"test_int_lower" => -2,
		]);

		$this->assertTrue($result->failures()->count() === 2);
	}

	/**
	 * Make sure floats and stringy floats are cast to the correct value.
	 */
	public function testIntNotStrict() {
		$this->validator
			->required("test_float")->int()->cast()->new()
		->required("test_string")->int()->cast();

		$result = $this->validator->validate([
			"test_float" => 3.5,
			"test_string" => "3.9",
		]);

		$this->assertTrue($result->valid());
		$this->assertEquals(3, $result->corrected()->get('test_float'));
		$this->assertEquals(3, $result->corrected()->get('test_string'));
	}

	/**
	 * Make sure floats and stringy floats fail when strict.
	 */
	public function testIntStrict() {
		$this->validator
			->required("test_float")->int()->strict()->cast()->new()
			->required("test_string")->int()->strict()->cast();

		$result = $this->validator->validate([
			"test_float" => 3.5,
			"test_string" => "3.9",
		]);

		$this->assertEquals(2, $result->failures()->count());
	}

}