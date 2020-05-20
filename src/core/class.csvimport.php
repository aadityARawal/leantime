<?php
namespace leantime\core;
use leantime\domain\repositories\clients as ClientRepository;
class csvImport
{
    private $file;
    private $clientRepository;
    public function __construct( $file )
    {
        $this->file = $file;
        $this->clientRepository = new ClientRepository();
        switch( $this->importCSVData( $file ) ) {
            case 200:
                return 200; // OK
            case 201:
                return 201; // Couldn't open the file
        }
    }

    public function setFile( $file ) {
        if ( ( substr( $file['name'], strlen( $file['name'] ) - 5, 4 ) ) === '.csv' ) {
            $this->file = $file;
            return true;
        }
        return false;
    }

    public function importCSVData( $file ) {
        if ( ! ( $csvFile = fopen( $file , 'r') ) ) {
            return 201;
        }
        $i = 1;
        while( ( $data = fgetcsv( $csvFile, 1000, ',' ) ) !== false ) {
            if ($i === 1) {
                $i++;
                continue;
            }
            $values = array(
                'name' => $data[1],
                'street' => $data[2],
                'zip' => $data[3],
                'city' => $data[4],
                'state' => $data[5],
                'country' => $data[6],
                'phone' => $data[7],
                'internet' => $data[8],
                'email' => $data[9],
            );
            $this->clientRepository->addClient($values);
        }
        return 200;
    }

}