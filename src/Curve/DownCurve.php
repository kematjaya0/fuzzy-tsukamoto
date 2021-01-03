<?php

/**
 * This file is part of the fuzzy-tsukamoto.
 */

namespace Kematjaya\FuzzyTsukamoto\Curve;

use Kematjaya\FuzzyTsukamoto\Exception\EmptyMinException;
use Kematjaya\FuzzyTsukamoto\Exception\EmptyMaxException;

/**
 * @package Kematjaya\FuzzyTsukamoto\Curve
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DownCurve extends AbstractCurve implements OutputCurveInterface
{
    
    public function __toString() 
    {
        return "DOWN CURVE";
    }
    
    /**
     * Calculating value
     * 
     * @return float
     */
    public function calculate(): ?float 
    {
        if (null === $this->getMin()) {
            throw new EmptyMinException();
        }
        
        if (null === $this->getMax()) {
            throw new EmptyMaxException();
        }
        
        if ($this->getValue() < $this->getMin()) {
            
            return 1;
        }
        
        if ($this->getValue() > $this->getMax()) {
            
            return 0;
        }
        
        $value = ($this->getMax() - $this->getValue()) / ($this->getMax() - $this->getMin());
        
        return round($value, 3);
    }
    
    public function reverseValue(float $value):?float
    {
        return $this->getMax() - (($this->getMax() - $this->getMin()) * $value);
    }
}
