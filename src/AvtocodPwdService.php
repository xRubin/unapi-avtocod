<?php

namespace unapi\avtocod;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use unapi\interfaces\ServiceInterface;

use function GuzzleHttp\json_decode;

/**
 * Операции сброса пароля
 */
class AvtocodPwdService implements ServiceInterface, LoggerAwareInterface
{
    use AvtocodServiceTrait;

    /**
     * Установка нового пароля
     * @param string $token Токен сброса пароля
     * @param string $pass Новый пароль (хэш)
     * @return PromiseInterface
     */
    public function resetApply(string $token, string $pass): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/pwd/reset/apply', [
            'headers' => [
                'Content-Type' =>  'application/json',
            ],
            'query' => [
                'token' => $token,
                'pass' => $pass,
            ]
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Проверка токена сброса пароля
     * @param string $token Токен сброса пароля
     * @param bool $_detailed
     * @return PromiseInterface
     */
    public function resetCheck(string $token, bool $_detailed = false): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/pwd/reset/apply', [
            'headers' => [
                'Content-Type' =>  'application/json',
            ],
            'query' => array_filter([
                'token' => $token,
                '_detailed' => $_detailed ? 'true' : 'false',
            ])
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }
}