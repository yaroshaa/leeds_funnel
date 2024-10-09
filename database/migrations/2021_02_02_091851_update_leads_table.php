<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function(Blueprint $table) {
            $table->unsignedBigInteger('deal_id')->nullable()->change();
            $table->string('lead_user_id')->nullable()->after('deal_id');
            $table->string('channel')->after('state')->default('pipedrive');
            $table->json('data')->after('channel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function(Blueprint $table) {
            $table->dropColumn(['lead_user_id', 'channel', 'data']);
        });
    }
}
