<?php declare(strict_types=1);

namespace PartsCatalogsSlim;

class FilterSetState
{
    const STATE_DELIMITER = ',';

    private $map;

    public function __construct(array $state)
    {
        $this->map = array_flip(array_filter($state));
    }

    public static function fromString($stateStr): FilterSetState
    {
        $state = self::unpackState($stateStr);
        return new FilterSetState($state);
    }

    public function __toString()
    {
        return $this->toString();
    }

    private static function unpackState(string $state): array
    {
        if (!$state) {
            return [];
        }
        return explode(self::STATE_DELIMITER, $state);
    }

    private static function packState(array $state): string
    {
        return implode(self::STATE_DELIMITER, $state);
    }

    public function hasIdx($idx): bool
    {
        return isset($this->map[$idx]);
    }

    public function setIdx(string $idx): FilterSetState
    {
        if ($this->hasIdx($idx)) {
            return $this;
        }
        $this->map[$idx] = count($this->map);

        return $this;
    }

    public function getIdxList(): array
    {
        return array_keys($this->map);
    }

    public function unsetIdx($idx): FilterSetState
    {
        if ($this->hasIdx($idx)) {
            unset($this->map[$idx]);
        }
        return $this;
    }

    public function withoutIdx($idx)
    {
        if ($this->hasIdx($idx)) {
            $newState = clone $this;
            unset($newState->map[$idx]);
            return $newState;
        }
        return $this;
    }

    public function toString(): string
    {
        $state = array_keys($this->map);
        return $this->packState($state);
    }
}
