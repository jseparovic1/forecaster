<?php

declare(strict_types=1);

namespace App;

use App\City\CityProvider;
use App\City\FailedToGetCities;
use App\Forecast\Days;
use App\Forecast\FailedToGetForecast;
use App\Forecast\ForecastProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ForecastCommand extends Command
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

    protected function configure(): void
    {
        $this->setDescription('Gets forecasts for of cities returned by musment api.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $cities = $this->cities->getAll();
        } catch (FailedToGetCities $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }

        foreach ($cities as $city) {
            try {
                $forecasts = $this->forecasts->getForecasts($city, new Days(2));
            } catch (FailedToGetForecast $exception) {
                $output->writeln(
                    sprintf('Skipping city %s. %s', $exception->getCity(), $exception->getMessage())
                );

                continue;
            }

            $output->writeln(
                sprintf('Processed city %s | %s', $city, implode(', ', $forecasts))
            );
        }

        return Command::SUCCESS;
    }
}
