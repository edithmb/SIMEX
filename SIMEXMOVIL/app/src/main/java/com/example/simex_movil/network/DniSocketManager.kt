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

class DniSocketManager(private val context: Context, private val token: String) {
    private lateinit var hubConnection: HubConnection

    // funcion principal
    fun connectAndUpload(
        uri: Uri,
        entityId: Int,
        entityType: String,
        fileName: String,
        onStatusUpdate: (String) -> Unit // actualiza UI
    ){
        // api .net
        val serverUrl = "http://10.0.2.2:5130/Hubs/DocumentsPersonHub"
        // tunel con token JWT
        hubConnection = HubConnectionBuilder.create(serverUrl).
        withAccessTokenProvider(Single.defer { Single.just(token) }).build()

        //recibir lo que manda .net
        hubConnection.on("Receivemessage", { message: String -> onStatusUpdate(message)
        }, String::class.java)

        hubConnection.on("UploadCompleted", { message: String, docId: Int ->
            onStatusUpdate("¡Éxito! \$message (ID guardado: \$docId)")
            hubConnection.stop() // cerramos el tunel
        }, String::class.java, Int::class.java)

        hubConnection.on("UploadedError", { error: String ->
            onStatusUpdate("Error de seguridad: $error")
            hubConnection.stop()
        }, String::class.java)

        // hilo secundario para que la pantalla no se congele
        CoroutineScope(Dispatchers.IO).launch {
            try {
                // arrancar tunel
                hubConnection.start().blockingAwait()

                withContext(Dispatchers.Main){
                    onStatusUpdate("Conectado.Preparando imagen..")
                }

                val base64File = ConvertUri(uri)

                if (base64File !=null ){
                    hubConnection.invoke("UploadDni", entityId, entityType, fileName, base64File)
                } else {
                    withContext(Dispatchers.Main) {
                        onStatusUpdate("Error al leer la foto del móvil.")
                    }
                }
            } catch (e: Exception) {
                withContext(Dispatchers.Main) {
                    onStatusUpdate("Error de red: ${e.message}")
                }
            }
        }
    }

    // leer bytes de movil
    private fun ConvertUri(uri: Uri):String?{
        return try {
            val inputStream = context.contentResolver.openInputStream(uri)
            val bytes = inputStream?.readBytes()
            inputStream?.close()
            Base64.encodeToString(bytes, Base64.NO_WRAP)

        } catch (e: Exception){
            null
        }
    }
}