<?php
namespace mageekguy\atoum\tests\units\filesystem;

use
	mageekguy\atoum,
	mageekguy\atoum\filesystem\file as testedClass
;

require_once __DIR__ . '/../../runner.php';

use mageekguy\atoum\mock\stream;

class file extends atoum\test
{
	public function test__construct() {
		$this
			->testedClass
				->isSubClassOf('\\mageekguy\\atoum\\filesystem\\node')
			->if($object = new testedClass())
			->then
				->string($object->getName())->isNotEmpty()
				->variable($object->getParent())->isNull()
				->object($object->getStream())->isInstanceOf('\\mageekguy\\atoum\\mock\\stream\\controller')
				->boolean(is_file($object))->isTrue()
				->string(file_get_contents($object))->isEmpty()
			->if($object = new testedClass($name = uniqid()))
			->then
				->string($object->getName())->isEqualTo($name)
				->variable($object->getParent())->isNull()
				->object($object->getStream())->isInstanceOf('\\mageekguy\\atoum\\mock\\stream\\controller')
				->boolean(is_file($object))->isTrue()
				->string(file_get_contents($object))->isEmpty()
			->if($this->mockGenerator->shunt('__construct'))
			->and($node = new \mock\mageekguy\atoum\filesystem\node())
			->and($node->getMockController()->getStream = stream::get())
			->and($object = new testedClass($name, $node))
			->then
				->string($object->getName())->isIdenticalTo($name)
				->object($object->getParent())->isIdenticalTo($node)
				->object($object->getStream())->isInstanceOf('\\mageekguy\\atoum\\mock\\stream\\controller')
				->boolean(is_file($object))->isTrue()
				->string(file_get_contents($object))->isEmpty()
			->if($object = new testedClass($name, $node))
			->then
				->string($object->getName())->isIdenticalTo($name)
				->object($object->getParent())->isIdenticalTo($node)
				->boolean(is_file($object))->isTrue()
				->string(file_get_contents($object))->isEmpty()
		;
	}

	public function testGetContent() {
		$this
			->if($object = new testedClass())
			->then
				->object($object->setContent($content = uniqid()))->isIdenticalTo($object)
				->string(file_get_contents($object))->isEqualTo($content)
			->if($object = new testedClass())
			->then
				->object($object->content($content = uniqid()))->isIdenticalTo($object)
				->string(file_get_contents($object))->isEqualTo($content)
		;
	}

	public function testGetName() {
		$this
			->if($object = new testedClass($name = uniqid()))
			->then
				->string($object->getName())->isEqualTo($name)
		;
	}

	public function testGetStream() {
		$this
			->if($object = new testedClass())
			->then
				->object($object->getStream())->isInstanceOf('\\mageekguy\\atoum\\mock\\stream\\controller')
		;
	}

	public function testGetParent() {
		$this
			->if($object = new testedClass())
			->then
				->variable($object->getParent())->isNull()
			->if($this->mockGenerator->shunt('__construct'))
			->and($node = new \mock\mageekguy\atoum\filesystem\node())
			->and($node->getMockController()->getStream = stream::get())
			->and($object = new testedClass(uniqid(), $node))
			->then
				->object($object->getParent())->isIdenticalTo($node)
		;
	}

	public function testEnd() {
		$this
			->if($object = new testedClass())
			->then
				->variable($object->end())->isNull()
				->variable($object->close())->isNull()
				->variable($object->parent())->isNull()
			->if($this->mockGenerator->shunt('__construct'))
			->and($node = new \mock\mageekguy\atoum\filesystem\node())
			->and($node->getMockController()->getStream = stream::get())
			->and($object = new testedClass(uniqid(), $node))
			->then
				->object($object->end())->isIdenticalTo($node)
				->object($object->close())->isIdenticalTo($node)
				->object($object->parent())->isIdenticalTo($node)
		;
	}

	public function testReferencedBy() {
		$this
			->if($object = new testedClass())
			->and($reference = null)
			->then
				->object($object->referencedBy($reference))->isIdenticalTo($object)
				->object($reference)->isIdenticalTo($object)
		;
	}

	public function test__call() {
		$this
			->if($object = new \mock\mageekguy\atoum\filesystem\file(uniqid()))
			->and($object->getMockController()->getStream = $stream = new \mock\mageekguy\atoum\stream\controller())
			->and($stream->getMockController()->invoke = function() {})
			->then
				->variable($object->foo())
				->mock($stream)
					->call('invoke')->withArguments('foo', array())->once()
				->variable($object->bar($firstArg = uniqid(), $secondArg = uniqid()))
				->mock($stream)
					->call('invoke')->withArguments('bar', array($firstArg, $secondArg))->once()
		;
	}

	public function test__get() {
		$this
			->if($object = new \mock\mageekguy\atoum\filesystem\file(uniqid()))
			->and($object->getMockController()->getStream = $stream = new \mock\mageekguy\atoum\stream\controller())
			->and($stream->getMockController()->__get = function() {})
			->then
				->variable($object->foo)
				->mock($stream)
					->call('__get')->withArguments('foo')->once()
		;
	}

	public function test__set() {
		$this
			->if($object = new \mock\mageekguy\atoum\filesystem\file(uniqid()))
			->and($object->getMockController()->getStream = $stream = new \mock\mageekguy\atoum\stream\controller())
			->and($stream->getMockController()->__set = function() {})
			->then
				->variable($object->foo = $arg = uniqid())
				->mock($stream)
					->call('__set')->withArguments('foo', $arg)->once()
		;
	}

	public function test__isset() {
		$this
			->if($object = new \mock\mageekguy\atoum\filesystem\file(uniqid()))
			->and($object->getMockController()->getStream = $stream = new \mock\mageekguy\atoum\stream\controller())
			->and($stream->getMockController()->__set = function() {})
			->then
				->boolean(isset($object->foo))->isFalse()
				->mock($stream)
					->call('__isset')->withArguments('foo')->once()
		;
	}

	public function test__toString() {
		$this
			->if($object = new testedClass($name = uniqid()))
			->then
				->castToString($object)->isEqualTo('atoum://' . $name)
		;
	}
}