<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaillistCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maillist_customer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('m_id')->unsigned();
            $table->integer('c_id')->unsigned();
            $table->timestamps();
            $table->foreign('c_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('m_id')->references('id')->on('maillist')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maillist_customer');
    }
}
