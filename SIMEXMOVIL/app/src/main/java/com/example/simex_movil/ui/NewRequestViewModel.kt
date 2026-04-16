package com.example.simex_movil.ui

import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.simex_movil.network.RetrofitClient
import kotlinx.coroutines.launch

// Los 3 estados posibles al enviar el formulario
sealed class SolicitudState {
    object Loading : SolicitudState()
    object Success : SolicitudState()
    data class Error(val message: String) : SolicitudState()
}

class NewRequestViewModel : ViewModel() {

    private val _estado = MutableLiveData<SolicitudState>()
    val estado: LiveData<SolicitudState> = _estado

    fun crearSolicitudCotizacion(token: String, datos: RequestOffer) {
        _estado.value = SolicitudState.Loading

        viewModelScope.launch {
            try {
                // Llamamos a la API
                val response = RetrofitClient.apiService.crearSolicitud(token, datos)
                println("URL LLAMADA: ${response.raw().request().url()}")
                if (response.isSuccessful) {
                    _estado.value = SolicitudState.Success
                } else {
                    val mensajeOculto = response.errorBody()?.string() ?: "Error vacío"
                    println("¡EL SECRETO DE LARAVEL!: $mensajeOculto")
                    _estado.value = SolicitudState.Error("Error al crear: ${response.code()}")
                }
            } catch (e: Exception) {
                _estado.value = SolicitudState.Error("Fallo de conexión: ${e.message}")
            }
        }
    }
}