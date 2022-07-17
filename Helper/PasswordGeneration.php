<?php

namespace LeoVirgilio\Infobase\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Math\Random;

class PasswordGeneration extends AbstractHelper
{

    /**
     * @var Random
     */
    protected $_mathRandom;

    /**
     * @param Random $mathRandom
     */
    public function __construct(
        Random $mathRandom
    )
    {
        $this->_mathRandom = $mathRandom;
    }

    /**
     * @param int $length
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function generatePassword(int $length = 10) : string
    {
        $chars = Random::CHARS_LOWERS
            . Random::CHARS_UPPERS
            . Random::CHARS_DIGITS;

        return $this->_mathRandom->getRandomString($length, $chars);
    }
}
