<?php
/**
 * PHP version 7
 *
 * LICENSE: This source file is subject to copyright
 *
 * @author      Florian Ajir <florian@tag-walk.com>
 * @copyright   2016-2018 TAGWALK
 * @license     proprietary
 */

namespace Tagwalk\ApiClientBundle\Security;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Tagwalk\ApiClientBundle\Model\User;
use Tagwalk\ApiClientBundle\Provider\ApiProvider;

class UserProvider implements UserProviderInterface
{
    /**
     * @var ApiProvider
     */
    private $provider;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param ApiProvider $provider
     * @param SerializerInterface $serializer
     */
    public function __construct(ApiProvider $provider, SerializerInterface $serializer)
    {
        $this->provider = $provider;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        try {
            $response = $this->provider->request('GET', '/api/users/' . $username, ['http_errors' => false]);
            if ($response->getStatusCode() === Response::HTTP_NOT_FOUND) {
                throw new UsernameNotFoundException();
            }
            $json = $response->getBody()->getContents();

            return $this->serializer->deserialize($json, User::class, 'json');
        } catch (RequestException $e) {
            throw new ServiceUnavailableHttpException('Unable to connect');
        }
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
