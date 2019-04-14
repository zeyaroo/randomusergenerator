<?php
/*
  Plugin Name: Random User Generator
  Plugin URI: https://zilpatech.agency/randomusergenerator
  Description: Generate random users
  Version: 1.0
  Author: Zeyar Oo
  Author URI: https://zilpatech.agency
*/

namespace zptrandomusergenerator;

add_action( '', __NAMESPACE__ . '\\randomusergenerator_activate' );
add_action( '', __NAMESPACE__ . '\\randomusergenerator_generate' );
add_action( '', __NAMESPACE__ . '\\randomusergenerator_random_users' );
add_action( '', __NAMESPACE__ . '\\randomusergenerator_get_user_roles' );
add_action( '', __NAMESPACE__ . '\\randomusergenerator_init' );
add_action('admin_menu', __NAMESPACE__ . '\\randomusergenerator_menu');

function randomusergenerator_menu(){
        add_menu_page( 'Random User Generator', 'Random User Generator', 'manage_options', 'randomusergenerator', __NAMESPACE__ . '\\randomusergenerator_init' );
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
    $randomusergenerator_letters[]=$letter;

}
foreach (range('A', 'Z') as $cap) {
    $randomusergenerator_capital[]=$cap;

}

foreach (range('0', '4') as $num) {
    $randomusergenerator_numbers[]=$num;
    
}
foreach (range('5', '9') as $num) {
    $randomusergenerator_numbers2[]=$num;
    
}

function randomusergenerator_generate($length){
$loop=$length*2;
global $randomusergenerator_letters,$randomusergenerator_numbers,$randomusergenerator_capital,$randomusergenerator_numbers2;


                $c=1;
                for($i=0;count($password)<$loop;$i++){
      
                        if($c==1){
                        $rand=mt_rand(0,count($letters)-1);
                        $password[]=$randomusergenerator_letters[$rand];
                       // unset($letters[$rand]);

                        $c++;
                        }elseif($c==2){
                $rand=mt_rand(0,4);
                        $password[]=$randomusergenerator_numbers2[$rand];
                       // unset($numbers2[$rand]);

                        $c++;
                       }elseif($c==3){
                        $rand=mt_rand(0,count($capital)-1);
                        $password[]=$randomusergenerator_capital[$rand];
                       // unset($capital[$rand]);
                        $c++;
                        }
                        elseif($c==4){
                      $rand=mt_rand(0,4);
                        $password[]=$randomusergenerator_numbers[$rand];
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

function randomusergenerator_random_users($num=70,$userlen=6,$pwlen=6){

$users=array();
for ($i=0;$i<$num;$i++){

  $username=strtolower(randomusergenerator_generate($userlen));
  $password=randomusergenerator_generate($pwlen);
  $users[$username]=$password;

}
return $users;
}



  function randomusergenerator_get_user_roles()
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



function randomusergenerator_init(){
$user_roles=randomusergenerator_get_user_roles();

?>
<div class="wrap">
<h1>Random User Generator</h1>

<form method="post" action="admin.php?page=randomusergenerator">

    <table class="form-table">
        <tr valign="top">
        <th scope="row">Number of Users</th>
        <td><input type="text" name="randomusergenerator_num" value="10" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Length of Username</th>
        <td><input type="text" name="randomusergenerator_userlen" value="6" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Length of Password</th>
        <td><input type="text" name="randomusergenerator_pwlen" value="6" /></td>
        </tr>

 <tr valign="top">
        <th scope="row">User Role</th>
        <td>   <select name="randomusergenerator_role">
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


if(isset($_POST['randomusergenerator_num'])){

$randomusergenerator_num=ctype_digit($_POST['randomusergenerator_num']) ? $_POST['randomusergenerator_num']:0;
$randomusergenerator_userlen=ctype_digit($_POST['randomusergenerator_userlen']) ? $_POST['randomusergenerator_userlen'] : 0;
$randomusergenerator_pwlen=ctype_digit($_POST['randomusergenerator_pwlen']) ? $_POST['randomusergenerator_pwlen'] : 0;
$randomusergenerator_role=sanitize_text_field($_POST['randomusergenerator_role']);

if($randomusergenerator_num==0 || $randomusergenerator_userlen==0 or $randomusergenerator_pwlen==0){
  echo "<h2 style='color:red;font-weight:800;margin-top:10px;'>&#9888; Please enter valid input.</h2>";
}else{
$randomusergenerator_random_users=randomusergenerator_random_users($randomusergenerator_num,$randomusergenerator_userlen,$randomusergenerator_pwlen);



{
?>
<h2>Copy the following data as you will not see the passwords again.</h2>
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

foreach ($randomusergenerator_random_users as $user=>$pass) {



$randomusergenerator_k1 = array_rand($randomusergenerator_fnames);
$randomusergenerator_k2=array_rand($randomusergenerator_lnames);
$randomusergenerator_fname=$randomusergenerator_fnames[$randomusergenerator_k1];
$randomusergenerator_lname=$randomusergenerator_lnames[$randomusergenerator_k2];
$randomusergenerator_website='https://example.com';
$randomusergenerator_role=esc_html($randomusergenerator_role);

$randomusergenerator_userdata = array(
    'user_login'  =>  $user,
    'user_url'    =>  $website,
    'user_pass'   =>  $pass,
    'user_email'     =>  $user.'@example.com',
    'role'      =>  $role,
    'first_name' => $randomusergenerator_fname,
    'last_name' => $randomusergenerator_lname,
);

 $randomusergenerator_user_id = wp_insert_user( $randomusergenerator_userdata ) ;

// //On success
 if ( ! is_wp_error( $randomusergenerator_user_id ) ) {
    //echo "User created : ". $user_id;
 }else{
  echo "<span color='red'>Could not generate $ser.</span>";
 }


   echo "<tr><td>$randomusergenerator_fname $randomusergenerator_lname</td><td>$user</td><td>$pass</td><td>$randomusergenerator_role</td><td>$user@example.com</td></tr>";
 
}

{
?>
</tbody>
</table>

<?php

}


}

}
}




?>
