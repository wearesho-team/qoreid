<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Response;

trait FieldMatchesTrait
{
    private function getFieldMatches(string $checkName, array $apiData): array
    {
        if (
            !array_key_exists('summary', $apiData)
            || !array_key_exists(
                $checkName,
                $apiData['summary']
            )
            || !array_key_exists('fieldMatches', $apiData['summary'][$checkName])
        ) {
            throw new \InvalidArgumentException('Invalid data array: required fieldMatches key is missing.');
        }
        $fieldMatches = $apiData['summary'][$checkName]['fieldMatches'];
        if (!is_array($fieldMatches)) {
            throw new \InvalidArgumentException('Invalid fieldMatches value, expected array.');
        }
        return $fieldMatches;
    }
}
