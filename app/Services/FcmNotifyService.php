<?php

namespace App\Services;

use Google\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;

class FcmNotifyService
{
    protected $accessToken;

    public function __construct()
    {
        $this->accessToken = $this->getAccessToken();
    }

    private function getAccessToken()
    {
        $path   = storage_path(config('firebase.google_credentials'));
        $client = new Client();
        $client->setAuthConfig($path);
        $client->addScope(config('firebase.auth_url'));

        $token = $client->fetchAccessTokenWithAssertion();

        return $token['access_token'];
    }

    public function getToken()
    {
        return $this->accessToken;
    }

    public function sendWithDeviceToken($deviceToken, $title, $body, $data)
    {
        $url = config('firebase.fcm_url');

        $payload = [
            'message' => [
                'token'        => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data'         => [
                    'title'     => $data['title'],
                    'body'      => $data['body'],
                    'action_id' => strval($data['action_id']),
                    'type'      => $data['type'],
                ],
            ],
        ];



        $ch = curl_init($url);

        $headers = [
            'Authorization: Bearer ' . $this->accessToken, // Include the Bearer token
            'Content-Type: application/json',
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $response = json_encode([
                'error' => 'cURL error: ' . curl_error($ch),
            ]);
        }

        curl_close($ch);

        return $response;
    }

    public function sendWithTopic($topic, $title, $body, $data)
    {
        $url     = config('firebase.fcm_url');
        $payload = [
            'message' => [
                'topic'        => $topic, // Ensure devices are subscribed to this topic
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data'         => [
                    'title'     => $data['title'],
                    'body'      => $data['body'],
                    'action_id' => strval($data['action_id']),
                    'type'      => $data['type'],
                ],
            ],
        ];

        $headers = [
            'Authorization: Bearer ' . $this->accessToken, // Ensure valid OAuth 2.0 Bearer token
            'Content-Type: application/json',
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $response = json_encode([
                'error' => 'cURL error: ' . curl_error($ch),
            ]);
        }

        curl_close($ch);

        return $response;
    }
}
