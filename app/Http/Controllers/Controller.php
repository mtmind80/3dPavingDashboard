<?php

namespace App\Http\Controllers;

use App\Models\ProposalActions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\WebConfig;
use App\Models\User;
use Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $returnTo;
    protected $tabSelected;


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $web_config = Cache::remember('webconfig', env('CACHE_TIMETOLIVE'), function () {
                $data=array();
                $web_config = WebConfig::all()->toArray();
                foreach($web_config as $wc)
                {
                    $data[$wc['key']] = $wc['value'];
                }
                return $data;
            });

            $today = date("Y-m-d H:i:s"); //mysql date format
            view()->share('today', $today);

            view()->share('debug_blade', 'false');
            view()->share('web_config', $web_config);
            session(['web_config'=> $web_config]);

            $this->returnTo = $request->returnTo ?? null;
            $this->tabSelected = $request->tab ?? null;

            if (!empty($this->returnTo) && !empty($this->tabSelected) && strpos($this->returnTo, 'tab=') === false) {
                if (strpos($this->returnTo, '?') === false) {
                    $this->returnTo .= '?tab=' . $this->tabSelected;
                } else {
                    $this->returnTo .= '&tab=' . $this->tabSelected;
                }
            }

            view()->share('lang', \Lang::locale());
            view()->share('returnTo', $this->returnTo);
            view()->share('tabSelected', $this->tabSelected);

            $lockout = session('lockout');
            if($lockout) {
              //  return redirect()->route('lockout');
            }

            //$site_button_class ="btn btn-info";
            $site_button_class ="btn btn-default";
            $site_button_class2 ="btn btn-success";
            view()->share('site_button_class', $site_button_class);
            view()->share('site_button_class2', $site_button_class2);

            view()->share('authuser', json_decode(json_encode(auth()->user()),true));
            return $next($request);
        });
    }

    public function noAccess() //catch non admin access to application
    {
        return redirect()->route('dashboard');
    }

    /* used in migrations to populate users from old db
    */
    public function processUsers()
    {

        //$config['rolelist'] = "
        //<option value='1'>Super Admin</option>
        //<option value='2'>Sales Manager</option>
        //<option value='3'>Sales Person</option>
        //<option value='4'>Job Site Manager</option>
        //<option value='5'>Field Worker</option>
        //<option value='6'>Office Worker</option>"
        //1 Super Admin
        //2 Admin
        //3 Sales Manager
        //4 Pavement Consultant
        //5 Field Manager
        //6 Labor;
        //
        // code to input users
        $results = DB::Table('crmtblcontacts')->select('cntId','cntFirstName',
            'cntLastName','cntAvatar','cntSignature','cntJobTitle','cntStatusId',
            'cntPrimaryEmail','cntPrimaryPhone','cntPassword','cntRole')->where('cntPrimaryEmail','NOT LIKE',"%allpaving.com")->where('cntIsEmployee','=',1)->get();
        $records =0;
        foreach($results as $userdata) {
            $records++;
            $user = new User();
            $user->fname = $userdata->cntFirstName;
            $user->lname = $userdata->cntLastName;
            $user->email = $userdata->cntPrimaryEmail;
            $user->phone = $userdata->cntPrimaryPhone;
            $user->status = $userdata->cntStatusId;
            $user->password = Hash::make($userdata->cntPassword);
            $user->role_id = $userdata->cntRole;
            $user->sales_goals = 5000000;
            $user->old_id = $userdata->cntId;
            $user->save();
        }
        return "Records Processed". $records;

    }

    public function globalrecordactions($proposal_id, $action_id, $note = null)
    {
        $proposalAction = new ProposalActions;
        $proposalAction->proposal_id = $proposal_id;
        $proposalAction->action_id = $action_id;
        $proposalAction->created_by = auth()->user()->id;
        $proposalAction->note = $note;
        $proposalAction->save();

    }

}
