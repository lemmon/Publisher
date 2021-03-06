<?php

//
// config
require '../config.php';

//
// bootstrap
require '../publisher/Bootstrap.php';

//
// run
$env    = new Env;
$db     = new Db;
$auth   = new Auth;

if ($f = $_GET and $f['email'] and $f['password']) {
    // create or update user
    if ($user = $db->query()->select('users')->where('email', $f['email'])->first()) {
        // update existing
        $db->query()->update('users')->set([
            'password'   => $auth->encrypt($f['password']),
            'updated_at' => new Lemmon\Sql\Expression('NOW()'),
        ])->where('id', $user->id)->limit(1)->exec();
        die('--updated');
    } else {
        // create new
        $db->query()->insert('users')->set([
            'name'       => $f['email'],
            'email'      => $f['email'],
            'password'   => $auth->encrypt($f['password']),
            'created_at' => new Lemmon\Sql\Expression('NOW()'),
            'updated_at' => new Lemmon\Sql\Expression('NOW()'),
        ])->exec();
        die('--created');
    }
}

//
//
die('--na');