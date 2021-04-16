# Jideola-Project

Once the repo is been Clone and migrated to localhost, it has to be kept inside a folder called projects in your localhost...

This project is based on Register, Login & Logout endpoints
Also you will need to download the sql database to test.

To test Register and login data, you will need to json encode array data and bin2hex.

For example login data: 

$data = json_encode(array(
    'username'=>'Davido',
    'password'=>'Ry634373!!'
)); <br/>
$data = bin2hex($data);



For example register data: 

$data = json_encode(array(
    'username'=>'Davido',
    'surname'=>'Olaleye',
    'firstname'=>'Olajide',
    'lastname'=>'Sunday',
    'email'=>'davido@yahoo.com',
    'phone'=>'+2348085100961',
    'address'=>'33A, Ikeja Lagos',
    'password'=>'Ry634373!!',
    'confirmPassword'=>'Ry634373!!',
    'terms'=>true
));
$data = bin2hex($data);


Bin2hex data will be passed to req : 7b22757365726e616d65223a2244617669646f222c2270617373776f7264223a225177657274793132333435212121227d

For logout endpoit, it requires hearder to be passed as x-progress then value will be the token sent from login.
For example : x-progress : 4f6c7155b49a55c39a0e36a537f64d84



Endpoints : 

http://localhost/projects/index.php/api/v1/register <br/>
http://localhost/projects/index.php/api/v1/login <br/>
http://localhost/projects/index.php/api/v1/logout
