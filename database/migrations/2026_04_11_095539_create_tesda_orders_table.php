<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tesda_orders', function (Blueprint $table) {
            $table->id();
            $table->string('program_code');
            $table->foreign('program_code')
                ->references('program_code')
                ->on('programs')
                ->onDelete('cascade');
            $table->text('subject');
            $table->string('series')->default(DB::raw('YEAR(CURRENT_DATE)'));
            $table->string('date_issued');
            $table->string('effectivity')->default('As Indicated');
            $table->string('supersedes')->nullable();
            $table->text('body');
            $table->boolean('with_employees')->default(false);
            $table->boolean('with_batch')->default(false);
            $table->text('closure');
            $table->text('signatory_name');
            $table->text('signatory_position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tesda_orders');
    }
};
