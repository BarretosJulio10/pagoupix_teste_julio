<?php

date_default_timezone_set('America/Sao_Paulo');


if ($_SERVER['SERVER_NAME'] == 'pagou.pix') {
    $dirname = ((object) pathinfo($_SERVER['SCRIPT_NAME']))->dirname;
    $dirname = str_replace('/panel', '', $dirname);
    define('SITE_URL', 'http://' . $_SERVER['SERVER_NAME']);
} else if($_SERVER['SERVER_NAME'] == 'localhost') {
    $dirname = ((object) pathinfo($_SERVER['SCRIPT_NAME']))->dirname;
    $dirname = str_replace('/panel', '', $dirname);
    define('SITE_URL', 'http://' . $_SERVER['SERVER_NAME'].':8000');
} elseif ($_SERVER['SERVER_NAME'] == 'pagoupix.computatus.org')
    define('SITE_URL', 'https://pagoupix.computatus.org');
else define('SITE_URL', 'https://pagoupix.com.br');

define('FORM_URL', 'https://form.pagoupix.com.br');
define('SITE_TITLE', 'Pagou Pix');
$url_system = SITE_URL . '/';

// PIN aprovacao de comprovantes
define('PIN_COMP', 1111);

// Dias faltando para mostrar aviso de vencimento
define('DAYS_DUE', 7);
 
// define o valor da assinatura
define('VALOR_ASSINATURA', '49,90');

// whatsapp compra de creditos parceiro
define('WPP_SUPORTE', '21982153814');

// dados pix plataforma
define('CHAVE_PIX', '59f23ab8-10b9-4b3c-ad7a-c67c67f59a1c');
define('BENIFICIARIO_PIX', 'hyper tracker');

//cores do site

define('SIDEBAR_COR', '#071332'); //blue | green | orange | red | yellow ou hexadecimal //  --cor sidebar
//define('SIDEBAR_COR','#07121a'); //blue | green | orange | red | yellow ou hexadecimal //  --cor sidebar

define('SIDEBAR_OPC_COR', '#114DE6');  // hexadecimal //  --cor opcoes menu
//define('SIDEBAR_OPC_COR','#01998e');  // hexadecimal //  --cor opcoes menu

define('SIDEBAR_LINK_COR', '#FFFFFF');  // hexadecimal //  --cor opcoes menu

define('COR_HEAD_1', '#3320A8');  // hexadecimal // --cor head panel
//define('COR_HEAD_1','#01423d');  // hexadecimal // --cor head panel

define('COR_HEAD_2', '#071332');  // hexadecimal // --cor head panel
//define('COR_HEAD_2','#07121a');  // hexadecimal // --cor head panel

// extensao do audio gravado pelo user (nao mexer)
$ext_audio = ".ogg";

// google auth login
define('AUTH_G_ENABLE', false);
define('AUTH_G_CLIENT_ID', '');
define('AUTH_G_CLIENT_SECRET', '');

// key checkout
define('KEY_CHECKOUT', '832YBVE78204POXSV23-34987OPEVX83920-X$SD09878X-22WS-23894765XCZXWQ435HTER564');

// recapctha google
if ($_SERVER['SERVER_NAME'] != 'pagou.pix') {
    if ($_SERVER['SERVER_NAME'] == 'pagoupix.computatus.org') {
        $key_site = '6LfVAYcnAAAAAMfF53LEzYWUDz1TXZJ4kiNFzWQP';
        $key_secret = '6LfVAYcnAAAAAHldFQhyEydNNISW01kiTorEzQe9';
    } else {
        $key_site = '6Lf1Y9kaAAAAAIiaH283UrUTzM0UgxZ529fWvsqv';
        $key_secret = '6Lf1Y9kaAAAAAFYa2xfLE-0l8jw9_DaVvYtQ2IQN';
    }
} else {
    $key_site = '6LfWa3cpAAAAABybNzcFXVHnTIUHf-1a4jQKRSoK';
    $key_secret = '6LfWa3cpAAAAAPwO6vn8Lcqs2j4isEnFaA3dcWXa';
}

function due_date($expire, $html = true)
{
    $now = strtotime('now');

    if ($now > $expire) {
        if ($html) :
            echo '<div class="blocked_sig" >Sua assinatura está expirada! Faça a renovação da sua assinatura <br /> <a class="btn_renew" href="buy" > <i class="fa fa-refresh" ></i> Renovar</a></div>';
        else :
            echo json_encode(['erro' => true, 'message' => 'Sua assinatura está expirada!']);
        endif;
    }
}

function verMatriz($str, $die=false) {
    echo '<pre>';
        var_dump($str);
    echo '</pre>';
    if ($die) die();
}

function due_date_sidebar($expire)
{
    $now = strtotime('now');
    if ($expire > $now) {

        $d = "dia";
        for ($i = 0; $i <= DAYS_DUE; $i++) {
            if ($i >  1) {
                $d = "dias";
            }
            $due = strtotime('+' . $i . ' days', $now);
            if (date('d/m/Y', $due) == date('d/m/Y', $expire)) {
                echo '<a href="buy" ><div style="cursor:pointer;margin: 20px;background-color: #f85a40;padding: 5px;border-radius: 7px;color: #FFF;">Faltam ' . $i . ' ' . $d . ' para o vencimento da sua assinatura</div></a>';
            }
        }
    }
}

function listarArquivos($diretorio, $caminhoBase = '')
{
    $pastas = array();
    $arquivos = array();

    // Verifica se o diretório é válido
    if (is_dir($diretorio)) {
        // Abre o diretório
        if ($handle = opendir($diretorio)) {
            // Loop através dos arquivos e subpastas
            while (($item = readdir($handle)) !== false) {
                // Ignora os diretórios "." e ".."
                if ($item != "." && $item != "..") {
                    // Verifica se o item é um arquivo
                    if (is_file($diretorio . "/" . $item)) {
                        // Adiciona o arquivo ao array de arquivos
                        $arquivos[] = $diretorio . "/" . $item;
                    } elseif (is_dir($diretorio . "/" . $item)) {
                        // Adiciona a subpasta ao array de pastas
                        $subpasta = $diretorio . "/" . $item . '_pasta@';
                        $pastas[] = $caminhoBase . $subpasta; // Inclui o caminho base da pasta
                        // Chama a função recursivamente para listar os arquivos da subpasta
                        $subArquivos = listarArquivos($subpasta, $caminhoBase);
                        // Adiciona os arquivos encontrados ao array de arquivos
                        $arquivos = array_merge($arquivos, $subArquivos);
                    }
                }
            }

            // Fecha o diretório
            closedir($handle);
        }
    }

    // Ordena as pastas em ordem alfabética
    sort($pastas);

    // Concatena as pastas e arquivos em uma única array
    $resultado = array_merge($pastas, $arquivos);

    return $resultado;
}
