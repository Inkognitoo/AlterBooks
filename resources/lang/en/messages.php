<?php

return [
    /*
     * UserController
     */

    'registration_success'  => 'Registration is almost complete. You must confirm the email, specified in the registration by clicking on the link in the email',

    'send_email_verify_success' => 'A new email confirmation request was submitted successfully',
    'send_email_verify_error'   => 'Your email already verified',

    'auth_success'                      => 'User successfully authorized',
    'auth_bad_password'                 => 'Incorrect password',
    'auth_user_not_found'               => 'User does not exist',
    'auth_not_enter_login_and_password' => 'You must specify a username and password',

    'unauth_success' => 'User session was successfully terminated',

    'reset_password_request_success'         => 'Recovery code was sent to your email',
    'reset_password_request_email_not_found' => 'Said email does not exist in the database or email was not verified',
    'reset_password_request_email_not_enter' => 'You must specify the email',

    'change_email_request_success'    => 'Request for change of email sent successfully',
    'change_email_request_not_verify' => 'Specified in the registration email has not been confirmed',

    'filling_profile_success' => 'Data successfully updated',

    'change_password_success'   => 'Password successfully changed',
    'change_password_not_match' => 'Current passwords do not match',

    'upload_avatar_success' => 'File successfully uploaded',

    /*
     * SocialController
     */

    'social_auth_provider_not_found' => 'Provider is not supported',

    'social_auth_callback_registration_success' => 'Registration is almost complete. You must confirm the email, specified in the registration by clicking on the link in the email',
    'social_auth_callback_auth_success'         => 'User successfully authorized',
    'social_auth_callback_not_giving_date'      => 'Failed to get the data from the social network',
    'social_auth_callback_not_process_date'     => 'Failed to process the data from the social network',
    'social_auth_callback_email_in_use'         => 'The email already in use',

    'enter_email_success'          => 'Registration is almost complete. You must confirm the email, specified in the registration by clicking on the link in the email',
    'enter_email_not_process_date' => 'Failed to process the data from the social network',
    'enter_email_forbidden'        => 'Forbidden',
];