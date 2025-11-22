<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AirtelPaymentController extends Controller
{
    protected $client;
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
            'http_errors' => false
        ]);

        $this->initializeConfig();
    }

    protected function initializeConfig()
    {
        try {
            $this->baseUrl = config('services.airtel.base_url');
            $this->clientId = trim(config('services.airtel.client_id'));
            $this->clientSecret = trim(config('services.airtel.client_secret'));

            if (empty($this->clientId)) {
                throw new \RuntimeException('Airtel Client ID is not configured');
            }

            if (empty($this->clientSecret)) {
                throw new \RuntimeException('Airtel Client Secret is not configured');
            }

        } catch (\Exception $e) {
            Log::critical('Airtel config initialization failed: ' . $e->getMessage());
            abort(500, 'Payment service configuration error');
        }
    }

    /**
     * Generate authentication token
     */
    protected function generateToken()
    {
        try {
            $response = $this->client->post("{$this->baseUrl}/auth/oauth2/token", [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                ],
                'form_params' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'client_credentials',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            if ($statusCode !== 200) {
                throw new \RuntimeException("Auth failed with status {$statusCode}: {$body}");
            }

            $data = json_decode($body, true);

            if (!isset($data['access_token'])) {
                throw new \RuntimeException('Invalid auth response: missing access_token');
            }

            return $data['access_token'];

        } catch (GuzzleException $e) {
            throw new \RuntimeException('Network error during authentication: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \RuntimeException('Authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Initiate payment
     */
    public function initiatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'phone_number' => 'required|string',
            'reference' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
        ], [
            'phone_number.regex' => 'The phone number must be a valid Tanzanian Airtel number'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $accessToken = $this->generateToken();
            $phoneNumber = $this->normalizePhoneNumber($request->phone_number);

            $payload = [
                'reference' => $request->reference,
                'subscriber' => [
                    'country' => 'TZ',
                    'currency' => 'TZS',
                    'msisdn' => $phoneNumber,
                ],
                'transaction' => [
                    'amount' => $request->amount,
                    'country' => 'TZ',
                    'currency' => 'TZS',
                    'id' => 'TXN_' . uniqid(),
                ],
            ];

            if ($request->has('description')) {
                $payload['transaction']['description'] = $request->description;
            }

            $response = $this->client->post("{$this->baseUrl}/merchant/v2/payments/", [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json',
                    'X-Country' => 'TZ',
                    'X-Currency' => 'TZS',
                ],
                'json' => $payload,
            ]);

            return $this->handleApiResponse($response);

        } catch (\RuntimeException $e) {
            Log::error('Payment initiation failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Payment processing failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function normalizePhoneNumber($phoneNumber)
    {
        $normalized = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Convert to Airtel format (remove country code if present)
        if (strlen($normalized) === 12 && strpos($normalized, '255') === 0) {
            return substr($normalized, 3);
        }

        if (strlen($normalized) === 9) {
            return $normalized;
        }

        throw new \RuntimeException('Invalid phone number format');
    }

    protected function handleApiResponse($response)
    {
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if ($statusCode >= 400) {
            $error = $data['message'] ?? $data['error'] ?? 'Unknown API error';
            throw new \RuntimeException("API request failed with status {$statusCode}: {$error}");
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Handle payment callback
     */
    public function paymentCallback(Request $request)
    {
        try {
            $signature = $request->header('X-Callback-Signature');
            $payload = $request->getContent();

            if (!$this->verifyCallbackSignature($signature, $payload)) {
                throw new \RuntimeException('Invalid callback signature');
            }

            $data = json_decode($payload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Invalid JSON payload');
            }

            // Process the callback
            $status = $data['status'] ?? null;
            $reference = $data['reference'] ?? null;

            if (!$status || !$reference) {
                throw new \RuntimeException('Missing required callback parameters');
            }

            // TODO: Implement your callback processing logic here
            // Example: Update database, send notifications, etc.

            return response()->json(['status' => 'success']);

        } catch (\RuntimeException $e) {
            Log::error('Callback processing failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    protected function verifyCallbackSignature($signature, $payload)
    {
        $secret = config('services.airtel.callback_secret');
        
        if (empty($secret)) {
            Log::warning('Callback secret not configured - signature verification skipped');
            return true;
        }

        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expectedSignature, $signature);
    }
}