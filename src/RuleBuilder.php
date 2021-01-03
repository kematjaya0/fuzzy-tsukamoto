<?php

/**
 * This file is part of the fuzzy-tsukamoto.
 */

namespace Kematjaya\FuzzyTsukamoto;

/**
 * @package Kematjaya\FuzzyTsukamoto
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class RuleBuilder 
{
    /**
     * 
     * @var array
     */
    private $rules = array();
    
    /**
     * 
     * @var string
     */
    private $key;
    
    const WHEN = 'when';
    const THEN = 'then';
    
    public function startWhen(string $key):self 
    {
        $this->rules[$key] = array();
        $this->key = $key;
        
        return $this;
    }
    
    public function andWhen(string $curveName, string $curveKey):self
    {
        $this->rules[$this->key][self::WHEN][] = [
            'name' => $curveName, 'key' => $curveKey
        ];
        
        return $this;
    }
    
    public function then(string $curveKey):self
    {
        $this->rules[$this->key][self::THEN] = $curveKey;
        
        return $this;
    }
    
    public function getRules():array 
    {
        return $this->rules;
    }
    
    public function __toString():?string
    {
        $ruleText = [];
        foreach ($this->rules as $k => $value) {
            
            $whentext = [];
            foreach ($value[self::WHEN] as $when) {
                 $whentext[] = sprintf("%s = %s", $when['name'], $when['key']);
            }
            
            $ruleText[] = sprintf("(%s) IF %s THEN %s", $k, implode(" AND ", $whentext), $value[self::THEN]);
        }
        
        return implode(', ', $ruleText);
    }
}
