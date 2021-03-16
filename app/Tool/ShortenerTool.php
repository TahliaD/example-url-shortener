<?php

/**
 * Tool/shortenerTool.php
 * Methods for the encoding and decoding of URLs
 *
 * @author Tahlia Dysart
 */

namespace App\Tool;

class ShortenerTool
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string 
     */
    private $storePath;

    /**
     * __construct
     * 
     * @param string $host
     * @param string $storePath
     */
    public function __construct(string $host, string $storePath)
    {
        $this->host = $host;
        $this->storePath = $storePath;
    } 

    /**
     * encode()
     * Encode the given url and return the shortened URL
     * 
     * @param string $url
     * @return string $encodedUrl
     */
    public function encode(string $url) : string 
    {
        $nonce = $this->generateNonce($url);
        $shortUrl = $this->host.$nonce;

        $this->saveUrl($url, $shortUrl);

        return $shortUrl;
    }

    /**
     * decode()
     * Decode the given url to its original, longer format 
     * 
     * @param string $url
     * @return string $decodedUrl
     */
    public function decode(string $shortUrl) : ?string 
    {   
        $data = json_decode(file_get_contents($this->storePath), true)["urls"];
        return $data[$shortUrl];
    }

    /**
     * parse()
     * Parse the url, stripping of illegal characters
     * 
     * @param string $url 
     * @return string $url
     */
    public function parse(string $url) : string 
    {   
        // Strip illegal characters
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        // Check url valid
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("URL is invalid");
        }

        return $url;
    }

    /**
     * generateNonce()
     * Generate a hash for the short URL 
     * 
     * @param string $secret 
     * @return string 
     */
    private function generateNonce(string $secret) : string
    {
        return hash('adler32', $secret);
    }

    /**
     * saveUrl()
     * Save Url set to the JSON store 
     * 
     * @param string $url,
     * @param string $shortUrl
     */
    private function saveUrl(string $url, string $shortUrl) 
    {   
        $data = json_decode(file_get_contents($this->storePath), true);
       
        $data["urls"][$shortUrl] = $url;

        file_put_contents($this->storePath, json_encode($data));
    }
}