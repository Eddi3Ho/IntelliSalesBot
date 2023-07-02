<?php
if (!function_exists('generate_text')) {
    function generate_text($prompt) {
        $ci =& get_instance();
        $ci->config->load('gpt');
        $api_key = $ci->config->item('gpt_api_key');

        $url = 'https://api.openai.com/v1/engines/text-davinci-003/completions';
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
        );
        $data = array(
            'prompt' => $prompt,
            'max_tokens' => 100,
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        $decoded_response = json_decode($response, true);
        $generated_text = $decoded_response['choices'][0]['text'];

        return $generated_text;
    }
}
