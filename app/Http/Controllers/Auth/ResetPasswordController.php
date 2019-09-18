<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNeuPage2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neu_page2s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sys_num');
            $table->string('rec');
            $table->string('hcp');
            $table->string('pmh');
            $table->string('dh');
            $table->string('sh');
            $table->string('habit');
            $table->string('accomodation');
            $table->string('stairs');
            $table->string('handrails');
            $table->string('wc');
            $table->string('child');
            $table->string('pregnancy');
            $table->string('wives');
            $table->string('mobility');
