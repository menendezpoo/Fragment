<?php
/**
 * Stub generated by xlatte
 */
class Transaction extends transactionBase{

    const MODE_CHARGED_ON_DEMAND = 1;

    const MODE_REST_WILL_CALL = 2;


    /**
     * Override.
     */
    public function onInserting(){
        $this->created = DL::dateTime();
    }
}