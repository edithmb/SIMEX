import java.io.DataInputStream
import java.io.DataOutputStream
import java.io.File
import java.io.FileOutputStream
import java.net.DatagramPacket
import java.net.DatagramSocket
import java.net.ServerSocket
import java.net.Socket

fun main(){
    val puerto = 8888 // puerto donde escucha el servidor
    val carpetaDestino = File("dnis_seguros")

    // si no existe carpeta crearla
    if(!carpetaDestino.exists()){
        carpetaDestino.mkdirs()
    }

    try {
        val serverSockets = ServerSocket(puerto)
        println("SERVIDOR DE DNIS KOTLIN INICIADO EN PUERTO $puerto")

        // bucle infinito para esperar siempre nuevos clientes
        while (true) {
            print("esperando conexiones")

            // bloquea hasta que uncliente se conecta
            val socketCliente = serverSockets.accept()
            println("cliente conectado desde ${socketCliente.inetAddress.hostAddress}")

            // se le asigna un hilo y servidor vuelve a esperar clientes
            Thread {
                atenderCliente(socketCliente, carpetaDestino)
            }.start()
        }
    } catch (e: Exception) {
        println("error en servidor ${e.message}")
    }
}

// funcion que ejecuta threads
fun atenderCliente(socket: Socket, carpetaDestino: File) {
    try {
        val entrada = DataInputStream(socket.getInputStream())
        val salida = DataOutputStream(socket.getOutputStream())

        // saber si es subir o bajar
        val accion = entrada.readUTF()

        if (accion == "SUBIR") {
            // nombre que android le quiere poner al archivo
            val nombreArchivo = entrada.readUTF()
            val tamanyoArchivo = entrada.readLong()

            println("recibiendo archivo $nombreArchivo")

            // carpeta donde se va a  guardar los bytes
            val archivoFisico =File(carpetaDestino, nombreArchivo)
            val fileOutptStream = FileOutputStream(archivoFisico)

            // bucle para leer bytes
            val buffer = ByteArray(4096)
            var bytesLeidos: Int
            var totalLeidos: Long = 0

            while (totalLeidos < tamanyoArchivo) {
                bytesLeidos = entrada.read(buffer)
                if (bytesLeidos == -1) break // fin de envio de datos

                fileOutptStream.write(buffer, 0, bytesLeidos)
                totalLeidos += bytesLeidos
            }

            fileOutptStream.close()
            println("archivo $nombreArchivo guardado correctamente")

            // confirmamos a android que todo bien

            salida.writeUTF("OK_SUBIDO")
            salida.flush()
        } else if (accion == "BAJAR") {
            val nombreArchivo = entrada.readUTF()
            println("El cliente quiere descargar el archivo: $nombreArchivo")

            val archivoFisico = File(carpetaDestino, nombreArchivo)

            if (!archivoFisico.exists()) {
                salida.writeLong(-1L) // Le decimos a Android que no existe
                salida.flush()
            } else {
                salida.writeLong(archivoFisico.length()) // Enviamos el tamaño

                // Enviamos los bytes
                val fileInputStream = java.io.FileInputStream(archivoFisico)
                val buffer = ByteArray(4096)
                var bytesLeidos: Int

                while (fileInputStream.read(buffer).also { bytesLeidos = it } != -1) {
                    salida.write(buffer, 0, bytesLeidos)
                }

                salida.flush()
                fileInputStream.close()
                println("Archivo $nombreArchivo enviado con éxito al cliente")
            }
        }
    } catch (e: Exception) {
        println("atendiendo a ${e.message}")
    } finally {
        socket.close() // cerramos la puerta del cliente al terminar
    }
}