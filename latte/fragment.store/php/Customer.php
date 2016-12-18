<?php
/**
 * Stub generated by xlatte
 */
class Customer extends customerBase{

    /**
     * @remote
     * @param string $text
     * @return Customer[]
     */
    public static function search($text){
        return DL::arrayOf('Customer', "
            SELECT #COLUMNS
            FROM customer
            WHERE (firstname LIKE '%$text%' OR lastname LIKE '%$text%')
            ORDER BY firstname, lastname
        ");
    }

}