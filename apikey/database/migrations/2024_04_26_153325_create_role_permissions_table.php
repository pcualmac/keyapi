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
        Schema::create('app_role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id');
            $table->foreignId('permission_id');
            $table->foreignId('application_id')->constrained();
            $table->primary(['role_id', 'permission_id', 'application_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_role_permission');
    }
};
