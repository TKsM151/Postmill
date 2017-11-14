<?php

namespace Tests\AppBundle\Entity;

use PHPUnit\Framework\TestCase;
use AppBundle\Entity\Theme;
use AppBundle\Entity\ThemeRevision;
use AppBundle\Entity\User;

class ThemeTest extends TestCase {
    /**
     * @expectedException \DomainException
     */
    public function testConstructorNeedsAtLeastOneCssField() {
        new Theme('foo', new User('u', 'p'), null, null, null, true, 'c');
    }

    /**
     * @doesNotPerformAssertions
     * @dataProvider cssParameterProvider
     *
     * @param $common
     * @param $day
     * @param $night
     */
    public function testAcceptsAllValidCombinationsOfCssAndNulls($common, $day, $night) {
        new Theme('boo', new User('u', 'p'), $common, $day, $night, true, 'c');
    }

    public function testGetsLatestRevisionCorrectly() {
        $theme = new Theme('a', new User('u', 'p'), 'body{}', null, null, true, 'c');
        $theme->addRevision(new ThemeRevision($theme, null, 'body{}', null, true, 'c', null, new \DateTime('yesterday')));

        $this->assertSame('body{}', $theme->getLatestRevision()->getCommonCss());
    }

    public function cssParameterProvider() {
        yield ['body{}', null, null];
        yield [null, 'body{}', null];
        yield [null, null, 'body{}'];
        yield ['body{}', 'body{}', null];
        yield [null, 'body{}', 'body{}'];
        yield ['body{}', null, 'body{}'];
        yield ['body{}', 'body{}', 'body{}'];
    }
}
