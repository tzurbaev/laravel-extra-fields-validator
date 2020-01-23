<?php

declare(strict_types=1);

namespace Laravel\ExtraFieldsValidator;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ExtraFieldsDetector
{
    public function getFirstExtraField(array $data, array $rules): ?string
    {
        $original = Arr::dot($data);
        $rules = Collection::make($rules);
        $keys = $rules->keys();
        $filtered = [];

        $rules->each(function ($rules, $key) use ($original, $keys, &$filtered) {
            if (is_string($rules)) {
                $rules = explode('|', $rules);
            }

            $nestedRules = $keys->filter(fn ($otherKey) => strpos($otherKey, $key.'.') === 0);

            if (in_array('array', $rules) && $nestedRules->isEmpty()) {
                $key .= '.*';
            }

            foreach ($original as $dotIndex => $element) {
                if (fnmatch($key, $dotIndex)) {
                    Arr::set($filtered, $dotIndex, $element);
                }
            }
        });

        $dottedFiltered = Arr::dot($filtered);

        if (empty($dottedFiltered) && !empty($original)) {
            return array_key_first($original);
        }

        return $this->getFirstMissingField($original, $dottedFiltered);
    }

    private function getFirstMissingField(array $original, array $filtered): ?string
    {
        foreach ($original as $field => $value) {
            if (!empty($value) && !array_key_exists($field, $filtered)) {
                return $field;
            }
        }

        return null;
    }
}
