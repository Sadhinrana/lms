<?php

return [
    "roles"=>[
        'headmaster'=>'headmaster',
        'student'=>'student',
        'content_manager'=>'content_manager',
        'company_head'=>'company_head',
        'instructor'=>'instructor',
        'chief_auditor'=>'chief_auditor',
        'auditor'=>'auditor',
        'head_teacher'=>'head_teacher'
    ],

    "default_files"=>[
        'avatar'=>'/file/users/general/default.png'
    ],

    // Permission Student, Instructor, content_manager, headmaster
    "crud" => ['headmaster', 'content_mamanager','content_manager'], 
    "dest" => ['headmaster','content_manager'],

    "route_permission" => [
        'course' => [
            'create' => ['headmaster','instructor','content_manager'],
            'update' => ['headmaster','instructor','content_manager'],
            'delete' => ['headmaster','instructor','content_manager'],
            'get' => ['headmaster','instructor','student','content_manager']
        ],
    ]

];
