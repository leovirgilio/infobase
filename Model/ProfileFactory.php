<?php

namespace LeoVirgilio\Infobase\Model;

use Magento\Framework\ObjectManagerInterface;
use LeoVirgilio\Infobase\Model\Profiles\Csv;
use LeoVirgilio\Infobase\Model\Profiles\Json;

class ProfileFactory
{
    /**
     * @var array|string[]
     */
    private array $_profileResources = [
        'sample-csv'  => Csv::class,
        'sample-json' => Json::class,
    ];

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $_objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * @param string $profileName
     * @param array $data
     * @return mixed
     */
    public function create(string $profileName , array $data = [])
    {
        if (!array_key_exists($profileName, $this->_profileResources)) {
            throw new \RuntimeException($profileName . ' is not valid. Available readers are: ' . implode(', ', array_keys($this->_profileResources)));
        }
        return $this->_objectManager->create($this->_profileResources[$profileName]);
    }

}
