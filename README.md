# mpago
Package para usar MercadoPago en Laravel.




## Install

Instalar el paquete

```bash
  composer require ales0sa/mpago
```
## Configurar .env

Agregar las credenciales de MercadoPago en el .env

```bash
  MP_TOKEN=*******************************************
  MP_PUBLIC_KEY=*************************
```


## Ejemplo de uso

```bash

Route::get('mpago', function(Ales0sa\Mpago\Mpago $mp) {

    $ref = 'uuid_del_pedido';

    $items = array(
        array(
            'title' => 'ITEM DE PRUEBA 1',
            'quantity' => 2,
            'unit_price' => 2.5,
        ),
        array(
            'title' => 'ITEM DE PRUEBA 2',
            'quantity' => 1,
            'unit_price' => 3,
        ),
        array(
            'title' => 'ITEM DE PRUEBA 3',
            'quantity' => 2,
            'unit_price' => 1,
        )
    );

    $data = $mp->newOrder($items, $ref);
    return view('checkout', compact('data')); 
});

Route::get('mpago/{id}', function(Ales0sa\Mpago\Mpago $mp, $id) {
       return $mp->findPayment( $id );
});


Route::get('feedback', function(Ales0sa\Mpago\Mpago $mp) {
    if(request()->get('payment_id')){
        $check = $mp->findPayment(request()->get('payment_id'));
        if($check){
            dd($check->status, $check);
        }else{
            dd('No se encontro el pago');
        }
    }
});


```


## Vista basica para el boton de pago 

Crear */resources/view/checkout.blade.php*

```bash
<script src="https://sdk.mercadopago.com/js/v2"></script>

<div class="cho-container"></div>

<script>
  const mp = new MercadoPago('{{ $data['public_key'] }}', {
    locale: 'es-AR'
  });

  mp.checkout({
    preference: {
      id: "{{ $data['id'] }}"
    },
    render: {
      container: '.cho-container',
      label: 'Pagar',
    }
  });
</script>
```