<?php

namespace Tests\Unit;

use App\Ratings\CSPRating;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use App\HTTPResponse;

/**
 * CSPRating is not good. There are many ways to bypass this "secure" rating.
 * TODO: Improve parsing and rating of CSP.
 *
 * Class CSPRatingTest
 * @package Tests\Unit
 */
class CSPRatingTest extends TestCase
{
    /** @test */
    public function cspRating_rates_c_because_header_is_not_set()
    {
        $client = $this->getMockedGuzzleClient([
            new Response(200),
        ]);
        $response = new HTTPResponse('https://testdomain', $client);
        $rating = new CSPRating($response);

        $this->assertEquals(0, $rating->score);
        $this->assertEquals($rating->errorMessage, 'HEADER_NOT_SET');
    }

    /** @test */
    public function cspRating_rates_50_because_header_is_set_with_unsafe_inline()
    {
        $client = $this->getMockedGuzzleClient([
            new Response(200, [
                "Content-Security-Policy" => "default-src 'none'; script-src 'unsafe-inline'; object-src 'none';",
            ]),
        ]);
        $response = new HTTPResponse('https://testdomain', $client);
        $rating = new CSPRating($response);

        $this->assertEquals(50, $rating->score);
        $this->assertTrue(collect($rating)->flatten()->contains('CSP_UNSAFE_INCLUDED'));
    }

    /** @test */
    public function cspRating_rates_50_because_header_is_set_with_unsafe_eval()
    {
        $client = $this->getMockedGuzzleClient([
            new Response(200, [
                "Content-Security-Policy" => "default-src 'none'; script-src 'unsafe-eval'; object-src 'none';",
            ]),
        ]);
        $response = new HTTPResponse('https://testdomain', $client);
        $rating = new CSPRating($response);

        $this->assertEquals(50, $rating->score);
        $this->assertTrue(collect($rating)->flatten()->contains('CSP_UNSAFE_INCLUDED'));
    }

    /** @test */
    public function cspRating_rates_75_because_header_is_set_without_unsafes_but_without_default_src_none()
    {
        $client = $this->getMockedGuzzleClient([
            new Response(200, [
                "Content-Security-Policy" => "default-src 'self';",
            ]),
        ]);
        $response = new HTTPResponse('https://testdomain', $client);
        $rating = new CSPRating($response);

        $this->assertEquals(75, $rating->score);
    }

    /** @test */
    public function cspRating_rates_a_because_header_is_set_without_unsafes_and_with_default_src_none()
    {
        $client = $this->getMockedGuzzleClient([
            new Response(200, [
                "Content-Security-Policy" => "default-src 'none';",
            ]),
        ]);
        $response = new HTTPResponse('https://testdomain', $client);
        $rating = new CSPRating($response);

        $this->assertEquals(100, $rating->score);
    }


    /** @test */
    public function cspRating_adds_comment_for_legacy_header()
    {
        $client = $this->getMockedGuzzleClient([
            new Response(200, [
                "X-Content-Security-Policy" => "default-src 'none';",
            ]),
        ]);
        $response = new HTTPResponse('https://testdomain', $client);
        $rating = new CSPRating($response);

        $this->assertTrue(collect($rating)->contains('CSP_LEGACY_HEADER_SET'));
    }

    /** @test */
    public function cspRating_can_handle_whitespaces()
    {
        $client = $this->getMockedGuzzleClient([
            new Response(200, [
                "Content-Security-Policy" => "default-src   'none';",
            ]),
        ]);
        $response = new HTTPResponse('https://testdomain', $client);
        $rating = new CSPRating($response);

        $this->assertEquals(100, $rating->score);
    }


    /**
     * This method sets and activates the GuzzleHttp Mocking functionality.
     * @param array $responses
     * @return Client
     */
    protected function getMockedGuzzleClient(array $responses)
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        return (new Client(["handler" => $handler])) ;
    }
}
