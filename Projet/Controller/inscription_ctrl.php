<?php 
session_start();

require_once dirname(__FILE__) . '/../Models/user.php';
$title = 'S\'incrire';

//Emplacement se connecter
if (isset($_GET['logout'])) {
    // vide le tableau session
    $_SESSION['user'] = [];
    // vide la variable session
    unset($_SESSION['user']);
    // détruit la session
    session_destroy();
}

if (isset($_POST['connexion']) && !empty($_POST['pseudo']) && !empty($_POST['password'])) {
    $_SESSION['user'] = ['auth' => true, 'pseudo' => $_POST['pseudo']];
};


$civility = $lastname =$email= $firstname =$password=$pseudo=$password=$birthdate= $verifPassword = "";
$errors = [];
$isSubmitted = false;
$regexNames = '/^[a-zéèîïêëç]+((?:\-|\s)[a-zéèéîïêëç]+)?$/i';
$regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/';
$regexTelephone = '/^(?:\+33|0033|0)[1-79]((?:([\-\/\s\.])?[0-9]){2}){4}$/';
$regexBirthday='/^((?:19|20)[0-9]{2})-((?:0[1-9])|(?:1[0-2]))-((?:0[1-9])|(?:1[0-9])|(?:2[0-9])|(?:3[01]))$/';
$post=[];
$photo = $_COOKIE['picture'] ?? 'avatar.jpg';



//validation formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['inscription'])) {
    $isSubmitted = true;

    $civility = trim(filter_input(INPUT_POST, 'civility', FILTER_SANITIZE_NUMBER_INT));
    $filtercivility = filter_var($civility, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 2]]);
    if (empty($civility)) {
        $errors['civility'] = 'Veuillez sélectionner un genre!';

    } else if (!$filtercivility) { // équivaut écrire $filterCivility == 'false'
        $errors['civility'] = 'Veuillez renseigner le champs!';
    }
    array_push($post,$civility);
    // valider 

    $lastname = trim(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING));
    // vérifier la validité de la valeur
    if (empty($lastname)) {
        $errors['lastname'] = 'Veuillez renseigner votre nom';
    } else if (!preg_match($regexNames, $lastname)) {
        $errors['lastname'] = 'La valeur renseignée ne correspond pas au format attendu';
    }
    array_push($post,$lastname);

    // validation du firstname

    $firstname = trim(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING));
    // vérifier la validité de la valeur
    if (empty($firstname)) {
        $errors['firstname'] = 'Veuillez renseigner votre prenom';
    } else if (!preg_match($regexNames, $firstname)) {
        $errors['firstname'] = 'La valeur renseignée ne correspond pas au format attendu';
    }
    array_push($post,$firstname);

    $pseudo = trim(filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING));
    // vérifier la validité de la valeur
    if (empty($pseudo)) {
        $errors['pseudo'] = 'Veuillez renseigner votre pseudo';
    } else if (!preg_match($regexNames, $pseudo)) {
        $errors['pseudo'] = 'La valeur renseignée ne correspond pas au format attendu';
    }
    array_push($post,$pseudo);

    $birthdate = trim(filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_STRING));
    	if (!empty($birthdate)) {
    		
    		// FIN

    		// créé le timestamp d'aujourd'hui
    		$today = strtotime("NOW");
    		// timestamp de mon input date
    		$convertBirthdate = strtotime($birthdate);
    		if (!preg_match($regexBirthday, $birthdate)) {
    			$errors['birthdate'] = 'Veuillez renseigner une date correcte';
            }
        
    		// vérifie que la date reste inférieur à NOW
    		elseif ($convertBirthdate > $today) {
    			$errors['birthdate'] = 'Votre date ne peut pas être supérieur à la date du jour';
    		}
    	}
    	else{
    		$errors['birthdate'] = 'Veuillez renseigner votre date de naissance';
        }
        $birthdate = preg_replace($regexBirthday,'$3/$2/$1',$birthdate);
        array_push($post,$birthdate);
        
    // validation du firstname

    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    // vérifier la validité de la valeur
    if (empty($email)) {
        $errors['email'] = 'Veuillez renseigner votre email';
    }
    $users = new Users('','','','','',$email);
    $usersMail = $users->readPregMatchMail();
    
    foreach ($usersMail as $email2) {
        $emailExist = $email2->email;
    }
    
    if ($email == $emailExist) {
        $errors['emailExist'] = 'Votre email existe déja';
    }
    array_push($post,$email);
    

    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
    // vérifier la validité de la valeur
    if (empty($password)) {
        $errors['password'] = 'Veuillez renseigner votre mot de passe';
    } else if (!preg_match($regexPassword, $password)) {
        $errors['password'] = 'La valeur renseignée ne correspond pas au format attendu';
    }
  

    $verifPassword = trim(filter_input(INPUT_POST, 'verifPassword', FILTER_SANITIZE_STRING));
    if (empty('verifPassword')) {
            $errors['verifPassword'] = 'Veuillez confirmer votre mot de passe';            
        } else if ($password != $verifPassword){
            $errors ['verifPassword'] = 'Les mots de passe ne correspondent pas';
        }   else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        }

        array_push($post,$hashed_password);

        if (!isset($_POST['cgu'])) {
            $errors['cgu'] = 'veuillez cocher la case pour envoyer le formulaire. <i class="far fa-smile-wink"></i>';
        }

        if (count($errors) == 0){
            $users = new Users(0,$lastname, $firstname, $birthdate, $email,$pseudo, $hashed_password,$photo,$zipCode,$civility,$cle,$actif,$admin_id);
            if ($users->create()) {
                $userCreated = true;
            }
        }
            

}
require_once dirname(__FILE__).'/../Controller/header_ctrl.php';
require_once dirname(__FILE__).'/../View/navbar.php';
require_once dirname(__FILE__).'/../View/navbarBottom.php';
require_once dirname(__FILE__).'/../View/inscription.php';
