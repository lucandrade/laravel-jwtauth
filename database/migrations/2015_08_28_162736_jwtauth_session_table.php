<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Lucandrade\JwtAuth\JwtAuthConfig;

class JwtauthSessionTable extends Migration
{

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return array $config
     */
    protected function getConfig()
    {
        return JwtAuthConfig::get();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $config = $this->getConfig();
        Schema::create($config["tbl_session_name"], function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->boolean('active')->default(true);
            $table->integer('created_at');
            $table->integer('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $config = $this->getConfig();
        Schema::drop($config["tbl_session_name"]);
    }
}
