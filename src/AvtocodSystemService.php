<?php

namespace unapi\avtocod;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use unapi\interfaces\ServiceInterface;

use function GuzzleHttp\json_decode;

/**
 * Операции уровня системы
 */
class AvtocodSystemService implements ServiceInterface, LoggerAwareInterface
{
    use AvtocodServiceTrait;

    /**
     * Изменение балансовых квот
     * @param array $quotesDefinition
     * @return PromiseInterface
     */
    public function balanceQuote(array $quotesDefinition): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/system/balance/quote', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $quotesDefinition
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Сброс кэша домена
     * @param string $domain_uid
     * @return PromiseInterface
     */
    public function cacheForce(string $domain_uid): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/system/cashe/force', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $domain_uid
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Создание/изменение доменов
     * @param array $domainsDefinition
     * @return PromiseInterface
     */
    public function setModel(array $domainsDefinition): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/system/model', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $domainsDefinition
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Создание доменов
     * @param array $domainsDefinition
     * @return PromiseInterface
     */
    public function addModel(array $domainsDefinition): PromiseInterface
    {
        return $this->getClient()->requestAsync('PUT', '/b2b/api/v1/system/model', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $domainsDefinition
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Запрос на сброс пароля
     * @param string $user_uid
     * @return PromiseInterface
     */
    public function resetPassword(string $user_uid): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/system/pwd/reset/init', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'query' => array_filter([
                'user_uid' => $user_uid
            ])
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Создание/обновление типов отчетов по конкретному домену
     * @param array $reportTypesDefinition
     * @return PromiseInterface
     */
    public function setReportType(array $reportTypesDefinition): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/system/report_types', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $reportTypesDefinition
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Создание типов отчетов по конкретному домену
     * @param array $reportTypesDefinition
     * @return PromiseInterface
     */
    public function addReportType(array $reportTypesDefinition): PromiseInterface
    {
        return $this->getClient()->requestAsync('PUT', '/b2b/api/v1/system/report_types', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $reportTypesDefinition
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Запрос на обновление данных в отчете
     * @param string $report_uid uid отчета
     * @param bool $noPay Не снимать квоту
     * @return PromiseInterface
     */
    public function refreshReport(string $report_uid, bool $noPay): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/system/reports/' . $report_uid .'/_refresh', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'query' => [
                'noPay' => $noPay ? 'true' : 'false',
            ]
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }
    /**
     * Поиск произвольных объектов
     * @param string $type_desc
     * @param bool $_pretty Признак наличия контента
     * @param string $_query Описатель запроса (язык зависит от контекста)
     * @param int $_size Максимальное количество данных в выдаче, размер страницы (не более 200)
     * @param int $_offset Смещение окна записей относительно всей выборки (для скролирующего обхода)
     * @param int $_page Номер страницы, позволяет вычислить _offset, через _size * (_page - 1), считается от 1
     * @param string $_sort Настройки сортировки, в общем случае "name,-date" - список полей упорядочения, где "-" означает "по убыванию"
     * @param bool $_calc_total Вычислять ли общее количество
     * @param bool $_detailed
     * @return PromiseInterface
     */
    public function search(string $type_desc, bool $_pretty = true, string $_query = '_all', int $_size = 20, int $_offset = 0, int $_page = 1, string $_sort = null, bool $_calc_total = false, bool $_detailed = false): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/system/search', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'query' => array_filter([
                'type_desc' => $type_desc,
                '_pretty' => $_pretty ? 'true' : 'false',
                '_query' => $_query,
                '_size' => $_size,
                '_offset' => $_offset,
                '_page' => $_page,
                '_sort' => $_sort,
                '_calc_total' => $_calc_total ? 'true' : 'false',
                '_detailed' => $_detailed ? 'true' : 'false',
            ])
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }
}