<?php

namespace App\Http\Controllers;

use App\Menu;
use App\MenuItem;
use App\User;
use App\UssdLog;
use Illuminate\Http\Request;

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

                case 0 :
                    //neutral user
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




}
