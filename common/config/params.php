<?php

$remote_ip = "172.16.152.50";
$remote_address = "http://172.16.152.50/";
$api_file = $remote_address."asterisk_api_lib/";

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,

    'IP_ADDRESS' => $remote_ip,
    'USER_ID' => 'root',
    'PASSWORD' => 'root@123',
    'S_LOCATION' => '/var/www/html/APIs/',
    'LOG_LOCATION' => '/var/www/html/edas_vaani_dev/backend/logs/',
    'DYNAMIC_TABLES_FILE' => '/var/www/html/edas_vaani_dev/common/dynamic_asterisk.txt',
    
    // APIS CREDS
    
    'VAANI_REMOTE_API_USERNAME' => 'test',          // REMOTE API USER NAME
    'VAANI_REMOTE_API_PASSWORD' => 'test@123',      // REMOTE API PASSWORD
    
    // ASTERISK REMOTE APIs (Start) //

    'ADD_SIP_API' => $api_file.'add_new_sip.php',       // To Add sip in queue
    "REMOVE_SIP_API" => $api_file."remove_existing_sip.php",    // To Remove member from Sales queue
    "PAUSE_API" => $api_file."agent_call_pause.php",    // For Pause Queue member
    "UNPAUSE_API" => $api_file."agent_call_unpause.php",    // For UnPause Queue Member
    "AGENT_CALL_HANGUP" => $api_file."customer_call_hangup.php",    // For UnPause Queue Member
    "AGENT_CALL_HOLD" => $api_file."agent_call_hold.php",   // For agent hold the live call
    "AGENT_CALL_UNHOLD" => $api_file."agent_call_unhold.php",   // For agent unhold the live call
    "AGENT_MANUAL_DIAL" => $api_file."agent_manual_dial.php",   // For agent manual dial
    "AGENT_BLINDCALL_TRANSFER" => $api_file."agent_blind_transfer.php",         // For agent blind call transfer
    "AGENT_BLINDCALL_TRANSFERAHold" => $api_file."agent_blind_transfer_afertHold.php",      // For agent blind call transfer after hold
    "INCALL_ACTION_API" => $api_file."incall_action.php",      // For incall_action of monitor/whisper/barge-in
    "asterisk_reload" => $api_file."api_to_send_data.php",      // asterisk reload
    "SERVICE_KEY_API" => $api_file."service_key.php",    // service key

    // ASTERISK REMOTE APIs (End) //

    "remo_context_url" => "/etc/asterisk/extensions-edas.conf",     // remote server context url (extensions-edas_temp.conf)
    "remo_extension_url" => "/etc/asterisk/extensions.conf",       // remote server extenstion url (extensions_temp.conf)
    "remo_queue_url" => "/etc/asterisk/queues.conf",       // remote server queue url (queues_temp.conf)
    "remo_sip_url" => "/etc/asterisk/sip-edas.conf",       // remote server sip url (sip_temp.conf)
    // "remo_sip_url" => "/etc/asterisk/sip.conf",       // remote server sip url (sip_temp.conf)
    "sound_path" => "/var/lib/asterisk/sounds/Ivr/edas",       // remote server sounds path
    "recordings_path" => "/recordings",                        // remote server recordings path
    "asterisk_backup" => "/etc/asterisk/asterisk_backup",       // remote server backup path
    "client_ini_path" => "/uc/config/ini",       // remote server queue ini path
    "features_url" => "/etc/asterisk/features.conf",       // remote server features file path

    // local matching //
    
    // "context_url" => "/srv/www/htdocs/vaani_dashboard/extensions_context.txt",      // context url (extensions-edas.conf)
    // "extension_url" => "/srv/www/htdocs/vaani_dashboard/extensions_routing.txt",   // extenstion url (extensions.conf)

    // database details "VICI@eos","eos_test"
    "DB_HOST" => "172.16.4.101",
    "USER" => "dbadmin",
    "DB_PASSWORD" => "VICI@eos",
    "DB_NAME" => "eos_test",
    "TABLE" => "pdf_data", // need two columns in this table PDF_NAME, PDF_TEXT

    // database details of primary db
    "PDB_HOST" => "VAUq99m88Cta1a/fjg==",
    "PUSER" => "BkB3tw==",
    "PDB_PASSWORD" => "VAAr7Q==",
    "PDB_NAME" => "BEFsvJrjrXE=",
    "PPORT" => "VgEo7w==",
    
    // database details of dummy db for copy tables
    "DUM_DB_HOST" => "VAUq99m88CteybXc",
    "DUM_USER" => "AVB5vYXjsA==",
    "DUM_DB_PASSWORD" => "M3tbkKjvsWk=",
    "DUM_DB_NAME" => "AUt2uIXjvUUOlPWPzBghVA==",
    "DUM_PORT" => "VgEo7w==",
];