<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use AppBundle\Entity\Tblproductdata;

/**
 * Class ImportCommand
 *
 * Used to parse data from csv-file, persist it to DB and make reports
 *
 * To use it read README.MD or simply run:
 * php app/console app:import:csv --testMode
 *
 * @package AppBundle\Command
 * @author Yauheni "Eugene" Svirydzenka <partagas@mail.ru>
 */
class ImportCommand extends ContainerAwareCommand
{
    protected $fileName;
    protected $includeFirstLine = false;
    protected $itemsParsedSuccesfully = 0;
    protected $itemsParsedWithError = 0;
    protected $parsedWithErrorProductsSet = [];
    protected $itemsParsedWithErrorLineNumbers = [];
    protected $itemsParsedWithErrorProducts = [];
    protected $productCodesDuplicated = [];

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:import:csv')
            ->setDescription('Import data from csv-file and use it')
            ->addArgument(
                'fileName',
                InputArgument::OPTIONAL,
                'Full file name to parse'
            )
            ->addOption(
                'testMode',
                null,
                InputOption::VALUE_NONE,
                'If set, parsed data will not be persisted to DB (only showed)'
            )
            ->addOption(
                'includeFL',
                null,
                InputOption::VALUE_NONE,
                'If set, first line of csv-file will be parsed'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $acceptedFileName = $input->getArgument('fileName');

        if (!$acceptedFileName == "") {
            $this->fileName = $acceptedFileName;
        } else {
            $this->fileName = "app/data/stock.csv";
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $notifications = new SymfonyStyle($input, $output);

        $notifications->title("Parsing CSV Data");

        if ($input->getOption('includeFL')) {
            $this->includeFirstLine = true;
            $notifications->note("First line will be parsed");
        }
        $notifications->note("First line will be skipped");

        $notifications->section("Starting to parse file...");

        $fileHandler = $this->getContainer()->get('app.parser')->readCSVFile($this->fileName);

        if (gettype($fileHandler) == "string" ) {
            $notifications->error($fileHandler);
        } else {
            $parsedData = $this->getContainer()->get('app.parser')->parseCSVFile($fileHandler, $this->includeFirstLine);
            $this->itemsParsedSuccesfully = count($parsedData['productsSet']);
            $this->parsedWithErrorProductsSet = $parsedData['parsedWithErrorProductsSet'];
            $this->productCodesDuplicated = $parsedData['productCodesDuplicated'];
            $this->itemsParsedWithError = count($this->parsedWithErrorProductsSet);
            $notifications->success("File $this->fileName sucessfully parsed");

            foreach ($this->parsedWithErrorProductsSet as $item) {
                $this->itemsParsedWithErrorLineNumbers[] = $item['lineNumber'];
                $this->itemsParsedWithErrorProducts[] = $item['item'];
            }

            $this->makeDetailedParsingReport($input, $output);
        }

        if ($input->getOption('testMode')) {
            $notifications->section("Running in test mode. Data will not be persisted to BD");
        } else {
            $notifications->section("Running in productions mode. Data will be persisted to BD");
            $persistingToDBReport = $this->saveDataToDB($parsedData['productsSet']);
            $notifications->section($persistingToDBReport);
            if (!empty($this->productCodesDuplicated)) {
                $notifications->note("Duplicated items! Not persisted to DB with product code:");
                foreach ($this->productCodesDuplicated as $item) {
                    $notifications->text($item);
                }
            }
        }
    }

    /**
     * Custom internal method for making report
     *
     * Created as public for better compability
     *
     * @param $input
     * @param $output
     * @return void
     */
    public function makeDetailedParsingReport($input, $output)
    {
        $notifications = new SymfonyStyle($input, $output);

        $notifications->section("Making detailed parsing report...");
        $notifications->listing([
            "Total items parsed: " . ($this->itemsParsedSuccesfully + $this->itemsParsedWithError),
            "Items parsed sucessfully: " . $this->itemsParsedSuccesfully,
            "Items parsed with errors: " . $this->itemsParsedWithError,
        ]);

        $notifications->text("Items parsed with errors in lines: ");

        foreach ($this->itemsParsedWithErrorLineNumbers as $lineNumber) {
            $notifications->text("#: $lineNumber");
        }

        $notifications->text("Items parsed with errors products code: ");

        foreach ($this->itemsParsedWithErrorProducts as $product) {
            $notifications->text("#: " . $product[0]);
        }
    }

    /**
     * Custom internal method for persisting data to DB
     *
     * Created as public for better compability
     *
     * @param array $parsedData List of parsed data
     * @return string $persistingToDBReport Persisting report
     */
    public function saveDataToDB($parsedData)
    {
        foreach ($parsedData as $item) {
            $dateTime = new \DateTime();
            $product = new Tblproductdata();

            $product->setStrproductname($item['productName']);
            $product->setStrproductdesc($item['productDescription']);
            $product->setStrproductcode($item['productCode']);

            $product->setDtmadded($dateTime);
            $product->setStmtimestamp($dateTime);

            if ($item['discontinued'] == "yes") {
                $product->setDtmdiscontinued($dateTime);
            } else {
                // provide another logic for this case
                // does not contradicts to given in task conditions
                $product->setDtmdiscontinued($dateTime);
            }

            $product->setStocklevel($item['stockLevel']);
            $product->setPrice($item['cost']);

            $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();
            $entityManager->persist($product);
            $entityManager->flush();
        }

        $persistingToDBReport = "Persisted to DB " . count($parsedData) . " items.";
        return $persistingToDBReport;
    }
}