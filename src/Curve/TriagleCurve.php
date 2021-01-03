<?php

/**
 * This file is part of the fuzzy-tsukamoto.
 */

namespace Kematjaya\FuzzyTsukamoto\Curve;

use Kematjaya\FuzzyTsukamoto\Exception\EmptyMinException;
use Kematjaya\FuzzyTsukamoto\Exception\EmptyMaxException;
use Kematjaya\FuzzyTsukamoto\Exception\EmptyMediumException;

/**
 * @package Kematjaya\FuzzyTsukamoto\Curve
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class TriagleCurve extends AbstractCurve
{
    /**
     * 
     * @var float
     */
    private $medium;
    
    public function getMedium(): ?float 
    {
        return $this->medium;
    }

    public function setMedium(float $medium):self 
    {
        $this->medium = $medium;
        
        return $this;
    }

    /**
     * Calculated value
     * 
     * @return float
     */
    public function calculate(): float 
    {
        if (null === $this->getMin()) {
            throw new EmptyMinException();
        }
        
        if (null === $this->getMax()) {
            throw new EmptyMaxException();
        }
        
        if (null === $this->getMedium()) {
            throw new EmptyMediumException();
        }
        
        if ($this->getValue() < $this->getMin() or $this->getValue() > $this->getMax()) {
            
            return 0;
        }
        
        if ($this->getValue() > $this->getMedium()) {
            $curve = new DownCurve($this->getValue());
            $curve->setMin($this->getMedium())->setMax($this->getMax());
            
            return $curve->calculate();
        }
        
        $curve = new UpCurve($this->getValue());
        $curve->setMin($this->getMin())->setMax($this->getMedium());

        return $curve->calculate();
    }
}
