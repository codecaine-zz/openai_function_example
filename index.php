<?php
// Function to get a response from OpenAI's GPT model
function getResponseFromOpenAI($api_key, $query, $max_tokens) {
    // Initialize a new cURL session
    $ch = curl_init();

    // API endpoint
    $url = 'https://api.openai.com/v1/chat/completions';

    // Prepare the data to be sent in the POST request
    $post_fields = array(
        "model" => "gpt-3.5-turbo", // The model to use
        "messages" => array(
            array(
                "role" => "user", // The role of the message sender
                "content" => $query // The content of the message
            )
        ),
        "max_tokens" => $max_tokens, // The maximum number of tokens to generate
        "temperature" => 0 // The randomness of the output (0 = deterministic, 1 = maximum randomness)
    );

    // Prepare the headers for the request
    $header = [
        'Content-Type: application/json', // The content type of the request
        'Authorization: Bearer ' . $api_key // The authorization header with the API key
    ];

    // Set the options for the cURL session
    curl_setopt($ch, CURLOPT_URL, $url); // The URL to fetch
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // To return the transfer as a string
    curl_setopt($ch, CURLOPT_POST, 1); // To send a POST request
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields)); // The data to send in the POST request
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header); // The headers to set for the request

    // Execute the cURL session
    $result = curl_exec($ch);

    // Check for errors and display them if any
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    }

    // Close the cURL session
    curl_close($ch);

    // Decode the response
    $response = json_decode($result);

    // Check if the desired property exists in the response and get its value
    $content = isset($response->choices[0]->message->content) ? $response->choices[0]->message->content : null;

    // Return the content of the response
    return $content;
}

// Usage
$api_key = ''; // Replace with your actual API key
$query = 'What is the best HIIT exercises for a male that is in his 40s?'; // User prompt
$max_tokens = 1000; // The maximum number of tokens to generate

// Call the function and get the response content
$response_content = getResponseFromOpenAI($api_key, $query, $max_tokens);

// Display the response content
echo "Response content: $response_content";
?>