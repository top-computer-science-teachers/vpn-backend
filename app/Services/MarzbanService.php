<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class MarzbanService
{
    public function createUser($user, $data, $token)
    {
        $vlessInbound = ['VLESS TCP REALITY'];
        $vlessFlow = 'xtls-rprx-vision';
        $expireTimestamp = $data['is_demo'] === true ?
            Carbon::now()->addDays(3)->timestamp :
            Carbon::now()->addMonths($data['months'])->timestamp;

        $url = 'https://spxrtxk.online:8000/api/user';

        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post($url, [
                'username' => $user->username,
                'status' => 'active',
                'expire' => $expireTimestamp,
                'inbounds' => [
                    'vless' => $vlessInbound
                ],
                'note' => 'from REST API',
                'proxies' => [
                    'vless' => [
                        'flow' => $vlessFlow
                    ]
                ]
            ]);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'error' => $response->json()
                ];
            }

            return [
                'success' => true
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }

    }
}
