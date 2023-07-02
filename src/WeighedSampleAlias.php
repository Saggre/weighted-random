<?php

namespace Saggre\WeightedRandom;

class WeighedSampleAlias
{
    protected WeighedSample $heavySample;
    protected WeighedSample $lightSample;
    protected float $proportion;

    /**
     * @param WeighedSample $heavySample
     * @param WeighedSample $lightSample
     * @param float $averageWeight
     */
    public function __construct(WeighedSample $heavySample, WeighedSample $lightSample, float $averageWeight)
    {
        $this->heavySample = $heavySample;
        $this->lightSample = $lightSample;
        $this->proportion  = $lightSample->getWeight() / $averageWeight;
    }

    public static function createAliasTable(array $weighedList): array
    {
        $weighedList   = WeighedSample::normalizeWeights($weighedList);
        $averageWeight = WeighedSample::getAverageWeight($weighedList);

        $tableSize  = count($weighedList);
        $aliasTable = [];
        for ($i = 0; $i < $tableSize; $i++) {
            $weighedList    = WeighedSample::sortWeights($weighedList);
            $lightestSample = WeighedSample::getLightestSample($weighedList);
            $heaviestSample = WeighedSample::getHeaviestSample($weighedList);

            $aliasTable[] = new WeighedSampleAlias(
                $heaviestSample,
                $lightestSample,
                $averageWeight
            );

            $weighedList = WeighedSample::removeSample($weighedList, $lightestSample);
            $heaviestSample->setWeight($heaviestSample->getWeight() - $averageWeight + $lightestSample->getWeight());
        }

        return $aliasTable;
    }

    /**
     * @param self[] $aliasTable
     * @param callable $rng
     *
     * @return WeighedSample
     */
    public static function sampleAliasTable(array $aliasTable, callable $rng): WeighedSample
    {
        $index  = (int) floor($rng() * max(0, count($aliasTable) - 1));
        $cutoff = $rng();

        if ($cutoff < $aliasTable[$index]->getProportion()) {
            return $aliasTable[$index]->getLightSample();
        }

        return $aliasTable[$index]->getHeavySample();
    }

    /**
     * @return WeighedSample
     */
    public function getHeavySample(): WeighedSample
    {
        return $this->heavySample;
    }

    /**
     * @return WeighedSample
     */
    public function getLightSample(): WeighedSample
    {
        return $this->lightSample;
    }

    /**
     * @return float
     */
    public function getProportion(): float
    {
        return $this->proportion;
    }
}
