package com.example.simex_movil.ui

import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.simex_movil.network.RetrofitClient
import kotlinx.coroutines.launch

class TrackingViewModel : ViewModel() {

    private val _operacion = MutableLiveData<LogisticsOperationResponse?>()
    val operacion: LiveData<LogisticsOperationResponse?> = _operacion

    private val _error = MutableLiveData<String>()
    val error: LiveData<String> = _error

    fun cargarDetalleOperacion(token: String, idBuscado: Int) {
        viewModelScope.launch {
            try {
                // Volvemos a pedir la caja a Laravel
                val response = RetrofitClient.apiService.getOperacionesCliente(token)

                if (response.isSuccessful && response.body() != null) {
                    val todasLasOperaciones = response.body()!!.data

                    //Buscamos en la lista la que tenga el ID exacto
                    val operacionEncontrada = todasLasOperaciones.find { it.id == idBuscado }

                    if (operacionEncontrada != null) {
                        _operacion.value = operacionEncontrada
                    } else {
                        _error.value = "No se encontró la operación"
                    }
                } else {
                    _error.value = "Error del servidor: ${response.code()}"
                }
            } catch (e: Exception) {
                _error.value = "Fallo de conexión: ${e.message}"
            }
        }
    }
}