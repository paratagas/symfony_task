<?php

namespace AppBundle\Tests\Utils;

use AppBundle\Utils\Parser;

/**
 * Class ParserTest
 *
 * Class Parser is created to test class Parser methods by unit testing
 *
 * To run this test:
 * phpunit -c app src/AppBundle/Tests/Utils/ParserTest.php
 *
 * @package AppBundle\Tests\Utils
 * @author Yauheni "Eugene" Svirydzenka <partagas@mail.ru>
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $fileName = "app/data/stock.csv";

    /**
     * Testing readCSVFile() method
     */
    public function testReadCSVFile()
    {
        $this->assertFileExists($this->fileName);
        $parser = new Parser();
        $result = $parser->readCSVFile($this->fileName);
        $this->assertInternalType('resource', $result);
    }

    /**
     * Testing parseCSVFile() method
     */
    public function testParseCSVFile()
    {
        $parser = new Parser();
        $fileHandler = fopen($this->fileName, "r");
        $result = $parser->parseCSVFile($fileHandler, false);

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('productsSet', $result);
        $this->assertArrayHasKey('parsedWithErrorProductsSet', $result);
        $this->assertArrayHasKey('productCodesDuplicated', $result);
    }

    /**
     * Testing filterParsedDataAccoringToRules() method
     */
    public function testFilterParsedDataAccoringToRules()
    {
        $parser = new Parser();

        $productItem1 = ['stockLevel' => 10, 'cost' => 10];
        $productItem2 = ['stockLevel' => 9, 'cost' => 4];
        $productItem3 = ['stockLevel' => 12, 'cost' => 2000];

        $trueResult = $parser->filterParsedDataAccoringToRules($productItem1);
        $falseResult1 = $parser->filterParsedDataAccoringToRules($productItem2);
        $falseResult2 = $parser->filterParsedDataAccoringToRules($productItem3);

        $this->assertTrue($trueResult);
        $this->assertFalse($falseResult1);
        $this->assertFalse($falseResult2);
    }

    /**
     * Testing controlUniqueProductCode() method
     */
    public function testControlUniqueProductCode()
    {
        $parser = new Parser();
        $productCode = 'Code1';
        $result = $parser->controlUniqueProductCode($productCode);
        $this->assertTrue($result);
    }
}