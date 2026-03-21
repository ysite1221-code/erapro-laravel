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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('title')->nullable();
            $table->text('story')->nullable();
            $table->text('philosophy')->nullable();
            $table->string('profile_img')->nullable();
            $table->string('area')->nullable();
            $table->string('area_detail')->nullable();
            $table->string('tags')->nullable();
            $table->float('avg_rating')->default(0);
            $table->string('diagnosis_type')->nullable();
            $table->integer('diagnosis_score')->nullable()->default(50);
            $table->integer('type_id')->default(0);
            $table->string('affiliation_url')->nullable();
            $table->tinyInteger('verification_status')->default(0); // 0:未提出, 1:審査中, 2:承認済, 9:否認
            $table->integer('plan_id')->default(0);                 // 0:無料, 1:課金プラン
            $table->string('stripe_customer_id')->nullable();
            $table->string('subscription_status')->default('free');
            $table->boolean('email_notification_flg')->default(1);
            $table->boolean('life_flg')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
