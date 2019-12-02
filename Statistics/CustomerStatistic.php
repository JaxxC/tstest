<?php

    class CustomerStatistic{

        public $customerId;
        public $sameContinentCallsNumber;
        public $totalCallsNumber;
        public $sameContinentCallsDuration;
        public $totalCallsDuration;

        public function __construct($customerId)
        {
            $this->customerId = $customerId;
        }

        /**
         * add Call, with check if it same continent or not
         *
         * @param  PhoneCall $call
         *
         * @return void
         */
        public function addCall(PhoneCall $call)
        {
            if($call->isCallSameContinent()){
                $this->addSameContinentCall($call);
            } else {
                $this->addNotSameContinentCall($call);
            }
        }

        /**
         * addSameContinentCall
         *
         * @param  PhoneCall $call
         *
         * @return void
         */
        public function addSameContinentCall(PhoneCall $call)
        {
            $this->sameContinentCallsNumber++;
            $this->totalCallsNumber++;
            $this->sameContinentCallsDuration += $call->duration;
            $this->totalCallsDuration += $call->duration;
        }

        /**
         * addNotSameContinentCall
         *
         * @param  PhoneCall $call
         *
         * @return void
         */
        public function addNotSameContinentCall(PhoneCall $call)
        {
            $this->totalCallsNumber++;
            $this->totalCallsDuration += $call->duration;
        }
    }