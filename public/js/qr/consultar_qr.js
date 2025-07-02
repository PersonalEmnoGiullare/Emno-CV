const qrCanvas = document.getElementById('modal-qr-canvas')
const downloadBtn = document.getElementById('download-qr')

// Función para asignar event listeners a botones QR
function asignarEventListenersQR () {
    // Remover listeners anteriores para evitar duplicados
    document.querySelectorAll('.view-qr-btn').forEach(btn => {
        // Clonar el botón para remover todos los event listeners
        const newBtn = btn.cloneNode(true)
        btn.parentNode.replaceChild(newBtn, btn)
    })

    // Asignar nuevos event listeners
    document.querySelectorAll('.view-qr-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            try {
                const qrCode = this.getAttribute('data-qr')
                const json_datos = this.getAttribute('data-qr_data')

                // Validar que los datos existan
                if (!qrCode || !json_datos) {
                    alert('Error: No se encontraron los datos del código QR')
                    return
                }

                const datos = JSON.parse(json_datos)
                const modal = document.getElementById('qr-modal')

                // Generar el QR en el canvas
                generarQR(qrCode)

                // Actualizar info del modal
                document.getElementById('modal-alias').textContent =
                    datos.alias + ': ' + datos.invitado || 'Sin alias'
                document.getElementById('modal-status').textContent =
                    datos.estado || 'Desconocido'
                document.getElementById('modal-date').textContent =
                    new Date(datos.fecha_generacion).toLocaleDateString() +
                        ' - ' +
                        new Date(datos.fecha_expiracion).toLocaleDateString() ||
                    'Sin fecha'

                // Mostrar modal
                modal.style.display = 'flex'
            } catch (error) {
                console.error('Error al procesar datos del QR:', error)
                alert('Error al mostrar el código QR')
            }
        })
    })
}

// funcion para generar el codigo qr al obtener respuesta del servidor
function generarQR (codigo) {
    const text = codigo
    const size = 400
    const color = '#8D00EB'

    // verificamos que el codigo recibido no sea nulo o no sea un texto vacio
    if (!codigo || codigo.trim() === '') {
        alert('No se ha proporcionado un código válido.')
        return
    }

    // Limpiar canvas y mostrar placeholder
    qrCanvas.style.display = 'block'
    qrCanvas.width = size
    qrCanvas.height = size

    // Generar el código QR
    QRCode.toCanvas(
        qrCanvas,
        text,
        {
            width: size,
            color: {
                dark: color,
                light: '#ffffff'
            },
            margin: 2
        },
        function (error) {
            if (error) {
                console.error(error)
                alert('Error al generar el QR')
                qrCanvas.style.display = 'none'
                downloadBtn.disabled = true
            } else {
                downloadBtn.disabled = false
            }
        }
    )
}

// Cerrar modal
document.querySelector('.close-modal').addEventListener('click', function () {
    document.getElementById('qr-modal').style.display = 'none'
})

document
    .getElementById('close-modal-btn')
    .addEventListener('click', function () {
        document.getElementById('qr-modal').style.display = 'none'
    })

// Descargar QR
document.getElementById('download-qr').addEventListener('click', function () {
    const canvas = document.getElementById('modal-qr-canvas')
    const link = document.createElement('a')
    link.download = 'codigo-qr.png'
    link.href = canvas.toDataURL('image/png')
    link.click()
})
// aplicar filtros
document.getElementById('apply-filters').addEventListener('click', function () {
    // mandamos llamar a la funcion obtenerDatosQr
    obtenerDatosQr()
})

// funcion para limpiar los filtros
document.getElementById('reset-filters').addEventListener('click', function () {
    // limpiamos los campos
    document.getElementById('status_filter').value = ''
    document.getElementById('qr_invitados').value = ''
    document.getElementById('date-filter').value = ''

    // mandamos llamar a la funcion obtenerDatosQr
    obtenerDatosQr()
})

// Peticion de tipo fetch para obtener los datos del QR
async function obtenerDatosQr () {
    // conectamos con los inputs para obtener los datos a enviar
    const usuario_id = parseInt(
        document.querySelector('meta[name="user-id"]').getAttribute('content')
    )

    const estado = document.getElementById('status_filter').value
    const invitado_id = parseInt(document.getElementById('qr_invitados').value)
    const fecha = document.getElementById('date-filter').value

    // generamos los datos a enviar
    const datos = {
        usuario_id: usuario_id,
        estado: estado,
        invitado_id: invitado_id,
        fecha: fecha
    }

    // hacemos la peticion al servidor
    fetch('api/cqc/listar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json'
        },
        body: JSON.stringify(datos)
    })
        .then(async response => {
            if (!response.ok) {
                // Si la respuesta no es exitosa (2xx), leemos el cuerpo del error
                const errorData = await response.json().catch(() => null)
                throw {
                    status: response.status,
                    statusText: response.statusText,
                    serverError: errorData
                }
            }
            return response.json()
        })
        .then(data => {
            if (data.success) {
                // recuperamos los datos
                const datos = data.data

                // limpiamos el contenedor
                const qrContainer = document.getElementById('qr-list')
                qrContainer.innerHTML = ''
                // generamos etiquetas para cada dato
                datos.forEach(item => {
                    const qrItem = document.createElement('div')
                    qrItem.className = 'qr-item'
                    qrItem.innerHTML = `
                        <div class="qr-alias" title = '${item.invitado}'>${
                        item.alias
                    }</div>
                        <div class='qr-date'>${new Date(
                            item.fecha_generacion
                        ).toLocaleDateString()} - ${new Date(
                        item.fecha_expiracion
                    ).toLocaleDateString()}</div>
                        <div class="status status-${item.estado}" title = '${
                        item.usos_restantes
                    }'
>${item.estado}</div>
                        
                        <button class="view-qr-btn" data-qr="${
                            item.codigo
                        }" data-qr_data='${JSON.stringify(
                        item
                    )}'>Ver QR</button>
                    `
                    qrContainer.appendChild(qrItem)
                })
                // Asignar event listeners a los nuevos botones
                asignarEventListenersQR()
            } else {
                alert('Error al generar el código QR: ' + data.message)
            }
        })
        .catch(error => {
            // Mensaje más detallado
            let errorMessage = 'Error al generar el código QR'
            alert(errorMessage)
        })
}

// Llamamos a la función para cargar los datos al inicio
obtenerDatosQr()
