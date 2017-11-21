<?php

namespace unapi\avtocod;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use unapi\interfaces\ServiceInterface;

use function GuzzleHttp\json_decode;

/**
 * Операции уровня домен
 */
class AvtocodDomainService implements ServiceInterface, LoggerAwareInterface
{
    use AvtocodServiceTrait;

    /**
     * Получение состояния балансов в целом по домену
     * @param string $date
     * @param bool $_detailed
     * @return PromiseInterface
     */
    public function balance(string $date, bool $_detailed = false): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/domain/balance', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'query' => array_filter([
                'date' => $date,
                '_detailed' => $_detailed ? 'true' : 'false',
            ])
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Получение списка операций
     * @param string $_query Описатель запроса (язык зависит от контекста)
     * @param int $_size Максимальное количество данных в выдаче, размер страницы (не более 200)
     * @param int $_offset Смещение окна записей относительно всей выборки (для скролирующего обхода)
     * @param int $_page Номер страницы, позволяет вычислить _offset, через _size * (_page - 1), считается от 1
     * @param string $_sort Настройки сортировки, в общем случае "name,-date" - список полей упорядочения, где "-" означает "по убыванию"
     * @param bool $_calc_total Вычислять ли общее количество
     * @param bool $_detailed
     * @return PromiseInterface
     */
    public function balanceHistory(string $_query = '_all', int $_size = 20, int $_offset = 0, int $_page = 1, string $_sort = null, bool $_calc_total = false, bool $_detailed = false): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/domain/balance', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'query' => array_filter([
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

    /**
     * Создание/обновление групп по конкретному домену
     * @param array $groupsDefinition
     * @return PromiseInterface
     */
    public function setGroups(array $groupsDefinition): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/domain/groups', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $groupsDefinition
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Создание групп по конкретному домену
     * @param array $groupsDefinition
     * @return PromiseInterface
     */
    public function addGroups(array $groupsDefinition): PromiseInterface
    {
        return $this->getClient()->requestAsync('PUT', '/b2b/api/v1/domain/groups', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $groupsDefinition
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Получение типов отчетов доступных конкретному домену
     * @param bool $_content Признак наличия контента
     * @param string $_query Описатель запроса (язык зависит от контекста)
     * @param int $_size Максимальное количество данных в выдаче, размер страницы (не более 200)
     * @param int $_offset Смещение окна записей относительно всей выборки (для скролирующего обхода)
     * @param int $_page Номер страницы, позволяет вычислить _offset, через _size * (_page - 1), считается от 1
     * @param string $_sort Настройки сортировки, в общем случае "name,-date" - список полей упорядочения, где "-" означает "по убыванию"
     * @param bool $_calc_total Вычислять ли общее количество
     * @param bool $_can_generate Может ли пользователь генерировать отчеты для данного типа
     * @return PromiseInterface
     */
    public function reportTypes(bool $_content = false, string $_query = '_all', int $_size = 20, int $_offset = 0, int $_page = 1, string $_sort = null, bool $_calc_total = false, bool $_can_generate = false): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/domain/report_types', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'query' => array_filter([
                '_content' => $_content ? 'true' : 'false',
                '_query' => $_query,
                '_size' => $_size,
                '_offset' => $_offset,
                '_page' => $_page,
                '_sort' => $_sort,
                '_calc_total' => $_calc_total ? 'true' : 'false',
                '_can_generate' => $_can_generate ? 'true' : 'false',
            ])
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
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/domain/search', [
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

    /**
     * Создание/обновление пользователей по конкретному домену
     * @param array $usersDefinition
     * @return PromiseInterface
     */
    public function setUsers(array $usersDefinition): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/domain/users', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $usersDefinition
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Создание пользователей по конкретному домену
     * @param array $usersDefinition
     * @return PromiseInterface
     */
    public function addUsers(array $usersDefinition): PromiseInterface
    {
        return $this->getClient()->requestAsync('PUT', '/b2b/api/v1/domain/users', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $usersDefinition
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }
}