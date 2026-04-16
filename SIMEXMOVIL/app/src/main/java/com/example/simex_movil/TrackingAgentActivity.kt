package com.example.simex_movil

import android.content.Context
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.lifecycle.lifecycleScope
import com.example.simex_movil.network.DotNetApiService
import com.example.simex_movil.network.RetrofitClient
import com.example.simex_movil.ui.UpdateLogisticsStatusRequest
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.button.MaterialButton
import kotlinx.coroutines.launch

class TrackingAgentActivity : AppCompatActivity() {

    private var operacionId: Int = -1

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_trackingagent)

        // RECUPERAR EL ID DE LA OPERACIÓN (Enviado desde el HomeAgent)
        operacionId = intent.getIntExtra("OPERACION_ID", -1)

        if (operacionId == -1) {
            Toast.makeText(this, "Error al cargar la operación", Toast.LENGTH_SHORT).show()
            finish()
            return
        }

        configurarMenuAgente()

        // botn cambiar estados
         val btnCambiarEstado = findViewById<MaterialButton>(R.id.btnCambiarEstado)
         btnCambiarEstado.setOnClickListener { mostrarDialogoEstados() }

        mostrarDialogoEstados()
    }

    private fun configurarMenuAgente() {
        val navButton = findViewById<BottomNavigationView>(R.id.bottom_navigation)
        navButton?.let {
            it.menu.findItem(R.id.nav_game)?.isVisible = false

            val colorAzul = ContextCompat.getColor(this, R.color.blue)
            val colorBlanco = ContextCompat.getColor(this, R.color.white)

            it.setBackgroundColor(colorAzul)
            it.itemIconTintList = android.content.res.ColorStateList.valueOf(colorBlanco)
            it.itemTextColor = android.content.res.ColorStateList.valueOf(colorBlanco)
        }
    }

    private fun mostrarDialogoEstados() {
        // Lo que ve el usuario
        val estadosEspanol = arrayOf(
            "En preparación",
            "Recogido",
            "En tránsito al puerto",
            "Aduana de origen",
            "Carga en origen",
            "En tránsito",
            "Descarga en destino",
            "Aduana de destino",
            "Entregado"
        )

        // Lo que espera bd
        val estadosBD = arrayOf(
            "in preparation",
            "picked up",
            "in transit to port",
            "customs clearance origin",
            "loading at origin",
            "in transit",
            "unloading at destination",
            "customs clearance destination",
            "delivered"
        )

        AlertDialog.Builder(this)
            .setTitle("Actualizar Estado")
            .setItems(estadosEspanol) { _, which ->
                // Cuando el agente toca una opción, cogemos el texto en inglés correspondiente
                val estadoParaBD = estadosBD[which]
                actualizarEstadoEnServidor(estadoParaBD)
            }
            .setNegativeButton("Cancelar", null)
            .show()
    }

    private fun actualizarEstadoEnServidor(nuevoEstado: String) {
        val sharedPref = getSharedPreferences("PreferenciasUsuario", Context.MODE_PRIVATE)
        val token = "Bearer " + (sharedPref.getString("token", "") ?: "")

        val request = UpdateLogisticsStatusRequest(status = nuevoEstado)

        lifecycleScope.launch {
            try {
                // LLAMAMOS A .NET
                val response = RetrofitClient.dotNetApiService.changeTrackingStatus(operacionId, token, request)

                if (response.isSuccessful) {
                    Toast.makeText(this@TrackingAgentActivity, "Estado actualizado", Toast.LENGTH_SHORT).show()

                    finish()
                } else if (response.code() == 403) {
                    // LÓGICA DE INCOTERMS DE LA API
                    Toast.makeText(this@TrackingAgentActivity, "Permiso denegado por el Incoterm actual.", Toast.LENGTH_LONG).show()
                } else {
                    Toast.makeText(this@TrackingAgentActivity, "Error al actualizar: ${response.code()}", Toast.LENGTH_SHORT).show()
                }
            } catch (e: Exception) {
                Toast.makeText(this@TrackingAgentActivity, "Fallo: ${e.message}", Toast.LENGTH_SHORT).show()
            }
        }
    }
}