<?php

namespace BlueBear\EngineBundle\Rules;


use BlueBear\EngineBundle\Behavior\HasException;

class Ruler
{
    use HasException;

    protected $rules = [];

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function matchRule(Rule $rule, $rulesParameters)
    {
        $callback = $rule->getCallback($rulesParameters);
        $ruleReturn = call_user_func_array($callback, $rulesParameters);
        // return should be a boolean
        $this->throwUnless(is_bool($ruleReturn), 'Invalid rule return for rule ' . get_class($rule));

        return $ruleReturn;
    }

    public function getRulesForEvent($eventName)
    {
        $this->throwUnless(array_key_exists($eventName, $this->rules));

        return new $this->rules[$eventName];
    }
}