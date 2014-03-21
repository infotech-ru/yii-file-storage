<?php
/**
 * @author Anton Tyutin <anton@tyutin.ru>
 */

namespace Infotech\FileStorage;

use PHPUnit_Framework_TestCase;

class FileDescriptorTest extends PHPUnit_Framework_TestCase
{

    public function testCreateFromPath()
    {
        new FileDescriptor('/tmp/unexistent', 0);
        new FileDescriptor('file://tmp/unexistent', 0);
        new FileDescriptor('http://autocrm.ru/favicon.ico', 0);
        new FileDescriptor(fopen('data://text/plain;base64,SGVsbG8gd29ybGQh', 'r'), 0);
    }

    /**
     * @expectedException Infotech\FileStorage\FileDescriptorException
     */
    public function testCreateWrongSize()
    {
        new FileDescriptor('/tmp/some/file', -1);
    }

    /**
     * @dataProvider provideCreateWrongPath
     * @expectedException Infotech\FileStorage\FileDescriptorException
     */
    public function testCreateWrongPath($wrongPath)
    {
        new FileDescriptor($wrongPath, 0);
    }

    /**
     * @param $scheme
     * @param $urlOrResource
     *
     * @dataProvider provideGetScheme
     */
    public function testGetScheme($scheme, $urlOrResource)
    {
        $file = new FileDescriptor($urlOrResource, 0);
        $this->assertEquals($scheme, $file->getScheme());
    }

    public function testGetBasename()
    {
        $file = new FileDescriptor('/tmp/some/file', 0);
        $this->assertEquals('file', $file->getBasename());

        $file = new FileDescriptor('/tmp/some/', 0);
        $this->assertEquals('some', $file->getBasename());
    }

    public function testGetDirname()
    {
        $file = new FileDescriptor('/tmp/some/file', 0);
        $this->assertEquals('/tmp/some', $file->getDirname());

        $file = new FileDescriptor('/tmp/some/', 0);
        $this->assertEquals('/tmp', $file->getDirname());

        $file = new FileDescriptor('file:///tmp/some/file', 0);
        $this->assertEquals('/tmp/some', $file->getDirname());
    }

    public function testGetPath()
    {
        $file = new FileDescriptor('/tmp/some/file', 0);
        $this->assertEquals('/tmp/some/file', $file->getPath());

        $file = new FileDescriptor('file:///tmp/some/file', 0);
        $this->assertEquals('/tmp/some/file', $file->getPath());

        $file = new FileDescriptor('some-scheme://tmp/some/file', 0);
        $this->assertEquals('tmp/some/file', $file->getPath());
    }

    public function testGetUri()
    {
        $file = new FileDescriptor('/tmp/some/file', 0);
        $this->assertEquals('file:///tmp/some/file', $file->getUri());

        $file = new FileDescriptor('some-scheme:///tmp/some/file', 0);
        $this->assertEquals('some-scheme:///tmp/some/file', $file->getUri());

        $file = new FileDescriptor('file:///tmp/some/file', 0);
        $this->assertEquals('file:///tmp/some/file', $file->getUri());
    }

    public function testGetSize()
    {
        $file = new FileDescriptor('/tmp/some/file', 523456);
        $this->assertEquals(523456, $file->getSize());

        $file = new FileDescriptor('some-scheme:///tmp/some/file', 0);
        $this->assertEquals(0, $file->getSize());
    }

    public function testGetMime()
    {
        $file = new FileDescriptor('/tmp/some/file', 0);
        $this->assertEquals(FileDescriptor::MIME_TYPE_DEFAULT, $file->getMime());

        $file = new FileDescriptor('some-scheme:///tmp/some/file', 0, 'text/plain');
        $this->assertEquals('text/plain', $file->getMime());
    }

    // providers


    public function provideGetScheme()
    {
        return array(
            array(FileDescriptor::SCHEME_LOCAL, '/tmp/file/name'),
            array(FileDescriptor::SCHEME_LOCAL, 'file:///tmp/file/name'),
            array(FileDescriptor::SCHEME_RESOURCE, fopen('data://text/plain;base64,SGVsbG8gd29ybGQh', 'r')),
            array('custom-scheme', 'custom-scheme://text/plain;base64,SGVsbG8gd29ybGQh'),
        );
    }

    public function provideCreateWrongPath()
    {
        return array(
            array(''),
            array([]),
            array(new \stdClass),
        );
    }

} 
