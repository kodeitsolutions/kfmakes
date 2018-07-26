<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDecimalsCostProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->decimal('cost_KFD',9,2)->default(0)->change();
            $table->decimal('cost_EKF',9,2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->decimal('cost_KFD',9,3)->default(0);
            $table->decimal('cost_EKF',9,3)->default(0);
        });
    }
}
