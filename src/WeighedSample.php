<?php

namespace Saggre\WeightedRandom;

class WeighedSample
{
    protected mixed $sample;
    protected float $weight;

    /**
     * @param mixed $sample
     * @param float $weight
     */
    public function __construct(mixed $sample, float $weight)
    {
        $this->sample = $sample;
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getSample(): mixed
    {
        return $this->sample;
    }

    /**
     * @param mixed $sample
     *
     * @return WeighedSample
     */
    public function setSample(mixed $sample): WeighedSample
    {
        $this->sample = $sample;

        return $this;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     *
     * @return WeighedSample
     */
    public function setWeight(float $weight): WeighedSample
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Normalize weights to [0,1].
     *
     * @param self[] $weighedList
     *
     * @return array
     */
    public static function normalizeWeights(array $weighedList): array
    {
        foreach ($weighedList as &$sample) {
            $sample->weight /= self::getTotalWeight($weighedList);
        }

        return $weighedList;
    }

    /**
     * Sort list by weight.
     *
     * @param array $weighedList
     *
     * @return array
     */
    public static function sortWeights(array $weighedList): array
    {
        usort($weighedList, function (self $a, self $b) {
            return $a->getWeight() <=> $b->getWeight();
        });

        return $weighedList;
    }

    /**
     * Get average weight.
     *
     * @param array $weighedList
     *
     * @return float
     */
    public static function getAverageWeight(array $weighedList): float
    {
        return self::getTotalWeight($weighedList) / count($weighedList);
    }

    /**
     * Get total weight.
     *
     * @param array $weighedList
     *
     * @return float
     */
    public static function getTotalWeight(array $weighedList): float
    {
        return array_reduce($weighedList, function ($carry, self $item) {
            return $carry + $item->getWeight();
        }, 0);
    }

    /**
     * Get the sample with the lightest weight.
     *
     * @param array $weighedList
     *
     * @return static
     */
    public static function getLightestSample(array $weighedList): self
    {
        return self::sortWeights($weighedList)[0];
    }

    /**
     * Get the sample with the heaviest weight.
     *
     * @param array $weighedList
     *
     * @return static
     */
    public static function getHeaviestSample(array $weighedList): self
    {
        return self::sortWeights($weighedList)[count($weighedList) - 1];
    }

    /**
     * Remove the lightest sample from the list.
     *
     * @param array $weighedList
     * @param WeighedSample $sample Sample to remove.
     *
     * @return array
     */
    public static function removeSample(array $weighedList, self $sample): array
    {
        $sampleIndex = array_search($sample, $weighedList);
        unset($weighedList[$sampleIndex]);

        return $weighedList;
    }
}
