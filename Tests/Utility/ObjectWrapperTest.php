<?php

namespace Fgms\EmailInquiriesBundle\Tests\Utility;

class ObjectWrapperTest extends \PHPUnit_Framework_TestCase
{
    private function expectThrows()
    {
        $this->expectException(\LogicException::class);
    }

    private function create($str = '{}')
    {
        return new \Fgms\EmailInquiriesBundle\Utility\BasicObjectWrapper(
			\Fgms\EmailInquiriesBundle\Json\Json::decodeRaw($str)
		);
    }

    public function testGetString()
    {
        $obj = $this->create('{"test":"foo"}');
        $str = $obj->getString('test');
        $this->assertSame('foo',$str);
    }

    public function testGetStringEmpty()
    {
        $obj = $this->create();
        $this->expectThrows();
        $obj->getString('test');
    }

    public function testGetStringMismatch()
    {
        $obj = $this->create('{"test":5}');
        $this->expectThrows();
        $obj->getString('test');
    }

    public function testGetOptionalString()
    {
        $obj = $this->create('{"foo":"bar"}');
        $str = $obj->getOptionalString('foo');
        $this->assertSame('bar',$str);
    }

    public function testGetOptionalStringEmpty()
    {
        $obj = $this->create();
        $str = $obj->getOptionalString('bar');
        $this->assertNull($str);
    }

    public function testGetOptionalStringMismatch()
    {
        $obj = $this->create('{"quux":17.2}');
        $this->expectThrows();
        $obj->getOptionalString('quux');
    }

    public function testGetObject()
    {
        $obj = $this->create('{"test":{"foo":"bar"}}');
        $o = $obj->getObject('test');
        $this->assertInstanceOf(\Fgms\EmailInquiriesBundle\Utility\ObjectWrapper::class,$o);
        $this->assertSame('bar',$o->getString('foo'));
    }

    public function testGetObjectEmpty()
    {
        $obj = $this->create();
        $this->expectThrows();
        $obj->getObject('test');
    }

    public function testGetObjectMismatch()
    {
        $obj = $this->create('{"test":5}');
        $this->expectThrows();
        $obj->getObject('test');
    }

    public function testGetOptionalObject()
    {
        $obj = $this->create('{"test":{"foo":"bar"}}');
        $o = $obj->getOptionalObject('test');
        $this->assertInstanceOf(\Fgms\EmailInquiriesBundle\Utility\ObjectWrapper::class,$o);
        $this->assertSame('bar',$o->getString('foo'));
    }

    public function testGetOptionalObjectEmpty()
    {
        $obj = $this->create();
        $o = $obj->getOptionalObject('bar');
        $this->assertNull($o);
    }

    public function testGetOptionalObjectMismatch()
    {
        $obj = $this->create('{"quux":17.2}');
        $this->expectThrows();
        $obj->getOptionalObject('quux');
    }

    public function testGetInteger()
    {
        $obj = $this->create('{"test":5}');
        $this->assertSame(5,$obj->getInteger('test'));
    }

    public function testGetIntegerEmpty()
    {
        $obj = $this->create();
        $this->expectThrows();
        $obj->getInteger('test');
    }

    public function testGetIntegerMismatch()
    {
        $obj = $this->create('{"test":5.2}');
        $this->expectThrows();
        $obj->getInteger('test');
    }

    public function testGetOptionalInteger()
    {
        $obj = $this->create('{"test":5}');
        $this->assertSame(5,$obj->getOptionalInteger('test'));
    }

    public function testGetOptionalIntegerEmpty()
    {
        $obj = $this->create();
        $this->assertNull($obj->getOptionalInteger('bar'));
    }

    public function testGetOptionalIntegerMismatch()
    {
        $obj = $this->create('{"quux":17.2}');
        $this->expectThrows();
        $obj->getOptionalInteger('quux');
    }

	public function testGetNull()
	{
		$obj = $this->create('{"foo":null}');
		$val = $obj->getNull('foo');
		$this->assertNull($val);
	}

	public function testGetNullMissing()
	{
		$obj = $this->create('{}');
		$this->expectThrows();
		$obj->getNull('foo');
	}

	public function testGetMultiple()
	{
		$obj = $this->create('{"foo":5}');
		$val = $obj->getStringOrInteger('foo');
		$this->assertSame(5,$val);
	}

	public function testGetMultipleMismatch()
	{
		$obj = $this->create('{"foo":5}');
		$this->expectThrows();
		$obj->getStringOrArray('foo');
	}
}
