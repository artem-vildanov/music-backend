<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration
{
    protected $connection = 'mongodb';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artists', function (Blueprint $collection) {
            $collection->index("name");
            $collection->index("userId");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
