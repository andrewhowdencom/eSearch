<?php

declare(strict_types = 1);

namespace App\Entities;

use App\Exceptions\InvalidEntityException;

/**
 * Responsible for expressing the state of a request when there is no other information about that request.
 *
 * @package App\Entities
 */
class Status implements SchemaDefinitionInterface, \JsonSerializable
{
    const CONTEXT = 'http://api.store.littleman.co/';
    const TYPE    = 'Status';

    const STATUS_SUCCESS = 'Success';
    const STATUS_FAILURE = 'Failure';

    const REASON_CREATED               = 'Created';
    const REASON_NOT_FOUND             = 'NotFound';
    const REASON_BAD_REQUEST           = 'BadRequest';
    const REASON_METHOD_NOT_ALLOWED    = 'MethodNotAllowed';
    const REASON_SERVICE_UNAVAILABLE   = 'ServiceUnavailable';
    const REASON_FORBIDDEN             = 'Forbidden';
    const REASON_INTERNAL_SERVER_ERROR = 'InternalServerError';
    const REASON_NOT_IMPLEMENTED       = 'NotImplemented';

    /**
     * The success of this status object
     *
     * @var string
     */
    private $status;

    /**
     * The reason of this status object
     *
     * @var string
     */
    private $reason;

    /**
     * The message associated with this failure
     *
     * @var string
     */
    private $message;

    public function __construct(
        string $status,
        string $reason,
        string $message
    ) {
        // Set the required properties
        $this->setStatus($status)
            ->setReason($reason)
            ->setMessage($message);
    }

    /**
     * Sets the status of this entity
     *
     * @param string $status
     * @throws InvalidEntityException if the status described is not understood.
     *
     * @return Status
     */
    private function setStatus(string $status)
    {
        if (!in_array($status, [self::STATUS_FAILURE, self::STATUS_SUCCESS])) {
            throw new InvalidEntityException("Invalid status: $status ");
        }

        $this->status = $status;

        return $this;
    }

    /**
     * Sets the reason of this entity
     *
     * @param string $reason
     * @throws InvalidEntityException if the reason described is not valid
     *
     * @return Status
     */
    private function setReason(string $reason)
    {
        $isValid = in_array(
            $reason,
            [
                self::REASON_BAD_REQUEST,
                self::REASON_CREATED,
                self::REASON_FORBIDDEN,
                self::REASON_METHOD_NOT_ALLOWED,
                self::REASON_INTERNAL_SERVER_ERROR,
                self::REASON_NOT_FOUND,
                self::REASON_SERVICE_UNAVAILABLE,
                self::REASON_NOT_IMPLEMENTED
            ]
        );

        if (!$isValid) {
            throw new InvalidEntityException("Invalid reason: $reason");
        }

        $this->reason = $reason;

        return $this;
    }


    /**
     * Sets the message. Inherent validation by way of strict type hints and string hit in method signature, but
     * no specific validation.
     *
     * @param string $message
     * @return Status
     */
    private function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    public function getContext(): string
    {
        return self::CONTEXT;
    }

    public function getType(): string
    {
         return self::TYPE;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Expresses how this object should be serialised to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            '@context' => $this->getContext(),
            '@type'    => $this->getType(),
            'status'   => $this->getStatus(),
            'reason'   => $this->getReason(),
            'message'  => $this->getMessage()
        ];
    }
}
