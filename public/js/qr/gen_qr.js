document.addEventListener('DOMContentLoaded', function () {
    // recuperamos los elementos de DOM
    const generateBtn = document.getElementById('generateBtn')
    const downloadBtn = document.getElementById('downloadBtn')
    const qrCanvas = document.getElementById('qrCanvas')
    const qrPlaceholder = document.getElementById('qrPlaceholder')

    // Generar QR
    generateBtn.addEventListener('click', function () {
        // conectamos con los inputs para obtener los datos a enviar
        const residente_id = parseInt(
            document.getElementById('residente_id').value
        )
        const privada_id = parseInt(document.getElementById('privada_id').value)
        const invitado_id = parseInt(
            document.getElementById('qr_invitados').value
        )
        const usos = document.getElementById('usos').value
        const validezHoras = document.getElementById('dias_caducar').value * 24

        // generamos los datos a enviar
        datos = {
            // _token: document
            //     .querySelector('meta[name="csrf-token"]')
            //     .getAttribute('content'), \\ pendiente por implementar
            invitado_id: invitado_id,
            privada_id: privada_id,
            residente_id: residente_id,
            usos: usos,
            validez_horas: validezHoras
        }

        // hacemos la peticion al servidor
        fetch('api/cqc', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json'
                // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // pendiente por implementar
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
                    generarQR(data.codigo)
                } else {
                    console.error('Error del servidor:', data)
                    alert('Error al generar el código QR: ' + data.message)
                }
            })
            .catch(error => {
                console.error('Error completo:', error)

                // Mensaje más detallado
                let errorMessage = 'Error al generar el código QR'

                if (error.serverError) {
                    console.error('Respuesta del servidor:', error.serverError)
                    errorMessage += ` (${error.status}): ${
                        error.serverError.message || 'Error desconocido'
                    }`
                } else if (error.status) {
                    errorMessage += ` (${error.status}: ${error.statusText})`
                }

                alert(errorMessage)
            })
    })

    // Descargar QR
    downloadBtn.addEventListener('click', function () {
        const link = document.createElement('a')
        link.download = 'codigo-qr.png'
        link.href = qrCanvas.toDataURL('image/png')
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
    })
})

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
    qrPlaceholder.style.display = 'none'
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
                qrPlaceholder.style.display = 'flex'
                qrCanvas.style.display = 'none'
                downloadBtn.disabled = true
            } else {
                downloadBtn.disabled = false
            }
        }
    )
}
