<?php

namespace Phirehose {

    /**
     * Overrides default functionality of gethostbynamel 
     * 
     * @return array
     * @see gethostbynamel()
     * @link http://www.php.net/manual/en/function.gethostbynamel.php
     */
    function gethostbynamel($hostname){ 
        return array("127.0.0.1"); 
    }


}