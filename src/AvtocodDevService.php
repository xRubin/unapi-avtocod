<?php

namespace unapi\avtocod;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use unapi\interfaces\ServiceInterface;

use function GuzzleHttp\json_decode;

/**
 * Операции в помощь разработчику
 */
class AvtocodDevService implements ServiceInterface, LoggerAwareInterface
{
    use AvtocodServiceTrait;

    /**
     * Проверка соединения
     * @param string $value Значение, отсылаемое на сервер - использовать для проверки правильности кодировки
     * @param int $stamp Пользовательский STAMP для контроля времени отклика
     * @return PromiseInterface
     */
    public function ping(string $value = null, int $stamp = null): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/dev/ping', [
            'query' => array_filter([
                'value' => $value,
                'stamp' => $stamp,
            ])
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Отладка формирования токена
     * @param string $user Идентификатор пользователя
     * @param string $pass Пароль (или md5 пароля)
     * @param bool $ishash Признак использования MD5 пароля
     * @param string $date Дата начала действия токена с точностью до секунды yyyy-MM-dd'T'HH:mm:ssX
     * @param int $age Срок действия токена (в секундах)
     * @return PromiseInterface
     */
    public function token(string $user = null, string $pass = null, bool $ishash = false, string $date = null, int $age = 999999999): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/dev/token', [
            'query' => array_filter([
                'user' => $user,
                'pass' => $pass,
                'ishash' => $ishash ? 'true' : 'false',
                'date' => $date,
                'age' => $age,
            ])
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Запрос отчетов в режиме имитации
     * @param string $report_uid uid отчета
     * @param bool $_content Признак наличия контента
     * @param bool $_detailed
     * @return PromiseInterface
     */
    public function getReport(string $report_uid, bool $_content = false, bool $_detailed = false): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/dev/user/reports/' . $report_uid, [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'query' => array_filter([
                '_content' => $_content ? 'true' : 'false',
                '_detailed' => $_detailed ? 'true' : 'false',
            ])
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Генерация нового отчета в режиме имитации
     * @param array $request request
     * @param string $report_type_uid uid типа отчета
     * @return PromiseInterface
     */
    public function makeReport(array $request, string $report_type_uid): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/dev/user/reports/' . $report_type_uid . '/_make', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $request
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Перегенерация отчета в режиме имитации
     * @param string $report_uid uid отчета
     * @return PromiseInterface
     */
    public function refreshReport(string $report_uid): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/dev/user/reports/' . $report_uid . '/_refresh', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }
}