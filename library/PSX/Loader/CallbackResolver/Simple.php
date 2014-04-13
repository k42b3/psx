<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2014 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace PSX\Loader\CallbackResolver;

use RuntimeException;
use PSX\Exception;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Loader\Callback;
use PSX\Loader\CallbackResolverInterface;
use PSX\Loader\Location;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Simple
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Simple implements CallbackResolverInterface
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function resolve(Location $location, Request $request, Response $response)
	{
		$source = $location->getSource();

		if(strpos($source, '::') !== false)
		{
			list($className, $method) = explode('::', $source, 2);
		}
		else
		{
			$className = $source;
			$method    = null;
		}

		if(class_exists($className))
		{
			$class = new $className($this->container, $location, $request, $response, $location->getParameters());

			return new Callback($class, $method, array($request, $response));
		}
		else
		{
			throw new RuntimeException('Class "' . $className . '" does not exists');
		}
	}
}