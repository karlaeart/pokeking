<?php
/**
 * Created by PhpStorm.
 * User: salome
 * Date: 1/3/2018
 * Time: 11:06 μμ
 */
?>
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PokeKing</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Styles -->
        <style>
            #pokemon-king {
                font-weight: bold;
            }
            span {
                color: #0000F0;
            }
        </style>
        <script>
            $(function() {
                $('.poke-btn').click(function () {
                    $.ajax({
                        url: '/find/pokemon/king',
                        success: function (data) {
                            console.log(data);
                            var pokemon_king = document.getElementById('pokemon-king');
                            pokemon_king.innerHTML = '<p>The Pokemon King is <span>' + data.forms[0].name + '</span>!!!</p>';
                            pokemon_king.innerHTML += '<p>Base Experience: ' + data.base_experience + '</p>';
                            pokemon_king.innerHTML += '<p>Height: ' + data.height + '</p>';
                            pokemon_king.innerHTML += '<p>Weight: ' + data.weight + '</p>';
                            pokemon_king.innerHTML += '<img src=\'' + data.sprites.front_default + '\'/>';
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                });
            });

        </script>
    </head>
    <body>
        <div class="container">
            <div class="row pokemon-table">
                <div class="col-md-10">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">sprite (front_default)</th>
                                <th scope="col">name</th>
                                <th scope="col">base_experience</th>
                                <th scope="col">height</th>
                                <th scope="col">weight</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paginator as $pokemon) {?>
                            <tr>
                                <td><?= $pokemon[0]->sprites->front_default?></td>
                                <td><?= $pokemon[0]->forms[0]->name?></td>
                                <td><?= $pokemon[0]->base_experience?></td>
                                <td><?= $pokemon[0]->height?></td>
                                <td><?= $pokemon[0]->weight?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="text-center pagination-links">
                        {!! $paginator->links() !!}
                    </div>
                    <div class="text-center pokeking-button">
                        <button type="button" class="poke-btn btn btn-info">Declare the Pokemon King!</button>
                    </div>
                    <div id="pokemon-king"></div>
                </div>
            </div>
        </div>
    </body>
</html>
