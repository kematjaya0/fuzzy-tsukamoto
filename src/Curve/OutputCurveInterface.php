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
interface OutputCurveInterface 
{
    public function reverseValue(float $value):?float;
}
