<?php

namespace SlashTrace\Formatter;

class StackFrameCallTextFormatter extends StackFrameCallFormatter
{
    /**
     * StackFrameCallTextFormatter constructor.
     * @param StackTraceTagFormatter $tagFormatter
     */
    public function __construct(private readonly ?\SlashTrace\Formatter\StackTraceTagFormatter $tagFormatter = null)
    {
    }

    protected function formatClass($class)
    {
        return $this->tag($class, StackTraceTagFormatter::TAG_CLASS);
    }

    protected function formatFunction($function)
    {
        return $this->tag($function, StackTraceTagFormatter::TAG_FUNCTION);
    }

    public function formatArgument($argument)
    {
        return $this->tag($this->serialize($argument), StackTraceTagFormatter::TAG_ARGUMENT);
    }

    private function tag($input, $tag)
    {
        if (is_null($this->tagFormatter)) {
            return $input;
        }
        return $this->tagFormatter->format($input, $tag);
    }
}