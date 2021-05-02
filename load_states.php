<?php
$country = $_GET['country'];
switch ($country) {
    case 'US':
        $url = "country_states/USA.csv";
        break;
    case 'CA':
        $url = "country_states/CA.csv";
        break;
    case 'AT':
        $url = "country_states/AT.csv";
        break;
    case 'AU':
        $url = "country_states/AU.csv";
        break;
    case 'BE':
        $url = "country_states/BE.csv";
        break;
    case 'DE':
        $url = "country_states/DE.csv";
        break;
    case 'ES':
        $url = "country_states/ES.csv";
        break;
    case 'FR':
        $url = "country_states/FR.csv";
        break;
    case 'GB':
        $url = "country_states/GB.csv";
        break;
    case 'IE':
        $url = "country_states/IE.csv";
        break;
    case 'IT':
        $url = "country_states/IT.csv";
        break;
    case 'NL':
        $url = "country_states/NL.csv";
        break;
    case 'SE':
        $url = "country_states/SE.csv";
        break;
    default:
        $url = "country_states/US.csv";
        break;
}

if (($handle = fopen($url, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        $array[] = array(
            "code" => $data[2],
            "name" => $data[3],
        );
    }

    echo json_encode($array);

}
