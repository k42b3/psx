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

namespace PSX\Console\Generate;

use PSX\Test\CommandTestCase;
use PSX\Test\Environment;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;

/**
 * ApiCommandTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class ApiCommandTest extends CommandTestCase
{
	public function testCommand()
	{
		$command = $this->getMockBuilder('PSX\Console\Generate\ApiCommand')
			->setConstructorArgs(array(Environment::getContainer()))
			->setMethods(array('makeDir', 'writeFile', 'isDir', 'isFile'))
			->getMock();

		$command->expects($this->at(0))
			->method('isDir')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo'))
			->will($this->returnValue(false));

		$command->expects($this->at(1))
			->method('makeDir')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo'));

		$command->expects($this->at(2))
			->method('isFile')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'Bar.php'))
			->will($this->returnValue(false));

		$command->expects($this->at(3))
			->method('writeFile')
			->with(
				$this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'Bar.php'), 
				$this->callback(function($source){
					$this->assertSource($this->getExpectedSource(), $source);
					return true;
				})
			);

		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'namespace' => 'Acme\Foo\Bar',
			'services'  => 'connection,template'
		));
	}

	/**
	 * @expectedException \RuntimeException
	 */
	public function testCommandFileExists()
	{
		$command = $this->getMockBuilder('PSX\Console\Generate\ApiCommand')
			->setConstructorArgs(array(Environment::getContainer()))
			->setMethods(array('makeDir', 'writeFile', 'isDir', 'isFile'))
			->getMock();

		$command->expects($this->at(0))
			->method('isDir')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo'))
			->will($this->returnValue(false));

		$command->expects($this->at(1))
			->method('makeDir')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo'));

		$command->expects($this->at(2))
			->method('isFile')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'Bar.php'))
			->will($this->returnValue(true));

		$command->expects($this->never())
			->method('writeFile');

		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'namespace' => 'Acme\Foo\Bar',
			'services'  => 'connection,template'
		));
	}

	public function testCommandAvailable()
	{
		$command = Environment::getService('console')->find('generate:api');

		$this->assertInstanceOf('PSX\Console\Generate\ApiCommand', $command);
	}

	public function testCommandRamlFile()
	{
		$command = $this->getMockBuilder('PSX\Console\Generate\ApiCommand')
			->setConstructorArgs(array(Environment::getContainer()))
			->setMethods(array('makeDir', 'writeFile', 'isDir', 'isFile'))
			->getMock();

		$command->expects($this->at(0))
			->method('isDir')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo'))
			->will($this->returnValue(false));

		$command->expects($this->at(1))
			->method('makeDir')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo'));

		$command->expects($this->at(2))
			->method('isFile')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'Bar.php'))
			->will($this->returnValue(false));

		$command->expects($this->at(3))
			->method('isFile')
			->with($this->equalTo('./test.raml'))
			->will($this->returnValue(true));

		$command->expects($this->at(4))
			->method('writeFile')
			->with(
				$this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'Bar.php'), 
				$this->callback(function($source){
					$this->assertSource($this->getExpectedRamlSource(), $source);
					return true;
				})
			);

		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'--raml'    => './test.raml',
			'namespace' => 'Acme\Foo\Bar',
			'services'  => 'connection'
		));
	}

	/**
	 * @expectedException \RuntimeException
	 */
	public function testCommandRamlFileNotExisting()
	{
		$command = $this->getMockBuilder('PSX\Console\Generate\ApiCommand')
			->setConstructorArgs(array(Environment::getContainer()))
			->setMethods(array('makeDir', 'writeFile', 'isDir', 'isFile'))
			->getMock();

		$command->expects($this->at(0))
			->method('isDir')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo'))
			->will($this->returnValue(false));

		$command->expects($this->at(1))
			->method('makeDir')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo'));

		$command->expects($this->at(2))
			->method('isFile')
			->with($this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'Bar.php'))
			->will($this->returnValue(false));

		$command->expects($this->at(3))
			->method('isFile')
			->with($this->equalTo('./test.raml'))
			->will($this->returnValue(false));

		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'--raml'    => './test.raml',
			'namespace' => 'Acme\Foo\Bar',
			'services'  => 'connection'
		));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCommandInvalidService()
	{
		$command = $this->getMockBuilder('PSX\Console\Generate\ApiCommand')
			->setConstructorArgs(array(Environment::getContainer()))
			->setMethods(array('makeDir', 'writeFile'))
			->getMock();

		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'namespace' => 'Acme\Foo\Bar',
			'services'  => 'connection,foo'
		));
	}

	public function testCommandEmptyService()
	{
		$command = $this->getMockBuilder('PSX\Console\Generate\ApiCommand')
			->setConstructorArgs(array(Environment::getContainer()))
			->setMethods(array('makeDir', 'writeFile'))
			->getMock();

		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'namespace' => 'Acme\Foo\Bar',
		));
	}

	public function testCommandOtherDiContainer()
	{
		$container = new Container();
		$container->set('foo', new \stdClass());

		$command = $this->getMockBuilder('PSX\Console\Generate\ApiCommand')
			->setConstructorArgs(array($container))
			->setMethods(array('makeDir', 'writeFile'))
			->getMock();

		$command->expects($this->at(1))
			->method('writeFile')
			->with(
				$this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'Bar.php'), 
				$this->callback(function($source){
					$this->assertSource($this->getExpectedOtherDiSource(), $source);
					return true;
				})
			);

		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'namespace' => 'Acme\Foo\Bar',
			'services'  => 'foo'
		));
	}

	public function testCommandOtherDiContainerNoObject()
	{
		$container = new Container();
		$container->set('foo', array('foo', 'bar'));

		$command = $this->getMockBuilder('PSX\Console\Generate\ApiCommand')
			->setConstructorArgs(array($container))
			->setMethods(array('makeDir', 'writeFile'))
			->getMock();

		$command->expects($this->at(1))
			->method('writeFile')
			->with(
				$this->equalTo('library' . DIRECTORY_SEPARATOR . 'Acme' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'Bar.php'), 
				$this->callback(function($source){
					$this->assertSource($this->getExpectedOtherDiSourceNoObject(), $source);
					return true;
				})
			);

		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'namespace' => 'Acme\Foo\Bar',
			'services'  => 'foo'
		));
	}

	protected function assertSource($expect, $actual)
	{
		$expect = str_replace(array("\r\n", "\n", "\r"), "\n", $expect);
		$actual = str_replace(array("\r\n", "\n", "\r"), "\n", $actual);

		$this->assertEquals($expect, $actual);
	}

	protected function getExpectedSource()
	{
		return <<<'PHP'
<?php

namespace Acme\Foo;

use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Loader\Context;

/**
 * Bar
 *
 * @see http://phpsx.org/doc/design/controller.html
 */
class Bar extends SchemaApiAbstract
{
	/**
	 * @Inject
	 * @var \Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @Inject
	 * @var \PSX\TemplateInterface
	 */
	protected $template;

	/**
	 * @return \PSX\Api\DocumentationInterface
	 */
	public function getDocumentation()
	{
		$resource = new Resource(Resource::STATUS_ACTIVE, $this->context->get(Context::KEY_PATH));

		$resource->addMethod(Resource\Factory::getMethod('GET')
			->addResponse(200, $this->schemaManager->getSchema('Acme\Foo\Schema\Collection')));

		return new Documentation\Simple($resource);
	}

	/**
	 * Returns the GET response
	 *
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doGet(Version $version)
	{
		return array(
			'message' => 'This is the default controller of PSX'
		);
	}

	/**
	 * Returns the POST response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doCreate(RecordInterface $record, Version $version)
	{
	}

	/**
	 * Returns the PUT response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doUpdate(RecordInterface $record, Version $version)
	{
	}

	/**
	 * Returns the DELETE response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doDelete(RecordInterface $record, Version $version)
	{
	}
}

PHP;
	}

	protected function getExpectedOtherDiSource()
	{
		return <<<'PHP'
<?php

namespace Acme\Foo;

use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Loader\Context;

/**
 * Bar
 *
 * @see http://phpsx.org/doc/design/controller.html
 */
class Bar extends SchemaApiAbstract
{
	/**
	 * @Inject
	 * @var stdClass
	 */
	protected $foo;

	/**
	 * @return \PSX\Api\DocumentationInterface
	 */
	public function getDocumentation()
	{
		$resource = new Resource(Resource::STATUS_ACTIVE, $this->context->get(Context::KEY_PATH));

		$resource->addMethod(Resource\Factory::getMethod('GET')
			->addResponse(200, $this->schemaManager->getSchema('Acme\Foo\Schema\Collection')));

		return new Documentation\Simple($resource);
	}

	/**
	 * Returns the GET response
	 *
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doGet(Version $version)
	{
		return array(
			'message' => 'This is the default controller of PSX'
		);
	}

	/**
	 * Returns the POST response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doCreate(RecordInterface $record, Version $version)
	{
	}

	/**
	 * Returns the PUT response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doUpdate(RecordInterface $record, Version $version)
	{
	}

	/**
	 * Returns the DELETE response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doDelete(RecordInterface $record, Version $version)
	{
	}
}

PHP;
	}

	protected function getExpectedOtherDiSourceNoObject()
	{
		return <<<'PHP'
<?php

namespace Acme\Foo;

use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Loader\Context;

/**
 * Bar
 *
 * @see http://phpsx.org/doc/design/controller.html
 */
class Bar extends SchemaApiAbstract
{
	/**
	 * @Inject
	 * @var array
	 */
	protected $foo;

	/**
	 * @return \PSX\Api\DocumentationInterface
	 */
	public function getDocumentation()
	{
		$resource = new Resource(Resource::STATUS_ACTIVE, $this->context->get(Context::KEY_PATH));

		$resource->addMethod(Resource\Factory::getMethod('GET')
			->addResponse(200, $this->schemaManager->getSchema('Acme\Foo\Schema\Collection')));

		return new Documentation\Simple($resource);
	}

	/**
	 * Returns the GET response
	 *
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doGet(Version $version)
	{
		return array(
			'message' => 'This is the default controller of PSX'
		);
	}

	/**
	 * Returns the POST response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doCreate(RecordInterface $record, Version $version)
	{
	}

	/**
	 * Returns the PUT response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doUpdate(RecordInterface $record, Version $version)
	{
	}

	/**
	 * Returns the DELETE response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doDelete(RecordInterface $record, Version $version)
	{
	}
}

PHP;
	}

	protected function getExpectedRamlSource()
	{
		return <<<'PHP'
<?php

namespace Acme\Foo;

use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Loader\Context;

/**
 * Bar
 *
 * @see http://phpsx.org/doc/design/controller.html
 */
class Bar extends SchemaApiAbstract
{
	/**
	 * @Inject
	 * @var \Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @return \PSX\Api\DocumentationInterface
	 */
	public function getDocumentation()
	{
		return Documentation\Parser\Raml::fromFile('./test.raml', $this->context->get(Context::KEY_PATH));
	}

	/**
	 * Returns the GET response
	 *
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doGet(Version $version)
	{
		return array(
			'message' => 'This is the default controller of PSX'
		);
	}

	/**
	 * Returns the POST response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doCreate(RecordInterface $record, Version $version)
	{
	}

	/**
	 * Returns the PUT response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doUpdate(RecordInterface $record, Version $version)
	{
	}

	/**
	 * Returns the DELETE response
	 *
	 * @param \PSX\Data\RecordInterface $record
	 * @param \PSX\Api\Version $version
	 * @return array|\PSX\Data\RecordInterface
	 */
	protected function doDelete(RecordInterface $record, Version $version)
	{
	}
}

PHP;
	}
}

