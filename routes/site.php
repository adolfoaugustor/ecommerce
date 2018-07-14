<?php

$routes->get('/', function($req, $res, $service) {

    $products = \src\models\Product::getAll();

    return view($service, 'home', 'site', array(
        'page_title' => 'Home',
        'products' => $products,
    ));
});

$routes->get('/products/[:slug]', function($req, $res, $service) use ($routes) {

    /**
     * @var $req \Klein\Request
     * @var $res \Klein\Response
     * @var $service \Klein\ServiceProvider
     */

    $slug = $req->param('slug');

    $product = \src\models\Product::findBySlug($slug);

    if (empty($product)) $routes->abort(404);

    $page_title = 'Product';

    return view($service, 'product', 'site', compact('page_title', 'product'));
});

$routes->post('/products/[:slug]/book-now', function($req, $res, $service) use ($routes) {

    /**
     * @var $req \Klein\Request
     * @var $res \Klein\Response
     * @var $service \Klein\ServiceProvider
     */

    $slug = $req->param('slug');

    /** @var \src\models\Product $product */
    $product = \src\models\Product::findBySlug($slug);

    if (empty($product)) $routes->abort(404);

    try
    {
        $name = $req->param('name');
        $email = $req->param('email');
        $cep = $req->param('cep');

        $result = [
            'code' => 500,
            'message' => 'Error',
        ];

        if (!empty($cep) && !empty($name) && !empty($email) && strlen($cep) === 8) {

            /** @var \src\models\Promotion $promotion */
            $promotion = $product->latestPromotion();

            $to = [
                ['name' => $name, 'email' => $email],
            ];
            $subject = 'Book Now';
            $body = sprintf(
                '<h2>Buyer Info</h2><p>Name: %s<br/>Email: %s<br/>Cep: %s</p><h2>Product Info</h2><p>Id: %s<br/>Name: %s<br/>Price: %s</p>',
                $name,
                $email,
                $cep,
                $product->id,
                $product->name,
                !empty($promotion) ? $promotion->getPrice() . ' ('.calcPercent($product->price, $promotion->price).'%)' : $product->getPrice()
            );

            $sendMail = sendMail($subject, $to, $body);

            if (is_string($sendMail)) {

                $result['message'] = $sendMail;

            } else {

                if ($sendMail) {

                    $result['code'] = 200;
                    $result['message'] = "Purchase made successfully\nEmail successfully sent";

                } else {

                    $result['message'] = 'There was an error with your purchase.';
                }
            }
        }
        else
        {
            $result = [
                'code' => 500,
                'message' => 'Preencha todos os campos',
            ];
        }
    }
    catch(\Exception $e)
    {
        $result = [
            'code' => 500,
            'message' => 'error 0',
            'error_info' => [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]
        ];
    }

    header('Content-Type: application/json');

    return json_encode($result);
});

$routes->post('/frete/calculate/[:from]/[:to]', function($req, $res, $service) {

    /**
     * @var $req \Klein\Request
     * @var $res \Klein\Response
     * @var $service \Klein\ServiceProvider
     */

//    $data = json_decode(file_get_contents('php://input'), true);

    $from = $req->param('from');
    $to = $req->param('to');
//    $type = $data['type'];
    $type = \EscapeWork\Frete\Correios\Data::PAC;
    $product = \src\models\Product::findBySlug($req->param('slug'));

    try {

        $frete = new \src\util\CustomPrecoPrazo();
        $frete->setCodigoServico($type)
    //        ->setCodigoEmpresa('Codigo')      # opcional
    //        ->setSenha('Senha')               # opcional
            ->setCepOrigem($from)
            ->setCepDestino($to)
            ->setPeso($product->weight / 1000)
            ->setAltura($product->height)
            ->setLargura($product->width)
            ->setComprimento($product->length)
            ->setDiametro($product->diameter);

        $cServico = $frete->calculate()['cServico'];

        $result = [
            'code' => 200,
            'message' => sprintf('PAC - R$ %s - %s dia(s).', $cServico['Valor'], $cServico['PrazoEntrega']),
            'value' => $cServico['Valor'],
            'deadline' => $cServico['PrazoEntrega'],
        ];
    }
    catch (\Exception $e) {

        $result = [
            'code' => 500,
            'message' => 'Error',
            'error_info' => [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ],
        ];
    }

    header('Content-Type: application/json');

    return json_encode($result);
});

$routes->get('/login', function($req, $res, $service) {

    /**
     * @var $req \Klein\Request
     * @var $res \Klein\Response
     * @var $service \Klein\ServiceProvider
     */

    if (authIsLogged()) {

        return $res->redirect('/admin')->send();
    }

    return view($service, 'login', 'site', array(
        'page_title' => 'Login',
    ));
});

$routes->post('/login', function($req, $res, $service) {

    /**
     * @var $req \Klein\Request
     * @var $res \Klein\Response
     * @var $service \Klein\ServiceProvider
     */

    $data = $req->params();

    $hasError = false;
    $keyFields = [
        'email' => 'Email',
        'password' => 'Password'
    ];

    foreach (array_keys($keyFields) as $keyField) {

        if (!array_key_exists($keyField, $data)) {

            $hasError = true;
            $service->flash(sprintf('The field \'%s\' is required', $keyFields[$keyField]), 'danger');
        }
    }

    if (!$hasError)
    {
        if ($data['email'] === 'admin@admin.com' && md5($data['password']) === 'c21f969b5f03d33d43e04f8f136e7682')
        {
            $user = new \stdClass();
            $user->name = 'Administrator';
            $user->email = $data['email'];
            $user->password = md5($data['password']);

            $_SESSION['authIsLogged'] = true;
            $_SESSION['authUser'] = $user;

            return $res->redirect('/admin')->send();
        }
    }

    $service->flash('Invalid credentials', 'danger');

    return $res->redirect('/login')->send();
});

$routes->respond('get', '/logout', function($req, $res, $service) {

    session_destroy();

    return $res->redirect('/login')->send();
});

$routes->respond('get', '/create-tables', function($req, $res, $service) {

    \src\db\Database::createTableProducts();
    \src\db\Database::createTablePromotions();

    header('Content-Type: application/json');

    echo json_encode([
        'code' => 200,
        'message' => 'success',
    ]);
});
