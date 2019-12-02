<?php

    class PhoneCall{
        
        public $customerId;
        public $callDate;
        public $duration;
        public $phoneNumber;
        public $customerIP;

        /**
         * @var Country
         */
        protected $phoneNumberRegionData;
        
        /**
         * @var Country
         */
        protected $ipData;

        /**
         * __construct
         *
         * @param  array $data
         * @return void
         */
        public function __construct($data)
        {
            $this->customerId = $data[0];
            $this->callDate = $data[1];
            $this->duration = $data[2];
            $this->phoneNumber = $data[3];
            $this->customerIP = $data[4];
        }

        /**
         * Get IP geodata frop ipstack API
         *
         * @return void
         */
        public function loadIpData()
        {
            $ipData = Ipstack::getIPInfo($this->customerIP);
            $this->ipData = new Country($ipData['continent_code'], $ipData['country_code'], $ipData['location']['calling_code']);
        }

        /**
         * add phone number calculated geodata to call
         *
         * @param  mixed $regionInfo
         *
         * @return void
         */
        public function setPhoneNumberRegionInfo($regionInfo)
        {
            $this->phoneNumberRegionData = new Country(
                $regionInfo['continent_code'], 
                $regionInfo['country_code'], 
                $regionInfo['phone_code']);
        }

        
        /**
         * Check if call as  counted as same continent
         *
         * @return void
         */
        public function isCallSameContinent()
        {
            return $this->phoneNumberRegionData->continentCode === $this->ipData->continentCode;
        }
    }