# Forecaster

Forecaster gets daily forecast via Weather API for each city returned by Musement API.

## Usage

Build container

```bash
    docker build -t jseparovic/forecaster .
```

Run it

```bash
    docker run --rm -it jseparovic/forecaster composer cities:forecasts
```

## Quality assurance 
Use following command to check run tests and check static analysis errors.

```bash
    docker run --rm jseparovic/forecaster composer check
```
