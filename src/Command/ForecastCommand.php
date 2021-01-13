<?php

declare(strict_types=1);

namespace App\Command;

use App\City\Provider\CityProvider;
use App\Forecast\Provider\ForecastProvider;
use App\Forecast\Provider\RangeInDays;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;

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
        } catch (Throwable $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }

        $stopwatch = (new Stopwatch());
        $stopwatch->start('forecast_fetch');

        $forecasts = $this->forecasts->getForecasts($cities, new RangeInDays(2));

        foreach ($forecasts as $city => $forecast) {
            $output->writeln(
                sprintf('Processed city %s | %s', $city, $forecast)
            );
        }

        $stopped = $stopwatch->stop('forecast_fetch');
        $output->writeln($stopped->__toString());

        return Command::SUCCESS;
    }
}
