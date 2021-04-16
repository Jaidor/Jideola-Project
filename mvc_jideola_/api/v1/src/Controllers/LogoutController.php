<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class LogoutController {
    use Request;

    public function logout(){
        global $jideola;

        /* Logout and destroy session */
        $jideola->destroy($jideola->getSessionID());
        $jideola->feedback(true, 'LOGOUT_OK', ['Logged out successfully']);
    }
}