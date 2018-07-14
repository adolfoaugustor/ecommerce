<?php

$routes->with('/admin', function () use ($routes) {

    $routes->get('/?', function ($req, $res, $service) use ($routes) {

        /**
         * @var $req \Klein\Request
         * @var $res \Klein\Response
         * @var $service \Klein\ServiceProvider
         */

        return $res->redirect('/admin/products')->send();
    });

    $routes->with('/products', function () use ($routes) {

        $routes->get('/?', function ($req, $res, $service) {

            authIsLoggedOrRedirectToLogin($res);

            $products = \src\models\Product::getAll();

            $page_title = 'Admin Products Index';

            return view($service, 'admin/products/index', 'site', compact('page_title', 'products'));
        });

        $routes->post('/?', function ($req, $res, $service) {

            /**
             * @var $req \Klein\Request
             * @var $res \Klein\Response
             * @var $service \Klein\ServiceProvider
             */

            authIsLoggedOrRedirectToLogin($res);

            $product = new \src\models\Product();

            $product->image = uploadFile('image');
            $product->name = $req->param('name');
            $product->slug = strToSlug($product->name);
            $product->price = number_format(str_replace(',', '.', $req->param('price')), 2, '.', ',');
            $product->description = $req->param('description');

            $product->weight = intval($req->param('weight'));
            $product->height = intval($req->param('height'));
            $product->width = intval($req->param('width'));
            $product->length = intval($req->param('length'));
            $product->diameter = intval($req->param('diameter'));

            $product->created_at = date('Y-m-d H:i:s');

            try
            {
                \src\models\Product::create([
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image,
                    'price' => $product->price,
                    'description' => $product->description,
                    'weight' => $product->weight,
                    'height' => $product->height,
                    'width' => $product->width,
                    'length' => $product->length,
                    'diameter' => $product->diameter,
                    'created_at' => $product->created_at,
                ]);
            }
            catch (\Exception $e)
            {
                $service->flash('There was an error creating the product<br/><small>Details: ('.$e->getMessage().')</small>', 'danger');

                $_SESSION['productCreateData'] = $product;

                return $res->redirect('/admin/products/create');
            }

            $service->flash(ucfirst($product->name).' successfully created', 'success');

            return $res->redirect('/admin/products');
        });

        $routes->get('/create', function ($req, $res, $service) {

            authIsLoggedOrRedirectToLogin($res);

            $page_title = 'Admin Products Create';

            $product = new \src\models\Product();

            if (array_key_exists('productCreateData', $_SESSION)) {

                $product = $_SESSION['productCreateData'];

                unset($_SESSION['productCreateData']);
            }

            return view($service, 'admin/products/create', 'site', compact('page_title', 'product'));
        });

        $routes->get('/[:slug]/remove', function ($req, $res, $service) {

            /**
             * @var $req \Klein\Request
             * @var $res \Klein\Response
             * @var $service \Klein\ServiceProvider
             */

            authIsLoggedOrRedirectToLogin($res);

            /**
             * @var $product \src\models\Product
             */
            $product = \src\models\Product::findBySlug($req->param('slug'));

            $productName = $product->name;

            $product->remove();

            $service->flash($productName.' deleted successfully', 'success');

            return $res->redirect('/admin/products');
        });

        $routes->get('/[:slug]/add-promotion', function ($req, $res, $service) use ($routes) {

            /**
             * @var $req \Klein\Request
             * @var $res \Klein\Response
             * @var $service \Klein\ServiceProvider
             */

            authIsLoggedOrRedirectToLogin($res);

            $page_title = 'Admin Products Add Promotion';

            $product = \src\models\Product::findBySlug($req->param('slug'));

            $promotion = new \src\models\Promotion();

            if (array_key_exists('promotionData', $_SESSION)) {

                $promotion = $_SESSION['promotionData'];

                unset($_SESSION['promotionData']);
            }

            if (empty($product)) $routes->abort(404);

            return view($service, 'admin/products/add-promotion', 'site', compact('page_title', 'product', 'promotion'));
        });

        $routes->post('/[:slug]/add-promotion', function ($req, $res, $service) use ($routes) {

            /**
             * @var $req \Klein\Request
             * @var $res \Klein\Response
             * @var $service \Klein\ServiceProvider
             */

            authIsLoggedOrRedirectToLogin($res);

            $product = \src\models\Product::findBySlug($req->param('slug'));

            if (empty($product)) $routes->abort(404);

            $promotion = new \src\models\Promotion();

            try
            {
                $promotion->product_id = $product->id;
                $promotion->start_date = dateBR2EN($req->param('start_date'));
                $promotion->end_date = dateBR2EN($req->param('end_date'));
                $promotion->price = number_format(str_replace(',', '.', $req->param('price')), 2, '.', ',');

                $promotion = \src\models\Promotion::create((array)$promotion);

                $service->flash('Promotion created successfully', 'success');

                return $res->redirect('/admin/products');
            }
            catch(\Exception $e)
            {
                $_SESSION['promotionData'] = $promotion;

                $service->flash("There was an error creating promotion<br/><small>{$e->getMessage()}</small>", 'danger');

                return $res->redirect('/admin/products/'.$product->slug.'/add-promotion');
            }
        });
    });
});
