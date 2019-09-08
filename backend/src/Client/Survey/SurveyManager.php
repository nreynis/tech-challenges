<?php

namespace IWD\JOBINTERVIEW\Client\Survey;

use Cocur\Chain\Chain;
use IWD\JOBINTERVIEW\Client\Exceptions\UnknownResponseType;
use IWD\JOBINTERVIEW\Client\Utils\ArrayUtils;
use IWD\JOBINTERVIEW\Client\Utils\MathUtils;

final class SurveyManager
{
    private const DATE_FORMAT = 'Y-m-d\TH:i:s.v\Z';

    /**
     * @var SubmissionsDataSource
     */
    private $dataSource;

    public function __construct(SubmissionsDataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * Get all survey results
     * @return array
     */
    public function getAllSubmissions(): array
    {
        $dataset = $this->dataSource->data();
        return is_array($dataset)
            ? $dataset
            : iterator_to_array($dataset);
    }

    /**
     * Returns an array of surveys, with name and code
     * [
     *   {"name": string, "code": string},
     *   ...
     * ]
     * @return array
     */
    public function getAllSurveys(): array
    {
        return Chain::create($this->getAllSubmissions())
            ->map(function($submission): array
            {
                return $submission['survey'];
            })
            ->unique(SORT_REGULAR)
            ->array;
    }

    /**
     * Get survey results filtered by code
     * @param array $surveyCodes
     * @return array
     */
    public function getSubmissions(array $surveyCodes): array
    {
        // We have to assign to a variable here.
        // We can't chain filter()->values()->array due to a bug in cocur/chain:
        // https://github.com/cocur/chain/issues/36
        $surveys = Chain::create($this->getAllSubmissions())
            ->filter(function($submission) use ($surveyCodes): bool
            {
                return in_array($submission['survey']['code'], $surveyCodes);
            })
            ->array;
        return array_values($surveys);
    }

    /**
     * Aggregate responses for each unique question (depending on it's type) in given submissions
     * @param array $submissions
     * @return array
     */
    public function aggregate(array $submissions): array
    {
        // flatten all responses to a non nested array
        $responses = ArrayUtils::flatmap($submissions, function(array $submission): array
        {
            return $submission['questions'];
        });
        // then we group responses by unique key
        $groups = [];
        foreach ($responses as $response) {
            $key = $response['label'].'___'.$response['type'];
            if(!array_key_exists($key, $groups)){
                $groups[$key] = [];
            }
            $groups[$key][] = $response;
        }
        // for each group of responses we aggregate results depending on it's type
        return Chain::create(array_values($groups))
            ->map(function($group): array
            {
                $groupType = $group[0]['type'];
                switch($groupType){
                    case 'qcm':
                        return $this->aggregateQCM($group);
                    case 'numeric':
                        return $this->aggregateNumeric($group);
                    case 'date':
                        return $this->aggregateDate($group);
                    default:
                        throw new UnknownResponseType($groupType);
                }
            })
            ->array;
    }

    /**
     * Aggregate responses of type QCM
     * @param array $responses
     * @return array
     */
    private function aggregateQCM(array $responses): array
    {
        // count the number of true per option
        $volume = [];
        foreach ($responses as $response){
            for($i = 0, $n = count($response['options']); $i<$n; $i++){
                if(!array_key_exists($response['options'][$i], $volume)){
                    $volume[$response['options'][$i]] = 0;
                }
                if($response['answer'][$i]){
                    $volume[$response['options'][$i]] += 1;
                }
            }
        }
        $count = count($responses);
        return [
            'type' => $responses[0]['type'],
            'label' => $responses[0]['label'],
            'numberOfResponses' => $count,
            'volumePerOption' => $volume
        ];
    }

    /**
     * Aggregate responses of type numeric
     * @param array $responses
     * @return array
     */
    private function aggregateNumeric(array $responses): array
    {
        // pluck and sort values
        $values = Chain::create($responses)
            ->map(function(array $response): float
            {
                return $response['answer'];
            })
            ->sort()
            ->array;
        $count = count($responses);
        return [
            'type' => $responses[0]['type'],
            'label' => $responses[0]['label'],
            'numberOfResponses' => $count,
            'minimum' => intval($values[0]),
            'average' => array_sum($values) / $count,
            'maximum' => intval($values[$count - 1]),
            'median' => MathUtils::median($values)
        ];
    }

    /**
     * Aggregate responses of type date
     * @param array $responses
     * @return array
     */
    private function aggregateDate(array $responses): array
    {
        // pluck, parse and sort dates
        $values = Chain::create($responses)
            ->map(function(array $response): \DateTime
            {
                return \DateTime::createFromFormat(self::DATE_FORMAT, $response['answer']);
            })
            ->sort()
            ->array;
        // quantize dates by week
        $quantized = Chain::create($values)
            ->map(function(\DateTime $date): string
            {
                return $date->format('Y \w\e\e\k W');
            })
            ->reduce(function(array $accumulator, string $date): array
            {
                if(!array_key_exists($date, $accumulator)){
                    $accumulator[$date] = 0;
                }
                $accumulator[$date] += 1;
                return $accumulator;
            }, []);
        return [
            'type' => $responses[0]['type'],
            'label' => $responses[0]['label'],
            'dates' => array_map(function(\DateTime $d): string
            {
                return $d->format(self::DATE_FORMAT);
            }, $values),
            'groupedByWeek' => $quantized
        ];
    }
}
