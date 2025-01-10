<?php

@session_start();

if (isset($_SESSION['CLIENT'], $_POST['dados'])) {

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        if ($_POST['dados'] != '') {

            $dados = trim($_POST['dados']);
            $dados = json_decode($dados);

            require_once '../../../class/Conn.class.php';
            require_once '../../../class/Options.class.php';
            require_once '../../../class/Cron.class.php';

            $options = new Options($client_id);
            $cron = new Cron($client_id);
            $setting_charge = $options->getOption('setting_charge', true);


            if (isset($_POST['juros'])) {

                if ($dados->juros_n <= 0) {
                    echo json_encode(['erro' => true, 'message' => 'Defina uma  porcentagem de juros']);
                    exit;
                }

                if ($dados->cobrar_multa == "sim") {
                    if ($dados->valor_multa == '') {
                        echo json_encode(['erro' => true, 'message' => 'Informe o valor da multa']);
                        exit;
                    } else {
                        $dados->valor_multa = str_replace(' ', '', str_replace('R$', '', $dados->valor_multa));
                    }
                }


                $setting_juros_multa = $options->getOption('setting_juros_multa', true);

                if ($setting_juros_multa) {

                    // update
                    $update = $options->editOption('setting_juros_multa', json_encode($dados));
                    if ($update) {
                        echo json_encode(['erro' => false, 'message' => 'Juros e multa alterado com sucesso.']);
                    } else {
                        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
                    }

                } else {
                    // create
                    $addopt = $options->addOption('setting_juros_multa', json_encode($dados));
                    if ($addopt) {
                        echo json_encode(['erro' => false, 'message' => 'Juros e multa alterado com sucesso.']);
                    } else {
                        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
                    }
                }

                exit;
            }


            if ($setting_charge) {
                // update 
               
                if (isset($_POST['last'], $_POST['interval'])) {

                    if (json_decode($setting_charge)->days_charge != "all") {
                        echo json_encode(['erro' => true, 'input_alert' => 'days_charge', 'message' => 'Para definir as cobranças após o vencimento, defina a verificação de cobranças todos os dias']);
                        exit;
                    }

                    if ($_POST['last'] == 1) {

                        if(!isset($dados->type, $dados->charge_last_1, $dados->charge_last_2, $dados->charge_last_3, $dados->charge_last_4, $dados->active)){
                            die(json_encode(['erro' > true, 'message' => 'Informe todos os campos']));
                        }

                        $setting_charge_interval = $options->getOption('setting_charge_interval');
                        $setting_charge_interval = $setting_charge_interval ? json_decode($setting_charge_interval) : false;

                        $setting_charge_last = $options->getOption('setting_charge_last', true);
                        if ($setting_charge_last) {
                            // update
                            $update = $options->editOption('setting_charge_last', json_encode($dados));
                            if ($update) {

                                if ($setting_charge_interval && $dados->active == 1) {
                                    $setting_charge_interval->active = 0;
                                    $options->editOption('setting_charge_interval', json_encode($setting_charge_interval));
                                }

                                echo json_encode(['erro' => false, 'message' => 'Cobranças após o vencimento alteradas com sucesso.']);
                            } else {
                                echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
                            }
                        } else {
                            // create
                            $addopt = $options->addOption('setting_charge_last', json_encode($dados));
                            if ($addopt) {

                                if ($setting_charge_interval && $dados->active == 1) {
                                    $setting_charge_interval->active = 0;
                                    $options->editOption('setting_charge_interval', json_encode($setting_charge_interval));
                                }

                                echo json_encode(['erro' => false, 'message' => 'Cobranças após o vencimento alteradas com sucesso.']);
                            } else {
                                echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
                            }
                        }

                    }


                    if ($_POST['interval'] == 1) {

                        if(!isset($dados->type, $dados->interval_days, $dados->max_send, $dados->next_date, $dados->active)){
                            die(json_encode(['erro' > true, 'message' => 'Informe todos os campos']));
                        }

                        $setting_charge_last = $options->getOption('setting_charge_last');
                        $setting_charge_last = $setting_charge_last ? json_decode($setting_charge_last) : false;

                        $setting_charge_interval = $options->getOption('setting_charge_interval', true);
                        if ($setting_charge_interval) {
                            // update
                            $update = $options->editOption('setting_charge_interval', json_encode($dados));
                            if ($update) {

                                if ($setting_charge_last && $dados->active == 1) {
                                    $setting_charge_last->active = 0;
                                    $options->editOption('setting_charge_last', json_encode($setting_charge_last));
                                }
                                
                                echo json_encode(['erro' => false, 'message' => 'Cobranças após o vencimento alteradas com sucesso.']);

                            } else {
                                echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
                            }
                        } else {
                            // create
                            $addopt = $options->addOption('setting_charge_interval', json_encode($dados));
                            if ($addopt) {

                                if ($setting_charge_last && $dados->active == 1) {
                                    $setting_charge_last->active = 0;
                                    $options->editOption('setting_charge_last', json_encode($setting_charge_last));
                                }
                                
                                echo json_encode(['erro' => false, 'message' => 'Cobranças após o vencimento alteradas com sucesso.']);

                            } else {
                                echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
                            }
                        }

                    }


                    exit;
                }


                try {

                    $setting_charge = json_decode($setting_charge);

                    $cron->removeCron($setting_charge->cronjobid);

                    $setting = new stdClass();
                    $setting->days_charge = $dados->days_charge;
                    $setting->hours_charge = $dados->hours_charge;
                    $setting->days_antes_charge = $dados->days_antes_charge;
                    $setting->expire_date_days = $dados->expire_date_days;
                    $setting->wpp_charge = $dados->wpp_charge;

                    if ($dados->days_charge != "false") {

                        if ($cron->addCron($setting)) {


                            $setting->cronjobid = $cron->cronjobid;

                            $setting_json = json_encode($setting);
                            $update = $options->editOption('setting_charge', $setting_json);

                            if ($update) {
                                echo json_encode(['erro' => false, 'message' => 'Configurações alteradas']);
                            } else {
                                echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde']);
                            }
                        } else {
                            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde']);
                        }

                    } else {
                        $setting_json = json_encode($setting);
                        $update = $options->editOption('setting_charge', $setting_json);

                        if ($update) {
                            echo json_encode(['erro' => false, 'message' => 'Configurações alteradas']);
                        } else {
                            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde']);
                        }
                    }

                } catch (\Exception $e) {
                    echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde']);
                }

            } else {

                if ($dados->days_charge != "false") {


                    if (isset($_POST['last'])) {
                        echo json_encode(['erro' => true, 'message' => 'Primeiro configure as cobranças no card acima']);
                        exit;
                    }

                    // add
                    $setting = new stdClass();
                    $setting->days_charge = $dados->days_charge;
                    $setting->hours_charge = $dados->hours_charge;
                    $setting->days_antes_charge = $dados->days_antes_charge;
                    $setting->wpp_charge = $dados->wpp_charge;

                    /*Add cronjob*/
                    if ($cron->addCron($setting)) {

                        // save cron id
                        $setting->cronjobid = $cron->cronjobid;

                        $setting_json = json_encode($setting);

                        $addopt = $options->addOption('setting_charge', $setting_json);

                        if ($addopt) {
                            echo json_encode(['erro' => false, 'message' => 'Configurações alteradas']);
                        } else {
                            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
                        }

                    } else {
                        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
                    }

                } else {
                    echo json_encode(['erro' => false, 'message' => 'Configurações alteradas']);
                }

            }


        } else {
            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
        }

    } catch (\Exception $e) {
        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

}
