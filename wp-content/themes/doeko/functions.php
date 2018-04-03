<?php

function theme_enqueue_child_style() {
    wp_enqueue_style('theme-child-style', get_stylesheet_directory_uri() . '/style.css', array('theme-style', 'theme-skin'), false, 'all');
}

add_action('wp_print_styles', 'theme_enqueue_child_style', 20);

function striking_child_name() {
    global $wp_admin_bar;
    $current_theme = wp_get_theme();
    $wp_admin_bar->add_menu(array(
        'id' => 'your_menu_id',
        'title' => 'Standard Child Theme'
    ));
}

add_action('admin_bar_menu', 'striking_child_name', 82);

/* *** AZURE INTEGRATION *** */
add_action('wpcf7_before_send_mail','send_data_to_azure',10,1);

function send_data_to_azure($contact_form)
    {
        $wpcf7 = WPCF7_ContactForm::get_current();
        $submission = WPCF7_Submission::get_instance();
        //Below statement will return all data submitted by form.
        $data = $submission->get_posted_data();
        //suppose you have a field which name is 'email' then you can access it by using following statement.
        //$user_passed_email =  $data['email'];



    }

/* * ************* Obsługa formularza kontaktowego **************** */

add_action('wp_enqueue_scripts', 'theme_enqueue_child_js');

function theme_enqueue_child_js() {

    wp_enqueue_script('myJs', get_template_directory_uri() . '/js/myjs.js', array('jquery'), NULL, true);
}

add_action('wp_head', 'my_js_variables');

function my_js_variables() {

    //for specific page templates
    $current_template = get_page_template();

    if (!isset($current_template)) {
        return;
    }
    ?>
    <script type="text/javascript">
        var ajaxurl = <?php echo json_encode(admin_url("admin-ajax.php")); ?>;
        var ajaxnonce = <?php echo json_encode(wp_create_nonce("itr_ajax_nonce")); ?>;
    </script><?php
}

add_filter('filter_dyn_select', 'filter_dyn_select_call', 10, 2);

function filter_dyn_select_call($var, $args = array()) {
    //pc::debug($args);

    $str = '';

    switch ($args['select']) {
        case 'klastry':
            $str = file_get_contents('https://doekodev.azurewebsites.net/api/v1/Contract/Clusters');
            break;
        case 'wojewodztwa':
            $str = file_get_contents('https://doekodev.azurewebsites.net/api/v1/Address/States');
            break;
        case 'wielkosc':
            $choices = array(
                '---' => '',
                'Od 1 do 9 osób' => '1',
                'Od 10 do 49 osób' => '2',
                'Od 50 do 249 osób' => '3',
                'Powyżej 249 osób' => '4'
            );

            return $choices;
            break;
    }

    $re = '/.*?"id":(\d*?),"text":"(.*?)"/';
    preg_match_all($re, $str, $out, PREG_PATTERN_ORDER);

    /*

      $subst = '$1:$2$';
      $result = preg_replace($re, $subst, $str);
      $s = rtrim($result, '$}]');
      $arrKlastry = explode('$', $s);
      foreach($arrKlastry as $klaster){

      $ex = explode(':', $klaster);

      $arrSelect[$ex[1]] = $ex[0];
      } */

    $arrSelect = [];
    $arrSelect['---'] = '';

    if (sizeof($out) > 0) {
        for ($i = 0; $i < sizeof($out[1]); $i++) {
            $arrSelect[$out[2][$i]] = $out[1][$i];
        }
    }
    return $arrSelect;
}

function fSelectResponse($str) {

    $re = '/.*?"id":(\d*?),"text":"(.*?)"/';
    preg_match_all($re, $str, $out, PREG_PATTERN_ORDER);
    /*
      $arrSelect = [];
      $arrSelect['---'] = '';
     */

    $selectHtml = '<option value="" selected="selected">---</option>';
    if (sizeof($out) > 0) {
        for ($i = 0; $i < sizeof($out[1]); $i++) {
            //$arrSelect[$out[2][$i]] = $out[1][$i];

            $selectHtml .= '<option value="' . $out[1][$i] . '">' . $out[2][$i] . '</option>';
        }
    }
    //echo json_encode($arrSelect);
    return $selectHtml;
}

add_action('wp_ajax_my_call', 'fMyCall');
add_action('wp_ajax_nopriv_my_call', 'fMyCall');

function fMyCall() {

    //pc::debug(isset($_POST["IdPow"]));

    if (isset($_POST["IdWoj"]) && $_POST["IdPow"] === '') {

        $idWoj = $_POST["IdWoj"];

        $str = file_get_contents('https://doekodev.azurewebsites.net/api/v1/Address/States/' . $idWoj . '/Districts');
        $selectHtml = fSelectResponse($str);

        echo $selectHtml;
    } else if ($_POST["IdWoj"] !== '' && $_POST["IdPow"] !== '') {

        $idWoj = $_POST["IdWoj"];
        $idPow = $_POST["IdPow"];

        $str = file_get_contents('https://doekodev.azurewebsites.net/api/v1/Address/States/' . $idWoj . '/Districts/' . $idPow . '/Communes');
//		pc::debug($str);
        $selectHtml = fSelectResponse($str);

        echo $selectHtml;
    } else {
        echo "functions.php 153";
    }

    die();
}

//Cred = Credentials
add_action('wp_ajax_my_cred_call', 'fCredCall');
add_action('wp_ajax_nopriv_my_cred_call', 'fCredCall');
function fCredCall() {
    
    $Credentials = (object) [
        'l' => 'Cluster.Investments',
        'p' => 'aLa@q8u9'
        ];
    
    //$Credentials = ['Cluster.Investments', 'aLa@q8u9'];
   
    echo json_encode($Credentials);
    
    die();
}

/* * *******************  CUSTOM VALIDATION ******************************* */

function contains($str, array $arr) {
    foreach ($arr as $a) {
        if (stripos($str, $a) !== false)
            return true;
    }
    return false;
}

function CheckNIP($str) {
    $str = preg_replace('/[^0-9]+/', '', $str);

    if (strlen($str) !== 10) {
        return false;
    }

    $arrSteps = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
    $intSum = 0;

    for ($i = 0; $i < 9; $i++) {
        $intSum += $arrSteps[$i] * $str[$i];
    }

    $int = $intSum % 11;
    $intControlNr = $int === 10 ? 0 : $int;

    if ($intControlNr == $str[9]) {
        return true;
    }

    return false;
}

function myValidate($inputVal, $type) {

    $arrAlerts = array(
        'emptyDynSel' => "Wymagane jest wypełnienie tego pola.",
        'lettOnly' => "W tym polu znajdować się mogą tylko wielkie i małe litery.",
        'illegralChars' => "W tym polu znajdują się niedozwolone znaki.",
        '2digitDecPoint' => "W tym polu proszę podać wartość maksymalnie do dwóch miejsc po przecinku.",
        'outOfRange' => "Podałeś wartość spoza zakresu.",
        'nip' => 'Podano nieprawidłowy NIP.',
        'PosalCode' => 'Podano nieprawidłowy kod pocztowy.',
        'orgName' => 'W tym polu podano niedozwolone znaki.',
        'telefon' => 'Proszę podać nr telefonu w formacie "+48 00 111 22 33" lub "+48 000 111 222".'
    );
        
    $plChars = 'ąćęłńóśźżĄĆĘŁŃÓŚŹŻ';
    
    $arrRegex = array(
        'lettOnly' => '/^[a-zA-Z'.$plChars.']*$/',
        'orgName' => '/^[a-zA-Z'.$plChars.'0-9\s\.,]*$/',
        'telefon' => '/^(\+48)[0-9\s]*$/',
        'ulica' => '/^[a-zA-Z'.$plChars.'0-9\-]*$/',
        'nr' => '/^[a-zA-Z0-9]*$/',
        'miejscowosc' => '/^[a-zA-Z'.$plChars.'\s\-]*$/',
        '2digitDecPoint' => '/^\d*(,|\.)?\d{1,2}$/',
        'PvdigitsOnly' => '/^[0-9,\.]*$/',
        'HTMLtags' => '/<[^>]*>/',
        'PosalCode' => '/^[0-9]{2}-[0-9]{3}$/Du'
    );

    $alert = "";

    switch ($type) {
        case 'emptyDynSel':
            if ($inputVal == '') {
                $alert = $arrAlerts['emptyDynSel'];
            }
            break;
        case 'lettOnly':
            if (!preg_match($arrRegex['lettOnly'], $inputVal)) {
                $alert = $arrAlerts['lettOnly'];
            }
            break;
        case 'orgName':
            if (!preg_match($arrRegex['orgName'], $inputVal)) {
                $alert = $arrAlerts['orgName'];
            }
            break;
        case 'nip':
            //$inputVal = preg_replace('/-/', '', $inputVal);
            //if (!preg_match('/^[0-9]{10}$/', $inputVal))
            if (!CheckNIP($inputVal)) {
                $alert = $arrAlerts['nip'];
            }
            break;

        case 'telefon':
            
            if (!preg_match($arrRegex['telefon'], $inputVal)) {
                $alert = $arrAlerts['telefon'];
            }
            break;
        case 'ulica':
            if (!preg_match($arrRegex['ulica'], $inputVal)) {
                $alert = $arrAlerts['illegralChars'];
            }
            break;
        case 'nrbudynku':
            if (!preg_match($arrRegex['nr'], $inputVal)) {
                $alert = $arrAlerts['illegralChars'];
            }
        case 'nrlokalu':
            if (!preg_match($arrRegex['nr'], $inputVal)) {
                $alert = $arrAlerts['illegralChars'];
            }
            break;
        case 'miejscowosc':
            if (!preg_match($arrRegex['miejscowosc'], $inputVal)) {
                $alert = $arrAlerts['illegralChars'];
            }
            break;
        case 'kodpocztowy':
            if (!preg_match($arrRegex['PosalCode'], $inputVal)) {
                $alert = $arrAlerts['PosalCode'];
            }
            break;
        //         * *******   MAM INSTALACJĘ   ******* 
        case 'posiadaminstalacje-mocinstalacji':

            $val = preg_replace('/,/', '.', $inputVal);
            $doubleVal = floatval($val);
            
            if (!preg_match($arrRegex['PvdigitsOnly'], $inputVal)) {
                $alert = $arrAlerts['illegralChars'];
            } else {
                if ($doubleVal < 0.1 || 10000 < $doubleVal) {
                    $alert = $arrAlerts['outOfRange'];
                } else if (!preg_match($arrRegex['2digitDecPoint'], $inputVal)) {
                    $alert = $arrAlerts['2digitDecPoint'];
                }
            }
            break;
        case 'posiadaminstalacje-rocznyuzysk':
            $val = preg_replace('/,/', '.', $inputVal);
            $doubleVal = floatval($val);

            if (!preg_match($arrRegex['PvdigitsOnly'], $inputVal)) {
                $alert = $arrAlerts['illegralChars'];
            } else {
                if ($doubleVal < 0.1 || 10000000 < $doubleVal) {
                    $alert = $arrAlerts['outOfRange'];
                } else if (!preg_match($arrRegex['2digitDecPoint'], $inputVal)) {
                    $alert = $arrAlerts['2digitDecPoint'];
                }
            }
            break;

        //         ********   CHCĘ INSTALACJĘ   ******* 
        //         ****** PPROKONSUMENCKA ***** 
        case 'chceinstalacje_prokonsumencka-rocznezuzycieenergii':
            $val = preg_replace('/,/', '.', $inputVal);
            $doubleVal = floatval($val);

            if (!preg_match($arrRegex['PvdigitsOnly'], $inputVal)) {
                $alert = $arrAlerts['illegralChars'];
            } else {
                if ($doubleVal < 0.1 || 50000 < $doubleVal) {
                    $alert = $arrAlerts['outOfRange'];
                } else if (!preg_match($arrRegex['2digitDecPoint'], $inputVal)) {
                    $alert = $arrAlerts['2digitDecPoint'];
                }
            }
            break;
        case 'chceinstalacje_prokonsumencka-proponowanamoc':
            $val = preg_replace('/,/', '.', $inputVal);
            $doubleVal = floatval($val);

            if (!preg_match($arrRegex['PvdigitsOnly'], $inputVal)) {
                $alert = $arrAlerts['illegralChars'];
            } else {
                if ($doubleVal < 0.1 || 10000 < $doubleVal) {
                    $alert = $arrAlerts['outOfRange'];
                } else if (!preg_match($arrRegex['2digitDecPoint'], $inputVal)) {
                    $alert = $arrAlerts['2digitDecPoint'];
                }
            }
            break;


        //         ****** FARMA *****
        case 'chceinstalacje_farma-proponowanamoc':
            $val = preg_replace('/,/', '.', $inputVal);
            $doubleVal = floatval($val);

            if (!preg_match($arrRegex['PvdigitsOnly'], $inputVal)) {
                $alert = $arrAlerts['illegralChars'];
            } else {
                if ($doubleVal < 0.1 || 10000 < $doubleVal) {
                    $alert = $arrAlerts['outOfRange'];
                } else if (!preg_match($arrRegex['2digitDecPoint'], $inputVal)) {
                    $alert = $arrAlerts['2digitDecPoint'];
                }
            }
            break;
        case 'chceinstalacje_farma-stanzaawansowania':            
            if (preg_match($arrRegex['HTMLtags'], $inputVal)) {
                $alert = $arrAlerts['illegralChars'];
            }
            break;
    }
    
    return $alert;
}


add_filter('wpcf7_validate_dynamicselect', 'custom_dynamicselect_validation_filter', 20, 2);
function custom_dynamicselect_validation_filter($result, $tag) {
    
    
    $arrNames = array("klaster", "wojewodztwa", "wielkosc");
    $alert = "";

    if (contains($tag->name, $arrNames)) {
            $alert = myValidate($_POST[$tag->name], 'emptyDynSel');
    }
    
    if($alert !== "")
        $result->invalidate($tag, $alert);
    
    return $result;
}



add_filter('wpcf7_validate_text*', 'custom_text_validation_filter', 20, 2);
function custom_text_validation_filter($result, $tag) {

    $alert = "";

    $arrNames = array("imie", "nazwisko", "nazwa");

    if (contains($tag->name, $arrNames)) {
        if ($_POST[$tag->name] != '') {
            $alert = myValidate($_POST[$tag->name], 'lettOnly');
        };
    }

    $arrNames = array("nazwa");

    if (contains($tag->name, $arrNames)) {
        if ($_POST[$tag->name] != '') {
            $alert = myValidate($_POST[$tag->name], 'orgName');
        };
    }

    $arrTypes = array("nip", "telefon", "ulica", "nrbudynku", "nrlokalu", "miejscowosc", "kodpocztowy", //Adres
        "posiadaminstalacje-mocinstalacji", "posiadaminstalacje-rocznyuzysk", //Posiadam instalację
        "chceinstalacje_prokonsumencka-rocznezuzycieenergii", "chceinstalacje_prokonsumencka-proponowanamoc", //Chcę instalację, prokonsumencka
        "chceinstalacje_farma-rocznezuzycieenergii"); //Chcę instalację, farma "chceinstalacje_farma-stanzaawansowanias" to textarea a nie text

    foreach ($arrTypes as $type) {
        if ($_POST[$tag->name] != '') {
            if (strpos($tag->name, $type) !== false) {
                $alert = myValidate($_POST[$tag->name], $type);
            }
        }
    }
    
    if($alert !== "")
        $result->invalidate($tag, $alert);
    
    return $result;
}


add_filter('wpcf7_validate_textarea*', 'custom_textarea_validation_filter', 20, 2);
function custom_textarea_validation_filter($result, $tag) {
    $arrTypes = array("chceinstalacje_farma-stanzaawansowania");
    $alert = "";

        foreach ($arrTypes as $type) {
        if ($_POST[$tag->name] != '') {
            if (strpos($tag->name, $type) !== false) {
                $alert = myValidate($_POST[$tag->name], $type);
            }
        }
    }
    if($alert !== "")
        $result->invalidate($tag, $alert);
    
    return $result;
}



add_filter('wpcf7_validate_text', 'custom_nrq_text_validation_filter', 20, 2);
function custom_nrq_text_validation_filter($result, $tag) {
    $arrNames = array("nazwacd");  //cause nazwacd is not required *
    $alert = "";

    if (contains($tag->name, $arrNames)) {
        if ($_POST[$tag->name] != '') {
            $alert = myValidate($_POST[$tag->name], 'lettOnly');
        };
    }
    
    if($alert !== "")
        $result->invalidate($tag, $alert);
    
    return $result;
}
