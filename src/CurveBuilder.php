<?php

/**
 * This file is part of the fuzzy-tsukamoto.
 */

namespace Kematjaya\FuzzyTsukamoto;

use Kematjaya\FuzzyTsukamoto\Curve\AbstractCurve;

/**
 * @package Kematjaya\FuzzyTsukamoto\Builder
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CurveBuilder 
{
    /**
     * 
     * @var array
     */
    private $curve;
    
    public function __construct() 
    {
        $this->curve = array();
    }
    
    public function addCurve(string $key, AbstractCurve $curve):self
    {
        $this->curve[$key] = $curve;
        
        return $this;
    }
    
    public function getCurve(string $key):AbstractCurve
    {
        if (!isset($this->curve[$key])) {
            throw new \Exception(sprintf("curve key not found: %s", $key));
        }
        
        return $this->curve[$key];
    }
    
    public function getCurves():array 
    {
        return $this->curve;
    }
}
