<?php

class ResponseMessages{


    public static function getStatusCodeMessage($status)
    {
        $CI =& get_instance();
        $codes = Array(
            100 => 'Invalid API key',
            101 => 'Invalid Auth Token',
            102 => 'Invalid Username',
            103 => 'Invalid Input Parameters',
            104 => 'An Error Occurred in User Registration',
            105 => $CI->lang->line('response_invalid_login'),
            106 => 'User authentication successfully done!',
            107 => 'User Not-Found',
            108 => "You've updated your profile",
            109 => $CI->lang->line('response_error_occur'),
            110 => $CI->lang->line('response_registration'),
            112 => 'Please select image',
            113 => 'Please select video',
            114 => "No results found right now",
            115 => "You're temporarily blocked from posting",
            116 => "User already registered",
            117 => $CI->lang->line('response_isEmail_exist'),
            118 => $CI->lang->line('response_error'),
            119 => $CI->lang->line('response_contact_verify'),
            120 => "A new password has been sent on your registered email",
            121 => $CI->lang->line('response_user_inactive'),
            122 => "Invalid Email or Password",
            123 => "Wrong Email or Username",
            124 => "Wrong Password",
            125 => "Successfully added",
            126 => $CI->lang->line('response_success'),
            127 => "Your status updated",
            128 => $CI->lang->line('response_generate_otp'),
            129 => $CI->lang->line('response_wrong_otp'),
            130 => $CI->lang->line('response_contact_not_exist'),
            131 => $CI->lang->line('response_send_otp'),

      
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => $CI->lang->line('response_found'),
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Please Verify Your Contact No',
            507 =>$CI->lang->line('response_ok'),
            508 =>$CI->lang->line('response_invalid_type'),
            509 =>$CI->lang->line('response_no_data_found'),
            510=>$CI->lang->line('response_no_email'),
            511=>$CI->lang->line('response_send_email'),
            512=>$CI->lang->line('response_data_update'),
            513=>$CI->lang->line('response_contact_exist'),
            514=>$CI->lang->line('response_club_added'),//Club added successfully
            515=>$CI->lang->line('response_club_leaved'),//Club leaved successfully
            516=>$CI->lang->line('response_feed_added'),//Feed added successfully
            517=>$CI->lang->line('response_already_applied'),//You already applied for this club
            518=>$CI->lang->line('response_successfully_applied'),//Successfully applied
            519=>$CI->lang->line('response_successfully_accepted'),//request accepted successfully
            520=>$CI->lang->line('response_error_otp'),//Problem sending OTP on given number
            521=>$CI->lang->line('response_success_otp'),//We have sent a PIN on given contact number. Please verify to continue
            522=>$CI->lang->line('response_club_not_allowed_join'),//Club is not allowed to join
            523=>$CI->lang->line('response_club_not_allowed_like'),//Club is not allowed to like
            524=>$CI->lang->line('response_request_remove'),//Request removed successfully
            525=>$CI->lang->line('response_already_answer'),//Request already accepted
            526=>$CI->lang->line('response_club_not_exist'),//Club id not exist
            527=>$CI->lang->line('response_news_feed_not_exist'),//News feed id not exist
            528=>$CI->lang->line('response_club_cat_not_exist'),//Club category id not exist
            529=>$CI->lang->line('response_data_not_exist'),//data not exist
            530=>$CI->lang->line('response_user_tag_exist'),//tag already exist
            531=>$CI->lang->line('response_add_user_tag'),//Tag added successfully
            532=>$CI->lang->line('response_club_member_status'),//Member status updated successfully
            533=>$CI->lang->line('response_club_user_id'),//Club user id not exist
            534=>$CI->lang->line('response_club_feed_status_update'),//Status updated successfully
            535=>$CI->lang->line('response_create_activity'),
            536=>$CI->lang->line('response_create_activity_event'),
            537=>$CI->lang->line('response_limit_activity_event'),
            538=>$CI->lang->line('response_hide_activity'),
            539=>$CI->lang->line('response_activity_not_exist'),
            540=>$CI->lang->line('response_display_activity'),
            541=>$CI->lang->line('response_remove_activity'),
            542=>$CI->lang->line('response_join_activity'),
            543=>$CI->lang->line('response_confirm_activity'),
            544=>$CI->lang->line('response_disjoin_activity'),
            545=>$CI->lang->line('response_unconfirm_activity'),
            546=>$CI->lang->line('response_nickname_clubmember'),
            547=>$CI->lang->line('response_max_user_activity'),
            548=>$CI->lang->line('response_news_feed_update'),
            549=>$CI->lang->line('response_no_place_confirm_activity'),
            550=>$CI->lang->line('response_delete_news_feed')
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}

?>