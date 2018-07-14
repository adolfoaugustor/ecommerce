<?php

if (!function_exists('view')) {

    function view($service, $view, $layout, $data) {

        $layout = __DIR__ . "/../views/layouts/$layout.php";

        $view = __DIR__ . "/../views/$view.php";

        if (!file_exists($view)) {

            throw new \Exception('view not found', 500);
        }

        return $service->layout($layout)->render($view, $data);
    }
}

if (!function_exists('authIsLogged')) {

    function authIsLogged() {

        if (array_key_exists('authIsLogged', $_SESSION)) {

            return $_SESSION['authIsLogged'];
        }

        return false;
    }
}

if (!function_exists('authIsLoggedOrRedirectToLogin')) {

    function authIsLoggedOrRedirectToLogin($response) {

        if (!authIsLogged()) {

            return $response->redirect('/login')->send();
        }
    }
}

if (!function_exists('authUser')) {

    function authUser() {

        if (array_key_exists('authUser', $_SESSION)) {

            return $_SESSION['authUser'];
        }

        return null;
    }
}

if (!function_exists('getDatabaseFields')) {

    function getDatabaseFields($fields, $className) {

        $classFields = array_keys(get_class_vars($className));

        if (empty($fields)) {

            $fields = $classFields;

        } else {

            if ($fields === ['*']) {

                $fields = $classFields;
            }

            $fields = array_values(array_intersect($classFields, $fields));
        }

        return $fields;
    }
}

if (!function_exists('strToSlug')) {

    function strToSlug($str, $delimiter = '-') {

        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));

        return $slug;

    }
}

if (!function_exists('uploadFile')) {

    function uploadFile($key) {

        if (array_key_exists($key, $_FILES)) {

            $uploadFolder = __DIR__.'/../public/uploads';

            if (!is_dir($uploadFolder)) {

                mkdir($uploadFolder, 0775);
            }

            $name = pathinfo($_FILES[$key]['name'], PATHINFO_FILENAME);
            $extension = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);

            $pathNewFile = sprintf('%s/%s.%s', $uploadFolder, $name, $extension);

            while (is_file($pathNewFile)) {

                $name = $name.'-'.strRandom(5);
                $pathNewFile = sprintf('%s/%s.%s', $uploadFolder, $name, $extension);
            }

            if (move_uploaded_file($_FILES[$key]['tmp_name'], $pathNewFile)) {

                return sprintf('/uploads/%s.%s', $name, $extension);
            }
        }

        return null;
    }
}

if (!function_exists('strRandom')) {

    function strRandom($length = 0) {

        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
}

if (!function_exists('sendMail')) {

    function sendMail($subject = '', array $to = [], $body = '') {

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try
        {
            $mail->SMTPDebug = false;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = base64_decode('c2NvZmlsZG5vc2ZlcmF0dUBnbWFpbC5jb20=');
            $mail->Password = base64_decode('MjRzbmZhYXI=');
            $mail->SMTPSecure = 'tls'; // tls, ssl, null
            $mail->Port = 587; // 587 (tls), 465 (ssl)
            $mail->setFrom('admin@admin.com', 'Administrator');
            array_walk($to, function($item) use (&$mail) {
                $mail->addAddress($item['email'], $item['name']);
            });
//            $mail->setLanguage('br', __DIR__.'/../vendor/phpmailer/phpmailer/language');
            $mail->addCC('adolfoaugustor@gmail.com', 'Adolfo Augusto');
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            return $mail->send();
        }
        catch(\Exception $e)
        {
            return $mail->ErrorInfo;
        }
    }
}

if (!function_exists('dateEN2BR')) {

    function dateEN2BR($dateString) {

        return implode('/', array_reverse(explode('-', $dateString)));
    }
}

if (!function_exists('dateBR2EN')) {

    function dateBR2EN($dateString) {

        return implode('-', array_reverse(explode('/', $dateString)));
    }
}

if (!function_exists('calcPercent')) {

    function calcPercent($total, $value) {

        return number_format((($value * 100) / $total) - 100, 0);
    }
}

/*
if (!function_exists('xxx')) {

    function xxx() {

        return null;
    }
}
*/
