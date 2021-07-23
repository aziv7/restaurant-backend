<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Role::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom des roles' => 'required',
        ]);
        return Role::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Role::find($id);
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
        $role = Role::find($id);
        $role->update($request->all());
        return $role;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Role::destroy($id);
    }

    /** affecter un role a un utilisateur */
    public function addRoleUser($role_id, $user_id)
    {
        $user = User::find($user_id);
        $role = Role::find($role_id);
        if ($role != null && $user != null) {//check if the user has already the role or not
            if ($user->roles()->find($role_id) == null)//test pour voir si le user a ce role ou nn
            {
                $role->users()->attach($user);//users() refers to the method user() in Role model
                return $role;
            }
            return response(array(
                'message' => 'role exists already',
            ), 403);
        }
        return response(array(
            'message' => 'role or user not found',
        ), 404);
    }

    public function unsetRoleFromUser($role_id, $user_id)
    {
        $user = User::find($user_id);
        $role = Role::find($role_id);
        if ($role != null && $user != null) {//check if the user has already the role or not
            if ($user->roles()->find($role_id) != null)//test pour voir si le user a ce role ou nn
            {
                $role->users()->detach($user);//users() refers to the method user() in Role model
                return $role;
            }
            return response(array(
                'message' => 'role n \'appartien pas au user',
            ), 403);
        }
        return response(array(
            'message' => 'role or user not found',
        ), 404);
    }
}
