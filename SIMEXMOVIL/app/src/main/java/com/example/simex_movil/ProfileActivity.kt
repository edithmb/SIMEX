package com.example.simex_movil

import android.content.Context
import android.net.Uri
import android.os.Bundle
import android.provider.OpenableColumns
import android.view.View
import android.widget.EditText
import android.widget.LinearLayout
import android.widget.Toast
import androidx.activity.result.contract.ActivityResultContracts
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.ViewModelProvider
import com.example.simex_movil.ui.PerfilState
import com.example.simex_movil.ui.ProfileViewModel
import com.example.simex_movil.ui.UserProfileRequest
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.button.MaterialButton
import okhttp3.MultipartBody
import java.io.File
import java.io.FileOutputStream

class ProfileActivity: AppCompatActivity() {

    private lateinit var viewModel: ProfileViewModel
    private var idUsuarioActual: Int = 0
    private var uriArchivoSeleccionado: Uri? = null

    //Entrar a la galeria
    private val selectorDeArchivos = registerForActivityResult(ActivityResultContracts.GetContent()) { uri: Uri? ->
        if (uri != null) {
            uriArchivoSeleccionado = uri
            val nombreArchivo = obtenerNombreArchivo(uri)
            val btnSeleccionar = findViewById<MaterialButton>(R.id.btn_seleccionar_archivo)

            // Cambiamos el texto para que vea que sí se seleccionó
            btnSeleccionar.text = "Subir: $nombreArchivo"
            btnSeleccionar.setBackgroundColor(android.graphics.Color.parseColor("#8AB242"))
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profile)

        viewModel = ViewModelProvider(this).get(ProfileViewModel::class.java)

        // 1. BUSCAMOS LOS ELEMENTOS
        val inputFirstName = findViewById<EditText>(R.id.inputFirstName)
        val inputLastName = findViewById<EditText>(R.id.inputLastName)
        val inputEmail = findViewById<EditText>(R.id.inputEmail)
        val inputTelephone = findViewById<EditText>(R.id.inputTelephoneNumber)

        val inputPassword = findViewById<EditText>(R.id.inputPassword)
        val inputConfirmarPass = findViewById<EditText>(R.id.inputConfirmarPass)
        val seccionPassword = findViewById<LinearLayout>(R.id.seccionPassword)

        val btnEditar = findViewById<android.widget.Button>(R.id.btnEditarPerfil)
        val btnGuardar = findViewById<MaterialButton>(R.id.btn_guardar_cambios)
        val btnSeleccionarArchivo = findViewById<MaterialButton>(R.id.btn_seleccionar_archivo)

        // 2. DATOS DE SESIÓN
        val sharedPref = getSharedPreferences("PreferenciasUsuario", Context.MODE_PRIVATE)
        val token = "Bearer " + (sharedPref.getString("token", "") ?: "")
        idUsuarioActual = sharedPref.getInt("client_id", 0)

        // 3. CARGAMOS DATOS AL ABRIR
        if (idUsuarioActual != 0) {
            viewModel.obtenerPerfil(token, idUsuarioActual)
        }

        // 4. BOTÓN: HABILIAR EDICIÓN
        btnEditar.setOnClickListener {
            inputFirstName.isEnabled = true
            inputLastName.isEnabled = true
            inputEmail.isEnabled = true
            inputTelephone.isEnabled = true

            seccionPassword.visibility = View.VISIBLE
            btnGuardar.visibility = View.VISIBLE
            btnEditar.visibility = View.GONE
        }

        // 5. BOTÓN: GUARDAR PERFIL
        btnGuardar.setOnClickListener {
            val pass1 = inputPassword.text.toString()
            val pass2 = inputConfirmarPass.text.toString()

            if (pass1.isNotEmpty() || pass2.isNotEmpty()) {
                if (pass1 != pass2) {
                    Toast.makeText(this, "Las contraseñas no coinciden", Toast.LENGTH_SHORT).show()
                    return@setOnClickListener
                }
            }

            val usuarioActualizado = UserProfileRequest(
                id = idUsuarioActual,
                firstName = inputFirstName.text.toString(),
                lastName = inputLastName.text.toString(),
                email = inputEmail.text.toString(),
                phoneNumber = inputTelephone.text.toString(),
                passwordHash = if (pass1.isNotEmpty()) pass1 else null,
                isActive = true
            )
            viewModel.actualizarPerfil(token, idUsuarioActual, usuarioActualizado)
        }

        // 6. BOTÓN: ABRIR GALERÍA
        btnSeleccionarArchivo.setOnClickListener {
            if (uriArchivoSeleccionado == null) {
                // Si no hay foto, abrimos la galería
                selectorDeArchivos.launch("*/*")
            } else {
                // Si ya eligió una foto, la enviamos al servidor
                enviarDniAlServidor(token)
            }
        }

        // 7. OBSERVADOR DE RESPUESTAS DEL SERVIDOR
        viewModel.estado.observe(this) { estado ->
            when (estado) {
                is PerfilState.SuccessLoad -> {
                    val user = estado.user
                    inputFirstName.setText(user.firstName)
                    inputLastName.setText(user.lastName)
                    inputEmail.setText(user.email)
                    inputTelephone.setText(user.phoneNumber ?: "")
                }
                is PerfilState.SuccessUpdate -> {
                    Toast.makeText(this, "Perfil actualizado con éxito", Toast.LENGTH_SHORT).show()
                    // Restauramos la vista
                    seccionPassword.visibility = View.GONE
                    btnGuardar.visibility = View.GONE
                    btnEditar.visibility = View.VISIBLE
                    inputPassword.setText("")
                    inputConfirmarPass.setText("")
                    // Volvemos a bloquear campos
                    inputFirstName.isEnabled = false
                    inputLastName.isEnabled = false
                    inputEmail.isEnabled = false
                    inputTelephone.isEnabled = false
                }
                is PerfilState.SuccessUploadDni -> {
                    Toast.makeText(this, "DNI subido con éxito", Toast.LENGTH_SHORT).show()
                    uriArchivoSeleccionado = null
                    btnSeleccionarArchivo.text = "Subir archivo"
                    btnSeleccionarArchivo.setBackgroundColor(android.graphics.Color.parseColor("#5C82B1"))
                }
                is PerfilState.Error -> {
                    Toast.makeText(this, estado.message, Toast.LENGTH_LONG).show()
                }
                is PerfilState.Loading -> { }
            }
        }
    }

    private fun enviarDniAlServidor(token: String) {
        val uri = uriArchivoSeleccionado ?: return

        // 1. Copiamos la foto a un archivo real temporal
        val archivoReal = crearArchivoTemporal(uri) ?: return

        // 2. Preparamos el archivo para Retrofit (Sintaxis universal)
        val mediaTypeArchivo = okhttp3.MediaType.parse("multipart/form-data")
        val requestFile = okhttp3.RequestBody.create(mediaTypeArchivo, archivoReal)
        val bodyArchivo = MultipartBody.Part.createFormData("archive", archivoReal.name, requestFile)

        // 3. Preparamos el ID del cliente (Sintaxis universal)
        val mediaTypeTexto = okhttp3.MediaType.parse("text/plain")
        val bodyId = okhttp3.RequestBody.create(mediaTypeTexto, idUsuarioActual.toString())

        // 4. Lo enviamos
        viewModel.subirDni(token, bodyId, bodyArchivo)
    }

    private fun crearArchivoTemporal(uri: Uri): File? {
        return try {
            val inputStream = contentResolver.openInputStream(uri) ?: return null
            val nombre = obtenerNombreArchivo(uri)
            val archivoTemp = File(cacheDir, nombre)
            val outputStream = FileOutputStream(archivoTemp)

            inputStream.copyTo(outputStream)

            inputStream.close()
            outputStream.close()
            archivoTemp
        } catch (e: Exception) {
            e.printStackTrace()
            null
        }
    }

    private fun obtenerNombreArchivo(uri: Uri): String {
        var nombre = "archivo_desconocido"
        contentResolver.query(uri, null, null, null, null)?.use { cursor ->
            if (cursor.moveToFirst()) {
                val index = cursor.getColumnIndex(OpenableColumns.DISPLAY_NAME)
                if (index != -1) {
                    nombre = cursor.getString(index)
                }
            }
        }
        return nombre
    }
}
