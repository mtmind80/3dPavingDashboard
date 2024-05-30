<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    public function __construct(Request $request)
    {
        parent::__construct();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 25;
        $data['datum'] = User::search($needle)->where('status', 1)->sortable()->paginate($perPage);

        $headers = [
            'id' => 'Edit',
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'title' => 'Email',
            'phone' => 'Phone',
            'status' => 'Status',
            'role_id' => 'Role',
        ];
        $data['headers'] = $headers;
        $data['needle'] = $needle;
        $data['perPage'] = $perPage;
        return view('users.index', $data);

    }

    public function indexAll(Request $request)
    {

        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 25;

        $headers = [
            'id' => 'Edit',
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'title' => 'Title',
            'phone' => 'Phone',
            'status' => 'Status',
            'role_id' => 'Role',
        ];

        $data['headers'] = $headers;
        $data['needle'] = $needle;
        $data['perPage'] = $perPage;

        $data['datum'] = User::search($needle)->where('status', 0)->sortable()->paginate($perPage);

        return view('users.indexall', $data);

    }


    /**
     * present new user form.
     *
     */
    public function new()
    {
        //
        return view('users.new');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //display the user
        $data['id'] = $id;
        $data['record'] = User::find($id)->toArray();
        $headers = [
            'id' => 'Edit',
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'title' => 'Title',
            'phone' => 'Phone',
            'status' => 'Status',
            'role_id' => 'Role',
        ];
        $data['headers'] = $headers;
        return view('users.show', $data);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // edit form for user
        $data['id'] = $id;
        $languageAry = ['en' => 'English', 'es' => 'Spanish'];
        $data['languageAry'] = $languageAry;
        $data['record'] = User::find($id)->toArray();
        return view('users.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //save update
        //save new
        $data = $request->all();

        $saveuser['fname'] = $data['fname'];
        $saveuser['lname'] = $data['lname'];
        $saveuser['email'] = $data['email'];
        $saveuser['phone'] = $data['phone'];
        $saveuser['language'] = $data['language'];
        $saveuser['status'] = (isset($data['status'])) ? 1 : 0;
        $saveuser['rate_per_hour'] = $data['rate_per_hour'];
        $saveuser['role_id'] = $data['role_id'];
        $saveuser['sales_goals'] = $data['sales_goals'];

        //print_r($saveuser);
        //exit();

        $user = User::find($id);
        $user->update($saveuser);

        \Session::flash('info', 'Your record was updated!');

        return redirect()->route('users');


    }


    public function updatepassword(Request $request, $id)
    {

        $validator = \Validator::make(
            [
                'password' => $request->password,
            ],
            [
                'password' => 'required|text',
            ]
        );

        if ($validator->fails()) {

            \Session::flash('info', $validator->messages()->first());

        } else {

            //save update
            $data = $request->all();
            $saveuser['password'] = Hash::make($data['password']);
            $user = User::find($id);
            $user->update($saveuser);

            \Session::flash('info', 'Your record was updated!');
        }
        return redirect()->route('edit_user',['id'=>$id]);


    }
    /**
     * Add the specified resource to storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //save new
        $data = $request->all();
        $saveuser['password'] = Hash::make($data['password']);
        $saveuser['fname'] = $data['fname'];
        $saveuser['lname'] = $data['lname'];
        $saveuser['email'] = $data['email'];
        $saveuser['phone'] = $data['phone'];
        $saveuser['language'] = $data['language'];
        $saveuser['status'] = ($data['status'] == 1) ? 1 : 0;
        $saveuser['rate_per_hour'] = $data['rate_per_hour'];
        $saveuser['role_id'] = $data['role_id'];

        //print_r($saveuser);
        //exit();

        $user = User::where('email', '=', $saveuser['email'])->first();

        if ($user === null) {
            $user = new User();
            $user->create($saveuser);
            \Session::flash('info', 'A new employee was created!');
        } else {
            $user->update($saveuser);
            \Session::flash('info', 'Your record was updated!');
        }

        return redirect()->route('users');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        \Session::flash('info', 'Your record was deleted!');
        return redirect()->route('users');

    }


    public function search(SearchRequest $request)
    {
        return $this->index($request);
    }

    public function searchall(SearchRequest $request)
    {
        return $this->indexAll($request);
    }


}
