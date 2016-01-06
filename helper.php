<?php

class WebshopContext {
    public static function getLanguage() {
        if(isset($_COOKIE[$language])) {
            return $_COOKIE[$language];
        } 
        else {
            return 'DE';
        }
    }
}

?>