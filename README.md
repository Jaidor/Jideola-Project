# Jideola-Project

Once the repo is been Clone and migrated to localhost, it has to be kept inside a folder called projects in your localhost...

This project is based on Register, Login & Logout. endpoints
Also you will need to download the sql database to test.

To test Register and login data, you will need to json encode array data and bin2hex.

Example : 

$data = json_encode(array(
    'username'=>'Davido',
    'password'=>'Qwerty12345!!!'
));
$data = bin2hex($data);


Bin2hex data will be passed to req : 7b22757365726e616d65223a2244617669646f222c2270617373776f7264223a225177657274793132333435212121227d



Endpoints : 

http://localhost/projects/index.php/api/v1/register <br/>
http://localhost/projects/index.php/api/v1/login <br/>
http://localhost/projects/index.php/api/v1/logout
