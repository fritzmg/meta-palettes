<?php

/**
 * @package    meta-palettes
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */


namespace ContaoCommunityAlliance\MetaPalettes\Test\Parser\Interpreter;

use ContaoCommunityAlliance\MetaPalettes\Parser\Interpreter\StringPalettesInterpreter;
use ContaoCommunityAlliance\MetaPalettes\Parser\MetaPaletteParser;
use PHPUnit\Framework\TestCase;

class StringPalettesInterpreterTest extends TestCase
{
    function testSimplePalette()
    {
        $interpreter = new StringPalettesInterpreter();
        $interpreter->startPalette('tl_test', 'default');
        $interpreter->addLegend('title', true, false);
        $interpreter->addFieldTo('title', 'headline');
        $interpreter->addLegend('config', true, true);
        $interpreter->addFieldTo('config', 'config');
        $interpreter->finishPalette();

        $this->assertEquals(
            '{title_legend},headline;{config_legend:hide},config',
            $GLOBALS['TL_DCA']['tl_test']['palettes']['default']
        );
    }

    function testInheritance()
    {
        $interpreter = new StringPalettesInterpreter();
        $parser = $this->getMockBuilder(MetaPaletteParser::class)
            ->setMethods(['parsePalette'])
            ->getMock();

        $parser
            ->expects($this->once())
            ->method('parsePalette')
            ->with('tl_test', 'default', $interpreter, true);


        $interpreter->startPalette('tl_test', 'test');
        $interpreter->inherit('default', $parser);

        // Parent config.
        $interpreter->addLegend('title', true, false);
        $interpreter->addFieldTo('title', 'headline');
        $interpreter->addLegend('config', true, true);
        $interpreter->addFieldTo('config', 'config');

        // Extended config
        $interpreter->addLegend('title', true, true);
        $interpreter->addFieldTo('title', 'title');
        $interpreter->addLegend('config', false, null);
        $interpreter->addFieldTo('config', 'config2', MetaPaletteParser::POSITION_BEFORE, 'config');

        $interpreter->finishPalette();

        $this->assertEquals(
            '{title_legend:hide},title;{config_legend:hide},config2,config',
            $GLOBALS['TL_DCA']['tl_test']['palettes']['test']
        );
    }

}