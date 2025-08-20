<?php

// function for send data to email -- envoi de email
                $orders = Order::whereId($order['id'])
                    ->with([
                        'user',
                        'products'
                        => fn($q) => $q->with('media')
                    ])
                    ->orderBy('created_at', 'DESC')->first();

                $data_products = [];

                foreach ($orders['products']  as $key => $value) {
                    $name = $value['title'];
                    $qte = $value['pivot']['quantity'];
                    $price = $value['pivot']['unit_price'];
                    $total = $value['pivot']['quantity'] * $value['pivot']['unit_price'];

                    array_push($data_products, ['name' => $name, 'qte' => $qte, 'price' => $price, 'total' => $total]);
                }




                //new send mail to admin after order
                $mail = new PHPMailer(true);
                // require base_path("vendor/autoload.php");

                /* Email SMTP Settings */
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'mail.akadi.ci';
                $mail->SMTPAuth = true;
                $mail->Username = 'info@akadi.ci';
                $mail->Password = 'S$UBfu.8s(#z';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('info@akadi.ci', 'info@akadi.ci');
                $mail->addAddress('Restaurantakadi@gmail.com');

                $mail->isHTML(true);


                $mail->Subject = 'Nouvelle commande';

                $mail->Body    =
                    '<b> Vous avez reçu une nouvelle
                                    <br> 
                                    <h3 style="text-align:center ; margin-bottom:30px"> <u>Detail de la commande</u> </h3>
                                    <div style="margin-bottom: 10px">
                                        <p>Nom du client : ' . Auth::user()->name . '</p>
                                        <p>Contact du client : ' . Auth::user()->phone . '</p>
                                       
                                    </div>

                                    </b>';
                foreach ($data_products  as $key => $value) {
                    $mail->Body .=

                        ' 
                         <div margin-bottom:10px>
                     <b> Produit ' . ++$key . ' <div>
                    <p>Nom : ' . $value['name'] . '</p>
                     <p>qte : ' . $value['qte'] . '</p>
                     <p>prix : ' . $value['price'] . '</p>
                    </div>
                    <br>
                                        ';
                }
                $mail->Body .= '
                  <b> 
                        <p>Total : ' . $order['total'] . '</p>
                        <p>Mode de livraison : ' . $order['mode_livraison'] . '</p>
                        <p>Adresse: ' . $order['address'] . '</p>
                        <p>Adresse de yango: ' . $order['address_yango'] . '</p>
                        <p>Note de la commande: ' . $order['note'] . '</p>


                 <a href="' . env('APP_URL') . '/admin/order?s=attente">Voir les commandes en attente</a>


                 <b>
                
                ';

                // $mail->addAttachment("storage/" . $orders['id'] . ".pdf");
                $mail->send();


