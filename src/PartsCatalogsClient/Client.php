<?php declare(strict_types=1);

namespace PartsCatalogsClient;

use Closure;
use Exception;
use GuzzleHttp\ClientInterface;
use PartsCatalogsClient\Models\Car;
use PartsCatalogsClient\Models\CarCollection;
use PartsCatalogsClient\Models\CarInfo;
use PartsCatalogsClient\Models\CarParameterInfo;
use PartsCatalogsClient\Models\CarsParametersCollection;
use PartsCatalogsClient\Models\Catalog;
use PartsCatalogsClient\Models\CatalogCollection;
use PartsCatalogsClient\Models\Group;
use PartsCatalogsClient\Models\GroupCollection;
use PartsCatalogsClient\Models\Model;
use PartsCatalogsClient\Models\ModelCollection;
use PartsCatalogsClient\Models\SchemeAndParts;
use Psr\Http\Message\ResponseInterface;

class Client
{
    const BASE_URI = 'https://api.parts-catalogs.com/';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var array
     */
    private $options;

    /**
     * Client constructor.
     *
     * @param string          $apiKey
     * @param array           $options
     * @param ClientInterface $httpClient
     */
    public function __construct(string $apiKey, array $options, ClientInterface $httpClient)
    {
        if (!isset($options['base_uri'])) {
            $options['base_uri'] = self::BASE_URI;
        }

        if (!isset($options['headers']['Authorization'])) {
            $options['headers']['Authorization'] = $apiKey;
        }
        $this->options = $options;

        $this->client = $httpClient;
    }

    /**
     * @return CatalogCollection|Catalog[]
     * @throws ClientException
     */
    public function getCatalogs(): CatalogCollection
    {
        $path = 'v1/catalogs/';

        $arrayToModel = function ($array) {
            return new CatalogCollection($array);
        };

        return $this->httpRequest($path, [], $arrayToModel);
    }

    /**
     * @param $catalogId
     *
     * @return Catalog|null
     * @throws ClientException
     */
    public function getCatalog($catalogId): ?Catalog
    {
        $catalogs = $this->getCatalogs();
        foreach ($catalogs as $catalog) {
            if ($catalog->id == $catalogId) {
                return $catalog;
            }
        }
        return null;
    }

    /**
     * @param string $catalogId
     *
     * @return ModelCollection|Model[]
     * @throws ClientException
     */
    public function getModels(string $catalogId): ModelCollection
    {
        $path = 'v1/catalogs/' . $catalogId . '/models';
        return $this->httpRequest($path, [], function ($array) {
            return new ModelCollection($array);
        });
    }

    /**
     * @param string $catalogId
     * @param string $modelId
     * @param array  $parameters
     *
     * @return CarCollection|Car[]
     * @throws ClientException
     */
    public function getCars(string $catalogId, string $modelId, array $parameters = []): CarCollection
    {
        $parametersStr = implode(",", $parameters);
        $path          = 'v1/catalogs/' . $catalogId . '/cars2';
        $query         = ['modelId' => $modelId, "parameter" => $parametersStr];

        $arrayToModel = function ($array, ResponseInterface $response) {
            $total = $response->getHeader('X-Total-Count');
            return new CarCollection($array, (int)$total[0]);
        };

        return $this->httpRequest($path, $query, $arrayToModel);
    }

    /**
     * @param string      $catalogId
     * @param string      $carId
     * @param string|null $groupId
     *
     * @return Group[]
     * @throws ClientException
     */
    public function getGroups(string $catalogId, string $carId, string $groupId = null): array
    {
        $groupPath = new GroupPath($groupId);
        return $this->getGroupsByPath($catalogId, $carId, $groupPath);
    }

    /**
     * @param string    $catalogId
     * @param string    $carId
     * @param GroupPath $groupPath
     *
     * @return GroupCollection|Group[]
     * @throws ClientException
     */
    public function getGroupsByPath(string $catalogId, string $carId, GroupPath $groupPath): GroupCollection
    {
        $groupId = $groupPath->getCurrentGroupId();
        $path    = 'v1/catalogs/' . $catalogId . '/groups2';
        $query   = ['carId' => $carId, 'groupId' => $groupId];

        $arrayToModel = function ($array) use ($groupPath) {
            return new GroupCollection($array, clone $groupPath);
        };

        return $this->httpRequest($path, $query, $arrayToModel);

    }

    /**
     * @param string $catalogId
     * @param string $carId
     * @param string $groupId
     * @param string $criteria
     *
     * @return SchemeAndParts
     * @throws ClientException
     */
    public function getSchemeAndParts(string $catalogId, string $carId, string $groupId, string $criteria = ''): SchemeAndParts
    {
        $query        = [
            'carId' => $carId,
            'groupId' => $groupId,

        ];
        if (!empty($criteria)) {
            $query['criteria'] = $criteria;
        }

        $path         = '/v1/catalogs/' . $catalogId . '/parts2';
        $arrayToModel = function ($array) {
            return SchemeAndParts::fromArray($array);
        };

        return $this->httpRequest($path, $query, $arrayToModel);
    }

    /**
     * @param $catalogId
     * @param $modelId
     *
     * @return null|Model
     * @throws ClientException
     */
    public function getModel($catalogId, $modelId): ?Model
    {
        $models = $this->getModels($catalogId);
        foreach ($models as $model) {
            if ($model->id == $modelId) {
                return $model;
            }
        }
        return null;
    }

    /**
     * @param       $catalogId
     * @param       $modelId
     *
     * @param array $parameterList
     *
     * @return CarParameterInfo[]|CarsParametersCollection
     * @throws ClientException
     */
    public function getCarsParameters($catalogId, $modelId, $parameterList = []): CarsParametersCollection
    {
        $path  = '/v1/catalogs/' . $catalogId . '/cars-parameters';
        $query = [
            'catalogId' => $catalogId,
            'modelId' => $modelId,
            'parameter' => implode(",", $parameterList),
        ];

        $arrayToModel = function ($array, ResponseInterface $response) {
            $carsCount = $response->getHeader('X-Cars-Count')[0];
            return new CarsParametersCollection($array, (int)$carsCount);
        };

        return $this->httpRequest($path, $query, $arrayToModel);
    }

    /**
     * @param string $catalogId
     * @param string $carId
     * @param string $criteria
     *
     * @return Car|null
     * @throws ClientException
     */
    public function getCar(string $catalogId, string $carId, string $criteria = ''): ?Car
    {
        $path  = '/v1/catalogs/' . $catalogId . '/cars2/' . $carId;
        $query = ['criteria' => $criteria];

        $arrayToModel = function ($array) {
            return Car::fromArray($array);
        };
        return $this->httpRequest($path, $query, $arrayToModel);
    }

    /**
     * @param string    $catalogId
     * @param string    $carId
     * @param GroupPath $groupPath
     *
     * @return Group|null
     * @throws ClientException
     */
    public function getGroupByPath(string $catalogId, string $carId, GroupPath $groupPath): ?Group
    {
        $path  = 'v1/catalogs/' . $catalogId . '/groups2';
        $query = ['carId' => $carId, 'groupId' => $groupPath->getParentGroupId()];

        $arrayToModel = function ($array) use ($groupPath) {
            foreach ($array as $item) {
                if ($item['id'] == $groupPath->getCurrentGroupId()) {
                    $group = Group::fromArray($item);
                    $group->setGroupPath($groupPath);
                    return $group;
                }
            }
            return null;
        };
        return $this->httpRequest($path, $query, $arrayToModel);
    }

    private function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param string  $path
     * @param array   $params
     *
     * @param Closure $closure
     *
     * @return mixed
     * @throws ClientException
     */
    private function httpRequest(string $path, array $params, Closure $closure)
    {
        $options          = $this->getOptions();
        $options['query'] = $params;

        try {
            $response = $this->getClient()->get($path, $options);
            $body     = $response->getBody();
            $array    = \GuzzleHttp\json_decode($body, true);
        } catch (Exception $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }

        return $closure($array, $response);
    }

    private function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param string $q
     *
     * @return CarInfo[]
     * @throws ClientException
     */
    public function carInfo(string $q): iterable
    {
        $path  = 'v1/car/info/';
        $query = ['q' => $q];

        $arrayToModel = function ($array) {
            $list = [];
            foreach ($array as $item) {
                $list[] = CarInfo::fromArray($item);
            }
            return $list;
        };
        return $this->httpRequest($path, $query, $arrayToModel);
    }

    /**
     * @param string    $catalogId
     * @param string    $carId
     * @param GroupPath $groupPath
     *
     * @return SchemeAndParts
     * @throws ClientException
     */
    public function getSchemeAndPartsByGroupPath(string $catalogId, string $carId, GroupPath $groupPath, string $criteria = ''): SchemeAndParts
    {
        return $this->getSchemeAndParts($catalogId, $carId, $groupPath->getCurrentGroupId(), $criteria);
    }
}
