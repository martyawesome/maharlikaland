<?php

return [
    'DEVELOPER_ID' => 1,

    'CURRENCY' => 'Php',

    'REQUEST_NUMERIC' => array('regex:/^ *(\d+|[1-9]\d{0,2}(,\d{3})*)(\.\d+)? *$/'),
    'REQUEST_NUMERIC_REQUIRED' => array('required' , 'regex:/^ *(\d+|[1-9]\d{0,2}(,\d{3})*)(\.\d+)? *$/'),

    'REQUEST_COORDINATES' => array('regex:/^ *(\d+|(\d+(\.\d+))) *, *(\d+|(\d+(\.\d+))) *$/'),
    'REQUEST_COORDINATES_REQUIRED' => array('required' , 'regex:/^ *(\d+|(\d+(\.\d+))) *, *(\d+|(\d+(\.\d+))) *$/'),

    'PROPERTIES_IMAGES_PATH' => 'img/properties/',
    'PROJECTS_IMAGES_PATH' => 'img/projects/' ,
    'PROJECTS_VICINITY_MAP_PATH' => 'vicinity_map/' ,
    'PROJECTS_SUBD_PLAN_PATH' => 'subd_plan/' ,
    'PROJECTS_SUBD_GALLERY_PATH' => 'gallery/' ,

    'PROPERTIES_DEFAULT_IMAGE_PATH' => 'img/defaults/icon-property-default.png',
    'PROJECTS_DEFAULT_IMAGE_PATH' => 'img/defaults/icon-project-default.png',

    'PROMOTIONAL_IMAGES_PATH' => 'img/promotional_images/',
    'PROMOTIONAL_VIDEOS_PATH' => 'vid/promotional_videos/',

    'USER_TYPE_ADMIN' => 1,
    'USER_TYPE_BROKER' => 2,
    'USER_TYPE_SALESPERSON' => 3,
    'USER_TYPE_PROSPECT_BUYER' => 4,
    'USER_TYPE_BUYER' => 5,
    'USER_TYPE_DEVELOPER_ADMIN' => 6,
    'USER_TYPE_DEVELOPER_SECRETARY' => 7,
    'USER_TYPE_DEVELOPER_ACCOUNTANT' => 8,
    'USER_TYPE_DEVELOPER_EMPLOYEE' => 9,
    'USER_TYPE_DEVELOPER_CONSTRUCTION' => 10,
    'USER_TYPE_DEVELOPER_GUARD' => 11,

    'PROPERTY_TYPE_LOT' => 4,

    'PROPERTY_STATUS_FOR_SALE' => '1',
    'PROPERTY_STATUS_FOR_RENT' => '2',
    'PROPERTY_STATUS_RESERVED' => '3',
    'PROPERTY_STATUS_FORECLOSED' => '4',
    'PROPERTY_STATUS_SOLD_ONGOING_DP' => '5',
    'PROPERTY_STATUS_SOLD_ONGOING_MA' => '6',
    'PROPERTY_STATUS_FULLY_PAID' => '7',
    'PROPERTY_STATUS_BANK_FINANCED' => '8',

    'PROPERTY_TYPE_LOT' => 4,

    'PAYMENT_TYPE_RESERVATION_FEE' => 1,
    'PAYMENT_TYPE_DOWNPAYMENT' => 2,
    'PAYMENT_TYPE_MA' => 3,
    'PAYMENT_TYPE_PENALTY_PAYMENT' => 4,
    'PAYMENT_TYPE_PENALTY_FEE' => 5,
    'PAYMENT_TYPE_FULL_PAYMENT' => 6,
    'PAYMENT_TYPE_BANK_FINANCE_PAYMENT' => 7,

    'PENALTY_TYPE_COMPOUNDED_PENALTY' => 1,
    'PENALTY_TYPE_NEGATIVE_PRINCIPAL' => 2,
    'PENALTY_TYPE_TRUE_INTEREST' => 3,

    'PENALTY_PERCENTAGE' => 0.03,
    'FULL_PAYMENT_INTEREST_PERCENTAGE' => 0.5,
    'CA_THRESHOLD' => 250,

    'PENALTY_COUNT_THRESHOLD' => 1,
    'PENALTY_DAYS_ADJUSTMENT' => 0,

    'WATER_SOURCE_PERCENTAGE' => 1.2,
    'ELECTRICITY_SOURCE_PERCENTAGE' => 1.2,

    'VOUCHER_CASH' => 1,
    'VOUCHER_CHECK' => 0,

    'MEDIA_TYPE_IMAGE' => 1,
    'MEDIA_TYPE_VIDEO' => 2,

    'HOLIDAY_REGULAR' => 1,
    'HOLIDAY_SPECIAL_NON_WORKING' => 2,

    'HOURS_OF_WORKING' => 8,

    'API_KEY_GOOGLE_CALENDAR' => 'AIzaSyCb7IencYoT1Tq9Bswlp4zARdade3CdU74'
];
