<?php

namespace Hcode\pagSeguro;

class Config {

    const SANDBOX = true;

    const SANDBOX_EMAIL = "theflash1409@gmail.com";
    const PRODUCTION_EMAIL = "theflash1409@gmail.com";

    const SANDBOX_TOKEN = "DF51F53D746842FD8751AECD382506B5";
    const PRODUCTION_TOKEN = "d3739cb9-3abc-4726-97ee-7ae837c9f15dd7e708864b20927b1690152d416ef87f757a-b337-44e0-8113-acfbf4348064";

    const SANDBOX_SESSIONS = "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions";
    const PRODUCTION_SESSIONS = "https://ws.pagseguro.uol.com.br/v2/sessions";

    public static function getAuthentication():array {

        if (Config::SANDBOX === true){

            return [ "email"=>Config::SANDBOX_EMAIL, "token"=>Config::SANDBOX_TOKEN];
        }else {

            return ["email"=>Config::PRODUCTION_EMAIL, "token"=>Config::PRODUCTION_TOKEN];
        }

    }

    public static function getUrlSessions():string {

        return (Config::SANDBOX === true) ? Config::SANDBOX_SESSIONS : Config::PRODUCTION_SESSIONS;
    }
}



?>