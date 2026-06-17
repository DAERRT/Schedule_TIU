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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id('id_lessons');
            $table->foreignId('id_groups')->constrained('groups')->onDelete('cascade');
            $table->foreignId('id_years')->constrained('years')->onDelete('cascade');
            $table->foreignId('id_subgroups')->constrained('sub_groups')->onDelete('cascade');
            $table->foreignId('id_rooms')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('id_teachers')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('id_subjects')->constrained('subjects')->onDelete('cascade');
            $table->enum('week_parity', ['odd', 'even', 'both']);
            $table->enum('week_day',[1,2,3,4,5,6,7]);
            $table->integer('order_number');
            $table->boolean('isZaoch')->default(false);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
