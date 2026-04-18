package com.example.simex_movil.ui

import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.simex_movil.network.RetrofitClient
import kotlinx.coroutines.launch

class NotificacionesViewModel : ViewModel() {

    // Aquí guardaremos la cantidad de mensajes sin leer
    private val _cantidadSinLeer = MutableLiveData<Int>()
    val cantidadSinLeer: LiveData<Int> = _cantidadSinLeer

    fun revisarNotificaciones(token: String) {
        viewModelScope.launch {
            try {
                // Hacemos la llamada a tu API de .NET
                val response = RetrofitClient.dotNetApiService.getNotificacionesSinLeer(token)

                if (response.isSuccessful && response.body() != null) {
                    // Contamos cuántas llegaron y actualizamos el LiveData
                    _cantidadSinLeer.value = response.body()!!.size
                } else {
                    _cantidadSinLeer.value = 0
                }
            } catch (e: Exception) {
                // Si hay error de red, no hacemos nada para no cerrar la app
                _cantidadSinLeer.value = 0
            }
        }
    }
}