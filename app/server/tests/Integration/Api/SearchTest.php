<?php

namespace App\Api;

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\TestCase;
use \App\Entities\Status;

class SearchTest extends TestCase
{
    /**
     * Bootstraps the application
     *
     * @return mixed|\Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        return require __DIR__.'/../../../bootstrap/app.php';
    }

    /**
     * Checks that the application correctly rejects invalid methods.
     */
    public function testInvalidMethod()
    {
        $this->post('/v1/search');
        $status = json_decode($this->response->getContent());

        $this->assertEquals(Status::STATUS_FAILURE, $status->status);
        $this->assertEquals(Status::REASON_METHOD_NOT_ALLOWED, $status->reason);
    }

    /**
     * Tests that the request will reject unless the query parameter is offered
     */
    public function testRequiresQueryParam()
    {
        $this->get('/v1/search');
        $status = json_decode($this->response->getContent());

        $this->assertEquals(Status::STATUS_FAILURE, $status->status);
        $this->assertEquals(Status::REASON_BAD_REQUEST, $status->reason);
    }

    /**
     * Verifies the conditions under which the endpoint is expected to return successfully
     */
    public function testOk()
    {
        $this->get('/v1/search?q=Product');

        // Ensure the content is JSON
        json_decode($this->response->getContent());
        $jsonError = json_last_error();

        $this->assertEquals(JSON_ERROR_NONE, $jsonError);

        // Verify the response is OK
        $this->assertEquals($this->response->status(), Response::HTTP_OK);
    }
}
