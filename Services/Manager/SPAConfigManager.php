<?php

namespace HOA\Bundle\SinglePageBundle\Services\Manager;


use Dacorp\ExtraBundle\Services\YmlFileManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class SPAConfigManager
{
    /**
     * @var YmlFileManager
     */
    private $ymlFileManager;

    /**
     * Path for configuration files
     * @var $path string
     */
    private $path;


    public function __construct(YmlFileManager $ymlFileManager, $path)
    {
        $this->ymlFileManager = $ymlFileManager;
        $this->path = $path;
    }

    public function loadSPAConfig($siteId = 'default',$getFile=false)
    {
        return ($this->existSPAConfig($siteId))?((!$getFile)?$this->ymlFileManager->loadYmlFile($siteId . '.yml',$this->path):file_get_contents($this->path.$siteId.'.yml')):null;

    }

    public function saveSPAConfig($siteId = 'default',$configData)
    {
        try {
            $yaml = new Parser();
            $value = $yaml->parse($configData);
            file_put_contents($this->path.$siteId.'.yml', $configData);
            return true;
        } catch (ParseException $e) {
           return false;
        }
    }

    public function existSPAConfig($siteId)
    {
        $fs = new Filesystem();
        return $fs->exists($this->path . $siteId . '.yml');
    }
    public function getAllSPAConfig()
    {
        $finder = new Finder();
        $finder->files()->in($this->path)->name('*.yml')->notName('*.save.yml')->notName('*.spec.yml');
        $validSPAConfigFiles=array();
        foreach ($finder as $file) {
            $validSPAConfigFiles[] =preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->getFilename());
        }
        return $validSPAConfigFiles;
    }
}