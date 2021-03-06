<?php
/**
 * Settings - Implements a simple Administration Settings.
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */

namespace App\Modules\System\Controllers;

use App\Core\BackendController;
use App\Modules\System\Models\UserLog;

use Auth;
use Config;
use Input;
use Redirect;
use Validator;

class Settings extends BackendController
{

    public function index()
    {
        // Load the options from database.
        $options = array(
            // The Application.
            'siteName'        => Config::get('app.name'),
            'ipAccessList'    => Config::get('app.ipAccessList'),
            'devEmails'       => Config::get('app.devEmails'),

            // The Mailer
            'mailDriver'      => Config::get('mail.driver'),
            'mailHost'        => Config::get('mail.host'),
            'mailPort'        => Config::get('mail.port'),
            'mailFromAddress' => Config::get('mail.from.address'),
            'mailFromName'    => Config::get('mail.from.name'),
            'mailEncryption'  => Config::get('mail.encryption'),
            'mailUsername'    => Config::get('mail.username'),
            'mailPassword'    => Config::get('mail.password'),
        );

        $jq = "
            //fadeout selected item and remove
            $('#ipextender').on('click', '.ipremove', function() {
                $(this).parent().fadeOut(300, function(){
                    $(this).parent().empty();
                    return false;
                });
                return false;
            });

            var ipoptions = '<tr>';
            ipoptions += '<td><input type=\"text\" name=\"ips[]\"></td>';
            ipoptions += '<td><input type=\"text\" name=\"names[]\"> <a href=\"#\" class=\"ipremove\">Remove</a></td></tr>';

            //add input
            $('a#ipadd').click(function() {
                $(ipoptions).fadeIn(\"slow\").appendTo('#ipextender');
                return false;
            });


            //fadeout selected item and remove
            $('#devemailextender').on('click', '.devemailremove', function() {
                $(this).parent().fadeOut(300, function(){
                    $(this).parent().empty();
                    return false;
                });
                return false;
            });

            var devemailoptions = '<tr>';
            devemailoptions += '<td><input type=\"text\" size=\"50\" name=\"emails[]\"></td>';
            devemailoptions += '<td><input type=\"text\" name=\"names[]\"> <a href=\"#\" class=\"devemailremove\">Remove</a></td></tr>';

            //add input
            $('a#devemailadd').click(function() {
                $(devemailoptions).fadeIn(\"slow\").appendTo('#devemailextender');
                return false;
            });

        ";

        return $this->getView()
            ->shares('title', 'Settings')
            ->shares('jq', $jq)
            ->with('options', $options);
    }

    public function store()
    {
        // Validate the Input data.
        $input = Input::all();

        $validator = $this->validate($input);

        if($validator->passes()) {

            // The Application.
            Config::set('app.name',          $input['siteName']);

            //ip addresses
            $iparray = [];
            if(isset($input['ips'])) {
                $i = 0;
                foreach($input['ips'] as $ip) {
                    $name = $input['names'][$i];
                    $iparray[$ip] = $name;
                    $i++;
                }
            }
            Config::set('app.ipAccessList', $iparray);

            //dev email addresses
            $devemailarray = [];
            if(isset($input['emails'])) {
                $i = 0;
                foreach($input['emails'] as $email) {
                    $name = $input['names'][$i];
                    $devemailarray[$email] = $name;
                    $i++;
                }
            }
            Config::set('app.devEmails', $devemailarray);

            // The Mailer
            Config::set('mail.driver',       $input['mailDriver']);
            Config::set('mail.host',         $input['mailHost']);
            Config::set('mail.port',         $input['mailPort']);
            Config::set('mail.from.address', $input['mailFromAddress']);
            Config::set('mail.from.name',    $input['mailFromName']);
            Config::set('mail.encryption',   $input['mailEncryption']);
            Config::set('mail.username',     $input['mailUsername']);
            Config::set('mail.password',     $input['mailPassword']);

            $log          = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->title   = "Updated settings";
            $log->section = "settings";
            $log->link    = "cp/settings";
            $log->type    = 'Update';
            $log->save();

            return Redirect::to('cp/settings')->withStatus('The Settings was successfully updated.');
        }

        return Redirect::back()->withInput()->withStatus($validator->errors(), 'danger');
    }

    private function validate(array $data)
    {
        // Validation rules
        $rules = array(
            // The Application.
            'siteName'        => 'required|max:100|alpha_dash',

            // The Mailer
            'mailDriver'      => 'required|alpha',
            'mailHost'        => 'required|alpha_dash',
            'mailPort'        => 'numeric',
            'mailFromAddress' => 'required|email',
            'mailFromName'    => 'required|alpha_dash',
            'mailEncryption'  => 'alpha',
            'mailUsername'    => 'alpha_dash',
            'mailPassword'    => 'alpha_dash',
        );

        $attributes = array(
            // The Application.
            'siteName'        => 'Site Name',

            // The Mailer
            'mailDriver'      => 'Mail Driver',
            'mailHost'        => 'Server Name',
            'mailPort'        => 'Server Port',
            'mailFromAddress' => 'Mail from Adress',
            'mailFromName'    => 'Mail from Name',
            'mailEncryption'  => 'Encryption',
            'mailUsername'    => 'Server Username',
            'mailPassword'    => 'Server Password',
        );

        return Validator::make($data, $rules, array(), $attributes);
    }

}
