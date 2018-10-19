<?php


namespace App\Tests\Util;

use PHPUnit\Framework\TestCase;
use App\Util\Slugger;

class SluggerTest extends TestCase
{
    public function testSlugger()
    {
        $this->assertEquals("kaamelott", Slugger::slugify("kaamelott"));
        $this->assertEquals("kaamelott", Slugger::slugify(" Kaamelott "));
        $this->assertEquals("kaamelott-livre-3", Slugger::slugify("kaamelott-livre-3"));
        $this->assertEquals("kaamelott---livre-3", Slugger::slugify(" Kaamelott - livre 3"));
    }
}
