<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReferralCodeGenerator
{
    public static function generateUniqueCode(int $length = 10): string
    {
        $length = max(6, min($length, 32));

        do {
            $code = Str::lower(Str::random($length));

            $exists = DB::table('affiliates')->where('ref_code', $code)->exists()
                || DB::table('freelances')->where('ref_code', $code)->exists();
        } while ($exists);

        return $code;
    }
}
