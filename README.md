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

![](https://raw.githubusercontent.com/sunnz/eacars/master/eacars.png)

## System requirements

* PHP 7 - developed on PHP 7.2.7
* Composer - handles all the PHP library dependencies
  * Guzzle PHP library is used for HTTP API request, so the PHP installation and configuration must satisify all the requirements
    of Guzzle, see: http://docs.guzzlephp.org/en/stable/overview.html#requirements

## Implementation

The majority of the logic can be found in [``src/Controller/CarMakeController.php``](https://github.com/sunnz/eacars/blob/master/src/Controller/CarMakeController.php).

``homepage()`` and ``test()`` handles routes ``/`` and ``/test``. Then a JSON object is requested, either via the API with Guzzle or from a locally hardcoded value, then it is passed to ``getRenderMakeCarShowsMap()``, which organises the data in the format and order accordingly. 

Finally the result is passed to [``templates/show.html.twig``](https://github.com/sunnz/eacars/blob/master/templates/show.html.twig) for rendering. Bootstrap is used to display the data neatly in a responsive grid.
