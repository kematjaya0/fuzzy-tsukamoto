<?php

/**
 * This file is part of the fuzzy-tsukamoto.
 */

namespace Kematjaya\FuzzyTsukamoto\Curve;

/**
 * @package Kematjaya\FuzzyTsukamoto\Curve
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
abstract class AbstractCurve implements CurveInterface
{
    
    /**
     * 
     * @var float
     */
    private $min;
    
    
    /**
     * 
     * @var float
     */
    private $max;
    
    /**
     * 
     * @var float
     */
    private $value;
    
    public function __construct(float $value = null)
    {
        $this->value = $value;
    }
    
    public function setValue(float $value):self
    {
        $this->value = $value;
        
        return $this;
    }
    
    public function getValue():float
    {
        return $this->value;
    }
    
    public function getMin(): ?float 
    {
        return $this->min;
    }

    public function getMax(): ?float 
    {
        return $this->max;
    }

    public function setMin(float $min) :self 
    {
        $this->min = $min;
        
        return $this;
    }

    public function setMax(float $max) :self
    {
        $this->max = $max;
        
        return $this;
    }
    
    abstract public function calculate():?float;
}
