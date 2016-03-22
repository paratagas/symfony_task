<?php

namespace AppBundle\Utils;

/**
 * Class Parser
 *
 * Class Parser is created as a part of test task
 * Used to parse data from csv-file and to check the rules
 *
 * @package AppBundle\Utils
 * @author Yauheni "Eugene" Svirydzenka <partagas@mail.ru>
 */
class Parser
{
    protected $productCodes = [];
    protected $productCodesDuplicated = [];

    /**
     * Creates file handler
     *
     * @param string $fileName The name of file to parse
     * @return resource|string File handler or error string
     */
    public function readCSVFile($fileName)
    {
        if ($fileHandler = fopen($fileName, "r")) {
            return $fileHandler;
        } else {
            return "Could not read file $fileName";
        }
    }

    /**
     * Parses csv-file data
     *
     * @param resource $fileHandler File handler
     * @param boolean $includeFirstLine Flag
     * @return array $returnedData Multidimensional array with several types of data
     */
    public function parseCSVFile($fileHandler, $includeFirstLine)
    {
        $this->productCodes = [];
        $lineNumber = 0;
        $productItem = [];
        $productsSet = [];
        $parsedWithErrorProductItem = [];
        $parsedWithErrorProductsSet = [];
        $returnedData = [];

        while (!feof($fileHandler)) {
            $oneLine = fgetcsv($fileHandler);
            $lineNumber++;

            if (!$includeFirstLine) {
                $includeFirstLine = "First line skipped. Go further!";
                continue;
            }

            if (count($oneLine) != 6) {
                if (!empty($oneLine)) {
                    $parsedWithErrorProductItem['item'] = $oneLine;
                    $parsedWithErrorProductItem['lineNumber'] = $lineNumber;
                    $parsedWithErrorProductsSet[] = $parsedWithErrorProductItem;
                    continue;
                }
                continue;
            }

            list($productCode, $productName, $productDescription, $stockLevel, $cost, $discontinued) = $oneLine;

            $isUniqueCode = $this->controlUniqueProductCode($productCode);
            if ($isUniqueCode) {
                $productItem['productCode'] = $productCode;
                $productItem['productName'] = $productName;
                $productItem['productDescription'] = $productDescription;

                if ($stockLevel == "") {
                    $stockLevel = 0;
                }
                $productItem['stockLevel'] = intval($stockLevel);
                $productItem['cost'] = floatval($cost);
                $productItem['discontinued'] = trim($discontinued);

                if ($this->filterParsedDataAccoringToRules($productItem)) {
                    $productsSet[] = $productItem;
                } else {
                    continue;
                }
            } else {
                $this->productCodesDuplicated[] = $productCode;
                continue;
            }
        }
        fclose($fileHandler);
        $returnedData['productsSet'] = $productsSet;
        $returnedData['parsedWithErrorProductsSet'] = $parsedWithErrorProductsSet;
        $returnedData['productCodesDuplicated'] = $this->productCodesDuplicated;
        return $returnedData;
    }

    /**
     * Checks product item according to given conditions
     *
     * @param array $productItem List of product items
     * @return bool
     * @todo If needed add another conditions to check
     */
    public function filterParsedDataAccoringToRules($productItem)
    {
        if ((($productItem['stockLevel'] < 10 ) && ($productItem['cost'] < 5)) || ($productItem['cost'] > 1000)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks whether product code is unique or not
     *
     * @param string $productCode Product code
     * @return bool
     */
    public function controlUniqueProductCode($productCode)
    {
        if (in_array($productCode, $this->productCodes)) {
            return false;
        } else {
            // update product codes
            $this->productCodes[] = $productCode;
            return true;
        }
    }
}