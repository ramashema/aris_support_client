<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttendedCollumnSupportRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_requests', function (Blueprint $table){
           $table->boolean('attended')->default(false);
           $table->dateTime('attended_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support_requests', function (Blueprint $table) {
           $table->dropColumn('attended');
           $table->dropColumn('attended_at');
        });
    }
}
