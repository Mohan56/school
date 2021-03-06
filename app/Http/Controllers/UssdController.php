<?php

namespace App\Http\Controllers;

use App\Menu;
use App\MenuItem;
use App\User;
use App\UssdLog;
use App\UssdResponse;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

class UssdController extends Controller
{
    public function index()
    {
        error_reporting(0);
        header('Content-type: text/plain');
        set_time_limit(100);

        //get inputs
        $sessionId = $_REQUEST["sessionId"];
        $serviceCode = $_REQUEST["serviceCode"];
        $phoneNumber = $_REQUEST["phoneNumber"];
        $text = $_REQUEST["text"];   //


        $data = ['phone' => $phoneNumber, 'text' => $text, 'service_code' => $serviceCode, 'session_id' => $sessionId];
//        print_r($data);
//        exit;
        //log USSD request
        UssdLog::create($data);

        //verify that the user exists
        $no = substr($phoneNumber, -9);

        $user = User::where('phone', "0" . $no)->orWhere('phone', "254" . $no)->first();

        if (!$user) {
            //if user phone doesn't exist, we check out if they have been registered to mifos
            $usr = array();
            $usr['phone'] = "0" . $no;
            $usr['session'] = 0;
            $usr['progress'] = 0;
            $usr['confirm_from'] = 0;
            $usr['menu_item_id'] = 0;

            $user = User::create($usr);
        }

        if (self::user_is_starting($text)) {
            //lets get the home menu
            //reset user
            self::resetUser($user);
            //user authentication
            self::authentication($user, 2);
            $message = '';

            $response = self::getMenuAndItems($user, 1);

            //get the home menu
            self::sendResponse($response, 1, $user);
        } else {

            //message is the latest stuff
            $result = explode("*", $text);
            if (empty($result)) {
                $message = $text;
            } else {
                end($result);
                // move the internal pointer to the end of the array
                $message = current($result);
            }


            switch ($user->session) {

//                case 0 :
//                    //neutral user
//                    break;
                case 0 : $response = self::authentication($user, $message);
                     break;
                case 1 :
                    $response = self::continueUssdMenuProcess($user, $message);
                    //echo "Main Menu";
                    break;
                case 2 :
                    //confirm USSD Process
                    $response = self::confirmUssdProcess($user, $message);
                    break;
                case 3 :
                    //Go back menu
                    $response = self::confirmGoBack($user, $message);
                    break;
                case 4 :
                    //Go back menu
                    $response = self::confirmGoBack($user, $message);
                    break;
                default:
                    break;
            }
            self::sendResponse($response, 1, $user);
        }
    }
    public function authentication($user,$menu){
        if ($user->Is_registered != 1) {
            // user has no registered;
            print_r("here");
            return self::getMenuAndItems($user,1);
        }else{
            print_r("hre");
            return "regiseed";
//            return self::getMenuAndItems($user,1);
        }

    }
    private static function sendResponse($response, $type=1, $user=null)
    {
        if ($type == 1) {
            $output = "CON ";


        } elseif($type == 2) {
            $output = "CON ";
            $response = $response.PHP_EOL."1. Back to main menu".PHP_EOL."2. Log out";
            $user->session = 4;
            $user->progress = 0;
            $user->save();
        }else{
            $output = "END ";
        }

        $output .= $response;
        header('Content-type: text/plain');
        echo $output;
        exit;
    }
    public function user_is_starting($text)
    {
        if (strlen($text) > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public function resetUser($user)
    {
        $user->session = 0;
        $user->progress = 0;
        $user->menu_id = 0;
        $user->confirm_from = 0;
        $user->menu_item_id = 0;

        return $user->save();

    }

    private static function confirmGoBack($user, $message)
    {
    }
    private static function confirmUssdProcess($user, $message)
    {
    }
    private static function continueUssdMenuProcess($user, $message)
    {

        $menu = menu::find($user->menu_id);

        //check the user menu
        switch ($menu->type) {
            case 0:
                //authentication mini app

                break;
            case 1:
                //continue to another menu
                $response = self::continueUssdMenu($user,$message,$menu);
                break;
            case 2:
                //continue to a processs
                $response = self::continueSingleProcess($user,$message,$menu);
                break;
            case 3:
                //infomation mini app
                //
                self::infoMiniApp($user,$menu);
                break;
            default :
                self::resetUser($user);
                $response = "An error occurred";
                break;
        }

        return $response;

    }
    private static function getMenuAndItems($user, int $menu_id)
    {
        //get main menu

        $user->menu_id = $menu_id;
        $user->session = 1;
        $user->progress = 1;
        $user->save();
        //get home menu
        $menu =  Menu::find($menu_id);

        $menu_items = self::getMenuItems($menu_id);


        $i = 1;
        $response = $menu->title.PHP_EOL;
        foreach ($menu_items as $key => $value) {
            $response = $response . $i . ": " . $value->description . PHP_EOL;
            $i++;
        }
        return $response;
    }
    //Menu Items Function
    public static function getMenuItems($menu_id)
    {
        $menu_items = MenuItem::whereMenuId($menu_id)->get();
        return $menu_items;
    }
    public function validationVariations($message, $option, $value)
    {
        if ((trim(strtolower($message)) == trim(strtolower($value))) || ($message == $option) || ($message == "." . $option) || ($message == $option . ".") || ($message == "," . $option) || ($message == $option . ",")) {
            return TRUE;
        } else {
            return FALSE;
        }

    }
    //store USSD response
    public function storeUssdResponse($user,$message){

        $data = ['user_id'=>$user->id,'menu_id'=>$user->menu_id,'menu_item_id'=>$user->menu_item_id,'response'=>$message];
        return UssdResponse::create($data);


    }
    //single process

    public function singleProcess($menu, $user,$step) {

        $menuItem = MenuItem::whereMenuIdAndStep($menu->id,$step)->first();

        if ($menuItem) {
            //update user data and next request and send back
            $user->menu_item_id = $menuItem->id;
            $user->menu_id = $menu->id;
            $user->progress = $step;
            $user->session = 1;
            $user->save();
            return $menuItem -> description;

        }

    }
    public function continueSingleProcess($user,$message,$menu){
        //validate input to be numeric
        $menuItem = menu_item::whereMenuIdAndStep($menu->id,$user->progress)->first();
        $message = str_replace(",","",$message);

        switch ($menu->id) {
            default :
                self::storeUssdResponse($user,$message);
                //check if we have another step
                $step = $user->progress + 1;
                $menuItem = menu_item::whereMenuIdAndStep($menu->id,$step)->first();
                if($menuItem){

                    $user->menu_item_id = $menuItem->id;
                    $user->menu_id = $menu->id;
                    $user->progress = $step;
                    $user->save();
                    return $menuItem -> description;
                }else{
                    $response = self::confirmBatch($user,$menu);
                    return $response;

                }
                break;
        }

        return $response;
    }
    public function nextMenuSwitch($user,$menu){

//		print_r($menu);
//		exit;
        switch ($menu->type) {
            case 0:
                //authentication mini app

                break;
            case 1:
                //continue to another menu
                $menu_items = self::getMenuItems($menu->id);
                $i = 1;
                $response = $menu->title.PHP_EOL;
                foreach ($menu_items as $key => $value) {
                    $response = $response . $i . ": " . $value->description . PHP_EOL;
                    $i++;
                }

                $user->menu_id = $menu->id;
                $user->menu_item_id = 0;
                $user->progress= 0;
                $user->save();
                //self::continueUssdMenu($user,$message,$menu);
                break;
            case 2:
                //start a process
//				print_r($menu);
//				exit;
                self::storeUssdResponse($user,$menu);

                $response = self::singleProcess($menu,$user,1);
                return $response;

                break;
            case 3:
                self::infoMiniApp($user,$menu);
                break;
            default :
                self::resetUser($user);
                $response = "An authentication error occurred";
                break;
        }

        return $response;

    }

    //continue USSD Menu
    public function continueUssdMenu($user,$message,$menu){
        //verify response
        $menu_items = self::getMenuItems($user->menu_id);

        $i = 1;
        $choice = "";
        $next_menu_id = 0;
        foreach ($menu_items as $key => $value) {
            if(self::validationVariations(trim($message),$i,$value->description)){
                $choice = $value->id;
                $next_menu_id = $value->next_menu_id;

                break;
            }
            $i++;
        }
        if(empty($choice)){
            //get error, we could not understand your response
            $response = "We could not understand your response". PHP_EOL;


            $i = 1;
            $response = $menu->title.PHP_EOL;
            foreach ($menu_items as $key => $value) {
                $response = $response . $i . ": " . $value->description . PHP_EOL;
                $i++;
            }

            return $response;
            //save the response
        }else{
            //there is a selected choice
            $menu = Menu::find($next_menu_id);
            //next menu switch
            $response = self::nextMenuSwitch($user,$menu);
            return $response;
            //save the response

        }

    }

}
