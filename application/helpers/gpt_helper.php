<?php
if (!function_exists('generate_text')) {
    function generate_text($messages) {
        $ci =& get_instance();
        $ci->config->load('gpt');
        $api_key = $ci->config->item('gpt_api_key');

        $url = 'https://api.openai.com/v1/chat/completions';

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
        );
        $data = array(
            'messages' => $messages,
            'max_tokens' => 1000,
            'model' => 'gpt-3.5-turbo',
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        $decoded_response = json_decode($response, true);
        $generated_text = $decoded_response['choices'][0]['message']['content'];
        $generated_text = str_replace("\n", "<br>", $generated_text);

        return $generated_text;
    }
}

if (!function_exists('generate_function')) {
    function generate_function($messages, $available_item) {

        $functions = [
            [
                "name" => "get_top_selling_category_dates",
                "description" => "Get the top selling category in terms of unit sold between two dates",
                "parameters" => [
                    "type" => "object",
                    "properties" => [
                        "start_date" => [
                            "type" => "string",
                            "description" => "The start date of when the data should be queried and it is in MySQL datetime format, e.g. '2023-09-21 10:00:00'",
                        ],
                        "end_date" => [
                            "type" => "string",
                            "description" => "The end date of when the category should be queried and it is in MySQL datetime format, e.g. '2023-09-21 10:00:00'",
                        ],
                        "limit" => [
                            "type" => "integer",
                            "description" => "The value that decides the number of top selling category eg: top 10, top 20. Default is 10",
                        ],
                    ],
                    "required" => ["start_date", "end_date", "limit"],
                ],
            ],
            [
                "name" => "get_top_selling_category_monthly",
                "description" => "Get the top selling category in terms of unit sold for a specific month and year",
                "parameters" => [
                    "type" => "object",
                    "properties" => [
                        "month" => [
                            "type" => "integer",
                            "description" => "The month for which data is quried for in number between 1 to 12",
                        ],
                        "year" => [
                            "type" => "string",
                            "description" => "The year that serves as a parameter to query the best selling category, only accepts from year 2018 to the current year",
                        ],
                        "limit" => [
                            "type" => "integer",
                            "description" => "The value that decides the number of top selling category eg: top 10, top 20. Default is 10",
                        ],
                    ],
                    "required" => ["month", "year", "limit"],
                ],
            ],
            [
                "name" => "get_top_earning_item_dates",
                "description" => "Get the top earning item or product in terms of sales generated between two dates specifically in graph format. Only triggers this if user specifically wanted graph format",
                "parameters" => [
                    "type" => "object",
                    "properties" => [
                        "start_date" => [
                            "type" => "string",
                            "description" => "The start date of when the data should be queried and it is in MySQL datetime format, e.g. '2023-09-21 10:00:00'",
                        ],
                        "end_date" => [
                            "type" => "string",
                            "description" => "The end date of when the category should be queried and it is in MySQL datetime format, e.g. '2023-09-21 10:00:00'",
                        ],
                        "limit" => [
                            "type" => "integer",
                            "description" => "The value that decides the number of top selling category eg: top 10, top 20. Default is 10",
                        ],
                    ],
                    "required" => ["start_date", "end_date", "limit"],
                ],
            ],
            [
                "name" => "get_top_earning_items_monthly",
                "description" => "Get the top earning item or product in terms of sales generated for a specific month and year specifically in graph format. Only triggers this if user specifically wanted graph format",
                "parameters" => [
                    "type" => "object",
                    "properties" => [
                        "month" => [
                            "type" => "integer",
                            "description" => "The month for which data is quried for in number between 1 to 12",
                        ],
                        "year" => [
                            "type" => "string",
                            "description" => "The year that serves as a parameter to query the best selling category, only accepts from year 2018 to the current year",
                        ],
                        "limit" => [
                            "type" => "integer",
                            "description" => "The value that decides the number of top selling category eg: top 10, top 20. Default is 10",
                        ],
                    ],
                    "required" => ["month", "year", "limit"],
                ],
            ],
            [
                "name" => "sales_earning_table_dates",
                "description" => "Get the top earning item or product in terms of sales generated between two dates specifically in table format. Only triggers this if user specifically wanted table format",
                "parameters" => [
                    "type" => "object",
                    "properties" => [
                        "start_date" => [
                            "type" => "string",
                            "description" => "The start date of when the data should be queried and it is in MySQL datetime format, e.g. '2023-09-21 10:00:00'",
                        ],
                        "end_date" => [
                            "type" => "string",
                            "description" => "The end date of when the category should be queried and it is in MySQL datetime format, e.g. '2023-09-21 10:00:00'",
                        ],
                        "limit" => [
                            "type" => "integer",
                            "description" => "The value that decides the number of top selling category eg: top 10, top 20. Default is 10",
                        ],
                    ],
                    "required" => ["start_date", "end_date", "limit"],
                ],
            ],
            [
                "name" => "sales_earning_table_monthly",
                "description" => "Get the top earning item or product in terms of sales generated for a specific month and year, and specifically in table format. Only triggers this if user specifically wanted table format",
                "parameters" => [
                    "type" => "object",
                    "properties" => [
                        "month" => [
                            "type" => "integer",
                            "description" => "The month for which data is quried for in number between 1 to 12",
                        ],
                        "year" => [
                            "type" => "string",
                            "description" => "The year that serves as a parameter to query the best selling category, only accepts from year 2018 to the current year",
                        ],
                        "limit" => [
                            "type" => "integer",
                            "description" => "The value that decides the number of top selling category eg: top 10, top 20. Default is 10",
                        ],
                    ],
                    "required" => ["month", "year", "limit"],
                ],
            ],
            [
                "name" => "compare_items_sales_unit_dates",
                "description" => "Compare sales generated or unit sold by one or more than one item between a time period of two month. For example: January 2023 to July 2023",
                "parameters" => [
                    "type" => "object",
                    "properties" => [
                        "start_month_year" => [
                            "type" => "string",
                            "description" => "The month and year where the time period begins. Eg: July 2023, January 2019 or November 2022",
                        ],
                        "end_month_year" => [
                            "type" => "string",
                            "description" => "The month and year where the time period ends. Eg: July 2023, January 2019 or November 2022",
                        ],
                        "item_id" => [
                            "type" => "integer",
                            "description" => "An array of item_id where each item_id represents an item. The following is the item_id to item name catalog you can refer to: {".$available_item."}.",
                        ],
                        "sales_or_unit" => [
                            "type" => "string",
                            "description" => "The decision to compare either sales generated or unit sold of an item where there are only two possible decision, eg: 'sales' or 'unit'. Default is 'sales'.",
                        ],
                    ],
                    "required" => ["start_date", "end_date", "item_id", "sales_or_unit"],
                ],
            ],
        ];
        
        $ci =& get_instance();
        $ci->config->load('gpt');
        $api_key = $ci->config->item('gpt_api_key');

        $url = 'https://api.openai.com/v1/chat/completions';

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
        );
        $data = array(
            'messages' => [
                $messages,
            ],
            'model' => 'gpt-3.5-turbo',
            'functions' => $functions,
            'function_call' => 'auto',
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        $decoded_response = json_decode($response, true);
        $generated_text = $decoded_response['choices'][0]['message']['function_call'];
        // $generated_text = str_replace("\n", "<br>", $generated_text);

        return $generated_text;
    }
}

// if (!function_exists('generate_function')) {
//     function generate_function($messages) {

//         $functions = [
//             [
//                 "name" => "get_top_selling_category_dates",
//                 "description" => "Get the top selling category between two dates",
//                 "parameters" => [
//                     "type" => "object",
//                     "properties" => [
//                         "start_date" => [
//                             "type" => "string",
//                             "description" => "The start date of when the data should be queried and it is in MySQL datetime format, e.g. '2023-09-21 10:00:00'",
//                         ],
//                         "end_date" => [
//                             "type" => "string",
//                             "description" => "The end date of when the category should be queried and it is in MySQL datetime format, e.g. '2023-09-21 10:00:00'",
//                         ],
//                     ],
//                     "required" => ["start_date", "end_date"],
//                 ],
//             ],
//             [
//                 "name" => "get_top_selling_category_monthly",
//                 "description" => "Get the top selling category for a specific month and year",
//                 "parameters" => [
//                     "type" => "object",
//                     "properties" => [
//                         "month" => [
//                             "type" => "integer",
//                             "description" => "The month for which data is quried for in number between 1 to 12",
//                         ],
//                         "year" => [
//                             "type" => "string",
//                             "description" => "The year that serves as a parameter to query the best selling category, only accepts from year 2018 to the current year",
//                         ],
//                     ],
//                     "required" => ["month", "year"],
//                 ],
//             ],
//         ];
        
//         $ci =& get_instance();
//         $ci->config->load('gpt');
//         $api_key = $ci->config->item('gpt_api_key');

        
//         $ch = curl_init('https://api.openai.com/v1/chat/completions');
//         curl_setopt($ch, CURLOPT_HTTPHEADER, [
//             'Content-Type: application/json',
//             'Authorization: Bearer ' . $api_key,
//         ]);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(
//             [
//                 'messages' => $messages,
//                 'max_tokens' => 1000,
//                 'model' => 'gpt-3.5-turbo',
//                 'functions' => $functions,
//                 'function_call' => 'auto',
//             ]
//         ));
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_POST, true);

//         $decoded_response = json_decode(curl_exec($ch));
//         // $generated_function = $decoded_response->choices[0]->message->function_call;

//         return $decoded_response;

//     }
// }


