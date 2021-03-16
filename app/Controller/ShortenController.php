<?php

/**
 * Controllers/ShortenController.php
 * Controller for handling encoding and decoding urls
 *
 * @author Tahlia Dysart
 */

namespace App\Controller;

use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Tool\ShortenerTool;

class ShortenController extends BaseController
{

    /**
     * @var ShortenerTool
     */
    protected $shortenerTool;

    /**
     * __construct
     * 
     * @param ShortenerTool $shortenerTool 
     */
    public function __construct(ShortenerTool $shortenerTool)
    {
        $this->shortenerTool = $shortenerTool;
    } 

    /**
     * encode()
     * Send the url to the shortener tool to be encoded 
     * 
     * @param Request $request
     * @param Response $response
     */
    public function encode(Request $request, Response $response)
    {   
        $requestBody = $request->getParsedBody();
        
        if (isset($requestBody["url"]) === false) {
            $data = ["message" => "Please provide an URL"];
            return $this->formatResponse($data, $response);
        }

        try {
            $url = $this->shortenerTool->parse($requestBody["url"]);
        } catch (\Exception $e) {
            return $this->handleError($e, $response);
        }

        $encodedUrl = $this->shortenerTool->encode($url);
       
        $data = ["url" => $encodedUrl];
        
        return $this->formatResponse($data, $response);
    }

    /**
     * decode()
     * Send the url to the shortener tool to be decoded
     * 
     * @param Request $request
     * @param Response $response
     */
    public function decode(Request $request, Response $response)
    {
        $requestBody = $request->getParsedBody();
        
        if (isset($requestBody["url"]) === false) {
            $data = ["message" => "Please provide an URL"];
            return $this->formatResponse($data, $response);
        }

        try {
            $url = $this->shortenerTool->parse($requestBody["url"]);
        } catch (\Exception $e) {
            return $this->handleError($e, $response);
        }

        $decodedUrl = $this->shortenerTool->decode($url);

        if (boolval($decodedUrl) === false) {
            $data = ["message" => "Long URL not found, is your short URL valid?"];
            return $this->formatResponse($data, $response);
        }

        $data = ["url" => $decodedUrl];
        
        return $this->formatResponse($data, $response);
    }
}