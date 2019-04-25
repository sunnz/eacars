<?php
/**
 * Created by PhpStorm.
 * User: Sunny
 * Date: 25/4/19
 */

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CarMakeController extends AbstractController
{
    // Guzzle client.
    private $client;
    // Sorting behavior for sort() and ksort(). See https://www.php.net/manual/en/function.sort.php
    private $sort = SORT_NATURAL;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://eacodingtest.digital.energyaustralia.com.au/api/v1/',
            'headers' => ['Accept' => 'application/json'],
        ]);
    }

    /**
     * @Route("/")
     */
    public function homepage()
    {
        try
        {
            $render_map = $this->getRenderMakeCarShowsMap($this->requestCarShows());

            return $this->render('show.html.twig', [
                'map' => $render_map,
            ]);
        }
        catch (\Exception $e)
        {
            return $this->render('error.html.twig', [
                'title' => 'Error: API failed',
                'message' => 'API returned empty response or "Failed Downstream service".',
            ]);
        }
    }

    /**
     * @Route("/test")
     */
    public function test()
    {
        $render_map = $this->getRenderMakeCarShowsMap($this->requestCarShowsTesting());
        dump($render_map);

        return $this->render('show.html.twig', [
            'map' => $render_map,
        ]);
    }

    /**
     * @param $shows array
     * json object from the API.
     * @return array
     * an array of make, car, and shows sorted alphabetically for rendering.
     */
    private function getRenderMakeCarShowsMap($shows)
    {
        // Map of make to an unordered list of car models.
        // For example $make_car_map['Hondaka']['models'] would be set to a list of all models from Hondaka.
        $make_car_map = [];
        // Map of car to an unordered list of shows that it presented in.
        // For example $car_show_map['Hondaka']['Elisa']['shows'] would be set to a list of all shows that had Elisa.
        $car_show_map = [];

        foreach ($shows as $show)
        {
            foreach ($show['cars'] as $car)
            {
                $model = $car['model'];
                $make = $car['make'];
                $make_car_map[$make]['models'][$model] = $model;
                $car_show_map[$make][$model]['shows'][] = $show['name'];
            }
        }

        // Build multidimensional sorted array of make, model, shows for rendering.
        $render_map = [];
        // First, sort make names and add them into map.
        ksort($make_car_map, $this->sort);
        foreach ($make_car_map as $make_name => $make)
        {
            $render_map[$make_name] = [];

            // Sort models and add them to the map under make.
            ksort($make['models'], $this->sort);
            $models = $make['models'];
            foreach ($models as $model_name)
            {
                // Sort shows and add them to the map under the model.
                sort($car_show_map[$make_name][$model_name]['shows'], $this->sort);
                $render_map[$make_name][$model_name] = $car_show_map[$make_name][$model_name]['shows'];
            }
        }

        return $render_map;
    }

    /**
     * @return array
     * an array of car shows, make, and model from the api.
     */
    private function requestCarShows()
    {
        $response = $this->client->get('cars');
        $car_shows = json_decode($response->getBody(), true);
        return $car_shows;
    }

    /**
     * @return array
     * an array of car shows, make, and model, defined statically.
     */
    private function requestCarShowsTesting()
    {
        $response = '[
          {
            "name": "Melbourne Motor Show",
            "cars": [
              {
                "make": "Julio Mechannica",
                "model": "Mark 4S"
              },
              {
                "make": "Hondaka",
                "model": "Elisa"
              },
              {
                "make": "Moto Tourismo",
                "model": "Cyclissimo"
              },
              {
                "make": "George Motors",
                "model": "George 15"
              },
              {
                "make": "Moto Tourismo",
                "model": "Delta 4"
              }
            ]
          },
          {
            "name": "Cartopia",
            "cars": [
              {
                "make": "Moto Tourismo",
                "model": "Cyclissimo"
              },
              {
                "make": "George Motors",
                "model": "George 15"
              },
              {
                "make": "Hondaka",
                "model": "Ellen"
              },
              {
                "make": "Moto Tourismo",
                "model": "Delta 16"
              },
              {
                "make": "Moto Tourismo",
                "model": "Delta 4"
              },
              {
                "make": "Julio Mechannica",
                "model": "Mark 2"
              }
            ]
          }
        ]';
        $car_shows = json_decode($response, true);
        return $car_shows;
    }
}