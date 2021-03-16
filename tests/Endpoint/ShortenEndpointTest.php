<?php

namespace App\Test\Endpoint;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Slim\Http\Response;
use PHPUnit\Framework\TestCase;

use App\Controller\ShortenController;
use App\Tool\ShortenerTool;
use App\Test\Traits\RequestTrait;

class ShortenEndpointTestTest extends TestCase
{
    use RequestTrait;

    private $host = "https://short.co/";
    private $storePath = "./test-store.json";

    public function testEncodeEndpoint(): void
    {
        // Create request with method and url
        $request = $this->createJsonRequest('POST', '/encode', ["url" => "https://www.testwebsite.co.uk/long-url"]);

        $shortenerTool = new ShortenerTool($this->host, $this->storePath);
        $shortenController = new ShortenController($shortenerTool);

        $response = $shortenController->encode($request, $this->createResponse());

        $expected = [
            "url" => "https://short.co/1bbe0e7f"
        ];

        // Asserts
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonData($expected, $response);

       $this->resetStore();
    }

    public function testDecodeEndpoint(): void
    {
        //Put the short url into the store for the decode func to find
        file_put_contents("./test-store.json", json_encode([
            "urls" => [
                "https://short.co/short-url" => "https://long.co/long-url"
            ]
        ]));

        // Create request with method and url
        $request = $this->createJsonRequest('GET', '/decode', ["url" => "https://short.co/short-url"]);

        $shortenerTool = new ShortenerTool($this->host, $this->storePath);
        $shortenController = new ShortenController($shortenerTool);

        $response = $shortenController->decode($request, $this->createResponse());
        
        $expected = [
            "url" => "https://long.co/long-url"
        ];

        // Asserts
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonData($expected, $response);

       $this->resetStore();
    }

    /**
     * resetStore()
     * Resets the test-store to default
     */
    private function resetStore() : void
    {
        file_put_contents("./test-store.json", json_encode([
            "urls" => []
        ]));
    }
}