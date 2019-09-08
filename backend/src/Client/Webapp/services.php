<?php

use IWD\JOBINTERVIEW\Client\Survey\JsonFileAdapter;
use IWD\JOBINTERVIEW\Client\Survey\SurveyManager;
use Silex\Application;

return [
    'survey_data_source' => function (Application $app) {
        return new JsonFileAdapter($app['data_path']);
    },
    'survey_manager' => function (Application $app) {
        return new SurveyManager($app['survey_data_source']);
    },
];
