package com.example.simex_movil

import android.content.Context
import android.os.Bundle
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.simex_movil.network.RetrofitClient
import com.example.simex_movil.ui.NotificacionesAdapter
import kotlinx.coroutines.launch

class NotificationsActivity : AppCompatActivity() {

    private lateinit var adaptador: NotificacionesAdapter
    private var tokenFormateado = ""

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_notifications)

        // 1. Preparamos el botón de volver
        val btnVolver = findViewById<ImageView>(R.id.btnVolverNotificaciones)
        btnVolver.setOnClickListener { finish() } // Cierra la pantalla y vuelve al Home

        // 2. Preparamos la lista (RecyclerView)
        val rvLista = findViewById<RecyclerView>(R.id.rvListaNotificaciones)
        rvLista.layoutManager = LinearLayoutManager(this)

        adaptador = NotificacionesAdapter(emptyList()) { notificacionTocada ->
            // --- ESTO PASA CUANDO EL USUARIO TOCA UNA TARJETA ---
            marcarComoLeidaEnBaseDeDatos(notificacionTocada.id)
        }
        rvLista.adapter = adaptador

        // 3. Sacamos el token de la caja fuerte
        val sharedPref = getSharedPreferences("PreferenciasUsuario", Context.MODE_PRIVATE)
        val token = sharedPref.getString("token", "") ?: ""
        tokenFormateado = "Bearer $token"

        // 4. Llamamos a .NET para pedir los mensajes
        if (token.isNotEmpty()) {
            cargarNotificaciones()
        }
    }

    private fun cargarNotificaciones() {
        lifecycleScope.launch {
            try {
                // Pedimos los datos a la API que creaste
                val response = RetrofitClient.dotNetApiService.getNotificacionesSinLeer(tokenFormateado)

                if (response.isSuccessful && response.body() != null) {
                    val lista = response.body()!!
                    adaptador.actualizarLista(lista) // Llenamos la pantalla
                }
            } catch (e: Exception) {
                Toast.makeText(this@NotificationsActivity, "Error al conectar", Toast.LENGTH_SHORT).show()
            }
        }
    }

    private fun marcarComoLeidaEnBaseDeDatos(idNotificacion: Int) {
        lifecycleScope.launch {
            try {
                // Llamamos a tu API PUT de .NET para apagar el puntito
                val response = RetrofitClient.dotNetApiService.marcarNotificacionLeida(tokenFormateado, idNotificacion)

                if (response.isSuccessful) {
                    // Si se marcó como leída con éxito, volvemos a cargar la lista
                    // para que esa notificación desaparezca de la pantalla de "No Leídos"
                    cargarNotificaciones()
                }
            } catch (e: Exception) {
                // Ignoramos error de red
            }
        }
    }
}