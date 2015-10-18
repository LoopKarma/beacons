<?php
namespace app\helpers;

use League\Csv\Reader;

class CsvParser
{
    public $error;

    public $delimiter = ';';
    public $enclosure = '"';
    public $escape = '\\';

    public function __construct(array $config = null)
    {
        if (is_array($config)) {
            $this->setConfig($config);
        }
    }

    public function getData($file, array $fieldsKeys = null)
    {
        $reader = $this->getReader($file);
        if ($reader instanceof Reader) {
            if (!$fieldsKeys) {
                return $reader->fetchAll();
            } else {
                return $reader->fetchAssoc($fieldsKeys);
            }
        }
        return false;
    }

    protected function setConfig(array $config)
    {
        foreach ($config as $configKey => $configValue) {
            if (isset($this->{$configKey}) && $this->validateStringConfigParams($configValue)) {
                $this->{$configKey} = $configKey;
            }
        }
    }

    private function validateStringConfigParams($value = false, $stringLength = 2)
    {
        return ($value && strlen($value) <= $stringLength);
    }

    private function getReader($file)
    {
        $reader = false;
        if (file_exists($file)) {
            $reader = Reader::createFromPath($file);
            $reader->setDelimiter($this->delimiter);
            $reader->setEnclosure($this->enclosure);
            $reader->setEscape($this->escape);
        } else {
            $this->error = 'File is not exists';
        }
        return $reader;
    }
}
