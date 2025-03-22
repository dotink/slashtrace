<?php

namespace SlashTrace\Tests\Fixtures\Code;

use DateTime;
use ErrorException;
use LogicException;
use stdClass;

class TestClass
{
    private static function bar(TestClass $object, array $countries = []): never
    {
        assert(is_object($object));
        assert(is_array($countries));

        throw new ErrorException("Something went wrong!", 1234, E_USER_ERROR, __FILE__, __LINE__, self::createPreviousException());
    }

    private static function createPreviousException()
    {
        return new LogicException();
    }

    public function __construct(private readonly ?\stdClass $parameter = null)
    {
    }

    public function initialize()
    {
        include __DIR__ . "/test_file.php";
    }

    public function doSomething(DateTime $when)
    {
        assert($when instanceof DateTime);
        $this->foo(new self, [
            "Finland", "Denmark", "Sweden"
        ]);
    }

    private function foo(TestClass $object, array $countries = [])
    {
        self::bar($object, $countries);
    }
}