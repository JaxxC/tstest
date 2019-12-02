<?php
    class Country{

        public $continentCode;
        public $countryCode;
        public $phoneCode;

        
        /**
         * __construct
         *
         * @param  string $continentCode
         * @param  string $countryCode
         * @param  string $phoneCode
         *
         * @return void
         */
        public function __construct($continentCode, $countryCode, $phoneCode)
        {
            $this->continentCode = $continentCode;
            $this->countryCode = $countryCode;
            $this->phoneCode = $phoneCode;
        }
    }