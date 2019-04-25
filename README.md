# eacars
Simple Symfony 4 app that consumes a test API and displays it

## Quick start

To clone the repository and run it locally:

```
git clone https://github.com/sunnz/eacars
cd eacars
composer install
./bin/console server:run
```

### Local test

Please goto ``http://127.0.0.1:8000/test`` in your web browser.

You should see that it dumps the JSON object from the API then renders the content according to the coding test.
The test route uses a statically hardcoded copy of the JSON object from the API instead of actually fetching the API.
This is done for development purpose and it would work without a network connection.

### API test

This is like ``/test``, but it doesn't dump the JSON object, and actually fetches the data from the API.

Please goto ``http://127.0.0.1:8000/`` in your web browser.

If the API endpoint is up and running, you would see a list of the models of cars and the show they attended,
grouped by make alphabetically.

In the event where the API fails or returns an empty result, or if you lost network connectivity, you would see an custom error page.
