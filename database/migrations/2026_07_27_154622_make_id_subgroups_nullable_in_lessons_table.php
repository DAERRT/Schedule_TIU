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
        DB::statement('PRAGMA foreign_keys = OFF');

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['id_subgroups']);
            $table->renameColumn('id_subgroups', 'tmp_id_subgroups');
            $table->integer('id_subgroups')->nullable()->after('id_years');
            $table->foreign('id_subgroups')->references('id_subgroups')->on('subgroups')->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('tmp_id_subgroups');
        });

        DB::statement('PRAGMA foreign_keys = ON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['id_subgroups']);
            $table->renameColumn('id_subgroups', 'tmp_id_subgroups');
            $table->foreignId('id_subgroups')->constrained('subgroups', 'id_subgroups')->onDelete('cascade');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('tmp_id_subgroups');
        });

        DB::statement('PRAGMA foreign_keys = ON');
    }
};
