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
        new FileDescriptor('/tmp/unexistent');
        new FileDescriptor('file://tmp/unexistent');
        new FileDescriptor('http://autocrm.ru/favicon.ico');
        new FileDescriptor(fopen('data://text/plain;base64,SGVsbG8gd29ybGQh', 'r'));
    }

    /**
     * @param $scheme
     * @param $urlOrResource
     *
     * @dataProvider provideGetScheme
     */
    public function testGetScheme($scheme, $urlOrResource)
    {
        $file = new FileDescriptor($urlOrResource);
        $this->assertEquals($scheme, $file->getScheme());
    }

    public function testGetBasename()
    {
        $file = new FileDescriptor('/tmp/some/file');
        $this->assertEquals('file', $file->getBasename());

        $file = new FileDescriptor('/tmp/some/');
        $this->assertEquals('some', $file->getBasename());
    }

    public function testGetDirname()
    {
        $file = new FileDescriptor('/tmp/some/file');
        $this->assertEquals('/tmp/some', $file->getDirname());

        $file = new FileDescriptor('/tmp/some/');
        $this->assertEquals('/tmp', $file->getDirname());

        $file = new FileDescriptor('file:///tmp/some/file');
        $this->assertEquals('/tmp/some', $file->getDirname());
    }

    public function testGetPath()
    {
        $file = new FileDescriptor('/tmp/some/file');
        $this->assertEquals('/tmp/some/file', $file->getPath());

        $file = new FileDescriptor('file:///tmp/some/file');
        $this->assertEquals('/tmp/some/file', $file->getPath());

        $file = new FileDescriptor('some-scheme://tmp/some/file');
        $this->assertEquals('tmp/some/file', $file->getPath());
    }

    public function testGetUri()
    {
        $file = new FileDescriptor('/tmp/some/file');
        $this->assertEquals('file:///tmp/some/file', $file->getUri());

        $file = new FileDescriptor('some-scheme:///tmp/some/file');
        $this->assertEquals('some-scheme:///tmp/some/file', $file->getUri());

        $file = new FileDescriptor('file:///tmp/some/file');
        $this->assertEquals('file:///tmp/some/file', $file->getUri());
    }

    public function provideGetScheme()
    {
        return array(
            array(FileDescriptor::SCHEME_LOCAL, '/tmp/file/name'),
            array(FileDescriptor::SCHEME_LOCAL, 'file:///tmp/file/name'),
            array(FileDescriptor::SCHEME_RESOURCE, fopen('data://text/plain;base64,SGVsbG8gd29ybGQh', 'r')),
            array('custom-scheme', 'custom-scheme://text/plain;base64,SGVsbG8gd29ybGQh'),
        );
    }

} 
