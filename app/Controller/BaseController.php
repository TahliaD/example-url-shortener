<?php

/**
 * Controller/BaseController.php
 * Controller for base methods on all controllers
 *
 * @author Tahlia Dysart
 */

namespace App\Controller;

use Slim\Http\Response;

class BaseController
{
    /**
     * handleError()
     * 
     * @param \Exception $e 
     * @param Response $response 
     * @return Response $response 
     */
    protected function handleError(\Exception $e, Response $response) : Response 
    {
        return $response->withStatus(500)
            ->write($e->getMessage());
    }

    /**
     * formatResponse 
     * Encode JSON data, put together response body
     * 
     * @param array $data 
     * @param Response $response 
     */
    protected function formatResponse(array $data, Response $response) : Response
    {
        $payload = json_encode($data);

        $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write($payload);
        
        return $response;
    }
}