<?php declare(strict_types=1);

use PartsCatalogsClient\Models\CarParameterInfo;
use PartsCatalogsClient\Models\CarParameterValue;
use PartsCatalogsClient\Models\CarsParametersCollection;
use PartsCatalogsSlim\FilterSet;
use PartsCatalogsSlim\FilterSetState;
use PHPUnit\Framework\TestCase;

final class FilterSetTest extends TestCase
{
    /**
     */
    public function testIsAvailable(): void
    {
        $cpi = new CarParameterInfo('a', 'a');
        $cpi->addValue(new CarParameterValue('11', '11'));
        $cpi->addValue(new CarParameterValue('12', '12'));
        $array[] = $cpi->toArray();

        $a = new CarsParametersCollection($array, 1);
        $b = new CarsParametersCollection($array, 1);


        $fs1   = new FilterSet($a, $b);
        $value = $fs1->getFilterByKey('a')->getValueByKey('11');

        $this->assertEquals(false, $value->isSelected());
        $this->assertEquals(true, $fs1->idxIsAvailable($value->getIdx()));

        $fs2 = new FilterSet($a, $a, new FilterSetState(["11"]));
        $value = $fs2->getFilterByKey('a')->getValueByKey('11');
        $this->assertEquals(true, $value->isSelected());
        $this->assertEquals(true, $fs1->idxIsAvailable($value->getIdx()));
    }
}



