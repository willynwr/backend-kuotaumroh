<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Proxy Controller
 * 
 * Meneruskan request dari frontend ke external API (tokodigi.id)
 * untuk menghindari CORS issues dan menyembunyikan external API endpoint
 */
class ProxyController extends Controller
{
    /**
     * Base URL untuk external API
     */
    private $externalApiUrl = 'https://tokodigi.id/api';

    /**
     * Proxy untuk GET request ke external API
     * 
     * @param Request $request
     * @param string $path - Path setelah /api/proxy/
     * @return \Illuminate\Http\JsonResponse
     */
    public function proxyGet(Request $request, $path = '')
    {
        try {
            $url = $this->externalApiUrl . '/' . $path;
            
            // Ambil query parameters dari request
            $queryParams = $request->query();
            
            Log::info('Proxy GET Request', [
                'url' => $url,
                'params' => $queryParams,
            ]);

            // Forward request ke external API
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get($url, $queryParams);

            // Return response dari external API
            return response()->json(
                $response->json(),
                $response->status()
            );

        } catch (\Exception $e) {
            Log::error('Proxy GET Error', [
                'message' => $e->getMessage(),
                'path' => $path,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Proxy request failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy untuk POST request ke external API
     * 
     * @param Request $request
     * @param string $path - Path setelah /api/proxy/
     * @return \Illuminate\Http\JsonResponse
     */
    public function proxyPost(Request $request, $path = '')
    {
        try {
            $url = $this->externalApiUrl . '/' . $path;
            
            // Ambil body dari request
            $body = $request->all();
            
            Log::info('Proxy POST Request', [
                'url' => $url,
                'body' => $body,
            ]);

            // Forward request ke external API
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($url, $body);

            Log::info('Proxy POST Response', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            // Return response dari external API
            return response()->json(
                $response->json(),
                $response->status()
            );

        } catch (\Exception $e) {
            Log::error('Proxy POST Error', [
                'message' => $e->getMessage(),
                'path' => $path,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Proxy request failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy untuk PUT request ke external API
     * 
     * @param Request $request
     * @param string $path - Path setelah /api/proxy/
     * @return \Illuminate\Http\JsonResponse
     */
    public function proxyPut(Request $request, $path = '')
    {
        try {
            $url = $this->externalApiUrl . '/' . $path;
            
            // Ambil body dari request
            $body = $request->all();
            
            Log::info('Proxy PUT Request', [
                'url' => $url,
                'body' => $body,
            ]);

            // Forward request ke external API
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->put($url, $body);

            // Return response dari external API
            return response()->json(
                $response->json(),
                $response->status()
            );

        } catch (\Exception $e) {
            Log::error('Proxy PUT Error', [
                'message' => $e->getMessage(),
                'path' => $path,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Proxy request failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy untuk DELETE request ke external API
     * 
     * @param Request $request
     * @param string $path - Path setelah /api/proxy/
     * @return \Illuminate\Http\JsonResponse
     */
    public function proxyDelete(Request $request, $path = '')
    {
        try {
            $url = $this->externalApiUrl . '/' . $path;
            
            Log::info('Proxy DELETE Request', [
                'url' => $url,
            ]);

            // Forward request ke external API
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->delete($url);

            // Return response dari external API
            return response()->json(
                $response->json(),
                $response->status()
            );

        } catch (\Exception $e) {
            Log::error('Proxy DELETE Error', [
                'message' => $e->getMessage(),
                'path' => $path,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Proxy request failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
