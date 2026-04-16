package com.example.simex_movil.ui
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import kotlinx.coroutines.launch
import com.example.simex_movil.network.RetrofitClient

class HomeViewModel : ViewModel() {

    private val _operaciones = MutableLiveData<List<LogisticsOperationResponse>>()
    val operaciones: LiveData<List<LogisticsOperationResponse>> = _operaciones

    private val _error = MutableLiveData<String>()
    val error: LiveData<String> = _error

    fun cargarOperaciones(token: String) {
        viewModelScope.launch {
            try {
                // 1. Usamos tu tubería personalizada
                val response = RetrofitClient.apiService.getOperacionesCliente(token)

                if (response.isSuccessful && response.body() != null) {
                    // 2. Recibimos TU caja
                    val cajaPaginada = response.body()!!

                    // 3. ¡Ahora sí encajan perfectamente!
                    _operaciones.value = cajaPaginada.data

                } else {
                    _error.value = "Error al cargar: ${response.code()}"
                }
            } catch (e: Exception) {
                _error.value = "Fallo de conexión: ${e.message}"
            }
        }
    }
}