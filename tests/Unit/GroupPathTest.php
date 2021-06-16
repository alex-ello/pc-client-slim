<?php declare(strict_types=1);

use PartsCatalogsClient\GroupPath;
use PHPUnit\Framework\TestCase;

final class GroupPathTest extends TestCase
{
    /**
     */
    public function testGroupPath(): void
    {
        $gp = new GroupPath();
        $this->assertEquals("", $gp->toString());
        $this->assertEquals("", $gp->getCurrentGroupId());
        $this->assertEquals("", $gp->getParentGroupId());

        $gp = new GroupPath("1");
        $this->assertEquals("1", $gp->toString());
        $this->assertEquals("1", $gp->getCurrentGroupId());
        $this->assertEquals("", $gp->getParentGroupId());

        $gp = new GroupPath("1", "2");
        $this->assertEquals("1,2", $gp->toString());
        $this->assertEquals("2", $gp->getCurrentGroupId());
        $this->assertEquals("1", $gp->getParentGroupId());

        $gp = new GroupPath("1", "2", "3");
        $this->assertEquals("1,2,3", $gp->toString());
        $this->assertEquals("3", $gp->getCurrentGroupId());
        $this->assertEquals("2", $gp->getParentGroupId());


        $gp = new GroupPath("1", "2");
        $gp->addId("3");
        $this->assertEquals("1,2,3", $gp->toString());
        $this->assertEquals("3", $gp->getCurrentGroupId());
        $this->assertEquals("2", $gp->getParentGroupId());
    }
}



