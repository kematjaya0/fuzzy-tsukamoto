<?php

/**
 * This file is part of the fuzzy-tsukamoto.
 */

namespace Kematjaya\FuzzyTsukamoto\Tests\Curve;

use Kematjaya\FuzzyTsukamoto\Exception\EmptyMinException;
use Kematjaya\FuzzyTsukamoto\Exception\EmptyMaxException;
use Kematjaya\FuzzyTsukamoto\Curve\DownCurve;
use PHPUnit\Framework\TestCase;

/**
 * @package Kematjaya\FuzzyTsukamoto\Tests\Curve
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DownCurveTest extends TestCase 
{
    public function testCalculateSuccess()
    {
        $min = 3;
        $max = 10;
        for ($i = $min; $i <= $max; $i++) {
            $curve = (new DownCurve($i))
                    ->setMin($min)->setMax($max);

            $expect = ($max - $i) / ($max - $min);
            $this->assertEquals(round($expect, 3), $curve->calculate());
        }
        
        for ($i = 0; $i < $min; $i++) {
            $curve = (new DownCurve($i))
                    ->setMin($min)->setMax($max);

            $expect = 1;
            $this->assertEquals(round($expect, 3), $curve->calculate());
        }
        
        for ($i = $max; $i < ($max + 5); $i++) {
            $curve = (new DownCurve($i))
                    ->setMin($min)->setMax($max);

            $expect = 0;
            $this->assertEquals(round($expect, 3), $curve->calculate());
        }
    }
    
    function testMinException()
    {
        $curve = new DownCurve(10);

        $this->expectException(EmptyMinException::class);
        $curve->calculate();
    }
    
    function testMaxException()
    {
        $curve = (new DownCurve(10))->setMin(1);

        $this->expectException(EmptyMaxException::class);
        $curve->calculate();
    }
    
    function testReverseValue()
    {
        $curve = new DownCurve();
        $curve->setMin(300000)->setMax(600000);
        $this->assertEquals(500100, $curve->reverseValue(0.333));
        $this->assertEquals(450000, $curve->reverseValue(0.5));
    }
}
