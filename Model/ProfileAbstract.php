<?php

namespace LeoVirgilio\Infobase\Model;

use LeoVirgilio\Infobase\Model\ProfileInterface;
use Magento\Framework\Filesystem\Directory\ReadFactory;

abstract class ProfileAbstract implements ProfileInterface
{
    /**
     * @var string
     */
    protected string $_filePath;

    /**
     * @var string
     */
    protected string $_fileName;

    /**
     * @var string
     */
    protected string $_relativePath;

    /**
     * @var string
     */
    protected string $_content;

    /**
     * @var ReadFactory
     */
    protected ReadFactory $_directoryReadFactory;

    /**
     * @param ReadFactory $directoryReadFactory
     */
    public function __construct(ReadFactory $directoryReadFactory)
    {
        $this->_directoryReadFactory = $directoryReadFactory;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void
    {
        $this->_filePath = $filePath;
    }

    /**
     * @return void
     */
    protected function _explodePath(): void
    {
        $exploded = explode(DIRECTORY_SEPARATOR, $this->_filePath);

        $this->_fileName = array_pop($exploded);
        $this->_relativePath = implode(DIRECTORY_SEPARATOR, $exploded) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    protected function _output(): void
    {
        $directoryRead = $this->_directoryReadFactory->create($this->_relativePath);
        $file = $directoryRead->openFile($this->_fileName);

        $this->_content = $file->readAll();
    }

}
