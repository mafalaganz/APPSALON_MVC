<?php
    foreach($alertas as $key => $mensajes):         // Iteramos el arreglo principal $alertas
        foreach($mensajes as $mensaje):             // Iteramos el arreglo contenido en la unica posicion del arreglo principal $alertas
?>
<div class="alerta <?php echo $key; ?>">            
        <?php echo $mensaje; ?>                     
</div>                                                   
<?php
        endforeach;
    endforeach;
?>