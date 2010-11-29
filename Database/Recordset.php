<?

//Clase que permite crear un recordset
class Recordset
{

    //Definici�n de variables
    var $manejador = "pgsql";
    var $servidor = "localhost";
    var $usuario = "postgres";
    var $clave = "";
    var $puerto = "5432";
    var $sql = "";
    var $limite_inicio = 0;
    var $limite_cantidad = 0;
    var $bd = "despacho_corresp";
    var $conexion;
    var $tabla;
    var $fila;
    var $posicion = -1;
    var $total_registros;
    var $total_campos;
    var $total_paginas;
    var $pagina_actual;
    var $pagina_anterior;
    var $pagina_siguiente;

    function getBD()
    {
        return $this->bd;
    }

    //Funci�n con la que se abre el recordset conectandose a la base de datos
    function abrir()
    {
        switch ($this->manejador)
        {
            case "mysql":
                if ($this->limite_cantidad > 0)
                {
                    $this->sql = $this->sql . " LIMIT " . $this->limite_inicio . "," . $this->limite_cantidad;
                }
                $this->conexion = @mysql_pconnect($this->servidor, $this->usuario, $this->clave) or die("<strong>" . htmlentities("�Servidor, usuario o clave incorecta!") . "</strong>");
                @mysql_select_db($this->bd) or die("<strong>" . htmlentities("�La base de datos (" . $this->bd . ") no se encuentra!") . "</strong>");
                $this->tabla = @mysql_query($this->sql) or die("<strong>" . htmlentities("�Error en la consulta: " . $this->sql . "!") . "</strong>");
                $this->total_registros = @mysql_num_rows($this->tabla);
                $this->total_campos = @mysql_num_fields($this->tabla);
                break;

            case "mssql":
                $this->conexion = @mssql_pconnect($this->servidor, $this->usuario, $this->clave) or die("<strong>" . htmlentities("�Servidor, usuario o clave incorecta!") . "</strong>");
                @mssql_select_db($this->bd) or die("<strong>" . htmlentities("�La base de datos (" . $this->bd . ") no se encuentra!") . "</strong>");
                $this->tabla = @mssql_query($this->sql) or die("<strong>" . htmlentities("�Error en la consulta: " . $this->sql . "!") . "</strong>");
                $this->total_registros = @mssql_num_rows($this->tabla);
                $this->total_campos = @mssql_num_fields($this->tabla);
                break;

            case "pgsql":
                if ($this->limite_cantidad > 0)
                    $this->sql = $this->sql . " LIMIT " . $this->limite_cantidad . " OFFSET " . $this->limite_inicio;
                $this->conexion = @pg_pconnect("host=$this->servidor port=$this->puerto dbname=$this->bd user=$this->usuario password=$this->clave") or die("<strong>" . htmlentities("�Servidor, puerto, db, usuario o clave incorecta!") . "</strong>");
                $this->tabla = @pg_query($this->sql) or die("<strong>" . htmlentities("�Error en la consulta: " . $this->sql . "!") . "</strong>");
                $this->total_registros = @pg_num_rows($this->tabla);
                $this->total_campos = @pg_num_fields($this->tabla);
        }
    }

    //Funci�n que libera la memoria del recordset
    function cerrar()
    {
        switch ($this->manejador)
        {
            case "mysql":
                @mysql_free_result($this->tabla);
                @mysql_close($this->conexion);
                break;

            case "mssql":
                @mssql_free_result($this->tabla);
                @mssql_close($this->conexion);
                break;

            case "pgsql":
                @pg_free_result($this->tabla);
                @pg_close($this->conexion);
                break;
        }
    }

    //Funci�n que permite desplazar el puntero del recordset
    function desplazar()
    {
        if ($this->posicion <= $this->total_registros && $this->posicion >= 0)
        {
            switch ($this->manejador)
            {
                case "mysql":
                    mysql_data_seek($this->tabla, $this->posicion);
                    $this->fila = mysql_fetch_array($this->tabla);
                    break;

                case "mssql":
                    mssql_data_seek($this->tabla, $this->posicion);
                    $this->fila = mssql_fetch_array($this->tabla);
                    break;

                case "pgsql":
                    pg_result_seek($this->tabla, $this->posicion);
                    $this->fila = pg_fetch_array($this->tabla);
                    break;
            }
        }
        else
        {
            echo "<strong>�la posici�n esta fuera del rango!</strong>";
        }
    }

    //Funci�n que permite mover el cursor al registro siguiente dentro del recordset
    function siguiente()
    {
        if ($this->total_registros > 0 && $this->posicion + 1 < $this->total_registros)
        {
            $this->posicion++;
            $this->desplazar();
        }
    }

    //Funci�n que permite mover el cursor al registro anterior dentro del recordset
    function anterior()
    {
        if ($this->total_registros > 0 && $this->posicion - 1 >= 0)
        {
            $this->posicion--;
            $this->desplazar();
        }
    }

    //Funci�n para desplazarse a un registro cualquiera segun la posici�n dentro del recordset
    function mover($posicion)
    {
        if ($this->total_registros > 0)
        {
            $this->posicion = $posicion;
            $this->desplazar();
        }
    }

    //Funci�n para desplazarse al principio del recordset
    function mover_inicio()
    {
        if ($this->total_registros > 0)
        {
            $this->posicion = 0;
            $this->desplazar();
        }
    }

    //Funci�n para desplazarse al final del recordset
    function mover_final()
    {
        if ($this->total_registros > 0)
        {
            $this->posicion = $this->total_registros - 1;
            $this->desplazar();
        }
    }

    //Funci�n para paginar los resultados
    //Solo trabaja con el motor de base de datos Mysql
    function paginar($pagina, $cantidad)
    {
        if ($this->manejador != "mysql" && $this->manejador != "pgsql")
            return;
        $this->abrir();
        if ($this->total_registros > 0)
        {
            $this->pagina_actual = $pagina;
            $this->total_paginas = ceil($this->total_registros / $cantidad);
            $this->pagina_anterior = 1;
            $this->pagina_siguiente = $this->total_paginas;
            if ($pagina > 1)
                $this->pagina_anterior = $pagina - 1;
            if ($pagina < $this->total_paginas)
                $this->pagina_siguiente = $pagina + 1;
            $this->cerrar();
            $this->limite_inicio = ($pagina * $cantidad) - $cantidad;
            $this->limite_cantidad = $cantidad;
            $this->abrir();
        }
    }

    //Funci�n que permite crear el link Primera P�gina del paginador
    //Solo trabaja con el motor de base de datos Mysql
    function paginarinicio($ruta, $target, $rutaimagen, $condicion = "")
    {
        if ($this->manejador != "mysql" && $this->manejador != "pgsql")
            return;
        if ($this->total_paginas > 1)
        {
            if ($this->pagina_actual > 1)
            {
                if ($condicion != "")
                    $condicion = "&" . $condicion;
                echo "<a href=\"$ruta" . "?pagina=1" . $condicion . "\" title=\"Primera P&aacute;gina\" target=\"$target\">";
                echo "\n\t<img src=\"$rutaimagen\" align=\"absmiddle\" border=\"0\" />";
                echo "\n</a>";
            } else
            {
                echo "<img src=\"$rutaimagen\" align=\"absmiddle\" border=\"0\" title=\"Primera P&aacute;gina\" />";
            }
        }
    }

    //Funci�n que permite crear el link Anterior P�gina del paginador
    //Solo trabaja con el motor de base de datos Mysql
    function paginaratras($ruta, $target, $rutaimagen, $condicion = "")
    {
        if ($this->manejador != "mysql" && $this->manejador != "pgsql")
            return;
        if ($this->total_paginas > 1)
        {
            if ($this->pagina_actual > 1)
            {
                if ($condicion != "")
                    $condicion = "&" . $condicion;
                echo "<a href=\"$ruta" . "?pagina=" . ($this->pagina_actual - 1) . $condicion . "\" title=\"Anterior P&aacute;gina\" target=\"$target\">";
                echo "\n\t<img src=\"$rutaimagen\" align=\"absmiddle\" border=\"0\" />";
                echo "\n</a>";
            } else
            {
                echo "<img src=\"$rutaimagen\" align=\"absmiddle\" border=\"0\" title=\"Anterior P&aacute;gina\" />";
            }
        }
    }

    //Funci�n que permite crear el link Siguiente P�gina del paginador
    //Solo trabaja con el motor de base de datos Mysql
    function paginarsiguiente($ruta, $target, $rutaimagen, $condicion = "")
    {
        if ($this->manejador != "mysql" && $this->manejador != "pgsql")
            return;
        if ($this->total_paginas > 1)
        {
            if ($this->pagina_actual < $this->total_paginas)
            {
                if ($condicion != "")
                    $condicion = "&" . $condicion;
                echo "<a href=\"$ruta" . "?pagina=" . ($this->pagina_actual + 1) . $condicion . "\" title=\"Siguiente P&aacute;gina\" target=\"$target\">";
                echo "\n\t<img src=\"$rutaimagen\" align=\"absmiddle\" border=\"0\" />";
                echo "\n</a>";
            } else
            {
                echo "<img src=\"$rutaimagen\" align=\"absmiddle\" border=\"0\" title=\"Siguiente P&aacute;gina\" />";
            }
        }
    }

    //Funci�n que permite crear el link �ltima P�gina del paginador
    //Solo trabaja con el motor de base de datos Mysql
    function paginarfin($ruta, $target, $rutaimagen, $condicion = "")
    {
        if ($this->manejador != "mysql" && $this->manejador != "pgsql")
            return;
        if ($this->total_paginas > 1)
        {
            if ($this->pagina_actual < $this->total_paginas)
            {
                if ($condicion != "")
                    $condicion = "&" . $condicion;
                echo "<a href=\"$ruta" . "?pagina=$this->total_paginas" . $condicion . "\" title=\"&Uacute;ltima P&aacute;gina\" target=\"$target\">";
                echo "\n\t<img src=\"$rutaimagen\" align=\"absmiddle\" border=\"0\" />";
                echo "\n</a>";
            } else
            {
                echo "<img src=\"$rutaimagen\" align=\"absmiddle\" border=\"0\" title=\"&Uacute;ltima P&aacute;gina\" />";
            }
        }
    }

    //Funci�n que permite crear todo el paginador
    function crearpaginador($ruta, $target, $rutaimagen, $condicion = "")
    {
        if ($this->total_paginas > 1)
        {
            echo '<table align="center" cellpadding="2" >';
            echo "\n\t<tr>";
            echo "\n\t\t<td>";
            $this->paginarinicio($ruta, $target, $rutaimagen . "inicio.gif", $condicion);
            echo "</td>";
            echo "\n\t\t<td>";
            $this->paginaratras($ruta, $target, $rutaimagen . "atras.gif", $condicion);
            echo "</td>";
            echo "\n\t\t<td>P&aacute;gina " . $this->pagina_actual . " / " . $this->total_paginas . "</td>";
            echo "\n\t\t<td>";
            $this->paginarsiguiente($ruta, $target, $rutaimagen . "siguiente.gif", $condicion);
            echo "</td>";
            echo "\n\t\t<td>";
            $this->paginarfin($ruta, $target, $rutaimagen . "ultimo.gif", $condicion);
            echo "</td>";
            echo "\n\t</tr>";
            echo "\n</table>";
        }
    }

    //Funcion que permite crear todo un paginador usando AJAX
    function CrearPaginadorAjax($archivo, $rutaimagen, $ajax, $condicion = "")
    {
        $html = '<table align="center" cellpadding="2" >
	<tr>
		<td><img style="cursor:pointer" title="Primera P&aacute;gina" src="' . $rutaimagen . 'inicio.gif" onclick="' . $ajax . '(\'' . $archivo . '?pagina=1' . $condicion . '\');" /></td>
		<td><img style="cursor:pointer" title="Anterior P&aacute;gina" src="' . $rutaimagen . 'atras.gif" onclick="' . $ajax . '(\'' . $archivo . '?pagina=' . $this->pagina_anterior . $condicion . '\');" /></td>
		<td>' . $this->pagina_actual . " / " . $this->total_paginas . '</td>
		<td><img style="cursor:pointer" title="Siguiente P&aacute;gina" src="' . $rutaimagen . 'siguiente.gif" onclick="' . $ajax . '(\'' . $archivo . '?pagina=' . $this->pagina_siguiente . $condicion . '\');" /></td>
		<td><img style="cursor:pointer" title="&Uacute;ltima P&aacute;gina" src="' . $rutaimagen . 'ultimo.gif" onclick="' . $ajax . '(\'' . $archivo . '?pagina=' . $this->total_paginas . $condicion . '\');" /></td>
	</tr>
</table>';
        if ($this->total_paginas == 1)
            $html = "&nbsp;";
        echo $html;
    }

    //Funci�n que permite llenar un ComboBox en HTML
    function llenarcombo($opciones = "", $checked = "")
    {
        echo "<select $opciones>";
        echo "<option value=''></option>";
        $this->abrir();
        if ($this->total_registros > 0)
        {
            for ($i = 1; $i <= $this->total_registros; $i++)
            {
                $this->siguiente();
                if ($this->fila[0] == $checked)
                {
                    $html = "<option value='" . $this->fila[0] . "' selected=\"selected\">" . $this->fila[1] . "</option>";
                }
                else
                {
                    $html = "<option value='" . $this->fila[0] . "'>" . $this->fila[1] . "</option>";
                }
                echo $html;
            }
        }
        echo "</select>";
    }

    //Funci�n que permite llenar un grupo de RadioButton en HTML
    function llenarradio($nombre, $ancho = "", $checked = "", $id = "")
    {
        $this->abrir();
        if ($this->total_registros > 0)
        {
            echo "<table width='$ancho'>";
            echo "<tr>";
            for ($i = 1; $i <= $this->total_registros; $i++)
            {
                $seleccionado = "";
                $this->siguiente();
                if ($checked != "")
                {
                    if ($this->fila[0] == $checked)
                    {
                        $seleccionado = "checked='checked'";
                    }
                }
                else
                {
                    if ($i == 1)
                    {
                        $seleccionado = "checked='checked'";
                    }
                }
                if ($i == 1)
                {
                    echo "<td align=\"left\">";
                }
                elseif ($i == $this->total_registros)
                {
                    echo "<td align=\"right\">";
                }
                else
                {
                    echo "<td align=\"center\">";
                }
                if ($id != "")
                {
                    $mid = "id='" . $id . $i . "'";
                }
                echo "<input type='radio' name='$nombre' value='" . $this->fila[0] . "' $mid $seleccionado/>&nbsp;" . $this->fila[1];
                echo "</td>";
            }
            echo "</tr>";
            echo "</table>";
        }
    }

    //Funci�n que permite optimizar una tabla
    function optimizartabla($tabla)
    {
        if ($this->manejador == "mysql")
        {
            @mysql_pconnect($this->servidor, $this->usuario, $this->clave) or die("<strong>" . htmlentities("�Servidor, usuario o clave incorecta!") . "</strong>");
            $sql = "OPTIMIZE TABLE $tabla";
            @mysql_db_query($this->bd, $sql);
        }
        elseif ($this->manejador == "pgsql")
        {
            @pg_pconnect("host=$this->servidor port=$this->puerto dbname=$this->bd user=$this->usuario password=$this->clave") or die("<strong>" . htmlentities("�Servidor, usuario o clave incorecta!") . "</strong>");
            @pg_query("VACUUM ANALIZE TABLE $tabla");
        }
    }

    //Funci�n que permite truncar una tabla
    function truncartabla($tabla)
    {
        if ($this->manejador == "mysql")
        {
            @mysql_pconnect($this->servidor, $this->usuario, $this->clave) or die("<strong>" . htmlentities("�Servidor, usuario o clave incorecta!") . "</strong>");
            $sql = "TRUNCATE TABLE $tabla";
            @mysql_db_query($this->bd, $sql);
        }
        elseif ($this->manejador == "pgsql")
        {
            @mysql_pconnect("host=$this->servidor port=$this->puerto dbname=$this->bd user=$this->usuario password=$this->clave") or die("<strong>" . htmlentities("�Servidor, usuario o clave incorecta!") . "</strong>");
            @pg_query("TRUNCATE $tabla");
        }
    }

    //Funci�n que permite codificar en UTF-8 bajo el formato HTML
    function codificar($texto)
    {
        return htmlentities(stripslashes($texto), ENT_QUOTES, "UTF-8");
    }

    //Funci�n que permite codificar en UTF-8 bajo el formato HTML
    function decodificar($texto)
    {
        return html_entity_decode(stripslashes($texto), ENT_QUOTES, "UTF-8");
    }

    //Funci�n que permite dar formato correcto a las fechas en MySQL
    function formatofecha($fecha, $signo="/")
    {
        $nfecha = explode($signo, strval($fecha));
        return $nfecha[2] . "-" . $nfecha[1] . "-" . $nfecha[0];
    }

    //Funci�n que permite dar formato moneda
    function moneda($valor)
    {
        return "Bs.F. " . number_format($valor, 2, ",", ".");
    }

    //Funci�n que permite dar formato numero
    function montos($valor, $deci = 2)
    {
        return number_format($valor, $deci, ",", ".");
    }

    //Funcion Mayusculas en HTML
    function masyusHTML($texto)
    {
        $text = $this->decodificar($texto);
        $text = strtoupper($text);
        $text = $this->codificar($text);
        return $text;
    }

}

?>