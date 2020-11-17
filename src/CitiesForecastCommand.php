<?php

declare(strict_types=1);

namespace App;

use App\City\CityProvider;
use App\Forecast\ForecastProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CitiesForecastCommand extends Command
{
    public const NAME = 'cities:forecast';

    private CityProvider $cities;
    private ForecastProvider $forecasts;

    public function __construct(CityProvider $cities, ForecastProvider $forecasts)
    {
        parent::__construct(self::NAME);

        $this->cities = $cities;
        $this->forecasts = $forecasts;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setDescription('Gets forecasts for of cities returned by musment api.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cities = $this->cities->getAll();

        foreach ($cities as $city) {
            $output->writeln($city->name());
        }

        return Command::SUCCESS;
    }
}
