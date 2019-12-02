<?php

    class CountriesMap{

        private $infoFile = '/countryInfo.txt';

        private $countries = [];
        
        // public 

        public function __construct()
        {
            $this->loadCountries();
        }

        /**
         * load Countries data from txt file
         *
         * @return void
         */
        protected function loadCountries()
        {
            $handler = fopen(__DIR__ . $this->infoFile, 'r');
            while (($row = fgets($handler)) !== false) {
                $data = str_getcsv($row, "\t");
                $this->countries[$data[0]] = [
                    'continent_code' => $data[8],
                    'country_code' => $data[0],
                    'phone_code' => $data[12]
                ];
            }
        
            fclose($handler);
        }

        /**
         * get Country geodata by phone number
         *
         * @param  mixed $phoneNumber
         *
         * @return void
         */
        public function getCountryInfoByPhoneNumber($phoneNumber)
        {
            foreach ($this->countries as $data){
                //if country have few codes
                $codes = explode('and', $data['phone_code']);
                foreach($codes as $code){
                    $sanitizedCode = preg_replace('/[^0-9]/', '', $code);

                    if ($code && $sanitizedCode && strpos($phoneNumber,  $sanitizedCode) === 0) {
                        return $data;
                    }
                }
            }
        }

    }