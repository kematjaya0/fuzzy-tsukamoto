<?php

/**
 * This file is part of the fuzzy-tsukamoto.
 */

namespace Kematjaya\FuzzyTsukamoto\Exception;

use Exception;

/**
 * @package Kematjaya\FuzzyTsukamoto\Exception
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class EmptyMinException extends Exception 
{
    public function __construct() 
    {
        $message = sprintf("minimal value must be set");
        parent::__construct($message);
    }
}
