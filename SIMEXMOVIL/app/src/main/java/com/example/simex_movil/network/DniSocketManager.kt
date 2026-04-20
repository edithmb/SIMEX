package com.example.simex_movil.network

import android.content.Context
import com.microsoft.signalr.HubConnection
import android.net.Uri
import com.microsoft.signalr.HubConnectionBuilder
import io.reactivex.rxjava3.core.Single
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import android.util.Base64
import java.io.DataInputStream
import java.io.DataOutputStream
import java.net.Socket
import javax.crypto.Cipher
import javax.crypto.KeyGenerator
import javax.crypto.SecretKey

class DniSocketManager(private val context: Context, private val token: String) {
    // ip del server kotlin
    private val IP_SERVER = "51.83.192.177"
    private val port = 8888

    // funcion principal
    fun connectAndUpload(
        uri: Uri,
        fileName: String,
        onStatusUpdate: (String) -> Unit, // actualiza progreso UI
        onSucces: (String, String) -> Unit // devuelve nombre de archivo y clave
    ){
        // hilo secundario para que la pantalla no se congele
        CoroutineScope(Dispatchers.IO).launch {
            try {
                withContext(Dispatchers.Main){
                    onStatusUpdate("Encriptando foto del DNI...")
                }

                // leer archivo de la galeria
                val inputStream = context.contentResolver.openInputStream(uri)
                val bytesOriginales = inputStream?.readBytes()
                inputStream?.close()

                if (bytesOriginales == null ){
                    withContext(Dispatchers.Main) { onStatusUpdate("error al leer")}
                    return@launch
                }

                // generar clave y encriptar
                val secretKey = generarClaveAES()
                val claveBase64 = Base64.encodeToString(secretKey.encoded, Base64.NO_WRAP)

                val cipher = Cipher.getInstance("AES")
                cipher.init(Cipher.ENCRYPT_MODE, secretKey)
                val encryptedBytes = cipher.doFinal(bytesOriginales)

                // conectar al servidor kotlin por sockets
                withContext(Dispatchers.Main){
                    onStatusUpdate("subiendo archivo cifrado")
                }

                val socket = Socket(IP_SERVER, port)
                val salida = DataOutputStream(socket.getOutputStream())
                val entrada = DataInputStream(socket.getInputStream())

                // enviar datos con nombre unico que tendra en el servidor
                val nombreUnico = "dni_${System.currentTimeMillis()}.aes"

                salida.writeUTF("SUBIR")
                salida.writeUTF(nombreUnico)
                salida.writeLong(encryptedBytes.size.toLong())
                salida.write(encryptedBytes)
                salida.flush()

                // esperar que el server confirme
                val respuesta = entrada.readUTF()
                socket.close() // cerramos socket

                withContext(Dispatchers.Main) {
                    if (respuesta.trim() == "OK_SUBIDO"){
                    onStatusUpdate("Subida exitosa al servidor")
                        // pasamos nombre y clave a la actividad
                        onSucces(nombreUnico,claveBase64)
                    } else {
                        onStatusUpdate("error del servidor $respuesta")
                    }
                }
                } catch (e: Exception) {
                    withContext(Dispatchers.Main){
                        onStatusUpdate("error de red/socket:${e.message}")
                    }
                }
        }
    }

    // generar la clave
    private fun generarClaveAES(): SecretKey {
        val keyGenerator = KeyGenerator.getInstance("AES")
        keyGenerator.init(128)
        return keyGenerator.generateKey()

    }

    fun downloadAndDecrypt(fileName: String, claveBase64: String, onStatusUpdate: (String) -> Unit, onSuccess: (File) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                // 1. Conectar al servidor Kotlin (Asegúrate de usar la IP/Dominio correcta)
                val socket = Socket(IP_SERVIDOR, 8888)
                val salida = DataOutputStream(socket.getOutputStream())
                val entrada = DataInputStream(socket.getInputStream())

                withContext(Dispatchers.Main) { onStatusUpdate("Solicitando archivo...") }

                // 2. Pedir el archivo al servidor
                salida.writeUTF("BAJAR")
                salida.writeUTF(fileName)
                salida.flush()

                // 3. Leer el tamaño del archivo que nos manda el servidor
                val tamanyoArchivo = entrada.readLong()

                if (tamanyoArchivo == -1L) {
                    withContext(Dispatchers.Main) { onStatusUpdate("Error: El archivo no existe en el servidor") }
                    socket.close()
                    return@launch
                }

                withContext(Dispatchers.Main) { onStatusUpdate("Descargando bytes seguros...") }

                // 4. Leer los bytes encriptados
                val buffer = ByteArray(4096)
                val bytesEncriptadosStream = java.io.ByteArrayOutputStream()
                var totalLeidos: Long = 0
                var bytesLeidos: Int

                while (totalLeidos < tamanyoArchivo) {
                    bytesLeidos = entrada.read(buffer)
                    if (bytesLeidos == -1) break
                    bytesEncriptadosStream.write(buffer, 0, bytesLeidos)
                    totalLeidos += bytesLeidos
                }

                socket.close() // Cerramos túnel
                val bytesEncriptados = bytesEncriptadosStream.toByteArray()

                withContext(Dispatchers.Main) { onStatusUpdate("Desencriptando...") }

                // 5. Desencriptar usando la clave que vino de .NET
                val bytesOriginales = desencriptar(bytesEncriptados, claveBase64)

                // 6. Guardar el archivo en la memoria del móvil (Carpeta Downloads)
                val downloadsFolder = android.os.Environment.getExternalStoragePublicDirectory(android.os.Environment.DIRECTORY_DOWNLOADS)
                val archivoFinal = File(downloadsFolder, "Desencriptado_$fileName")

                FileOutputStream(archivoFinal).use { output ->
                    output.write(bytesOriginales)
                }

                withContext(Dispatchers.Main) {
                    onSuccess(archivoFinal)
                }

            } catch (e: Exception) {
                android.util.Log.e("SOCKET_DOWNLOAD", "Error al descargar", e)
                withContext(Dispatchers.Main) {
                    onStatusUpdate("Error de red: ${e.message ?: "desconocido"}")
                }
            }
        }
    }
}