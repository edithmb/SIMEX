package com.example.simex_movil

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.simex_movil.network.RetrofitClient
import com.example.simex_movil.ui.TrackingAdapter
import com.google.android.material.bottomnavigation.BottomNavigationView
import kotlinx.coroutines.launch

class HomeAgentActivity : AppCompatActivity() {

    private lateinit var adaptador: TrackingAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_homeagent)

        // CONFIGURACIÓN DEL MENÚ Y COLORES
        val navButton = findViewById<BottomNavigationView>(R.id.bottom_navigation)
        navButton.menu.findItem(R.id.nav_game)?.isVisible = false

        val colorAzul = androidx.core.content.ContextCompat.getColor(this, R.color.blue)
        val colorBlanco = androidx.core.content.ContextCompat.getColor(this, R.color.white)

        navButton.setBackgroundColor(colorAzul)
        navButton.itemIconTintList = android.content.res.ColorStateList.valueOf(colorBlanco)
        navButton.itemTextColor = android.content.res.ColorStateList.valueOf(colorBlanco)

        // CONFIGURACIÓN DE LA LISTA (RecyclerView)
        val rvOperaciones = findViewById<RecyclerView>(R.id.rvOperacionesAgente)
        rvOperaciones.layoutManager = LinearLayoutManager(this)

        // Inicializamos el adaptador vacío y definimos qué pasa al hacer CLICK
        adaptador = TrackingAdapter(emptyList()) { operacion ->
            val intent = Intent(this, TrackingAgentActivity::class.java)
            intent.putExtra("OPERACION_ID", operacion.id)
            startActivity(intent)
        }
        rvOperaciones.adapter = adaptador

        // LLAMADA A LARAVEL PARA TRAER LOS DATOS
        cargarOperaciones()
    }

    private fun cargarOperaciones() {
        val sharedPref = getSharedPreferences("PreferenciasUsuario", Context.MODE_PRIVATE)
        val token = "Bearer " + (sharedPref.getString("token", "") ?: "")

        lifecycleScope.launch {
            try {
                // Llamamos a la API de Laravel
                val response = RetrofitClient.apiService.getAllOperaciones(token)

                if (response.isSuccessful && response.body() != null) {
                    val listaResponse = response.body()!!
                    // 'data' es la lista de operaciones dentro del objeto paginado de Laravel
                    adaptador.actualizarDatos(listaResponse.data)
                } else {
                    Toast.makeText(this@HomeAgentActivity, "Error al obtener datos", Toast.LENGTH_SHORT).show()
                }
            } catch (e: Exception) {
                Toast.makeText(this@HomeAgentActivity, "Fallo de conexión: ${e.message}", Toast.LENGTH_LONG).show()
            }
        }
    }
}