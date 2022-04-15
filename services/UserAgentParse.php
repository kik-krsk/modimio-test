<?php

namespace app\services;

use BrowscapPHP\Browscap;
use BrowscapPHP\BrowscapUpdater;
use BrowscapPHP\Helper\IniLoaderInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use MatthiasMullie\Scrapbook\Adapters\Flysystem;
use MatthiasMullie\Scrapbook\Psr16\SimpleCache;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Yii;

class UserAgentParse
{
    /**
     * Maybe any other PSR-16 compatible caches.
     *
     * @var mixed
     */
    public $cache;

    /**
     * Maybe any other PSR-3 compatible logger.
     *
     * @var mixed
     */
    public $logger;

    /**
     * __construct.
     *
     * @param null|CacheInterface  $cache
     * @param null|LoggerInterface $logger
     * @param mixed                $browscapClass
     */
    public function __construct(?CacheInterface $cache = null, ?LoggerInterface $logger = null, $browscapClass = IniLoaderInterface::PHP_INI_LITE)
    {
        if (is_null($cache)) {
            $cacheDir = Yii::getAlias('@runtime') . '/ua';
            $fileCache = new LocalFilesystemAdapter($cacheDir);
            $filesystem = new Filesystem($fileCache);
            $this->cache = new SimpleCache(new Flysystem($filesystem));
        }
        $this->logger = $logger ?? new Logger('name');
        $bc = new BrowscapUpdater($this->cache, $this->logger);
        $bc->update($browscapClass);
    }

    /**
     * parse.
     *
     * @param null|string $string
     */
    public function parse(?string $string)
    {
        $bc = new Browscap($this->cache, $this->logger);

        return $string ? $bc->getBrowser($string) : $bc->getBrowser();
    }
}
