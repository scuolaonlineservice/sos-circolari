<?php
defined('_JEXEC') or die;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
require "aws-sdk/vendor/autoload.php";

class S3Helper {
    public function upload($name, $content) {
        $s3 = new S3Client([
            "version" => "latest",
            "region" => "eu-central-1",
            "credentials" => [
                "key" => getenv("AWS_ACCESS_KEY_ID"),
                "secret" => getenv("AWS_SECRET_ACCESS_KEY")
            ]
        ]);

        try {
            $result = $s3->putObject([
                'ACL' => 'public-read',
                'Body' => $content,
                'Bucket' => 'scuolaonlineservice',
                'Key' => $name,
                'ContentType' => 'application/pdf'
            ]);
            return $result;
        } catch (S3Exception $error) {
            return $error->getMessage();
        }
    }
}