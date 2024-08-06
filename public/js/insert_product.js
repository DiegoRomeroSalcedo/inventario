document.addEventListener('DOMContentLoaded', () => {
    const costoInput = document.getElementById('precio_costo');
    const retencionInput = document.getElementById('retefuente');
    const fleteInput = document.getElementById('costo_flete');
    const ivaInput = document.getElementById('costo_iva');
    const costoFinalInput = document.getElementById('costo_final');
    const utilidadInput = document.getElementById('utilidad');
    const precioVentaInput = document.getElementById('precio_venta');
    const descuentoInput = document.getElementById('descuento');
    const preciodescuentoInput = document.getElementById('precioventa_desc');
    const rentabilidadInput = document.getElementById('rentabilidad');

    const toggleCheckboxInput = document.getElementById('toggleCheckbox');
    const extrafiledsContainer = document.getElementById('extrafileds');
    const extrafiledsTwoContainer = document.getElementById('extrafileds_two');

    function formatPrice(price) {
        return price.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    
    const actualizarCostoFinal = () => {
        const costo = parseFloat(costoInput.value.replace(/,/g, '')) || 0;
        const retencion = parseFloat(retencionInput.value.replace(/,/g, '')) || 0;
        const flete = parseFloat(fleteInput.value.replace(/,/g, '')) || 0;
        const iva = parseFloat(ivaInput.value.replace(/,/g, '')) || 0;

        const reteTotal = costo * (retencion / 100);
        const fleteTotal = costo * (flete / 100);
        const ivaTotal = costo * (iva / 100);

        const costoFinal = costo + reteTotal + fleteTotal + ivaTotal;
        costoFinalInput.value = formatPrice(costoFinal);

        console.log(`Costo Final Actualizado: ${costoFinal}`);

        actualizarPrecioVenta();
    };

    const actualizarPrecioVenta = () => {
        const costoFinal = parseFloat(costoFinalInput.value.replace(/,/g, '')) || 0;
        const utilidad = parseFloat(utilidadInput.value.replace(/,/g, '')) || 0;

        const utilidadTotal = costoFinal * (utilidad / 100);
        const ventaFinal = costoFinal + utilidadTotal;

        precioVentaInput.value = formatPrice(ventaFinal);

        descuentos();
        updateRentabilidad();
    };

    const descuentos = () => {
        const precioVenta = parseFloat(precioVentaInput.value) || 0;
        const descuento = parseFloat(descuentoInput.value) || 0;

        const descuentoTotal = precioVenta * (descuento / 100);
        const precioTotal = precioVenta - descuentoTotal;

        preciodescuentoInput.value = precioTotal.toFixed(2);

        updateRentabilidad();
    };

    const updateRentabilidad = () => {
        const costoFinal = parseFloat(costoFinalInput.value) || 0;
        const precioVenta = parseFloat(precioVentaInput.value) || 0;
        const preciodescuento = parseFloat(preciodescuentoInput.value) || 0;

        let rentabilidadBruta;
        if (costoFinal === 0) {
            rentabilidadBruta = 0;
        } else {
            let gananciaBruta;
            if (window.getComputedStyle(extrafiledsContainer).display === 'none') {
                gananciaBruta = precioVenta - costoFinal;
            } else {
                gananciaBruta = preciodescuento - costoFinal;
            }
            rentabilidadBruta = (gananciaBruta / costoFinal) * 100;
        }
        rentabilidadInput.value = rentabilidadBruta.toFixed(2);
    };

    const toggleFileds = () => {
        if (toggleCheckboxInput.checked) {
            extrafiledsContainer.classList.remove('hidden');
            extrafiledsTwoContainer.classList.remove('hidden');
            extrafiledsContainer.querySelector('input').required = true;
            extrafiledsTwoContainer.querySelector('input').required = true;
        } else {
            extrafiledsContainer.classList.add('hidden');
            extrafiledsTwoContainer.classList.add('hidden');
            extrafiledsContainer.querySelector('input').required = false;
            extrafiledsTwoContainer.querySelector('input').required = false;
        }

        updateRentabilidad();
    };

    toggleCheckboxInput.addEventListener('change', toggleFileds);
    costoInput.addEventListener('input', actualizarCostoFinal);
    retencionInput.addEventListener('input', actualizarCostoFinal);
    fleteInput.addEventListener('input', actualizarCostoFinal);
    ivaInput.addEventListener('input', actualizarCostoFinal);
    utilidadInput.addEventListener('input', actualizarPrecioVenta);
    descuentoInput.addEventListener('input', descuentos);
});
