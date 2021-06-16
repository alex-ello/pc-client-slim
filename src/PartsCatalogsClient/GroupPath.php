<?php declare(strict_types=1);

namespace PartsCatalogsClient;

class GroupPath
{
    const DELIMITER = ',';

    /**
     * @var string[]
     */
    private $groupIds;

    private $offset;

    public function __construct(string ...$groupIds)
    {
        $groupIds = array_filter($groupIds);
        $this->groupIds = $groupIds;
        $this->offset = count($groupIds) - 1;
    }


    public static function fromString($string)
    {
        return self::decode($string);
    }

    private static function decode($string): GroupPath
    {
        $list = explode(self::DELIMITER, $string);
        return new GroupPath(...$list);
    }

    private static function encode(array $groupIds)
    {
        return implode(self::DELIMITER, $groupIds);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function getCurrentGroupId(): string
    {
        if (isset($this->groupIds[$this->offset])) {
            return $this->groupIds[$this->offset];
        }
        return "";
    }

    public function getParentGroupId(): string
    {
        $offset = $this->offset - 1;
        if (isset($this->groupIds[$offset])) {
            return $this->groupIds[$offset];
        }
        return '';
    }

    public function addId(string $id): GroupPath
    {
        $this->groupIds[] = $id;
        $this->offset++;
        return $this;
    }

    public function toString(): string
    {
        return self::encode($this->groupIds);
    }
}
