let indiceLinea = 1;

function agregarLinea(){
    const contenedor = document.getElementById('lineas_factura');
    const primerSelect = contenedor.querySelector('select');
    const opcionesHTML = primerSelect ? primerSelect.innerHTML : '<option value="">--Selecciona Producto--</option>';
    const tr = document.createElement('tr');
    tr.className = 'linea_factura';
    tr.innerHTML = `
        <td>
            <input type="number" name="lineas[${indiceLinea}][cantidad]" placeholder="Cantidad" required>
        </td>
        <td>
            <select name="lineas[${indiceLinea}][producto_id]" required>
                ${opcionesHTML}
            </select>
        </td>
        <td>
            <input type="number" step="0.01" name="lineas[${indiceLinea}][precio_unitario]" placeholder="Precio" required>
        </td>
        <td>
            <span class="total_linea">0,00€</span>
            <button type="button" onclick="this.parentElement.parentElement.remove();">Eliminar</button>
        </td>
    `;
    contenedor.appendChild(tr);
    indiceLinea++;
}

