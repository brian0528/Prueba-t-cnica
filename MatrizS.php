<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Filas y Columnas</title>
    <style>
        .seleccionada {
            background-color: #ddd;
        }
        .deshabilitada {
            pointer-events: none;
            background-color: lightgray !important;
        }
    </style>
</head>
<body>

<form id="seleccionForm" name="seleccionForm" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <table border="1">
        <?php
        $matriz = [];
        for ($i = 0; $i < 5; $i++) {
            echo '<tr>';
            for ($j = 0; $j < 10; $j++) {
                $elemento = "Elemento $i-$j";
                $casillaId = "casilla-$i-$j";
                $checkboxId = "checkbox-$i-$j";

                echo '<td>';
                echo "<label id='$casillaId'>";
                echo "<input type='checkbox' name='selecciones[]' value='$i-$j' onclick='marcarCasilla(\"$casillaId\", $i, $j)' class='casilla' id='$checkboxId'>";
                echo '</label>';
                echo '</td>';
            }
            echo '</tr>';
        }
        ?>
    </table>
    <br>
    <button type="button" onclick="marcarAleatorio()">Marcar Aleatorio</button>

</form>
<div id="resultado-selecciones"></div>

<script>
var casillasManualesSeleccionadas = 0;

function marcarCasilla(casillaId, fila, columna) {
    var casilla = document.getElementById(casillaId);

    if (casilla && !casilla.classList.contains("deshabilitada")) {
        if (casilla.classList.contains("seleccionada")) {
            desmarcarCasilla(casilla, fila, columna);
        } else if (casillasManualesSeleccionadas < 5) {
            marcarCasillaSeleccionada(casilla, fila, columna);
        }
    }
}

function desmarcarCasilla(casilla, fila, columna) {
    casilla.classList.remove("seleccionada");
    casillasManualesSeleccionadas--;

    // Eliminar estilos cuando desmarcas
    casilla.style.backgroundColor = "";
    casilla.style.color = "";

    // Restaurar el estilo de fila y columna
    var checkboxesFila = document.querySelectorAll(`.casilla[id^='checkbox-${fila}-']`);
    var checkboxesColumna = document.querySelectorAll(`.casilla[id^='checkbox-'][id$='-${columna}']`);
    checkboxesFila.forEach(function (checkbox) {
        checkbox.parentElement.classList.remove("seleccionada");
        checkbox.classList.remove("deshabilitada");
    });
    checkboxesColumna.forEach(function (checkbox) {
        checkbox.parentElement.classList.remove("seleccionada");
        checkbox.classList.remove("deshabilitada");
    });

    if (casillasManualesSeleccionadas < 5) {
        habilitarCasillas();
    }
}

function marcarCasillaSeleccionada(casilla, fila, columna) {
    casilla.classList.add("seleccionada");
    casillasManualesSeleccionadas++;

    // Aplicar estilos cuando marcas
    casilla.style.backgroundColor = "green";
    casilla.style.color = "white";

    // Deshabilitar el estilo de fila y columna
    var checkboxesFila = document.querySelectorAll(`.casilla[id^='checkbox-${fila}-']`);
    var checkboxesColumna = document.querySelectorAll(`.casilla[id^='checkbox-'][id$='-${columna}']`);

    console.log(checkboxesFila);
    checkboxesFila.forEach(function (checkbox) {
        checkbox.parentElement.classList.add("seleccionada");
        checkbox.classList.add("deshabilitada");
        checkbox.disabled = true;

    });
    checkboxesColumna.forEach(function (checkbox) {
        checkbox.parentElement.classList.add("seleccionada");
        checkbox.classList.add("deshabilitada");
        checkbox.disabled = true;

    });

    if (casillasManualesSeleccionadas === 5) {
        deshabilitarCasillasRestantes();
    }
}

function deshabilitarCasillasRestantes() {
    var checkboxes = document.querySelectorAll('.casilla:not(.seleccionada)');
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('click', prevenirDefault);
        checkbox.classList.add("deshabilitada");
    });
}

function habilitarCasillas() {
    var checkboxes = document.querySelectorAll('.casilla');
    checkboxes.forEach(function (checkbox) {
        checkbox.removeEventListener('click', prevenirDefault);
        checkbox.classList.remove("deshabilitada");
    });
}

function prevenirDefault(event) {
    event.preventDefault();
}

function enviarSelecciones() {
    var checkboxes = document.querySelectorAll('.casilla');

    var selecciones = Array.from(checkboxes).filter(function (checkbox) {
        return checkbox.checked;
    });

    var seleccionValues = selecciones.map(function (seleccion) {
        return seleccion.value;
    });

    var resultadoDiv = document.getElementById('resultado-selecciones');
    resultadoDiv.innerHTML = "Selecciones: " + seleccionValues.join(", ");
}

function marcarAleatorio() {
    if (casillasManualesSeleccionadas === 5) {
        alert("Ya has seleccionado 5 casillas manualmente");
        deshabilitarCasillasRestantes();
        return;
    }

    while (casillasManualesSeleccionadas < 5) {
        var filaAleatoria = Math.floor(Math.random() * 5);
        var columnaAleatoria = Math.floor(Math.random() * 10);
        var casillaId = "casilla-" + filaAleatoria + "-" + columnaAleatoria;

        var casilla = document.getElementById(casillaId);
        if (casilla && !casilla.classList.contains("deshabilitada") && !casilla.classList.contains("seleccionada")) {
            marcarCasillaSeleccionada(casilla, filaAleatoria, columnaAleatoria);
            return;
        }
    }
}
</script>

</body>
</html>
