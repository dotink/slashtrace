<?php

namespace SlashTrace\Tests\Doubles\StackTrace;

use SlashTrace\StackTrace\StackTraceInspector;

class MaxDepthStackTraceInspector extends StackTraceInspector
{

    public function __construct(private $maxDepth)
    {
    }

    public function fromException($exception)
    {
        return array_slice(parent::fromException($exception), 0, $this->maxDepth);
    }


}