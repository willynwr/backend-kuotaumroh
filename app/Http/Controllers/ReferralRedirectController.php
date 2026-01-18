<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Freelance;
use Illuminate\Http\Request;

class ReferralRedirectController extends Controller
{
    public function redirect(Request $request, string $code)
    {
        $code = trim($code);

        $affiliate = Affiliate::query()
            ->where('is_active', true)
            ->where('link_referral', $code)
            ->first();

        if ($affiliate) {
            return redirect()->away($this->buildFrontendUrl("affiliate:{$affiliate->id}"), 302);
        }

        $freelance = Freelance::query()
            ->where('is_active', true)
            ->where('link_referral', $code)
            ->first();

        if ($freelance) {
            return redirect()->away($this->buildFrontendUrl("freelance:{$freelance->id}"), 302);
        }

        return $this->invalidReferralResponse($request);
    }

    private function buildFrontendUrl(string $ref): string
    {
        $frontendUrl = rtrim((string) config('app.frontend_url'), '/');
        $refValue = rawurlencode($ref);

        return "{$frontendUrl}/ref.html?ref={$refValue}";
    }

    private function invalidReferralResponse(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Referral tidak valid'], 404);
        }

        return response(
            '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Referral Tidak Valid</title></head><body><h1>Referral tidak valid</h1></body></html>',
            404,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }
}
