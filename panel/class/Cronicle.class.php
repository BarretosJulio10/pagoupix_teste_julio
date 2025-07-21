<?php

/**
 * Cronicle – Integração com a API oficial
 */
class Cronicle {

    private $apiKey;
    private $host;
    private $category;
    public $client_id;
    public $cronjobid = null;

    public function __construct($id = 0) {
        $this->client_id = $id;
        $this->host = 'http://62.171.160.245:3012'; // ou https://seu.dominio
        $this->apiKey = '3085f373a17fa295cd910a061ab02522'; // gere no painel do Cronicle
        $this->category = 'cmcdwsxet03';
    }

    private function request(string $endpoint, array $data) {
        $url = "{$this->host}/api/app/{$endpoint}/v1?api_key={$this->apiKey}";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($data)
        ]);

        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($code >= 200 && $code < 300) ? json_decode($res) : false;
    }

    public function addCron($setting) {
        
         // definir hora --------------
         $hours_explode = explode('-',$setting->hours_charge);
         $hour          = rand($hours_explode[0], $hours_explode[1]);
         $minute       = rand(0,59);
         // definir hora fim  --------

        $weekdays = [0, 1, 2, 3, 4, 5, 6];
        if ($setting->days_charge !== 'all' && $setting->days_charge !== 'false') {
            $weekdays = is_array($setting->days_charge)
                ? $setting->days_charge
                : [$setting->days_charge];
        }

        $url = "https://pagoupix.com.br/api/cron/charges/{$this->client_id}";

        $payload = [
            "title" => "Cobranças do cliente #{$this->client_id}",
            "enabled" => 1,
            "plugin" => "http",
            "category" => $this->category,
            "target" => "allgrp",
            "params" => [
                "url" => $url,
                "method" => "GET"
            ],
            "plugin" => "urlplug",
            "timezone" => "America/Sao_Paulo",
            "timing" => [
                "hours" => [$hour],
                "minutes" => [$minute],
                "weekdays" => $weekdays
            ]
        ];

        $res = $this->request('create_event', $payload);
        
        if ($res && isset($res->code) && $res->code === 0) {
            $this->cronjobid = $res->id;
            return true;
        }

        return false;
    }

    public function removeCron($event_id) {
        $res = $this->request('delete_event', ['id' => $event_id]);
        return $res && $res->code === 0;
    }

    public function getCron($event_id) {
        return $this->request('get_event', ['id' => $event_id]);
    }

    public function runNow($event_id) {
        $res = $this->request('run_event', ['id' => $event_id]);
        return $res && $res->code === 0;
    }
}
