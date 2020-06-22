<?php

use \Sts\Page;
use \Sts\ApiVendala;

#unset($_SESSION['User']['currentListProducts']);

$app->get('/', function () { 

    $oApiVendala = new ApiVendala();

    $oApiVendala->verifyLogin();

    $page = new Page();
    $page->setTpl("index",[
        'listProducts'=> $oApiVendala->getProducts(false)['data'],
        'listCategories'=> $oApiVendala->getCategories()
    ]);
});


$app->post('/save-product',function(){  
    $data = ["dataForm"=>$_POST,"dataFile"=>$_FILES];
    $oApiVendala = new ApiVendala();
    $result = $oApiVendala->saveProduct($data);
    unset($_SESSION['User']['currentListProducts']);
    exit;
});

$app->post('/login',function(){

  $fields = [
      "email" => $_POST['email'],
      "password" => trim($_POST['password'])
  ];

  $oApiVendala = new ApiVendala();  

  $response = $oApiVendala->getLoginWithToken($fields);

  if($response){
    $_SESSION['User']['access_token'] = $response['access_token'];
    die(json_encode(['success'=>true,'msg'=>'Usuário logado com sucesso!']));
} else {
    $_SESSION['User']['access_token'] = "";    
    die(json_encode(['success'=>false,'msg'=>'Usuário ou senha inválidos!']));
}
});

$app->get('/login',function(){  
    $page = new Page();
    $page->setTpl('login',[]);
});

$app->get('/logout',function(){  
    $oApiVendala = new ApiVendala();  
    $oApiVendala->logout();
    header("Location: login");
    exit;
});

$app->delete('/products/:id',function($id){
    $oApiVendala = new ApiVendala();
    $result = $oApiVendala->deleteProduct($id);
    die($result);
});

$app->get('/products',function(){

    $pg = $_GET['page'];  
    $page = new Page();
    $oApiVendala = new ApiVendala();  

    $oApiVendala->verifyLogin();

    $qtdPagesProductApi = $oApiVendala->getProducts(true)['data']['last_page'];

    $pages = [];
    for ($x=0; $x <  $qtdPagesProductApi ; $x++) { 
        array_push($pages, [
            'href'=>'/products?'.http_build_query([
                'page'=>$x+1,
                'search'=>$search
            ]),
            'text'=>$x+1
        ]);
    }

    $page->setTpl('products',[
        'listProducts'=> $oApiVendala->getProducts(true,$pg)['data']['data'],
        'pages'=> $pages
    ]);

});

$app->post('/add-product-list',function(){
    $_SESSION['User']['currentListProducts'][$_POST['idProduct']] = ['qtd'=>$_POST['qtd'],'nameProduct'=>$_POST['nameProduct']];
    die(json_encode($_SESSION['User']['currentListProducts']));
});

$app->post('/remove-product-list',function(){
    unset($_SESSION['User']['currentListProducts'][$_POST['idProduct']]);
    die(json_encode($_SESSION['User']['currentListProducts']));
});

$app->post('/getting-ready-list',function(){
    $aProductList = $_SESSION['User']['currentListProducts'];
    $aList = [];
    foreach ($aProductList as $product) {
        $aList[] = $product['qtd'] ." x ". $product['nameProduct'] ;
    }
    die(implode(" | ",$aList));
});



