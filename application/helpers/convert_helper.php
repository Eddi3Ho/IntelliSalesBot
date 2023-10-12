<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use \ConvertApi\ConvertApi;

class ConvertApiHelper
{
    private static $instance; // Singleton instance
    private $apiSecret;

    private function __construct()
    {
        $this->apiSecret = 'vnBLdz401oQwK5Mr'; 
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function convertFileToPdf($file_name)
    {
        $file_path = FCPATH . 'assets/files/' . $file_name . '.pdf';
        $savePath = FCPATH . 'assets/text_file/';

        ConvertApi::setApiSecret($this->apiSecret);

        $result = ConvertApi::convert(
            'txt',
            [
                'File' => $file_path,
            ],
            'pdf'
        );

        $result->saveFiles($savePath);
    }

    public function convertGetThumbnail($file_name)
    {
        $file_path = FCPATH . 'assets/files/' . $file_name . '.pdf';
        $savePath = FCPATH . 'assets/thumbnail/';

        ConvertApi::setApiSecret($this->apiSecret);

        $result = ConvertApi::convert(
            'png',
            [
                'File' => $file_path,
                'PageRange' => '1',
            ],
            'pdf'
        );

        $result->saveFiles($savePath);
    }
}

