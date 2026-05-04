<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HutchSMS {

    private $CI;
    private $loginUrl = 'https://bsms.hutch.lk/api/login';
    private $sendUrl = 'https://bsms.hutch.lk/api/sendsms';
    private $tokenUrl = 'https://bsms.hutch.lk/api/token/accessToken';
    private $username;
    private $password;
    private $mask;
    private $campaign;
    private $accessToken;
    private $refreshToken;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Configs_model');
        $this->username = $this->CI->Configs_model->fetch_config_value(null, 'sms_username');
        $this->password = $this->CI->Configs_model->fetch_config_value(null, 'sms_password');
        $this->mask = $this->CI->Configs_model->fetch_config_value(null, 'sms_mask');
        $this->campaign = $this->CI->Configs_model->fetch_config_value(null, 'sms_campaign');
    }

    private function login() {
        $ch = curl_init($this->loginUrl);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: */*',
                'X-API-VERSION: v1'
            ),
            CURLOPT_POSTFIELDS => json_encode(array(
                'username' => $this->username,
                'password' => $this->password
            ))
        ));
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === FALSE || $httpCode != 200) {
            return false;
        }
        $data = json_decode($response, TRUE);
        if (isset($data['accessToken'])) {
            $this->accessToken = $data['accessToken'];
            $this->refreshToken = isset($data['refreshToken']) ? $data['refreshToken'] : null;
            return true;
        }
        return false;
    }

    public function formatPhone($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 2) === '94' && strlen($phone) >= 11) {
            return $phone;
        }
        if (substr($phone, 0, 1) === '0') {
            return '94' . substr($phone, 1);
        }
        if (strlen($phone) === 9) {
            return '94' . $phone;
        }
        return $phone;
    }

    public function send($phone, $message) {
        if (!$this->username || !$this->password) {
            return array('status' => 'error', 'message' => 'SMS credentials not configured');
        }
        if (!$this->login()) {
            return array('status' => 'error', 'message' => 'SMS login failed');
        }
        $formattedPhone = $this->formatPhone($phone);
        $ch = curl_init($this->sendUrl);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: */*',
                'X-API-VERSION: v1',
                'Authorization: Bearer ' . $this->accessToken
            ),
            CURLOPT_POSTFIELDS => json_encode(array(
                'campaignName' => $this->campaign ?: 'Sale Receipt',
                'mask' => $this->mask ?: 'EzyPOS',
                'numbers' => $formattedPhone,
                'content' => $message
            ))
        ));
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 401) {
            return array('status' => 'error', 'message' => 'SMS auth failed (401)');
        }
        if ($response === FALSE) {
            return array('status' => 'error', 'message' => 'SMS request failed');
        }
        $data = json_decode($response, TRUE);
        return array('status' => 'success', 'response' => $data, 'serverRef' => isset($data['serverRef']) ? $data['serverRef'] : null);
    }
}
