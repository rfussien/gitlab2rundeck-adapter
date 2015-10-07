<?php namespace G2R;

use PHPUnit_Framework_TestCase;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    protected $dataFolder;

    public function setup()
    {
        $this->dataFolder = dirname(__DIR__) . "/data";
    }

    protected function loadFile($file)
    {
        return file_get_contents($this->locate($file));
    }

    protected function locate($file)
    {
        return dirname(__DIR__) . "/data/{$file}";
    }
}
