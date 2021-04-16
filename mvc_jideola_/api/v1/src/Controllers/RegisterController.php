<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class RegisterController {
    use Request;

    public function register(){
        global $jideola;


        /* Validate */
        $checkArray = ['username', 'firstname', 'surname', 'lastname', 'email', 'phone','address','password','confirmPassword','terms'];
        foreach ($checkArray as $check){
            if(isset($this->request[$check])) {
                if (empty($this->request[$check])) $jideola->feedback(false, 'REG_000', ['Oops! '.$check.' was not provided.']);
            }else $jideola->feedback(false, 'REG_001', ['Oops! '.$check.' was not set.']);
        }

        $email = $this->request['email'];
        $phone = $this->request['phone'];
        $username = $this->request['username'];
        $pass = $this->request['password'];
        $confirm_pass = $this->request['confirmPassword'];
        $surname = $this->request['surname'];
        $firstname = $this->request['firstname'];
        $lastname = $this->request['lastname'];
        $address = $this->request['address'];

        /* Checks if username, email & phone already exist */
        $qry = "SELECT SUM(IF(email like '$email',1,0)) as email, SUM(IF(username like '$username',1,0)) as username,SUM(IF(phone like '$phone',1,0)) as phone FROM #__users";
	    $used = $jideola->dbrow($qry);

        if($used['username'] > 0) $jideola->feedback(false, 'REG_002', ['Oops! Username already exist.']);
        if($used['email'] > 0) $jideola->feedback(false, 'REG_003', ['Oops! Email already exist.']);
        if($used['phone'] > 0) $jideola->feedback(false, 'REG_004', ['Oops! Phone already exist.']);
      

        if (!empty($used['email'])) {
            $jideola->feedback(false, 'REG_005', ['Email already exist']);
        }
        if (!empty($used['phone'])) {
            $app->feedback(false, 'REG_006', ['Phone number already exist']);
        }
        if (!empty($used['username'])) {
            $jideola->feedback(false, 'REG_007', ['Username already exist']);
        }

        if($pass != $confirm_pass) $jideola->feedback(false, 'REG_009', ['Password do not match']);
        /* Check if password is strong */
        if (strlen($pass) < 8)  $jideola->feedback(false, 'REG_100', ['Password should be at least 8 characters']);
        if (!$jideola->passwordChecks($pass))  $jideola->feedback(false, 'REG_101', ['Password is not strong']);

        $names = $surname.' '.$firstname.' '.$lastname;
        $pass = password_hash($pass, PASSWORD_DEFAULT);


        /* Insert query */
       $query = $jideola->dbcountchanges("insert into #__users (username,names,email,phone,address,password) values('$username','$names','$email','$phone','$address','$pass')");
       if($query > 0) $jideola->feedback(true, 'OK', ['Registration successful']);
       else $jideola->feedback(true, 'REG_102', ['Unable to register']);
    }
}