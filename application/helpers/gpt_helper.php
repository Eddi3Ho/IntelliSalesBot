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
?>