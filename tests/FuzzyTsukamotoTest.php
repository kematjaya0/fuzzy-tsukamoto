<?php

/**
 * This file is part of the fuzzy-tsukamoto.
 */

namespace Kematjaya\FuzzyTsukamoto\Tests;

use Kematjaya\FuzzyTsukamoto\Curve\DownCurve;
use Kematjaya\FuzzyTsukamoto\Curve\UpCurve;
use Kematjaya\FuzzyTsukamoto\Curve\TriagleCurve;
use Kematjaya\FuzzyTsukamoto\CurveBuilder;
use Kematjaya\FuzzyTsukamoto\FuzzyTsukamoto;
use Kematjaya\FuzzyTsukamoto\RuleBuilder;
use PHPUnit\Framework\TestCase;
    
/**
 * @package Kematjaya\FuzzyTsukamoto\Tests
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class FuzzyTsukamotoTest extends TestCase
{
    public function testKurvaGaji(): CurveBuilder
    {
        $value = 3000000;
        $kurvaGaji = (new CurveBuilder())
                ->addCurve(FuzzyTsukamoto::KEY_MIN, (new DownCurve($value))->setMin(2000000)->setMax(4000000))
                ->addCurve(FuzzyTsukamoto::KEY_MAX, (new UpCurve($value))->setMin(3000000)->setMax(5000000));
        
        $this->assertEquals(2, count($kurvaGaji->getCurves()));
        
        return $kurvaGaji;
    }
    
    public function testKurvaMasakerja(): CurveBuilder
    {
        $value = 4;
        $kurvaMasaKerja = (new CurveBuilder())
                ->addCurve(FuzzyTsukamoto::KEY_MIN, (new DownCurve($value))->setMin(2)->setMax(5))
                ->addCurve(FuzzyTsukamoto::KEY_MIDDLE, (new TriagleCurve($value))->setMedium(5)->setMin(3)->setMax(7))
                ->addCurve(FuzzyTsukamoto::KEY_MAX, (new UpCurve($value))->setMin(5)->setMax(8));
        
        $this->assertEquals(3, count($kurvaMasaKerja->getCurves()));
        
        return $kurvaMasaKerja;
    }
    
    public function testKurvaBonus(): CurveBuilder
    {
        $kurvaBonus = (new CurveBuilder())
                ->addCurve(FuzzyTsukamoto::KEY_MIN, (new DownCurve())->setMin(300000)->setMax(600000))
                ->addCurve(FuzzyTsukamoto::KEY_MAX, (new UpCurve())->setMin(300000)->setMax(600000));
        
        $this->assertEquals(2, count($kurvaBonus->getCurves()));
        
        return $kurvaBonus;
    }
    
    /**
     * @depends testKurvaGaji
     * @depends testKurvaMasakerja
     * @depends testKurvaBonus
     */
    public function testCalculate(CurveBuilder $kurvaGaji, CurveBuilder $kurvaKerja, CurveBuilder $kurvaBonus)
    {
        $tsukamoto = new FuzzyTsukamoto();
        $tsukamoto->addDecisionCurve('gaji', $kurvaGaji)
                ->addDecisionCurve('kerja', $kurvaKerja)
                ->setOutputCurve($kurvaBonus);
        
        $ruleBuilder = new RuleBuilder();
        $ruleBuilder->startWhen(1)
                ->andWhen('kerja', FuzzyTsukamoto::KEY_MIN)
                ->andWhen("gaji", FuzzyTsukamoto::KEY_MIN)
                ->then(FuzzyTsukamoto::KEY_MIN);
        
        $ruleBuilder->startWhen(2)
                ->andWhen('kerja', FuzzyTsukamoto::KEY_MIN)
                ->andWhen("gaji", FuzzyTsukamoto::KEY_MAX)
                ->then(FuzzyTsukamoto::KEY_MIN);
        
        $ruleBuilder->startWhen(3)
                ->andWhen('kerja', FuzzyTsukamoto::KEY_MIDDLE)
                ->andWhen("gaji", FuzzyTsukamoto::KEY_MIN)
                ->then(FuzzyTsukamoto::KEY_MIN);
        
        $ruleBuilder->startWhen(4)
                ->andWhen('kerja', FuzzyTsukamoto::KEY_MIDDLE)
                ->andWhen("gaji", FuzzyTsukamoto::KEY_MAX)
                ->then(FuzzyTsukamoto::KEY_MAX);
        
        $ruleBuilder->startWhen(5)
                ->andWhen('kerja', FuzzyTsukamoto::KEY_MAX)
                ->andWhen("gaji", FuzzyTsukamoto::KEY_MIN)
                ->then(FuzzyTsukamoto::KEY_MAX);
        
        $ruleBuilder->startWhen(6)
                ->andWhen('kerja', FuzzyTsukamoto::KEY_MAX)
                ->andWhen("gaji", FuzzyTsukamoto::KEY_MAX)
                ->then(FuzzyTsukamoto::KEY_MAX);
        
        $result = $tsukamoto->calculated($ruleBuilder);
        $this->assertEquals(470000, round($result, -3));
    }
}
