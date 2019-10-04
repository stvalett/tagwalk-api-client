<?php
/**
 * PHP version 7.
 *
 * LICENSE: This source file is subject to copyright
 *
 * @author      Steve Valette <steve@tag-walk.com>
 * @copyright   2019 TAGWALK
 * @license     proprietary
 */

namespace Tagwalk\ApiClientBundle\Manager;

use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Tagwalk\ApiClientBundle\Exception\ApiAccessDeniedException;
use Tagwalk\ApiClientBundle\Model\ShowroomUser;
use Tagwalk\ApiClientBundle\Provider\ApiProvider;

class ShowroomUserManager
{
    /**
     * @var ApiProvider
     */
    private $apiProvider;

    /**
     * @var SerializerInterface|Serializer
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ApiProvider         $apiProvider
     * @param SerializerInterface $serializer
     */
    public function __construct(ApiProvider $apiProvider, SerializerInterface $serializer)
    {
        $this->apiProvider = $apiProvider;
        $this->serializer = $serializer;
        $this->logger = new NullLogger();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return ShowroomUser
     */
    private function deserialize($response): ShowroomUser
    {
        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            ShowroomUser::class,
            JsonEncoder::FORMAT
        );
    }

    /**
     * @param string $email
     *
     * @return ShowroomUser|null
     */
    public function get(string $email): ?ShowroomUser
    {
        $showroomUser = null;
        $apiResponse = $this->apiProvider->request('GET', '/api/showroom/users/'.$email, [RequestOptions::HTTP_ERRORS => false]);
        if ($apiResponse->getStatusCode() === Response::HTTP_OK) {
            $showroomUser = $this->deserialize($apiResponse);
        } elseif ($apiResponse->getStatusCode() !== Response::HTTP_NOT_FOUND) {
            $this->logger->error('ShowroomUserManager::get unexpected status code', [
                'code'    => $apiResponse->getStatusCode(),
                'message' => $apiResponse->getBody()->getContents(),
            ]);
        }

        return $showroomUser;
    }

    /**
     * @param ShowroomUser $user
     *
     * @return ShowroomUser|null
     */
    public function create(ShowroomUser $user): ?ShowroomUser
    {
        $data = $this->serializer->normalize($user);
        $data = array_filter($data, static function ($v) {
            return $v !== null;
        });
        $apiResponse = $this->apiProvider->request('POST', '/api/showroom/users/register', [
                RequestOptions::HTTP_ERRORS => false,
                RequestOptions::JSON        => $data,
            ]);
        $created = null;
        switch ($apiResponse->getStatusCode()) {
            case Response::HTTP_FORBIDDEN:
                throw new ApiAccessDeniedException();
            case Response::HTTP_CREATED:
                $created = $this->deserialize($apiResponse);
                break;
            case Response::HTTP_CONFLICT:
                $this->logger->notice('ShowroomUser already exists',
                    [
                        'code'    => $apiResponse->getStatusCode(),
                        'message' => $apiResponse->getBody()->getContents(),
                    ]);
                break;
            default:
                $this->logger->error('ShowroomUserManager::create unexpected status code',
                    [
                        'code'    => $apiResponse->getStatusCode(),
                        'message' => $apiResponse->getBody()->getContents(),
                    ]
                );
        }

        return $created;
    }

    /**
     * @param string $property
     * @param string $value
     *
     * @return ShowroomUser|null
     */
    public function findBy(string $property, string $value): ?ShowroomUser
    {
        $data = null;
        $apiResponse = $this->apiProvider->request('GET', '/api/showroom/users/find', [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::QUERY       => [
                'key'   => $property,
                'value' => $value,
            ],
        ]);
        if ($apiResponse->getStatusCode() === Response::HTTP_OK) {
            $data = $this->deserialize($apiResponse);
        } elseif ($apiResponse->getStatusCode() === Response::HTTP_NOT_FOUND) {
            $this->logger->error('ShowroomUserManager::findBy unexpected status code', [
                'code'      => $apiResponse->getStatusCode(),
                'message'   => $apiResponse->getBody()->getContents(),
            ]);
        }

        return $data;
    }

    /**
     * @param string $email
     * @param array  $data
     *
     * @return ShowroomUser|null
     */
    public function patch(string $email, $data): ?ShowroomUser
    {
        $apiResponse = $this->apiProvider->request('PATCH',
            '/api/showroom/users',
            [
                RequestOptions::QUERY       => ['email' => $email],
                RequestOptions::JSON        => $data,
                RequestOptions::HTTP_ERRORS => false,
            ]);
        $updated = null;
        if ($apiResponse->getStatusCode() === Response::HTTP_OK) {
            $updated = $this->deserialize($apiResponse);
        } else {
            $this->logger->error('ShowroomUserManager::update unexpected status code',
                [
                    'code'    => $apiResponse->getStatusCode(),
                    'message' => $apiResponse->getBody()->getContents(),
                ]);
        }

        return $updated;
    }
}
