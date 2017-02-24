<?php

namespace telesign\sdk\phoneid;

use telesign\sdk\Example;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

use Psr\Http\Message\RequestInterface;

final class PhoneIdClientTest extends TestCase {

  const EXAMPLE_CUSTOMER_ID = Example::CUSTOMER_ID;
  const EXAMPLE_SECRET_KEY = Example::SECRET_KEY;
  const EXAMPLE_API_HOST = Example::API_HOST;
  const EXAMPLE_REFERENCE_ID = Example::REFERENCE_ID;
  const EXAMPLE_PHONE_NUMBER = Example::PHONE_NUMBER;

  /**
  * @dataProvider getRequestExamples
  */
  function testRequestFormat ($data) {
    $mock = new MockHandler([ new Response() ]);
    $client = new PhoneIdClient(
      self::EXAMPLE_CUSTOMER_ID, self::EXAMPLE_SECRET_KEY, self::EXAMPLE_API_HOST, 10, null, $mock
    );
    $client->$data["method"](...$data["args"]);
    $request = $mock->getLastRequest();

    $this->assertInstanceOf(RequestInterface::class, $request);
    $this->assertEquals($data["request"]["uri"], $request->getUri());

    parse_str($request->getBody()->getContents(), $actual_fields);

    $this->assertEquals($data["request"]["fields"], $actual_fields);
  }

  function getRequestExamples () {
    return [
      [[
        "method" => "phoneid",
        "args" => [
          self::EXAMPLE_PHONE_NUMBER,
          [ "optional_param" => "123" ]
        ],
        "request" => [
          "uri" => self::EXAMPLE_API_HOST . "/v1/phoneid/" . self::EXAMPLE_PHONE_NUMBER,
          "fields" => [
            "optional_param" => "123"
          ]
        ]
      ]]
    ];
  }

}
