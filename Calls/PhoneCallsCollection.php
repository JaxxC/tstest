<?php

    class PhoneCallsCollection{
       
        public $calls = [];
        protected $groupedCalls= [];

        /**
         * @var CountriesMap
         */
        private $map;

        public function __construct()
        {
            $this->map = new CountriesMap();
        }
 
        /**
         * add Call data to collection
         *
         * @param  mixed $data
         *
         * @return void
         */
        public function addCall($data)
        {
            $phoneCall = new PhoneCall($data);
            
            $phoneCall->setPhoneNumberRegionInfo($this->map->getCountryInfoByPhoneNumber($phoneCall->phoneNumber));
            $phoneCall->loadIpData();

            $this->calls[] = $phoneCall;
        }

        /**
         * grouping calls by customerId in arrays where key is customerId
         *
         * @return void
         */
        public function groupCallsByUser()
        {
            $groupedCalls = [];
            
            foreach ($this->calls as $call)
            {
                $groupedCalls[$call->customerId][] = $call;
            }

            return $groupedCalls;
        }

    }