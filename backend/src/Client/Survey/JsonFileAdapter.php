<?php

namespace IWD\JOBINTERVIEW\Client\Survey;

use IWD\JOBINTERVIEW\Client\Exceptions\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class JsonFileAdapter implements SubmissionsDataSource
{
    /**
     * @var string
     */
    private $dataPath;

    public function __construct($dataPath)
    {
        // check if directory exists
        if(!file_exists($dataPath) || !is_dir($dataPath)){
            throw new DirectoryNotFoundException($dataPath);
        }
        $this->dataPath = $dataPath;
    }

    /**
     * @inheritDoc
     */
    public function data(): iterable
    {
        $finder = new Finder();
        $jsonFiles = $finder->files()->in($this->dataPath)->name('*.json');
        foreach($jsonFiles as $file){
            /*
             * I've decided to log and skip here because I don't wan't the API to fail if some external factor
             * leave an invalid file. We could also decide to throw if we are confident that nothing else could
             * mess with the data directory.
             */
            if(!$file->isReadable()){
                // TODO if we had a logging service we should spawn a warning here
                // $this->logger->warning('Skipped file "'.$filename.'" it isn\'t readable.');
            }
            else if(!$this->isValid($file)){
                // TODO if we had a logging service we should spawn a warning here
                // $this->logger->warning('Skipped file "'.$filename.'" unexpected format');
            }
            else{
                $contents = file_get_contents($file->getRealPath());
                yield json_decode($contents, JSON_OBJECT_AS_ARRAY);
            }
        }
    }

    private function isValid(SplFileInfo $file): bool
    {
        // TODO proper validation, ideally with json-schema
        // for now I'll just fallback to bare minimum
        $contents = file_get_contents($file->getRealPath());
        json_decode($contents);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
