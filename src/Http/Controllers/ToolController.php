<?php

namespace Runline\ProfileTool\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Rules\StrongPassword;

class ToolController extends Controller
{
    public function index()
    {
        return response()->json([
            [
                "component" => "text-field",
                "prefixComponent" => true,
                "indexName" => __("Name"),
                "name" => __("Name"),
                "attribute" => "name",
                "value" => auth()->user()->name,
                "panel" => null,
                "sortable" => false,
                "textAlign" => "left"
            ],
            [
                "component" => "text-field",
                "prefixComponent" => true,
                "indexName" => __("E-mail address"),
                "name" => __("E-mail address"),
                "attribute" => "email",
                "value" => auth()->user()->email,
                "panel" => null,
                "sortable" => false,
                "textAlign" => "left"
            ],
            [
                "component" => "password-field",
                "prefixComponent" => true,
                "indexName" => __("Password"),
                "name" => __("Password"),
                "attribute" => "password",
                "value" => null,
                "panel" => null,
                "sortable" => false,
                "textAlign" => "left"
            ],
            [
                "component" => "password-field",
                "prefixComponent" => true,
                "indexName" => __("Password Confirmation"),
                "name" => __("Password Confirmation"),
                "attribute" => "password_confirmation",
                "value" => null,
                "panel" => null,
                "sortable" => false,
                "textAlign" => "left"
            ]
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $rules = [
            'name' =>  ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['nullable', 'string', 'confirmed'],
        ];

        if (auth()->user()->isAdmin()) {
            $rules['password'][] = new StrongPassword();
        }

        request()->validate($rules);

        if(request()->has('password')) {
            auth()->user()->update([
                'name' => request('name'),
                'email' => request('email'),
                'password' => bcrypt(request('password')),
            ]);
        } else {
            auth()->user()->update(request()->only('name', 'email'));
        }

        return response()->json(__("Your profile has been updated!"));
    }
}
