<?php declare(strict_types=1);

use PartsCatalogsClient\Models\NameString;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase
{
    /**
     * @dataProvider textProvider
     */
    public function testBeautify($name, $expected): void
    {
        $result = NameString::beautify($name);
        $this->assertEquals($expected, $result);
    }

    public function textProvider(): array
    {
        $set = [
            ['Access.,Infotainment,Miscell.', 'Access., infotainment, miscell.'],
            ['Front axle, steering', 'Front axle, steering'],
            ['accessory messages, sounds and indicator lamps', 'Accessory messages, sounds and indicator lamps'],
            ['audio / navigation / telematics', 'Audio / Navigation / Telematics'],
            ['telephone/multimedia', 'Telephone / Multimedia'],
            ['telephone /multimedia', 'Telephone / Multimedia'],
            ['telephone/ multimedia', 'Telephone / Multimedia'],
            ['ABS/ASR/ESP', 'ABS / ASR / ESP'],
            ['BRAKE WEAR HARNESS ABS / ABR', 'Brake wear harness ABS / ABR'],
            ['Condenser, liquid tank & piping', 'Condenser, liquid tank & Piping'],
            ['BODY(FRONT,ROOF & FLOOR)', 'Body(front, roof & Floor)']
        ];
        return $set;
    }
}



