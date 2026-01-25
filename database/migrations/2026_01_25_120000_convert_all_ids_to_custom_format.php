<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Mengubah semua ID dari auto-increment bigint menjadi custom string ID:
     * - agent: AGT00001 (8 char)
     * - affiliate: AFT00001 (8 char)
     * - freelance: FRL00001 (8 char)
     * - pesanan: ORD0000001 (10 char) - sudah string
     * - pembayaran: PAY0000001 (10 char) - sudah string
     * - rekening: BAC000001 (9 char)
     * - reward: RWD000001 (9 char)
     * - withdraw: WDW000001 (9 char) - sudah string
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ============================================
        // BACKUP ALL DATA FIRST
        // ============================================
        $affiliates = DB::table('affiliate')->get();
        $freelances = DB::table('freelance')->get();
        $agents = DB::table('agent')->get();
        $rekenings = DB::table('rekening')->get();
        $rewards = DB::table('reward')->get();
        $withdraws = DB::table('withdraw')->get();
        $pembayarans = DB::table('pembayaran')->get();
        $margins = DB::table('margin')->get();

        // ============================================
        // DROP ALL DEPENDENT TABLES FIRST
        // ============================================
        Schema::dropIfExists('withdraw');
        Schema::dropIfExists('rekening');
        Schema::dropIfExists('margin');
        Schema::dropIfExists('agent');
        Schema::dropIfExists('affiliate');
        Schema::dropIfExists('freelance');
        Schema::dropIfExists('reward');

        // ============================================
        // 1. AFFILIATE TABLE - AFT00001
        // ============================================
        
        Schema::create('affiliate', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('nama');
            $table->string('email');
            $table->string('no_wa');
            $table->string('provinsi');
            $table->string('kab_kota');
            $table->text('alamat_lengkap');
            $table->date('date_register');
            $table->boolean('is_active')->default(true);
            $table->string('link_referral');
            $table->integer('saldo_fee')->default(0);
            $table->integer('total_fee')->default(0);
            $table->string('ref_code')->nullable();
            $table->timestamps();

            // Indexes
            $table->unique('email', 'affiliates_email_unique');
            $table->unique('no_wa', 'affiliates_no_wa_unique');
            $table->unique('link_referral', 'affiliates_link_referral_unique');
            $table->unique('ref_code', 'affiliates_ref_code_unique');
            $table->index('email', 'affiliates_email_index');
            $table->index('date_register', 'affiliates_date_register_index');
            $table->index('is_active', 'affiliates_is_active_index');
            $table->index('kab_kota', 'affiliates_kab_kota_index');
            $table->index('provinsi', 'affiliates_provinsi_index');
        });

        // Re-insert data with new ID format
        foreach ($affiliates as $index => $affiliate) {
            $newId = 'AFT' . str_pad($affiliate->id, 5, '0', STR_PAD_LEFT);
            DB::table('affiliate')->insert([
                'id' => $newId,
                'nama' => $affiliate->nama,
                'email' => $affiliate->email,
                'no_wa' => $affiliate->no_wa,
                'provinsi' => $affiliate->provinsi,
                'kab_kota' => $affiliate->kab_kota,
                'alamat_lengkap' => $affiliate->alamat_lengkap,
                'date_register' => $affiliate->date_register,
                'is_active' => $affiliate->is_active,
                'link_referral' => $affiliate->link_referral,
                'saldo_fee' => $affiliate->saldo_fee ?? 0,
                'total_fee' => $affiliate->total_fee ?? 0,
                'ref_code' => $affiliate->ref_code,
                'created_at' => $affiliate->created_at,
                'updated_at' => $affiliate->updated_at,
            ]);
        }

        // Store mapping for foreign key updates
        $affiliateMapping = [];
        foreach ($affiliates as $affiliate) {
            $affiliateMapping[$affiliate->id] = 'AFT' . str_pad($affiliate->id, 5, '0', STR_PAD_LEFT);
        }

        // ============================================
        // 2. FREELANCE TABLE - FRL00001
        // ============================================
        
        Schema::create('freelance', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('nama');
            $table->string('email');
            $table->string('no_wa');
            $table->string('provinsi');
            $table->string('kab_kota');
            $table->text('alamat_lengkap');
            $table->date('date_register');
            $table->boolean('is_active')->default(true);
            $table->string('link_referral');
            $table->integer('saldo_fee')->default(0);
            $table->integer('total_fee')->default(0);
            $table->string('ref_code')->nullable();
            $table->timestamps();

            // Indexes
            $table->unique('email', 'freelances_email_unique');
            $table->unique('no_wa', 'freelances_no_wa_unique');
            $table->unique('link_referral', 'freelances_link_referral_unique');
            $table->unique('ref_code', 'freelances_ref_code_unique');
            $table->index('email', 'freelances_email_index');
            $table->index('date_register', 'freelances_date_register_index');
            $table->index('is_active', 'freelances_is_active_index');
            $table->index('kab_kota', 'freelances_kab_kota_index');
            $table->index('provinsi', 'freelances_provinsi_index');
        });

        foreach ($freelances as $freelance) {
            $newId = 'FRL' . str_pad($freelance->id, 5, '0', STR_PAD_LEFT);
            DB::table('freelance')->insert([
                'id' => $newId,
                'nama' => $freelance->nama,
                'email' => $freelance->email,
                'no_wa' => $freelance->no_wa,
                'provinsi' => $freelance->provinsi,
                'kab_kota' => $freelance->kab_kota,
                'alamat_lengkap' => $freelance->alamat_lengkap,
                'date_register' => $freelance->date_register,
                'is_active' => $freelance->is_active,
                'link_referral' => $freelance->link_referral,
                'saldo_fee' => $freelance->saldo_fee ?? 0,
                'total_fee' => $freelance->total_fee ?? 0,
                'ref_code' => $freelance->ref_code,
                'created_at' => $freelance->created_at,
                'updated_at' => $freelance->updated_at,
            ]);
        }

        $freelanceMapping = [];
        foreach ($freelances as $freelance) {
            $freelanceMapping[$freelance->id] = 'FRL' . str_pad($freelance->id, 5, '0', STR_PAD_LEFT);
        }

        // ============================================
        // 3. AGENT TABLE - AGT00001
        // ============================================
        
        Schema::create('agent', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('affiliate_id', 10)->nullable();
            $table->string('freelance_id', 10)->nullable();
            $table->string('kategori_agent');
            $table->string('email');
            $table->string('nama_pic');
            $table->string('no_hp');
            $table->string('nama_travel')->nullable();
            $table->string('jenis_travel')->nullable();
            $table->integer('total_traveller')->nullable()->default(0);
            $table->string('provinsi');
            $table->string('kabupaten_kota');
            $table->text('alamat_lengkap');
            $table->text('link_gmaps')->nullable();
            $table->decimal('long', 10, 7)->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->string('link_referal')->nullable();
            $table->date('date_approve')->nullable();
            $table->string('logo')->nullable();
            $table->string('surat_ppiu')->nullable();
            $table->integer('saldo')->nullable();
            $table->integer('saldo_bulan')->default(0);
            $table->integer('saldo_tahun')->default(0);
            $table->string('status')->default('pending');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            // Indexes
            $table->unique('no_hp', 'agents_no_hp_unique');
            $table->index('email', 'agents_email_index');
            $table->index('affiliate_id', 'agents_affiliate_id_index');
            $table->index('freelance_id', 'agents_freelance_id_index');
            $table->index('kategori_agent', 'agents_kategori_agent_index');
            $table->index('provinsi', 'agents_provinsi_index');
            $table->index('kabupaten_kota', 'agents_kabupaten_kota_index');
            $table->index('status', 'agents_status_index');
            $table->index('is_active', 'agents_is_active_index');
            $table->index('date_approve', 'agents_date_approve_index');
        });

        $agentMapping = [];
        foreach ($agents as $agent) {
            $newId = 'AGT' . str_pad($agent->id, 5, '0', STR_PAD_LEFT);
            $agentMapping[$agent->id] = $newId;
            
            DB::table('agent')->insert([
                'id' => $newId,
                'affiliate_id' => $agent->affiliate_id ? ($affiliateMapping[$agent->affiliate_id] ?? null) : null,
                'freelance_id' => $agent->freelance_id ? ($freelanceMapping[$agent->freelance_id] ?? null) : null,
                'kategori_agent' => $agent->kategori_agent,
                'email' => $agent->email,
                'nama_pic' => $agent->nama_pic,
                'no_hp' => $agent->no_hp,
                'nama_travel' => $agent->nama_travel,
                'jenis_travel' => $agent->jenis_travel,
                'total_traveller' => $agent->total_traveller,
                'provinsi' => $agent->provinsi,
                'kabupaten_kota' => $agent->kabupaten_kota,
                'alamat_lengkap' => $agent->alamat_lengkap,
                'link_gmaps' => $agent->link_gmaps,
                'long' => $agent->long,
                'lat' => $agent->lat,
                'link_referal' => $agent->link_referal,
                'date_approve' => $agent->date_approve,
                'logo' => $agent->logo,
                'surat_ppiu' => $agent->surat_ppiu,
                'saldo' => $agent->saldo,
                'saldo_bulan' => $agent->saldo_bulan,
                'saldo_tahun' => $agent->saldo_tahun,
                'status' => $agent->status,
                'is_active' => $agent->is_active,
                'created_at' => $agent->created_at,
                'updated_at' => $agent->updated_at,
            ]);
        }

        // ============================================
        // 4. REKENING TABLE - BAC000001
        // ============================================
        
        Schema::create('rekening', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('agent_id', 10);
            $table->string('nama_rekening');
            $table->string('bank');
            $table->string('nomor_rekening');
            $table->timestamps();

            $table->index('agent_id', 'rekenings_agent_id_index');
        });

        $rekeningMapping = [];
        foreach ($rekenings as $rekening) {
            $newId = 'BAC' . str_pad($rekening->id, 6, '0', STR_PAD_LEFT);
            $rekeningMapping[$rekening->id] = $newId;
            
            DB::table('rekening')->insert([
                'id' => $newId,
                'agent_id' => $agentMapping[$rekening->agent_id] ?? $rekening->agent_id,
                'nama_rekening' => $rekening->nama_rekening,
                'bank' => $rekening->bank,
                'nomor_rekening' => $rekening->nomor_rekening,
                'created_at' => $rekening->created_at,
                'updated_at' => $rekening->updated_at,
            ]);
        }

        // ============================================
        // 5. REWARD TABLE - RWD000001
        // ============================================
        
        Schema::create('reward', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('nama_reward');
            $table->integer('poin')->default(0);
            $table->integer('stok')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        foreach ($rewards as $reward) {
            $newId = 'RWD' . str_pad($reward->id, 6, '0', STR_PAD_LEFT);
            
            DB::table('reward')->insert([
                'id' => $newId,
                'nama_reward' => $reward->nama_reward,
                'poin' => $reward->poin,
                'stok' => $reward->stok,
                'is_active' => $reward->is_active,
                'created_at' => $reward->created_at,
                'updated_at' => $reward->updated_at,
            ]);
        }

        // ============================================
        // 6. WITHDRAW TABLE - WDW000001
        // ============================================
        
        Schema::create('withdraw', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('agent_id', 10);
            $table->string('rekening_id', 10);
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->text('alasan_reject')->nullable();
            $table->string('status')->default('pending');
            $table->date('date_approve')->nullable();
            $table->timestamps();

            $table->index('agent_id', 'withdraw_agent_id_index');
            $table->index('rekening_id', 'withdraw_rekening_id_index');
        });

        foreach ($withdraws as $index => $withdraw) {
            // Extract number from old ID or use index
            $oldNumber = is_numeric($withdraw->id) ? $withdraw->id : ($index + 1);
            $newId = 'WDW' . str_pad($oldNumber, 6, '0', STR_PAD_LEFT);
            
            DB::table('withdraw')->insert([
                'id' => $newId,
                'agent_id' => $agentMapping[$withdraw->agent_id] ?? $withdraw->agent_id,
                'rekening_id' => $rekeningMapping[$withdraw->rekening_id] ?? $withdraw->rekening_id,
                'jumlah' => $withdraw->jumlah,
                'keterangan' => $withdraw->keterangan,
                'alasan_reject' => $withdraw->alasan_reject,
                'status' => $withdraw->status,
                'date_approve' => $withdraw->date_approve,
                'created_at' => $withdraw->created_at,
                'updated_at' => $withdraw->updated_at,
            ]);
        }

        // ============================================
        // 7. UPDATE PEMBAYARAN TABLE - agent_id foreign key
        // ============================================
        
        foreach ($pembayarans as $pembayaran) {
            if (isset($agentMapping[$pembayaran->agent_id])) {
                DB::table('pembayaran')
                    ->where('id', $pembayaran->id)
                    ->update(['agent_id' => $agentMapping[$pembayaran->agent_id]]);
            }
        }

        // Modify pembayaran agent_id column to string
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('agent_id', 10)->change();
        });

        // ============================================
        // 8. UPDATE MARGIN TABLE - foreign keys
        // ============================================
        
        foreach ($margins as $margin) {
            $updates = [];
            if ($margin->agent_id && isset($agentMapping[$margin->agent_id])) {
                $updates['agent_id'] = $agentMapping[$margin->agent_id];
            }
            if ($margin->affiliate_id && isset($affiliateMapping[$margin->affiliate_id])) {
                $updates['affiliate_id'] = $affiliateMapping[$margin->affiliate_id];
            }
            if ($margin->freelance_id && isset($freelanceMapping[$margin->freelance_id])) {
                $updates['freelance_id'] = $freelanceMapping[$margin->freelance_id];
            }
            
            if (!empty($updates)) {
                DB::table('margin')
                    ->where('id', $margin->id)
                    ->update($updates);
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible due to data transformation
        // A manual restore from backup would be required
        throw new \Exception('This migration cannot be reversed. Please restore from backup if needed.');
    }
};
