<?php
/**
 * Stub generated by xlatte
 */
class Cart extends cartBase{

    static $STATUS_UNKNOWN = 0;

    static $STATUS_OPEN = 1;

    static $STATUS_CHECKOUT = 2;

    static $STATUS_PAID = 3;

    static $STATUS_DELIVERED = 4;

    /**
     * Gets the cart by guid
     * @param string $guid
     * @return Cart
     */
    public static function byGuid($guid){
        return DL::oneOf('Cart', "
            SELECT #COLUMNS
            FROM cart
            WHERE guid = '$guid'
        ");
    }

    /**
     * Adds the specified item to the cart in session
     * @remote
     * @param string $guid
     * @param int $amount
     * @return CartPage
     * @throws SecurityException
     */
    public static function addGuid($guid, $amount = 1){
        $cart = self::getSessionCart();
        $page = Page::byUrlQ($guid);

        if($cart->status != self::$STATUS_OPEN)
            throw new SecurityException("Cart is not open");

        $cp = self::getGuidItem($guid);

        if(!$cp){
            $cp = new CartPage();
            $cp->idcart = $cart->idcart;
            $cp->idpage = $page->idpage;
            $cp->amount = $amount;
            $cp->ua = $_SERVER['HTTP_USER_AGENT'];
            $cp->ip = $_SERVER['REMOTE_ADDR'];
        }else{
            $cp->amount++;
        }

        //TODO: Calculate price

        $cp->save();

        return $cp;
    }

    /**
     * Changes the amount of the item in the cart
     * @remote
     * @param string $guid
     * @param number $amount
     * @throws SecurityException
     */
    public function changeAmountGuid($guid, $amount){

        $cart = self::getSessionCart();

        if($cart->status != self::$STATUS_OPEN)
            throw new SecurityException("Cart is not open");

        $cp = self::getGuidItem($guid);
        $cp->amount = $amount;
        $cp->update();

    }

    /**
     * Creates the session cart
     */
    public function createSessionCart($guid = ''){
        $c = new Cart();
        $c->ua = $_SERVER['HTTP_USER_AGENT'];
        $c->ip = $_SERVER['REMOTE_ADDR'];
        $c->guid = $guid ? $guid : Page::generateGUID('cart');
        $c->status = self::$STATUS_OPEN;
        $c->insert();

        setcookie('fragment_cart', $c->guid, time() + 3600 * 24 * 365, '/');
    }

    /**
     * Gets the Guid item
     * @remote
     * @param string $guid
     * @return CartPage
     */
    public static function getGuidItem($guid){
        $cart = self::getSessionCart();
        $page = Page::byUrlQ($guid);


        return CartPage::byCartAndPage($cart->idcart, $page->idpage);


    }

    /**
     * Gets the session cart
     */
    public function getSessionCart(){

        $cart = null;

        if(isset($_COOKIE['fragment_cart'])){
            $cart = self::byGuid($_COOKIE['fragment_cart']);

            if(!$cart)
                $cart = self::createSessionCart($_COOKIE['fragment_cart']);

        }else{
            $cart = self::createSessionCart();
        }

        return $cart;
    }

    /**
     * Removes the item from the cart
     * @remote
     * @param string $guid
     * @throws SecurityException
     */
    public function removeGuid($guid){
        $cart = self::getSessionCart();

        if($cart->status != self::$STATUS_OPEN)
            throw new SecurityException("Cart is not open");

        $cp = self::getGuidItem($guid);
        $cp->delete();
    }

    /**
     * Override
     */
    public function onInserting()
    {
        $this->created = DL::dateTime();
    }
}