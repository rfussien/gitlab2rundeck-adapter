<?php namespace G2R\Config;

use G2R\Exception\Exception;
use Noodlehaus\Config;

abstract class AbstractConfig extends Config
{
    protected $path;

    public function __construct($path)
    {
        parent::__construct($path);

        $this->path = $path;

        $this->checkRequirements($this->getRequirements());
    }

    protected function checkRequirements(array $fields)
    {
        foreach ($fields as $field) {
            if (is_null($this->get($field))) {
                throw new Exception(
                    "The field {$field} is missing in {$this->path}"
                );
            }
        }
    }

    public function getFilename()
    {
        return $this->path;
    }

    abstract protected function getRequirements();
}
