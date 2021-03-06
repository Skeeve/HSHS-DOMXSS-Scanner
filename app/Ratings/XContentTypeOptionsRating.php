<?php

namespace App\Ratings;

use GuzzleHttp\Client;
use App\HTTPResponse;


class XContentTypeOptionsRating extends Rating
{

    public function __construct(HTTPResponse $response) {
        parent::__construct($response);

        $this->name = "X_CONTENT_TYPE_OPTIONS";
        $this->scoreType = "warning";
    }

    protected function rate()
    {
        $header = $this->getHeader('x-content-type-options');

        if ($header === null) {
            $this->hasError = true;
            $this->errorMessage = "HEADER_NOT_SET";
        } elseif ( gettype($header) === "string" ) {
            $this->hasError = true;
            $this->errorMessage = "GENERAL_ERROR";
            $this->testDetails->push([
 		'placeholder' => 'GENERAL_ERROR',
		'values' => [
			'ERRORTEXT' => $header
		]
	    ]);
        } elseif (count($header) > 1) {
            $this->hasError = true;
            $this->errorMessage = "HEADER_SET_MULTIPLE_TIMES";
            $this->testDetails->push(['placeholder' => 'HEADER_SET_MULTIPLE_TIMES', 'values' => ['HEADER' => $header]]);
        } else {
            $header = $header[0];

            if (strpos($header, 'nosniff') !== false) {
                $this->score = 100;
                $this->testDetails->push(['placeholder' => 'XCTO_CORRECT', 'values' => ['HEADER' => $header]]);
            }
            else {
                $this->testDetails->push(['placeholder' => 'XCTO_NOT_CORRECT', 'values' => ['HEADER' => $header]]);
            }
        }
    }
}

