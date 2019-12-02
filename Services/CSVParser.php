<?php
    

    class CSVParser{

        /**
         * @var string
         */
        protected $csvFile;

        /**
         * @var PhoneCallsCollection
         */
        public $calls;

        /**
         * __construct
         *
         * @param  string $csvFile
         *
         * @return void
         */
        public function  __construct($csvFile)
        {
            $this->csvFile = $csvFile;
            $this->calls = new PhoneCallsCollection();
        }

        /**
         * Parse csv file 
         *
         * @return PhoneCallsCollection
         */
        public function parse()
        {
            if (($handler = fopen($this->csvFile, "r")) !== FALSE) {
                while (($data = fgetcsv($handler, 1000, ",")) !== FALSE){		
                    $this->calls->addCall($data);
                }
                fclose($handler);
            }
            
            return $this->calls;
        }
    }