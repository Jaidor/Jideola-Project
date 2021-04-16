<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class LoginController {
    use Request;

    public function login(){
        global $jideola;

        /* Validate */
        $checkArray = ['username', 'password'];
        foreach ($checkArray as $check){
            if(isset($this->request[$check])) {
                if (empty($this->request[$check])) $jideola->feedback(false, 'LOG_000', ['Oops! '.$check.' was not provided.']);
            }else $jideola->feedback(false, 'LOG_001', ['Oops! '.$check.' was not set.']);
        }

        $username = $this->request['username'];
        $password = $this->request['password'];

        /* Checks if login user exist */
        $user = $jideola->dbrow("select * from #__users where username = '$username'");
        $password_match = password_verify($password, $user['password']);
        $session_token = md5($jideola->unique() . rand(0, 100));

        /* Clear old sessions */
        $query="delete from #__sessions where session_user_id = '{$user['id']}' and session_expires <= '".time()."'  ";
        $jideola->dbquery($query);

        if($user){
            if ($user['username'] && !$password_match) {

                $time = 30 * 60; /* Block for 30 minutes */
                $nextlogin = time() + $time;
                $query = "update #__users set login_fail = login_fail+1, blocked_till = '$nextlogin' where id = '{$user['id']}' ";
                $jideola->dbquery($query);
                $jideola->feedback(false, 'LOG_002', ['Oops! Invalid password entered']);
            }

            /* Start session */
            $time = 250 * 60; /* 250 minutes makes 1hr */
            $expires = time() + $time;
            $query = "insert into #__sessions(session_id, session_user_id, session_expires, session_start_time, session_ip)
                    values('$session_token','{$user['id']}','$expires',now(),'{$jideola->ip}')";
            $jideola->dbquery($query);

            /* Update users table */
            $query = "update #__users set last_login = latest_login, latest_login = now(), login_count = login_count+1, login_fail=0 where id = '{$user['id']}'";
            $jideola->dbquery($query);

            $jideola->feedback(true, 'LOGIN_OK', ['Login successful'], ['username' => $user['username'], 'names' => $user['names'],
            'email' => $user['email'], 'session_token' => $session_token]);

        }else $jideola->feedback(false, 'LOG_003', ['Oops! Invalid username entered']);
    }
}