<?php

declare(strict_types = 1);

namespace App\Entities;

use \PHPUnit\Framework\TestCase;
use \App\Exceptions\InvalidEntityException;

class StatusTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testValidConstruction()
    {
        // If the object is constructed without throwing exceptions, this is fine.
        (new Status("Failure", "NotFound", 'This object is not found'));
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testValidStatuses()
    {
        foreach (['Success', 'Failure'] as $status) {
            (new Status($status, "NotFound", "Fake News"));
        }
    }

    /**
     * Verify that the constructor rejects invalid statuses
     */
    public function testInvalidStatus()
    {
        $this->expectException(InvalidEntityException::class);

        (new Status("Nonsense", "NotFound", 'This object is not found'));
    }

    /**
     * Verifies the status object supports the reasons required by the application.
     *
     * @doesNotPerformAssertions
     */
    public function testValidReasons()
    {
        $validReasons = [
            "Created",
            "NotFound",
            "MethodNotAllowed",
            "BadRequest",
            "Forbidden",
            "InternalServerError",
            "ServiceUnavailable",
            "NotImplemented"
        ];

        foreach ($validReasons as $status) {
            (new Status("Failure", $status, "Fake News"));
        }
    }

    /**
     * Verify the constructor rejects invalid reasons
     */
    public function testInvalidReason()
    {
        $this->expectException(InvalidEntityException::class);

        (new Status("Failure", "Nonsense", 'This object is nonsense'));
    }

    /**
     * Make sure the class can be serialised to JSON correctly
     */
    public function testJsonSerialization()
    {
        $comparison = json_encode(
            [
                '@context' => 'http://api.store.littleman.co/',
                '@type' => 'Status',
                'status' => 'Failure',
                'reason' => 'NotFound',
                'message' => 'The item you are searching for was not found'
            ]
        );

        $object = new Status(
            'Failure',
            'NotFound',
            'The item you are searching for was not found'
        );

        $this->assertSame(
            $comparison,
            json_encode($object),
            "The JSON serilaized Status object was not as expected"
        );
    }
}
