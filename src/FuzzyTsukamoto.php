<?php

/**
 * This file is part of the fuzzy-tsukamoto.
 */

namespace Kematjaya\FuzzyTsukamoto;

use Kematjaya\FuzzyTsukamoto\Curve\OutputCurveInterface;

/**
 * @package Kematjaya\FuzzyTsukamoto
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class FuzzyTsukamoto 
{
    const KEY_MIN = 'min';
    const KEY_MIDDLE = 'middle';
    const KEY_MAX = 'max';
    
    /**
     * 
     * @var array
     */
    private $decisonCurve = [];
    
    /**
     * 
     * @var CurveBuilder
     */
    private $outputCurve;
    
    /**
     * 
     * @var array
     */
    private $ruleValues = [];
    
    /**
     * 
     * @var float
     */
    private $output;
    
    public function addDecisionCurve(string $key, CurveBuilder $builder):self
    {
        $this->decisonCurve[$key] = $builder;
        
        return $this;
    }
    
    public function getDecisionCurves():array 
    {
        return $this->decisonCurve;
    }
    
    public function setOutputCurve(CurveBuilder $builder):self
    {
        $this->outputCurve = $builder;
        
        return $this;
    }
    
    /**
     * Get Output Curve
     * 
     * @return CurveBuilder
     */
    public function getOutputCurve():CurveBuilder 
    {
        return $this->outputCurve;
    }
    
    /**
     * Get Output of calculated
     * 
     * @return float|null
     */
    public function getOutput():?float
    {
        return $this->output;
    }
    
    /**
     * Get array of rule include value
     * 
     * @return array
     */
    public function getRuleValues():array
    {
        return $this->ruleValues;
    }
    
    /**
     * Calculate fuzzy tsukamoto by RuleBuilder value
     * 
     * @param RuleBuilder $ruleBuilder
     * @return type
     * @throws \Exception
     */
    public function calculated(RuleBuilder $ruleBuilder) 
    {
        $memberShipValue = $this->countPredicatRuleValues($ruleBuilder);
        
        $zUpper = 0;
        $zUnder  = 0;
        foreach ($memberShipValue as $value) {
            $zUpper += $value['z'];
            $zUnder  += $value['min'];
        }
        
        $this->ruleValues = $memberShipValue;
        
        return $this->countOutput($zUpper, $zUnder);
    }
    
    /**
     * Count Predicate Rule Values
     * 
     * @param RuleBuilder $ruleBuilder
     * @return type
     * @throws \Exception
     */
    public function countPredicatRuleValues(RuleBuilder $ruleBuilder)
    {
        $result = [];
        $decisionCurves = $this->getDecisionCurves();
        foreach ($ruleBuilder->getRules() as $k => $rule) {
            $whens = $rule[RuleBuilder::WHEN];
            foreach ($whens as $when) {

                if (!isset($decisionCurves[$when['name']])) {
                    throw new \Exception(sprintf("curve '%s' not found.", $when['name']));
                }

                $curveBuilder = $decisionCurves[$when['name']];
                $curve = $curveBuilder->getCurve($when['key']);

                $result[$k][RuleBuilder::WHEN][sprintf("%s,%s", $when['name'], $when['key'])] = $curve->calculate();
            }

            $result[$k]['min'] = min(array_values($result[$k][RuleBuilder::WHEN]));
            
            $then = $rule[RuleBuilder::THEN];
            
            $this->countOutputCurve($then, $result[$k]);
        }
           
        return $result;
    }
    
    /**
     * Count output value from Curve
     * 
     * @param string $key
     * @param type $array
     * @return type
     * @throws \Exception
     */
    protected function countOutputCurve(string $key, &$array)
    {
        $outputCurve = $this->getOutputCurve();
        $output = $outputCurve->getCurve($key);
        if (!$output instanceof OutputCurveInterface) {
            throw new \Exception(sprintf("output curve expected '%s', actual '%s' class", OutputCurveInterface, get_class($output)));
        }

        $array['value'] = $output->reverseValue($array['min']);
        $array['z'] = $array['value'] * $array['min'];
        
        return $array;
    }
    
    /**
     * Count final Z
     * 
     * @param float $upper
     * @param float $under
     * @return float
     */
    protected function countOutput(float $upper, float $under):float
    {
        $this->output = round($upper / $under, 2);
        
        return $this->output;
    }
}
