<?php

function validEmail($str) 
{
    return (!preg_match("/^([a-z0-9\+\-]+)(\.[a-z0-9\+\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}

function api_response($token = null, $status = null, $message = null, $data = null)
{
    $array = [
        "status" => $status,
        "message" => $message,
        "data" => ["access_token" => $token, "user_data" => $data, 'expires_in' => auth()->factory()->getTTL()],
    ];

    return response($array, $status);
}

function api_response_not_found($status = null, $message = null, $data = null)
{
    $array = [
        "status" => $status,
        "message" => $message,
        "data" => $data,
    ];

    return response($array, $status);
}

function upload_photo($image)
{
    $extension = strtolower($image->extension());
    $filename = time().rand(1,10000). "." . $extension;
    $image->move("uploads", $filename);
    return $filename;
}