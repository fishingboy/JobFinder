<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Asserts that a variable is of type array.
     * @param $actual
     * @param string $message
     */
    public static function assertIsArray($actual, $message = '')
    {
        $delta = 0.0;
        $maxDepth = 10;
        $canonicalize = false;
        $ignoreCase = false;

        $constraint = new PHPUnit_Framework_Constraint_IsEqual(
            'array',
            $delta,
            $maxDepth,
            $canonicalize,
            $ignoreCase
        );

        self::assertThat(gettype($actual), $constraint, $message);
    }
}
