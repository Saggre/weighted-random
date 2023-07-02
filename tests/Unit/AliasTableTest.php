<?php

namespace Saggre\WeightedRandom\Tests;

use PHPUnit\Framework\TestCase;
use Saggre\WeightedRandom\WeighedSample;
use Saggre\WeightedRandom\WeighedSampleAlias;

class AliasTableTest extends TestCase
{
    public function testAliasTableCreation()
    {
        $aliasTable = WeighedSampleAlias::createAliasTable([
            new WeighedSample(1, 0.2),
            new WeighedSample(2, 0.3),
            new WeighedSample(3, 0.5),
        ]);

        $this->assertCount(3, $aliasTable);
    }
}
