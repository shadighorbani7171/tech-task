<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for adding additional user fields required by the business domain.
 * This follows DDD principles by implementing the User aggregate requirements.
 */
return new class extends Migration
{
    /**
     * Add the required fields to users table based on domain requirements:
     * - surname: User's family name
     * - phone: Contact number
     * - country: User's country (will be selected from predefined list)
     * - gender: User's gender (enum: male, female, other)
     * - profile_picture: Optional profile image
     * - introduction: Optional user bio/introduction
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Personal Information
            $table->string('surname')->comment('User\'s family name');
            $table->string('phone')->comment('User\'s contact number');
            
            // Geographic Information
            $table->string('country')->comment('User\'s country from predefined list');
            
            // Demographics
            $table->enum('gender', ['male', 'female', 'other'])->comment('User\'s gender');
            
            // Optional Profile Data
            $table->string('profile_picture')->nullable()->comment('Path to user\'s profile picture');
            $table->text('introduction')->nullable()->comment('User\'s bio or introduction');
        });
    }

    /**
     * Reverse the migrations by removing the added fields.
     * This maintains database integrity when rolling back.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'phone',
                'country',
                'gender',
                'profile_picture',
                'introduction'
            ]);
        });
    }
};
