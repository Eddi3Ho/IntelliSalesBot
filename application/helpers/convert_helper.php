<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require_once base_url('vendor/autoload.php');
require_once FCPATH . 'vendor/autoload.php';

use \ConvertApi\ConvertApi;

function convertFileToPdf($file_name)
{
    $file_path = FCPATH . 'assets/files/'.$file_name.'.pdf';
    $savePath = FCPATH . 'assets/text_file/'; // Use FCPATH to specify the project's root directory

    ConvertApi::setApiSecret('vnBLdz401oQwK5Mr');
    // $result = ConvertApi::convert('pdf', ['File' => $filePath]);
    $result = ConvertApi::convert(
        'txt',
        [
            // 'File' => base_url('assets/files/'. $file_name . '.php' ),
            'File' => $file_path,
        ],
        'pdf'
    );
    // return $result->getFile()->getContents();
    $result->saveFiles($savePath);
    // $result->getFile()->save(base_url('assets/text_file'));
}
