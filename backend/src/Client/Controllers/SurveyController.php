<?php

namespace IWD\JOBINTERVIEW\Client\Controllers;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

class SurveyController implements ControllerProviderInterface
{
    /**
     * @inheritDoc
     */
    public function connect(Application $app): ControllerCollection
    {
        $controller = $app["controllers_factory"];

        $controller->get('/', [$this, 'list'])->method('GET');
        $controller->get('/{code}', [$this, 'aggregateResults'])->method('GET');
        return $controller;
    }

    /**
     * List all surveys
     * @param Application $app
     * @return JsonResponse
     */
    public function list(Application $app): JsonResponse
    {
        $surveyManager = $app['survey_manager'];
        return new JsonResponse($surveyManager->getAllSurveys());
    }

    /*
     * View aggregated results for given survey
     * @param Application $app
     * @param string $code
     * @return JsonResponse
     */
    public function aggregateResults(Application $app, string $code): JsonResponse
    {
        $surveyManager = $app['survey_manager'];
        $results = $surveyManager->getSubmissions([$code]);
        if(count($results) === 0){
            $app->abort(404, "Unknown survey '$code'");
        }
        $aggregatedResults = $surveyManager->aggregate($results);
        return new JsonResponse($aggregatedResults);
    }
}
