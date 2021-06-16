<?php declare(strict_types=1);

namespace PartsCatalogsClient\Models;

use PartsCatalogsClient\GroupPath;

class Group
{
    public $id;
    public $name;
    public $img;
    public $description;
    public $hasParts;
    public $parentId;
    public $groupPath;

    /**
     * @param array          $array
     *
     * @return Group
     */
    public static function fromArray($array)
    {
        $obj              = new Group();
        $obj->id          = $array['id'];
        $obj->name        = $array['name'];
        $obj->img         = $array['img'];
        $obj->description = $array['description'];
        $obj->hasParts    = $array['hasParts'];
        $obj->parentId    = $array['parentId'];

        return $obj;
    }

    public function getPath(): GroupPath
    {
        return $this->groupPath;
    }

    public function setGroupPath(GroupPath $groupPath): self
    {
        $this->groupPath = $groupPath;
        return $this;
    }
}
