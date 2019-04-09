<?php
/*
  Plugin Name: Random User Generator
  Plugin URI: https://zilpatech.agency/randomusergenerator
  Description: Generate random users
  Version: 1.0
  Author: Zeyar Oo
  Author URI: https://zilpatech.agency
*/

add_action('admin_menu', 'randomusergenerator_menu');
 
function randomusergenerator_menu(){
        add_menu_page( 'Random User Generator', 'Random User Generator', 'manage_options', 'randomusergenerator', 'test_init' );
}
function randomusergenerator_activate() {
 
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/randomusers';
    if (! is_dir($upload_dir)) {
       mkdir( $upload_dir, 0700 );
    }
}
 
register_activation_hook( __FILE__, 'randomusergenerator_activate' );


foreach (range('a', 'z') as $letter) {
    $letters[]=$letter;

}
foreach (range('A', 'Z') as $cap) {
    $capital[]=$cap;

}

foreach (range('0', '4') as $num) {
    $numbers[]=$num;
    
}
foreach (range('5', '9') as $num) {
    $numbers2[]=$num;
    
}

function generate($length){
$loop=$length*2;
global $letters,$numbers,$capital,$numbers2;

                $c=1;
                for($i=0;count($password)<$loop;$i++){
      
                        if($c==1){
                        $rand=mt_rand(0,count($letters)-1);
                        $password[]=$letters[$rand];
                       // unset($letters[$rand]);

                        $c++;
                        }elseif($c==2){
                $rand=mt_rand(0,4);
                        $password[]=$numbers2[$rand];
                       // unset($numbers2[$rand]);

                        $c++;
                       }elseif($c==3){
                        $rand=mt_rand(0,count($capital)-1);
                        $password[]=$capital[$rand];
                       // unset($capital[$rand]);
                        $c++;
                        }
                        elseif($c==4){
                      $rand=mt_rand(0,4);
                        $password[]=$numbers[$rand];
                       // unset($number[$rand]);

                        $c=1;
                        }
                        shuffle($password);
                      $password=array_unique($password);

                }
       $p=str_shuffle(implode("",$password));
       $start=mt_rand(0,$loop-1-$length);
                 $p =  substr($p, $start, $length);
                 return $p;

        }

function random_users($num=70,$userlen=6,$pwlen=6){

$users=array();
for ($i=0;$i<$num;$i++){
  $username=strtolower(generate($userlen));
  $password=generate($pwlen);
  $users[$username]=$password;

}
return $users;
}
$num=$_POST['num'];
$userlen=$_POST['userlen'];
$pwlen=$_POST['pwlen'];

$random_users=random_users($num,$userlen,$pwlen);


function get_user_roles()
    {
        if (!function_exists('get_editable_roles')) {
            require_once ABSPATH . 'wp-admin/includes/user.php';
        }

        $editable_roles = get_editable_roles();

        foreach ($editable_roles as &$role) {
            $role['name'] = translate_user_role($role['name']);
        }

        return $editable_roles;

    }


 $user_roles=get_user_roles();

function test_init(){

global $user_roles;
global $random_users;


?>
<div class="wrap">
<h1>Random User Generator</h1>

<form method="post" action="admin.php?page=randomusergenerator">

    <table class="form-table">
        <tr valign="top">
        <th scope="row">Number of Users</th>
        <td><input type="text" name="num" value="10" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Length of Username</th>
        <td><input type="text" name="userlen" value="6" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Length of Password</th>
        <td><input type="text" name="pwlen" value="6" /></td>
        </tr>

 <tr valign="top">
        <th scope="row">User Role</th>
        <td>   <select name="role">
      <?php foreach ($user_roles as $user_roleslug => $user_role): ?>

      <option value="<?php echo $user_roleslug; ?>"<?php echo $user_roleslug === 'subscriber' ? ' selected' : ''; ?>><?php echo $user_role['name']; ?></option>
      <?php endforeach; ?>


    </select></td>
  </tr>
    </table>
    
    <input type="submit" value="Generate">

</form>
</div>


<?php


if(isset($_POST['num'])){

$website='https://example.com';
$role=$_POST['role'];
{
?>
<h1>Copy the following data as you will not see the passwords again.</h1>
<table class="widefat fixed" cellspacing="0">
    <thead>
    <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Password</th>
            <th>Role</th>
            <th>Email</th>
    </tr>
    </thead>
    <tbody>
<?php
}

include("names.php");

foreach ($random_users as $user=>$pass) {



$k1 = array_rand($fnames);
$k2=array_rand($lnames);
$fname=$fnames[$k1];
$lname=$lnames[$k2];

$userdata = array(
    'user_login'  =>  $user,
    'user_url'    =>  $website,
    'user_pass'   =>  $pass,
    'user_email'     =>  $user.'@example.com',
    'role'      =>  $role,
    'first_name' => $fname,
    'last_name' => $lname,
);

 $user_id = wp_insert_user( $userdata ) ;

// //On success
 if ( ! is_wp_error( $user_id ) ) {
    //echo "User created : ". $user_id;
 }else{
  echo "<span color='red'>Could not generate $ser.</span>";
 }


   echo "<tr><td>$fname $lname</td><td>$user</td><td>$pass</td><td>$role</td><td>$user@example.com</td></tr>";
 
}

{
?>
</tbody>
</table>

<?php

}




}
}





?>
