package com.example.simex_movil

import android.os.Bundle
import android.net.Uri
import androidx.activity.result.contract.ActivityResultContracts
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.example.simex_movil.network.DniSocketManager
import com.google.android.material.button.MaterialButton
import com.google.android.material.bottomnavigation.BottomNavigationView

class ProfileActivity: AppCompatActivity() {

    // gestor de sockets
    private lateinit var dniSocketManager: DniSocketManager

    private val pickFileLauncher = registerForActivityResult(ActivityResultContracts.GetContent()) {
        uri: Uri? ->
        if (uri != null) {
            Toast.makeText(this, "Iniciando túnel seguro...", Toast.LENGTH_SHORT).show()
            subirDniConHilosYSockets(uri)
        } else {
            Toast.makeText(this, "No se seleccionó ningún archivo", Toast.LENGTH_SHORT).show()
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profile)

        val navButton = findViewById<BottomNavigationView>(R.id.bottom_navigation)
        navButton.selectedItemId = R.id.nav_profile
        menuConfiguration(this, navButton)

        // inicializar el manager de dni socket
        val tokenGuardado = "hdahd"
        dniSocketManager = DniSocketManager(this, tokenGuardado)

        val btnSelectFile = findViewById<MaterialButton>(R.id.btn_seleccionar_archivo)

        btnSelectFile.setOnClickListener {
            pickFileLauncher.launch("*/*") //abre galeria pidiendo pdfs o imagenes
        }

    }

    private fun subirDniConHilosYSockets(uri:Uri){
        // datos de prueba
        val entityId = 1
        val entityType = "Client"
        val fileName = "dni_seguro.jpg"

        dniSocketManager.connectAndUpload(
            uri = uri,
            entityId = entityId,
            entityType = entityType,
            fileName = fileName,
            onStatusUpdate = { mensajeDelServidor ->
                Toast.makeText(this, mensajeDelServidor, Toast.LENGTH_LONG).show()
            }
        )
    }
}