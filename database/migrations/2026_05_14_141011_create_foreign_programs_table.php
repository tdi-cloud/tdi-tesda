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
        Schema::create('foreign_programs', function (Blueprint $table) {
            $table->id();

            $table->string('program_title');

            $table->string('modality');

            /*
            |--------------------------------------------------------------------------
            | General Program Dates
            |--------------------------------------------------------------------------
            */

            $table->date('program_start')->nullable();
            $table->date('program_end')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Hybrid Dates
            |--------------------------------------------------------------------------
            */

            $table->date('online_start')->nullable();
            $table->date('online_end')->nullable();

            $table->date('inperson_start')->nullable();
            $table->date('inperson_end')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Other Details
            |--------------------------------------------------------------------------
            */

            $table->integer('slots')->nullable();

            $table->string('organizing_sponsor')->nullable();

            $table->string('country')->nullable();

            $table->string('status_of_program')->nullable();

            $table->date('submission_date')->nullable();

            $table->date('interview_date')->nullable();

            $table->text('invited_agencies')->nullable();

            $table->string('attached_agency')->nullable();

            $table->date('embassy_deadline')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foreign_programs');
    }
};
