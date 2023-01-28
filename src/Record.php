<?php

declare(strict_types=1);

namespace Hereldar\Records;

abstract readonly class Record
{
    public function is(array|object $that): bool
    {
        return is_object($that)
            && $this::class === $that::class
            && (array) $this === (array) $that;
    }

    public function isNot(array|object $that): bool
    {
        return !is_object($that)
            || $this::class !== $that::class
            || (array) $this !== (array) $that;
    }

    public function isEqual(array|object $that): bool
    {
        $a = (array) $this;
        $b = (array) $that;

        self::sortKeysRecursively($a);
        self::sortKeysRecursively($b);

        return $a === $b;
    }

    public function isNotEqual(array|object $that): bool
    {
        $a = (array) $this;
        $b = (array) $that;

        self::sortKeysRecursively($a);
        self::sortKeysRecursively($b);

        return $a !== $b;
    }

    public function isSimilar(array|object $that): bool
    {
        return ((array) $this == (array) $that);
    }

    public function isNotSimilar(array|object $that): bool
    {
        return ((array) $this != (array) $that);
    }

    private static function sortKeysRecursively(array &$array): void
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                self::sortKeysRecursively($value);
            }
        }

        ksort($array);
    }
}
