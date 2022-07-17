<?php
namespace LeoVirgilio\Infobase\Model;

use Magento\Framework\Filesystem\Directory\ReadFactory;

interface ProfileInterface
{
    /**
     * @param ReadFactory $directoryReadFactory
     */
    public function __construct(ReadFactory $directoryReadFactory);

    /**
     * @return array
     */
    public function read(): array;

    /**
     * @param string $filePath
     * @return void
     */
    public function setFilePath(string $filePath): void;
}
