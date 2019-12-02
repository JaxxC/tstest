<?php

    class Ipstack{
        
        private static $apiKey = 'd9f000dbc0237078dfb39bf8033d244c';
 
        /**
         * get IP Info from ipstack API
         *
         * @param string $IP
         *
         * @return void
         */
        public static function getIPInfo($IP)
        {
            $ch = curl_init('http://api.ipstack.com/'. $IP .'?access_key='. self::$apiKey);  
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($ch);
            curl_close($ch);

            return json_decode($json, TRUE);
        }
    }