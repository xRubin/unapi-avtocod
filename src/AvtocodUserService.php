<?php

namespace unapi\avtocod;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use unapi\interfaces\ServiceInterface;

use function GuzzleHttp\json_decode;

/**
 * Операции уровня пользователя
 */
class AvtocodUserService implements ServiceInterface, LoggerAwareInterface
{
    use AvtocodServiceTrait;

    /**
     * Информация о текущем пользователе
     * @param bool $_detailed
     * @return PromiseInterface
     */
    public function getReport(bool $_detailed = false): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/dev/user', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'query' => array_filter([
                '_detailed' => $_detailed ? 'true' : 'false',
            ])
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Проверка доступности квоты по типу отчета
     * @param string $report_type_desc uid типа отчета
     * @param bool $_detailed
     * @return PromiseInterface
     */
    public function balanceReport(string $report_type_desc, bool $_detailed = false): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/user/balance/' . $report_type_desc, [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'query' => array_filter([
                '_detailed' => $_detailed ? 'true' : 'false',
            ])
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Получение типов отчетов доступных конкретному пользователю
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
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/user/report_types', [
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
     * Получение списка отчетов по запросу
     * @param bool $_content Признак наличия контента
     * @param string $_query Описатель запроса (язык зависит от контекста)
     * @param int $_size Максимальное количество данных в выдаче, размер страницы (не более 200)
     * @param int $_offset Смещение окна записей относительно всей выборки (для скролирующего обхода)
     * @param int $_page Номер страницы, позволяет вычислить _offset, через _size * (_page - 1), считается от 1
     * @param string $_sort Настройки сортировки, в общем случае "name,-date" - список полей упорядочения, где "-" означает "по убыванию"
     * @param bool $_calc_total Вычислять ли общее количество
     * @param bool $_detailed
     * @return PromiseInterface
     */
    public function reports(bool $_content = false, string $_query = '_all', int $_size = 20, int $_offset = 0, int $_page = 1, string $_sort = null, bool $_calc_total = false, bool $_detailed = false): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/user/reports', [
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
                '_detailed' => $_detailed ? 'true' : 'false',
            ])
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Получение имеющихся отчетов
     * @param string $report_desc
     * @param bool $_content Признак наличия контента
     * @param bool $_detailed
     * @return PromiseInterface
     */
    public function getReports(string $report_desc, bool $_content = false, bool $_detailed = false): PromiseInterface
    {
        return $this->getClient()->requestAsync('GET', '/b2b/api/v1/user/reports/' . $report_desc, [
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
     * Генерация нового отчета
     * @param string $report_type_uid
     * @param array $makeReportRequest
     * @return PromiseInterface
     */
    public function makeReport(string $report_type_uid, array $makeReportRequest): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/user/reports/' . $report_type_uid .'/_make', [
            'headers' => [
                'Authorization' => $this->getAuthorization(),
                'Content-Type' =>  'application/json',
            ],
            'json' => $makeReportRequest
        ])->then(function (ResponseInterface $response) {
            $answer = $response->getBody()->getContents();
            $this->getLogger()->info($answer);
            return json_decode($answer, true);
        });
    }

    /**
     * Запрос на обновление данных в отчете
     * @param string $report_uid
     * @return PromiseInterface
     */
    public function refreshReport(string $report_uid): PromiseInterface
    {
        return $this->getClient()->requestAsync('POST', '/b2b/api/v1/user/reports/' . $report_uid .'/_refresh', [
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