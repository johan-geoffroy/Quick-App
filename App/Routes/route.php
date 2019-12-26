<?php declare(strict_types=1);


use Slim\App;


return function(App $app){


//Group Route App
$app->get('/', \App\Controllers\PagesController\PageHome::class)->setName('home');
$app->get('/inscription', \App\Controllers\PagesController\PageInscription::class)->setName('inscription');
$app->get('/dashboard', \App\Controllers\PagesController\PageDashboard::class)->setName('dashboard');
$app->get('/contact', \App\Controllers\PagesController\PageContact::class)->setName('contact');


//Group Route User
$app->post('/loguser', \App\Users\LogUser::class)->setName('loguser');
$app->post('/adduser', \App\Users\AddUser::class)->setName('adduser');
$app->post('/resetpassword', \App\Users\ResetPasswordUser::class)->setName('resetpassword');
$app->get('/changepassword{token}', \App\Users\ChangePasswordUser::class)->setName('changepassword');
$app->post('/changemdp', \App\Users\ChangeMdp::class)->setName('changemdp');
$app->post('/logoutuser', \App\Users\LogOutUser::class)->setName('logout');


//Action Mailer
$app->post('/sendmail', \App\Mails\Mailer::class)->setName('sendmail');
};

