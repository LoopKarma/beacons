<?php
namespace app\helpers;

use League\Csv\Writer;

class CsvWriter
{
    /** @var \League\Csv\Writer */
    protected $writer;

    public $delimiter = ",";
    public $newLine = "\n";

    public function __construct(array $config = [])
    {
        $this->writer = Writer::createFromFileObject(new \SplTempFileObject());
        $this->setConfig($config);
    }

    protected function setConfig(array $config)
    {
        if (isset($config['delimiter'])) {
            $this->delimiter = $config['delimiter'];
        }
        $this->writer->setDelimiter($this->delimiter);

        if (isset($config['newLine'])) {
            $this->newLine = $config['newLine'];
        }
        $this->writer->setNewline($this->newLine);
    }

    public function writeHeader(array $header)
    {
        $this->writer->insertOne($header);
    }

    public function writeContent(array $content)
    {
        $this->writer->insertAll($content);
    }

    public function writeLine(array $line)
    {
        $this->writer->insertOne($line);
    }

    public function getContent()
    {
        return $this->writer;
    }

    public function saveToFile($filePath, $openMode = 'w+')
    {
        $file = new \SplFileObject($filePath, $openMode);
        return $file->fwrite($this->writer);
    }

    public function output($fileName)
    {
        $this->writer->output($fileName);
    }
}