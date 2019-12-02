<?php

    class Builder{

        /**
         * @var PhoneCallsCollection
         */
        private $phoneCallsCollection;

        /**
         * @var mixed
         */
        private $statistic;
        
        /**
         * parse Csv file to PhoneCallsCollection
         *
         * @param  string $path
         *
         * @return void
         */
        public function parseCsv($path)
        {
            $csvParser = new CSVParser($path);
            $this->phoneCallsCollection = $csvParser->parse();
        }

        /**
         * build Statistic for customers
         *
         * @return array CustomerStatistic 
         */
        public function buildStatistic()
        {
            $groupedCalls = $this->phoneCallsCollection->groupCallsByUser();

            foreach ($groupedCalls as $customerId => $calls){
                $customerStatistic = new CustomerStatistic($customerId);
                foreach($calls as $call){
                    $customerStatistic->addCall($call);
                }
                $this->statistic[$customerId] = $customerStatistic;
            }

            return $this->statistic;
        }
    }