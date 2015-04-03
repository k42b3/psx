<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\Data\Schema\Parser;

use PSX\Http;

/**
 * JsonSchemaTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class JsonSchemaTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * The offical json schema is recursive so we check whether we can parse it
	 * without a problem
	 */
	public function testParseRecursion()
	{
		$schema   = JsonSchema::fromFile(__DIR__ . '/schema.json');
		$property = $schema->getDefinition();

		$this->assertInstanceOf('PSX\Data\Schema\PropertyInterface', $property);
	}

	public function testParseExternalResource()
	{
		$handler  = Http\Handler\Mock::getByXmlDefinition(__DIR__ . '/http_mock.xml');
		$http     = new Http($handler);
		$resolver = new JsonSchema\RefResolver($http);

		$parser   = new JsonSchema(__DIR__, $resolver);
		$schema   = $parser->parse(file_get_contents(__DIR__ . '/test_schema.json'));
		$property = $schema->getDefinition();

		$this->assertInstanceOf('PSX\Data\Schema\PropertyInterface', $property);
		$this->assertInstanceOf('PSX\Data\Schema\Property\Integer', $property->get('id'));
		$this->assertInstanceOf('PSX\Data\Schema\Property\ComplexType', $property->get('bar'));
		$this->assertInstanceOf('PSX\Data\Schema\Property\ArrayType', $property->get('bar')->get('number'));
		$this->assertInstanceOf('PSX\Data\Schema\Property\Integer', $property->get('bar')->get('number')->getPrototype());
		$this->assertEquals(4, $property->get('bar')->get('number')->getPrototype()->getMin());
		$this->assertInstanceOf('PSX\Data\Schema\Property\Integer', $property->get('value'));
		$this->assertEquals(0, $property->get('value')->getMin());

		$this->assertInstanceOf('PSX\Data\Schema\Property\ComplexType', $property->get('object'));
		$this->assertEquals('description', $property->get('object')->getDescription());
		$this->assertInstanceOf('PSX\Data\Schema\Property\ArrayType', $property->get('array'));
		$this->assertEquals(1, $property->get('array')->getMinLength());
		$this->assertEquals(9, $property->get('array')->getMaxLength());
		$this->assertInstanceOf('PSX\Data\Schema\Property\Boolean', $property->get('boolean'));
		$this->assertInstanceOf('PSX\Data\Schema\Property\Integer', $property->get('integer'));
		$this->assertEquals(1, $property->get('integer')->getMin());
		$this->assertEquals(4, $property->get('integer')->getMax());
		$this->assertInstanceOf('PSX\Data\Schema\Property\Float', $property->get('number'));
		$this->assertInstanceOf('PSX\Data\Schema\Property\String', $property->get('string'));
		$this->assertEquals('[A-z]+', $property->get('string')->getPattern());
		$this->assertEquals(['foo', 'bar'], $property->get('string')->getEnumeration());
		$this->assertEquals(2, $property->get('string')->getMinLength());
		$this->assertEquals(4, $property->get('string')->getMaxLength());
		$this->assertInstanceOf('PSX\Data\Schema\Property\Date', $property->get('date'));
		$this->assertInstanceOf('PSX\Data\Schema\Property\DateTime', $property->get('datetime'));
		$this->assertInstanceOf('PSX\Data\Schema\Property\Duration', $property->get('duration'));
		$this->assertInstanceOf('PSX\Data\Schema\Property\Time', $property->get('time'));
		$this->assertInstanceOf('PSX\Data\Schema\Property\String', $property->get('unknown'));
	}
}
