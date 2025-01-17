<?php
use GuzzleHttp\Client;

function generateMpesaAccessToken()
{
    $consumerKey = 'AhnfmrRF8Gw6InD4WbmFCRGm6pvEMpfVZYNDXuMsdg3rh22z'; // Replace with your consumer key
    $consumerSecret = 'MFn6fuTGnBnz8S9lKjVua9EfqgbKODRczhoAcyx3pqob8kTCA41XPQIRysVO03jA'; // Replace with your consumer secret

    $client = new Client();
    $response = $client->request('GET', 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials', [
        'auth' => [$consumerKey, $consumerSecret]
    ]);

    $data = json_decode($response->getBody(), true);
    return $data['access_token'];
}

function initiateMpesaStkPush($phone, $amount, $item)
{
    $accessToken = generateMpesaAccessToken();

    $BusinessShortCode = '174379'; // Replace with your business shortcode
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; // Replace with your passkey
    $Timestamp = date('YmdHis');
    $Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);

    $client = new Client();
    $response = $client->request('POST', 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
        'headers' => [
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'BusinessShortCode' => $BusinessShortCode,
            'Password' => $Password,
            'Timestamp' => $Timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $BusinessShortCode,
            'PhoneNumber' => $phone,
            'CallBackURL' => 'https://your-backend-url.com/api/callback', // Replace with your callback URL
            'AccountReference' => $item,
            'TransactionDesc' => "Payment for $item",
        ],
    ]);

    return json_decode($response->getBody(), true);
}
