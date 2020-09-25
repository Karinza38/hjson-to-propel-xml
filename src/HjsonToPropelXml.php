<?php

namespace HjsonToPropelXml;

use Psr\Log\LoggerInterface;

/**
 * Main class
 */
class HjsonToPropelXml
{

    private $Database;

    public $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function getXml()
    {
        return $this->Xml;
    }

    /**
     * Go through the nested array and convert to object than xml
     *
     * @param array $obj
     * @return void
     */
    public function convert(array $obj)
    {
        static $level = 0;
        // foreach level
        try {
            foreach ($obj as $key => $el) {
                if ($level == 0) {  // root level
                    $this->logger->info("convert start - found database '$key'");
                    $this->Database = new Database(['name' => $key], $this->logger);
                    $level++;
                    if (is_array($el) && count($obj) == 1) {
                        if (!$this->convert($el)) {
                            $this->Xml = $this->Database->getXml();
                            $this->logger->info("convert ended - found " . $this->Database->getTableCount() . " tables");
                        }

                        return 0;
                    } else {
                        $this->logger->error("HJSON parser - Empty database or parsing error - make sure all tables are in the database bracket");
                        return 1;
                    }
                } else {
                    $done = false;
                    $done = $this->Database->add($key, $el, $level);

                    if (is_array($el) && !$done) {
                        $level++;
                        $this->convert($el);
                        $level--;
                    }
                }
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception);
        }
    }
}
