<?php declare(strict_types=1);

namespace PartsCatalogsSlim\View;

use PartsCatalogsSlim\Router;
use PartsCatalogsSlim\View\CarsView\CarView;

class LayoutView
{
    /**
     * @var CarView|null
     */
    private $clientCar;
    private $title = '';

    public function setTitle(string $titile): self
    {
        $this->title = $titile;
        return $this;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function setClientCar(CarView $car): self
    {
        $this->clientCar = $car;
        return $this;
    }

    public function getClientCar(): CarView
    {
        if ($this->clientCar === null) {
            return new CarView();
        }
        return $this->clientCar;
    }

    public function urlToCatalogs()
    {
        return Router::urlToCatalogs();
    }
}
