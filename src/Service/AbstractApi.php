<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 26.11.2018
 * Time: 14:10
 */

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Bridge\Monolog\Logger;

abstract class AbstractApi
{
    public const FORMAT_NONE = '';
    public const FORMAT_XML = 'xml';
    public const FORMAT_JSON = 'json';

    private $httpLog = [];

    private $requestFormat = '';
    private $responseFormat = '';

    /**
     * ContainerInterface constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->httpLog = [];
        $history = Middleware::history($this->httpLog);
        $stack = HandlerStack::create();
        $stack->push($history);
        $config['handler'] = $stack;
        $this->client = new Client($config);
    }


    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return array
     */
    protected function request($method, $uri, $options)
    {
        $this->httpLog = [];
        $r = [
            'method' => $method,
            'uri' => $uri,
            'options' => $options,
            'response' => [],
            'log' => '',
            'log_level' => Logger::DEBUG,
        ];
        try {
            $response = $this->client->request($method, $uri, $options);
            $body = $response->getBody()->__toString();
            if ($this->responseFormat == self::FORMAT_XML) {
                $body = $this->xml2array(simplexml_load_string($body));
            } elseif ($this->responseFormat == self::FORMAT_JSON) {
                $body = json_decode($body, true);
            }
            $r['response'] = $body;
        } catch (\Exception $e) {
            $r['log_level'] = Logger::ERROR;
            $r['log'] .= $e->getMessage() . "\n"
                . $method . ' ' . $uri . "\n"
                . json_encode($options) . "\n";
        }

        //<editor-fold desc="Log">
        try {
            foreach ($this->httpLog as $transaction) {
                /** @var Request $request */
                $request = $transaction['request'];
                if ($request instanceof Request) {
                    $r['log'] .= $request->getMethod() . ' ' . $request->getUri() . ' HTTP/' . $request->getProtocolVersion() . "\n";
                    foreach ($request->getHeaders() as $name => $value) {
                        $r['log'] .= $name . ': ' . implode(';', $value) . "\n";
                    }
                    $r['log'] .= "\n";
                    $body = $request->getBody()->__toString();
                    if ($this->requestFormat == self::FORMAT_XML) {
                        $body = $this->formatXml($body);
                    } elseif ($this->requestFormat == self::FORMAT_JSON) {
                        $body = $this->formatJson($body);
                    }
                    $r['log'] .= $body;
                    $r['log'] .= "\n";
                } else {
                    $r['log'] .= "Request is empty\n";
                }
                /** @var Response $response */
                $response = $transaction['response'];
                if ($response instanceof Response) {
                    $r['log'] .= 'HTTP/' . $response->getProtocolVersion() . ' ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase() . "\n";
                    foreach ($response->getHeaders() as $name => $value) {
                        $r['log'] .= $name . ': ' . implode(';', $value) . "\n";
                    }
                    $r['log'] .= "\n";
                    $body = $response->getBody()->__toString();
                    if ($this->responseFormat == self::FORMAT_XML) {
                        $body = $this->formatXml($body);
                    } elseif ($this->responseFormat == self::FORMAT_JSON) {
                        $body = $this->formatJson($body);
                    }
                    $r['log'] .= $body;
                } else {
                    $r['log'] .= "Response is empty\n";
                }
            }
        } catch (\Exception $e) {
            $r['log'] .= $e->__toString() . "\n";
        }
        //</editor-fold>
        return $r;
    }

    protected function formatXml($data)
    {
        try {
            $dom = dom_import_simplexml(simplexml_load_string($data))->ownerDocument;
            $dom->formatOutput = true;
            return $dom->saveXML();
        } catch (\Exception $e) {
            return $data;
        }
    }

    protected function formatJson($data)
    {
        try {
            return json_encode(json_decode($data, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return $data;
        }
    }

    /**
     * @param array $data
     * @param \SimpleXMLElement $xmlElement
     * @return string
     */
    protected function array2xml($data, &$xmlElement)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key;
            }
            if (is_array($value)) {
                $subNode = $xmlElement->addChild($key);
                $this->array2xml($value, $subNode);
            } else {
                $xmlElement->addChild("$key", htmlspecialchars("$value"));
            }
        }
        $dom = dom_import_simplexml($xmlElement)->ownerDocument;
        $dom->formatOutput = true;
        return $dom->saveXML();
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     * @param array $out
     * @return array
     */
    protected function xml2array($xmlElement, $out = array())
    {
        foreach ((array)$xmlElement as $index => $node) {
            $out[$index] = (is_object($node)) ? $this->xml2array($node) : $node;
        }
        return $out;
    }

    /**
     * @return string
     */
    public function getRequestFormat(): string
    {
        return $this->requestFormat;
    }

    /**
     * @param string $requestFormat
     */
    public function setRequestFormat(string $requestFormat)
    {
        $this->requestFormat = $requestFormat;
    }

    /**
     * @return string
     */
    public function getResponseFormat(): string
    {
        return $this->responseFormat;
    }

    /**
     * @param string $responseFormat
     */
    public function setResponseFormat(string $responseFormat)
    {
        $this->responseFormat = $responseFormat;
    }
}