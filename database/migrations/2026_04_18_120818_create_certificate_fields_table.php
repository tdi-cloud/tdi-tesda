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
        Schema::create('certificate_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained()->cascadeOnDelete();

            $table->string('field_name'); // name, program, date
            $table->integer('x'); // position
            $table->integer('y');
            $table->integer('font_size')->default(20);
            $table->string('font_weight')->default('normal');
            $table->string('text_align')->default('center');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_fields');
    }
};
