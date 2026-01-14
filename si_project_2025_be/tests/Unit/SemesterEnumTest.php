<?php

namespace Tests\Unit;

use App\Enums\SemesterEnum;
use PHPUnit\Framework\TestCase;

class SemesterEnumTest extends TestCase
{
    public function test_semester_enum_values(): void
    {
        $this->assertEquals('Z', SemesterEnum::WINTER->value);
        $this->assertEquals('L', SemesterEnum::SUMMER->value);
    }

    public function test_semester_enum_cases_count(): void
    {
        $this->assertCount(2, SemesterEnum::cases());
    }
}
