<?php
return [
    [
        'survey' => [
            'name' => 'Survey A',
            'code' => 'SA'
        ],
        'questions' => [
            [
                'type' => 'qcm',
                'label' => 'Question 1?',
                'options' => ['Option 1', 'Option 2', 'Option 3'],
                'answer' => [true, true, false]
            ],
            [
                'type' => 'numeric',
                'label' => 'Question 2?',
                'options' => null,
                'answer' => 12
            ],
            [
                'type' => 'date',
                'label' => 'Question 3?',
                'options' => null,
                'answer' => '2019-09-07T15:21:45.000Z'
            ]
        ]
    ],
    [
        'survey' => [
            'name' => 'Survey A',
            'code' => 'SA'
        ],
        'questions' => [
            [
                'type' => 'qcm',
                'label' => 'Question 1?',
                'options' => ['Option 3', 'Option 1', 'Option 2'],
                'answer' => [false, true, false]
            ],
            [
                'type' => 'numeric',
                'label' => 'Question 2?',
                'options' => null,
                'answer' => 15
            ],
            [
                'type' => 'date',
                'label' => 'Question 3?',
                'options' => null,
                'answer' => '2019-09-07T18:21:45.000Z'
            ]
        ]
    ],
    [
        'survey' => [
            'name' => 'Survey B',
            'code' => 'SB'
        ],
        'questions' => [
            [
                'type' => 'qcm',
                'label' => 'Question 1?',
                'options' => ['Option 1', 'Option 3', 'Option 4'],
                'answer' => [false, true, false]
            ],
            [
                'type' => 'numeric',
                'label' => 'Question 2?',
                'options' => null,
                'answer' => 21
            ],
            [
                'type' => 'date',
                'label' => 'Question 3?',
                'options' => null,
                'answer' => '2019-09-07T16:21:45.000Z'
            ]
        ]
    ]
];