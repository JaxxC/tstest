<?php
    include_once 'Statistics/Builder.php';
    include_once 'Statistics/CustomerStatistic.php';
    include_once 'Statistics/Renderer.php';

    include_once 'Services/CSVParser.php';
    include_once 'Services/Ipstack.php';
    require_once 'Calls/PhoneCall.php';
    require_once 'Countries/CountriesMap.php';
    require_once 'Calls/PhoneCallsCollection.php';
    require_once 'Countries/Country.php';

    if(!empty($_FILES['uploaded_file']))
    {
        $path = "uploads/";
        $path = $path . basename( $_FILES['uploaded_file']['name']);
        if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
            $statisticBuilder = new Builder();
            $statisticBuilder->parseCsv($path);
            $statistic = $statisticBuilder->buildStatistic();
            echo Renderer::render($statistic);
        } else{
            echo "There was an error uploading the file, please try again!";
        }
    }
