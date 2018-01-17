<?php


    function debug($var, $mode = 1) {
        echo '<div style="background-color: #5bc0de; padding: 5px;">';
        $trace = debug_backtrace();//Fonction prédéfinie retounant un tableau ARRAY contenant des informations tel que la ligne et le fichier ou est executé la fonction
        //echo '<pre>'; print_r($trace); echo '</pre>';
        $trace = array_shift($trace); // extrait la 1ere valeur d'un tableau et la retourne en raccourssiant le tableau d'un élément
        
        //echo '<pre>'; print_r($trace); echo '</pre>';
        echo "Debug demandé dans le fichier: $trace[file] à la ligne $trace[line] . <hr>";
        if ($mode === 1) { //Mode par défaut
            echo '<pre>'; print_r($var); echo '</pre>';
        }else { // Si le mode n'est pas 1, on tombera forcement dans le else.
            echo '<pre>'; var_dump($var); echo '</pre>';
        }
        echo '</div>';

    }

