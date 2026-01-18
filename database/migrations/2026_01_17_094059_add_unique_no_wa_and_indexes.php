<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel Affiliates
        Schema::table('affiliates', function (Blueprint $table) {
            // Unique constraint untuk no_wa (hanya jika belum ada)
            if (!$this->hasUniqueConstraint('affiliates', 'no_wa')) {
                $table->unique('no_wa');
            }
            
            // Indexes untuk optimasi query
            if (!$this->hasIndex('affiliates', 'affiliates_email_index')) {
                $table->index('email');
            }
            if (!$this->hasIndex('affiliates', 'affiliates_provinsi_index')) {
                $table->index('provinsi');
            }
            if (!$this->hasIndex('affiliates', 'affiliates_kab_kota_index')) {
                $table->index('kab_kota');
            }
            if (!$this->hasIndex('affiliates', 'affiliates_is_active_index')) {
                $table->index('is_active');
            }
            if (!$this->hasIndex('affiliates', 'affiliates_date_register_index')) {
                $table->index('date_register');
            }
        });

        // Tabel Freelances
        Schema::table('freelances', function (Blueprint $table) {
            // Unique constraint untuk no_wa (hanya jika belum ada)
            if (!$this->hasUniqueConstraint('freelances', 'no_wa')) {
                $table->unique('no_wa');
            }
            
            // Indexes untuk optimasi query
            if (!$this->hasIndex('freelances', 'freelances_email_index')) {
                $table->index('email');
            }
            if (!$this->hasIndex('freelances', 'freelances_provinsi_index')) {
                $table->index('provinsi');
            }
            if (!$this->hasIndex('freelances', 'freelances_kab_kota_index')) {
                $table->index('kab_kota');
            }
            if (!$this->hasIndex('freelances', 'freelances_is_active_index')) {
                $table->index('is_active');
            }
            if (!$this->hasIndex('freelances', 'freelances_date_register_index')) {
                $table->index('date_register');
            }
        });

        // Tabel Agents
        Schema::table('agents', function (Blueprint $table) {
            // Unique constraint untuk no_hp (hanya jika belum ada)
            if (!$this->hasUniqueConstraint('agents', 'no_hp')) {
                $table->unique('no_hp');
            }
            
            // Indexes untuk optimasi query
            if (!$this->hasIndex('agents', 'agents_email_index')) {
                $table->index('email');
            }
            if (!$this->hasIndex('agents', 'agents_affiliate_id_index')) {
                $table->index('affiliate_id');
            }
            if (!$this->hasIndex('agents', 'agents_freelance_id_index')) {
                $table->index('freelance_id');
            }
            if (!$this->hasIndex('agents', 'agents_kategori_agent_index')) {
                $table->index('kategori_agent');
            }
            if (!$this->hasIndex('agents', 'agents_provinsi_index')) {
                $table->index('provinsi');
            }
            if (!$this->hasIndex('agents', 'agents_kabupaten_kota_index')) {
                $table->index('kabupaten_kota');
            }
            if (!$this->hasIndex('agents', 'agents_is_active_index')) {
                $table->index('is_active');
            }
            if (!$this->hasIndex('agents', 'agents_status_index')) {
                $table->index('status');
            }
            if (!$this->hasIndex('agents', 'agents_date_approve_index')) {
                $table->index('date_approve');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tabel Affiliates
        Schema::table('affiliates', function (Blueprint $table) {
            if ($this->hasUniqueConstraint('affiliates', 'no_wa')) {
                $table->dropUnique(['no_wa']);
            }
            if ($this->hasIndex('affiliates', 'affiliates_email_index')) {
                $table->dropIndex(['email']);
            }
            if ($this->hasIndex('affiliates', 'affiliates_provinsi_index')) {
                $table->dropIndex(['provinsi']);
            }
            if ($this->hasIndex('affiliates', 'affiliates_kab_kota_index')) {
                $table->dropIndex(['kab_kota']);
            }
            if ($this->hasIndex('affiliates', 'affiliates_is_active_index')) {
                $table->dropIndex(['is_active']);
            }
            if ($this->hasIndex('affiliates', 'affiliates_date_register_index')) {
                $table->dropIndex(['date_register']);
            }
        });

        // Tabel Freelances
        Schema::table('freelances', function (Blueprint $table) {
            if ($this->hasUniqueConstraint('freelances', 'no_wa')) {
                $table->dropUnique(['no_wa']);
            }
            if ($this->hasIndex('freelances', 'freelances_email_index')) {
                $table->dropIndex(['email']);
            }
            if ($this->hasIndex('freelances', 'freelances_provinsi_index')) {
                $table->dropIndex(['provinsi']);
            }
            if ($this->hasIndex('freelances', 'freelances_kab_kota_index')) {
                $table->dropIndex(['kab_kota']);
            }
            if ($this->hasIndex('freelances', 'freelances_is_active_index')) {
                $table->dropIndex(['is_active']);
            }
            if ($this->hasIndex('freelances', 'freelances_date_register_index')) {
                $table->dropIndex(['date_register']);
            }
        });

        // Tabel Agents
        Schema::table('agents', function (Blueprint $table) {
            if ($this->hasUniqueConstraint('agents', 'no_hp')) {
                $table->dropUnique(['no_hp']);
            }
            if ($this->hasIndex('agents', 'agents_email_index')) {
                $table->dropIndex(['email']);
            }
            if ($this->hasIndex('agents', 'agents_affiliate_id_index')) {
                $table->dropIndex(['affiliate_id']);
            }
            if ($this->hasIndex('agents', 'agents_freelance_id_index')) {
                $table->dropIndex(['freelance_id']);
            }
            if ($this->hasIndex('agents', 'agents_kategori_agent_index')) {
                $table->dropIndex(['kategori_agent']);
            }
            if ($this->hasIndex('agents', 'agents_provinsi_index')) {
                $table->dropIndex(['provinsi']);
            }
            if ($this->hasIndex('agents', 'agents_kabupaten_kota_index')) {
                $table->dropIndex(['kabupaten_kota']);
            }
            if ($this->hasIndex('agents', 'agents_is_active_index')) {
                $table->dropIndex(['is_active']);
            }
            if ($this->hasIndex('agents', 'agents_status_index')) {
                $table->dropIndex(['status']);
            }
            if ($this->hasIndex('agents', 'agents_date_approve_index')) {
                $table->dropIndex(['date_approve']);
            }
        });
    }

    /**
     * Check if a unique constraint exists
     */
    private function hasUniqueConstraint($table, $column)
    {
        $connection = DB::connection();

        if (method_exists($connection, 'getDoctrineSchemaManager')) {
            $schemaManager = $connection->getDoctrineSchemaManager();
            $indexes = $schemaManager->listTableIndexes($table);

            foreach ($indexes as $index) {
                if (!$index->isUnique()) {
                    continue;
                }

                if (in_array($column, $index->getColumns(), true)) {
                    return true;
                }
            }

            return false;
        }

        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Column_name = ? AND Non_unique = 0", [$column]);

        return count($indexes) > 0;
    }

    /**
     * Check if an index exists
     */
    private function hasIndex($table, $indexName)
    {
        $connection = DB::connection();

        if (method_exists($connection, 'getDoctrineSchemaManager')) {
            $schemaManager = $connection->getDoctrineSchemaManager();
            $indexes = $schemaManager->listTableIndexes($table);
            $target = strtolower((string) $indexName);

            foreach ($indexes as $name => $index) {
                if (strtolower((string) $name) === $target) {
                    return true;
                }
            }

            return false;
        }

        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);

        return count($indexes) > 0;
    }
};
