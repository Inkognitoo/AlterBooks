<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'ru',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'daily'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        /*
         * Custom Service Providers
         */
        App\MongoDB\ServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,

        'MongoDB' => App\MongoDB\Facade::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Timezones
    |--------------------------------------------------------------------------
    |
    | Список таймзон
    |
    */

    'timezones' => [
        "UTC",
        "America/Adak",
        "America/Argentina/Buenos_Aires",
        "America/Argentina/La_Rioja",
        "America/Argentina/San_Luis",
        "America/Atikokan",
        "America/Belem",
        "America/Boise",
        "America/Caracas",
        "America/Chihuahua",
        "America/Cuiaba",
        "America/Denver",
        "America/El_Salvador",
        "America/Godthab",
        "America/Guatemala",
        "America/Hermosillo",
        "America/Indiana/Tell_City",
        "America/Inuvik",
        "America/Kentucky/Louisville",
        "America/Lima",
        "America/Managua",
        "America/Mazatlan",
        "America/Mexico_City",
        "America/Montreal",
        "America/Nome",
        "America/Ojinaga",
        "America/Port-au-Prince",
        "America/Rainy_River",
        "America/Rio_Branco",
        "America/Santo_Domingo",
        "America/St_Barthelemy",
        "America/St_Vincent",
        "America/Tijuana",
        "America/Whitehorse",
        "America/Anchorage",
        "America/Argentina/Catamarca",
        "America/Argentina/Mendoza",
        "America/Argentina/Tucuman",
        "America/Atka",
        "America/Belize",
        "America/Buenos_Aires",
        "America/Catamarca",
        "America/Coral_Harbour",
        "America/Curacao",
        "America/Detroit",
        "America/Ensenada",
        "America/Goose_Bay",
        "America/Guayaquil",
        "America/Indiana/Indianapolis",
        "America/Indiana/Vevay",
        "America/Iqaluit",
        "America/Kentucky/Monticello",
        "America/Los_Angeles",
        "America/Manaus",
        "America/Mendoza",
        "America/Miquelon",
        "America/Montserrat",
        "America/Noronha",
        "America/Panama",
        "America/Port_of_Spain",
        "America/Rankin_Inlet",
        "America/Rosario",
        "America/Sao_Paulo",
        "America/St_Johns",
        "America/Swift_Current",
        "America/Toronto",
        "America/Winnipeg",
        "America/Anguilla",
        "America/Argentina/ComodRivadavia",
        "America/Argentina/Rio_Gallegos",
        "America/Argentina/Ushuaia",
        "America/Bahia",
        "America/Blanc-Sablon",
        "America/Cambridge_Bay",
        "America/Cayenne",
        "America/Cordoba",
        "America/Danmarkshavn",
        "America/Dominica",
        "America/Fort_Wayne",
        "America/Grand_Turk",
        "America/Guyana",
        "America/Indiana/Knox",
        "America/Indiana/Vincennes",
        "America/Jamaica",
        "America/Knox_IN",
        "America/Louisville",
        "America/Marigot",
        "America/Menominee",
        "America/Moncton",
        "America/Nassau",
        "America/North_Dakota/Beulah",
        "America/Pangnirtung",
        "America/Porto_Acre",
        "America/Recife",
        "America/Santa_Isabel",
        "America/Scoresbysund",
        "America/St_Kitts",
        "America/Tegucigalpa",
        "America/Tortola",
        "America/Yakutat",
        "America/Antigua",
        "America/Argentina/Cordoba",
        "America/Argentina/Salta",
        "America/Aruba",
        "America/Bahia_Banderas",
        "America/Boa_Vista",
        "America/Campo_Grande",
        "America/Cayman",
        "America/Costa_Rica",
        "America/Dawson",
        "America/Edmonton",
        "America/Fortaleza",
        "America/Grenada",
        "America/Halifax",
        "America/Indiana/Marengo",
        "America/Indiana/Winamac",
        "America/Jujuy",
        "America/Kralendijk",
        "America/Lower_Princes",
        "America/Martinique",
        "America/Merida",
        "America/Monterrey",
        "America/New_York",
        "America/North_Dakota/Center",
        "America/Paramaribo",
        "America/Porto_Velho",
        "America/Regina",
        "America/Santarem",
        "America/Shiprock",
        "America/St_Lucia",
        "America/Thule",
        "America/Vancouver",
        "America/Yellowknife",
        "America/Araguaina",
        "America/Argentina/Jujuy",
        "America/Argentina/San_Juan",
        "America/Asuncion",
        "America/Barbados",
        "America/Bogota",
        "America/Cancun",
        "America/Chicago",
        "America/Creston",
        "America/Dawson_Creek",
        "America/Eirunepe",
        "America/Glace_Bay",
        "America/Guadeloupe",
        "America/Havana",
        "America/Indiana/Petersburg",
        "America/Indianapolis",
        "America/Juneau",
        "America/La_Paz",
        "America/Maceio",
        "America/Matamoros",
        "America/Metlakatla",
        "America/Montevideo",
        "America/Nipigon",
        "America/North_Dakota/New_Salem",
        "America/Phoenix",
        "America/Puerto_Rico",
        "America/Resolute",
        "America/Santiago",
        "America/Sitka",
        "America/St_Thomas",
        "America/Thunder_Bay",
        "America/Virgin",
        "Indian/Antananarivo",
        "Indian/Kerguelen",
        "Indian/Reunion",
        "Australia/ACT",
        "Australia/Currie",
        "Australia/Lindeman",
        "Australia/Perth",
        "Australia/Victoria",
        "Europe/Amsterdam",
        "Europe/Berlin",
        "Europe/Chisinau",
        "Europe/Helsinki",
        "Europe/Kiev",
        "Europe/Madrid",
        "Europe/Moscow",
        "Europe/Prague",
        "Europe/Sarajevo",
        "Europe/Tallinn",
        "Europe/Vatican",
        "Europe/Zagreb",
        "Pacific/Apia",
        "Pacific/Efate",
        "Pacific/Galapagos",
        "Pacific/Johnston",
        "Pacific/Marquesas",
        "Pacific/Noumea",
        "Pacific/Ponape",
        "Pacific/Tahiti",
        "Pacific/Wallis",
        "Indian/Chagos",
        "Indian/Mahe",
        "Australia/Adelaide",
        "Australia/Darwin",
        "Australia/Lord_Howe",
        "Australia/Queensland",
        "Australia/West",
        "Europe/Andorra",
        "Europe/Bratislava",
        "Europe/Copenhagen",
        "Europe/Isle_of_Man",
        "Europe/Lisbon",
        "Europe/Malta",
        "Europe/Nicosia",
        "Europe/Riga",
        "Europe/Simferopol",
        "Europe/Tirane",
        "Europe/Vienna",
        "Europe/Zaporozhye",
        "Pacific/Auckland",
        "Pacific/Enderbury",
        "Pacific/Gambier",
        "Pacific/Kiritimati",
        "Pacific/Midway",
        "Pacific/Pago_Pago",
        "Pacific/Port_Moresby",
        "Pacific/Tarawa",
        "Pacific/Yap",
        "Africa/Abidjan",
        "Africa/Asmera",
        "Africa/Blantyre",
        "Africa/Ceuta",
        "Africa/Douala",
        "Africa/Johannesburg",
        "Africa/Kinshasa",
        "Africa/Lubumbashi",
        "Africa/Mbabane",
        "Africa/Niamey",
        "Africa/Timbuktu",
        "Africa/Accra",
        "Africa/Bamako",
        "Africa/Brazzaville",
        "Africa/Conakry",
        "Africa/El_Aaiun",
        "Africa/Juba",
        "Africa/Lagos",
        "Africa/Lusaka",
        "Africa/Mogadishu",
        "Africa/Nouakchott",
        "Africa/Tripoli",
        "Africa/Addis_Ababa",
        "Africa/Bangui",
        "Africa/Bujumbura",
        "Africa/Dakar",
        "Africa/Freetown",
        "Africa/Kampala",
        "Africa/Libreville",
        "Africa/Malabo",
        "Africa/Monrovia",
        "Africa/Ouagadougou",
        "Africa/Tunis",
        "Africa/Algiers",
        "Africa/Banjul",
        "Africa/Cairo",
        "Africa/Dar_es_Salaam",
        "Africa/Gaborone",
        "Africa/Khartoum",
        "Africa/Lome",
        "Africa/Maputo",
        "Africa/Nairobi",
        "Africa/Porto-Novo",
        "Africa/Windhoek",
        "Africa/Asmara",
        "Africa/Bissau",
        "Africa/Casablanca",
        "Africa/Djibouti",
        "Africa/Harare",
        "Africa/Kigali",
        "Africa/Luanda",
        "Africa/Maseru",
        "Africa/Ndjamena",
        "Africa/Sao_Tome",
        "Atlantic/Azores",
        "Atlantic/Faroe",
        "Atlantic/St_Helena",
        "Atlantic/Bermuda",
        "Atlantic/Jan_Mayen",
        "Atlantic/Stanley",
        "Atlantic/Canary",
        "Atlantic/Madeira",
        "Atlantic/Cape_Verde",
        "Atlantic/Reykjavik",
        "Atlantic/Faeroe",
        "Atlantic/South_Georgia",
        "Asia/Aden",
        "Asia/Aqtobe",
        "Asia/Baku",
        "Asia/Calcutta",
        "Asia/Dacca",
        "Asia/Dushanbe",
        "Asia/Hong_Kong",
        "Asia/Jayapura",
        "Asia/Kashgar",
        "Asia/Kuala_Lumpur",
        "Asia/Magadan",
        "Asia/Novokuznetsk",
        "Asia/Pontianak",
        "Asia/Riyadh",
        "Asia/Shanghai",
        "Asia/Tehran",
        "Asia/Ujung_Pandang",
        "Asia/Vladivostok",
        "Asia/Almaty",
        "Asia/Ashgabat",
        "Asia/Bangkok",
        "Asia/Choibalsan",
        "Asia/Damascus",
        "Asia/Gaza",
        "Asia/Hovd",
        "Asia/Jerusalem",
        "Asia/Kathmandu",
        "Asia/Kuching",
        "Asia/Makassar",
        "Asia/Novosibirsk",
        "Asia/Pyongyang",
        "Asia/Saigon",
        "Asia/Singapore",
        "Asia/Tel_Aviv",
        "Asia/Ulaanbaatar",
        "Asia/Yakutsk",
        "Asia/Amman",
        "Asia/Ashkhabad",
        "Asia/Beirut",
        "Asia/Chongqing",
        "Asia/Dhaka",
        "Asia/Harbin",
        "Asia/Irkutsk",
        "Asia/Kabul",
        "Asia/Katmandu",
        "Asia/Kuwait",
        "Asia/Manila",
        "Asia/Omsk",
        "Asia/Qatar",
        "Asia/Sakhalin",
        "Asia/Taipei",
        "Asia/Thimbu",
        "Asia/Ulan_Bator",
        "Asia/Yekaterinburg",
        "Asia/Anadyr",
        "Asia/Baghdad",
        "Asia/Bishkek",
        "Asia/Chungking",
        "Asia/Dili",
        "Asia/Hebron",
        "Asia/Istanbul",
        "Asia/Kamchatka",
        "Asia/Kolkata",
        "Asia/Macao",
        "Asia/Muscat",
        "Asia/Oral",
        "Asia/Qyzylorda",
        "Asia/Samarkand",
        "Asia/Tashkent",
        "Asia/Thimphu",
        "Asia/Urumqi",
        "Asia/Yerevan",
        "Asia/Aqtau",
        "Asia/Bahrain",
        "Asia/Brunei",
        "Asia/Colombo",
        "Asia/Dubai",
        "Asia/Ho_Chi_Minh",
        "Asia/Jakarta",
        "Asia/Karachi",
        "Asia/Krasnoyarsk",
        "Asia/Macau",
        "Asia/Nicosia",
        "Asia/Phnom_Penh",
        "Asia/Rangoon",
        "Asia/Seoul",
        "Asia/Tbilisi",
        "Asia/Tokyo",
        "Asia/Vientiane",
        "Australia/Canberra",
        "Australia/LHI",
        "Australia/NSW",
        "Australia/Tasmania",
        "Australia/Broken_Hill",
        "Australia/Hobart",
        "Australia/North",
        "Australia/Sydney",
        "Pacific/Chuuk",
        "Pacific/Fiji",
        "Pacific/Guam",
        "Pacific/Kwajalein",
        "Pacific/Niue",
        "Pacific/Pitcairn",
        "Pacific/Saipan",
        "Pacific/Truk",
        "Pacific/Chatham",
        "Pacific/Fakaofo",
        "Pacific/Guadalcanal",
        "Pacific/Kosrae",
        "Pacific/Nauru",
        "Pacific/Palau",
        "Pacific/Rarotonga",
        "Pacific/Tongatapu",
        "Pacific/Easter",
        "Pacific/Funafuti",
        "Pacific/Honolulu",
        "Pacific/Majuro",
        "Pacific/Norfolk",
        "Pacific/Pohnpei",
        "Pacific/Samoa",
        "Pacific/Wake",
        "Antarctica/Casey",
        "Antarctica/McMurdo",
        "Antarctica/Vostok",
        "Antarctica/Davis",
        "Antarctica/Palmer",
        "Antarctica/DumontDUrville",
        "Antarctica/Rothera",
        "Antarctica/Macquarie",
        "Antarctica/South_Pole",
        "Antarctica/Mawson",
        "Antarctica/Syowa",
        "Arctic/Longyearbyen",
        "Europe/Athens",
        "Europe/Brussels",
        "Europe/Dublin",
        "Europe/Istanbul",
        "Europe/Ljubljana",
        "Europe/Mariehamn",
        "Europe/Oslo",
        "Europe/Rome",
        "Europe/Skopje",
        "Europe/Tiraspol",
        "Europe/Vilnius",
        "Europe/Zurich",
        "Europe/Belfast",
        "Europe/Bucharest",
        "Europe/Gibraltar",
        "Europe/Jersey",
        "Europe/London",
        "Europe/Minsk",
        "Europe/Paris",
        "Europe/Samara",
        "Europe/Sofia",
        "Europe/Uzhgorod",
        "Europe/Volgograd",
        "Europe/Belgrade",
        "Europe/Budapest",
        "Europe/Guernsey",
        "Europe/Kaliningrad",
        "Europe/Luxembourg",
        "Europe/Monaco",
        "Europe/Podgorica",
        "Europe/San_Marino",
        "Europe/Stockholm",
        "Europe/Vaduz",
        "Europe/Warsaw",
        "Indian/Cocos",
        "Indian/Mauritius",
        "Indian/Christmas",
        "Indian/Maldives",
        "Indian/Comoro",
        "Indian/Mayotte",
        "Australia/Brisbane",
        "Australia/Eucla",
        "Australia/Melbourne",
        "Australia/South",
        "Australia/Yancowinna"
    ],
];
