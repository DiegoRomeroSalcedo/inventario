document.addEventListener('DOMContentLoaded', function() {

    let form = document.getElementById('update_form_marcas');

    if (form) {
        form.addEventListener('submit', async function(event) {
            event.preventDefault(); // Evitamos el envio normal del form

            const BASE_URL = '/inventario/public';
            let formData = new FormData(this);

            try {
                let response = await fetch(`${BASE_URL}/search-update-marcas`, { //Hemos definido el endpoint
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not OK');
                }

                let data = await response.json();
                
                //Manejamos la respuesta de la solicitud
                let resultsDiv = document.getElementById('results');
                resultsDiv.innerHTML = '';

                if (data && data.marcas.length > 0) {
                    //Formamos el template string
                    let tableHTML = `<table class="tabla_resultados">
                        <thead>
                            <tr>
                                <th>ID MARCA</th>
                                <th>NOMBRE MARCA</th>
                                <th>USUARIO INSERCIÓN</th>
                                <th>FECHA INSERCIÓN</th>
                                <th>USUARIO ACTUALIZACIÓN</th>
                                <th>FECHA ACTUALIZACIÓN</th>
                            </tr>
                        </thead>
                        <tbody>`;

                    data.marcas.forEach(marca => {
                        let row = `<tr>
                            <td><a href="${BASE_URL}/update-form-marca?data=${encodeURIComponent(marca.encriptado)}">${marca.id_marca}</a></td>
                            <td>${marca.nombre_marca}</td>
                            <td>${marca.usuario_insercion}</td>
                            <td>${marca.fecha_insercion}</td>
                            <td>${marca.usuario_actualizacion}</td>
                            <td>${marca.fecha_actualizacion}</td>
                        </tr>`;
                        tableHTML += row; //Concatenamos las filas
                    });

                    tableHTML += `</tbody></table>`;
                    resultsDiv.innerHTML = tableHTML; //Agregamos la tabla al DIV de resultados en la vista
                } else {
                    resultsDiv.innerHTML = '<p>No se hallarón resultados</p>';
                }
            } catch (error) {
                console.error("Error: ", error);
            }
        });
    } else {
        console.error('Formulario no encontrado');
    }
});
