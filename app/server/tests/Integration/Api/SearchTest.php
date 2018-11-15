<?php

namespace App\Api;

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
}
