<?php
// src/Controller/MapboxController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class MapBoxController extends AbstractController
{
    /**
     * @Route("/get-places", name="get_places")
     */
    public function getPlacesAction(Request $request)
    {
        $query = $request->query->get('query');
        $accessToken = 'pk.eyJ1IjoieW9zcmNoYXJlazMxIiwiYSI6ImNsdDZkd2JzcTBjcHYybW12NzE4aGNseDYifQ.pv6x004655o_tl9uncoxag';

        $url = sprintf(
            'https://api.mapbox.com/geocoding/v5/mapbox.places/%s.json?access_token=%s',
            urlencode($query),
            $accessToken
        );

        $response = file_get_contents($url);
        $places = json_decode($response, true)['features'];

        return new JsonResponse($places);
    }
}
