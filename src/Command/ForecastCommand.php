<?php

declare(strict_types=1);

namespace App\Command;

use App\City\Provider\CityProviderInterface;
use App\Forecast\Provider\ForecastProviderInterface;
use App\Forecast\Provider\RangeInDays;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ForecastCommand extends Command
{
    public const NAME = 'cities:forecast';

    private CityProviderInterface $cities;
    private ForecastProviderInterface $forecasts;

    public function __construct(CityProviderInterface $cities, ForecastProviderInterface $forecasts)
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
        } catch (Throwable $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }

        foreach ($cities as $city) {
            try {
                $forecast = $this->forecasts->getForecast($city, new RangeInDays(2));
            } catch (Throwable $exception) {
                $output->writeln(
                    sprintf('Skipping city %s. %s', $city->name(), $exception->getMessage())
                );

                continue;
            }

            $output->writeln(
                sprintf('Processed city %s | %s', $city, $forecast)
            );
        }

        return Command::SUCCESS;
    }
}
