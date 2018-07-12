<?php

$settings['wikiApi'] = "http://localhost/test"; // Location of Api.php
$settings['wikiUser'] = "Nischayn22"; // Username of account with read,write permissions
$settings['wikiPassword'] = "Password"; // Password

$settings['csv_file'] = 'usernames.csv';

$settings['smtp_host'] = '';
$settings['smtp_username'] = '';
$settings['smtp_password'] = '';

$settings['subject'] = 'Your account has been created';

$settings['body'] = 'Hi {username}, <br>Your account on testwiki has now been created. <br>
Please use the following login details: 
<br>
Username: {username}
<br>
Password:{password}
<br>
Thanks,
Nischay
';

$settings['from'] = 'nischay@example.com';
$settings['cc'] = array( 'nischay@example.com' );