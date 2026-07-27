<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id('id_groups');
            $table->foreignId('id_subgroups')->nullable()->constrained('subgroups', 'id_subgroups')->onDelete('cascade');
            $table->foreignId('id_worksheets')->constrained('worksheets', 'id_worksheets')->onDelete('cascade');
            $table->string('name');
            $table->integer('stud_num')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};