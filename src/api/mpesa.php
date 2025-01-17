<?php

class Mpesa
{
    private $config;

    public function __construct()
    {
        $this->config = include('../config/config.php');
    }

    public function generateAccessToken()
    {
        $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

        $credentials = base64_encode("{$this->config['consumer_key']}:{$this->config['consumer_secret']}");

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: Basic {$credentials}"]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response);
        return $result->access_token ?? null;
    }

    public function lipaNaMpesa($phone, $amount, $accountReference)
    {
        $accessToken = $this->generateAccessToken();
        $timestamp = date('YmdHis');
        $password = base64_encode($this->config['shortcode'] . $this->config['passkey'] . $timestamp);

        $url = "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";

        $data = [
            'BusinessShortCode' => $this->config['shortcode'],
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $this->config['shortcode'],
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->config['callback_url'],
            'AccountReference' => $accountReference,
            'TransactionDesc' => 'Payment',
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json",
        ]);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}
?>
