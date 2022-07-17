<?php

namespace LeoVirgilio\Infobase\Model;

use LeoVirgilio\Infobase\Helper\PasswordGeneration;
use Magento\Customer\Model\CustomerFactory;
use Magento\Store\Model\StoreManagerInterface;

class Import
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $_storeManager;

    /**
     * @var CustomerFactory
     */
    private CustomerFactory $_customerFactory;

    /**
     * @var PasswordGeneration
     */
    private PasswordGeneration $_passwordGeneration;

    /**
     * @param StoreManagerInterface $storeManager
     * @param CustomerFactory $customerFactory
     * @param PasswordGeneration $passwordGeneration
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        CustomerFactory       $customerFactory,
        PasswordGeneration    $passwordGeneration,
    )
    {
        $this->_storeManager = $storeManager;
        $this->_customerFactory = $customerFactory;
        $this->_passwordGeneration = $passwordGeneration;
    }


    /**
     * @param array $data
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function process(array $data): bool
    {
        $data = $this->_prepare($data);

        if (!$this->_validate($data)) {
            return false;
        }

        $customer = $this->_customerFactory->create();
        $customer->setWebsiteId($data['website_id']);

        $customer->loadByEmail($data['emailaddress']);

        if ($customer->getId()) {
            return false;
        }

        $customer->setWebsiteId($data['website_id'])
            ->setStore($data['store'])
            ->setFirstname($data['fname'])
            ->setLastname($data['lname'])
            ->setEmail($data['emailaddress'])
            ->setPassword($data['password']);
        $customer->save();

        return true;
    }

    /**
     * @param array $data
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function _prepare(array &$data): array
    {
        $store = $this->_storeManager->getStore();

        $data['store'] = $store;
        $data['store_id'] = $store->getStoreId();
        $data['website_id'] = $this->_storeManager->getStore()->getWebsiteId();
        $data['password'] = $this->_passwordGeneration->generatePassword();

        return $data;
    }

    /**
     * @param $data
     * @return bool
     */
    private function _validate($data): bool
    {
        $requiredFields = ['emailaddress', 'fname', 'lname'];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $data)) {
                return false;
            }
        }

        return true;
    }
}
