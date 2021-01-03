<?php

/**
 * This file is part of the fuzzy-tsukamoto.
 */

namespace Kematjaya\FuzzyTsukamoto\Tests\Curve;

use Kematjaya\FuzzyTsukamoto\Exception\EmptyMinException;
use Kematjaya\FuzzyTsukamoto\Exception\EmptyMaxException;
use Kematjaya\FuzzyTsukamoto\Exception\EmptyMediumException;
use Kematjaya\FuzzyTsukamoto\Curve\TriagleCurve;
use PHPUnit\Framework\TestCase;

/**
 * @package Kematjaya\FuzzyTsukamoto\Tests\Curve
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class TriagleCurveTest extends TestCase
{
    public function testCalculateSuccess()
    {
        $min = 3;
        $med = 5;
        $max = 10;
        for ($i = $min; $i <= $med; $i++) {
            $curve = (new TriagleCurve($i))
                    ->setMedium($med)->setMin($min)->setMax($max);

            $expect = ($i - $min) / ($med - $min);
            $this->assertEquals($expect, $curve->calculate());
        }
        
        for ($i = 0; $i < $min; $i++) {
            $curve = (new TriagleCurve($i))
                    ->setMedium($med)->setMin($min)->setMax($max);

            $expect = 0;
            $this->assertEquals($expect, $curve->calculate());
        }
        
        for ($i = $med; $i < $max; $i++) {
            $curve = (new TriagleCurve($i))
                    ->setMedium($med)->setMin($min)->setMax($max);

            $expect = ($max - $i) / ($max - $med);
            $this->assertEquals($expect, $curve->calculate());
        }
    }
    
    function testMinException()
    {
        $curve = new TriagleCurve(10);

        $this->expectException(EmptyMinException::class);
        $curve->calculate();
    }
    
    function testMaxException()
    {
        $curve = (new TriagleCurve(10))->setMin(1);

        $this->expectException(EmptyMaxException::class);
        $curve->calculate();
    }
    
    function testMediumException()
    {
        $curve = (new TriagleCurve(10))->setMin(1)->setMax(10);

        $this->expectException(EmptyMediumException::class);
        $curve->calculate();
    }
}
