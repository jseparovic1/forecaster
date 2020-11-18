# Forecaster

Forecaster gets daily forecast via Weather API for each city returned by Musement API.

## Usage

Get api key from https://www.weatherapi.com.

Build container

```bash
    docker build -t jseparovic/forecaster .
```

Run it

```bash
    docker run --rm -it -e WEATHER_API_KEY='API_KEY' jseparovic/forecaster composer cities:forecasts
```

## Add vendor dependencies to host in development
```bash
    docker run --rm --interactive --tty --volume $PWD:/app composer install
```

## Quality assurance 
Use following command to check run tests and check static analysis errors.

```bash
    docker run --rm jseparovic/forecaster composer check
```

Run test only
```bash
    docker run --rm jseparovic/forecaster composer test
```

Run phpstan check only
```bash
    docker run --rm jseparovic/forecaster composer stan-check
```

