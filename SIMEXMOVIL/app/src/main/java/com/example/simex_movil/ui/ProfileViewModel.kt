package com.example.simex_movil.ui

import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.simex_movil.network.RetrofitClient
import kotlinx.coroutines.launch
import okhttp3.MultipartBody
import okhttp3.RequestBody

sealed class PerfilState {
    object Loading : PerfilState()
    data class SuccessLoad(val user: UserResponse) : PerfilState()
    object SuccessUpdate : PerfilState()
    object SuccessUploadDni : PerfilState()
    data class Error(val message: String) : PerfilState()
}

class ProfileViewModel : ViewModel() {

    private val _estado = MutableLiveData<PerfilState>()
    val estado: LiveData<PerfilState> = _estado

    // 1. TRAER LOS DATOS (GET)
    fun obtenerPerfil(token: String, userId: Int) {
        _estado.value = PerfilState.Loading
        viewModelScope.launch {
            try {
                val response = RetrofitClient.dotNetApiService.getUserProfile(userId, token)
                if (response.isSuccessful && response.body() != null) {
                    _estado.value = PerfilState.SuccessLoad(response.body()!!)
                } else {
                    _estado.value = PerfilState.Error("Error al cargar: ${response.code()}")
                }
            } catch (e: Exception) {
                _estado.value = PerfilState.Error("Fallo de conexión: ${e.message}")
            }
        }
    }

    // 2. ACTUALIZAR LOS DATOS (PUT)
    fun actualizarPerfil(token: String, userId: Int, userData: UserProfileRequest) {
        _estado.value = PerfilState.Loading
        viewModelScope.launch {
            try {
                val response = RetrofitClient.dotNetApiService.updateUserProfile(userId, token, userData)
                if (response.isSuccessful) {
                    _estado.value = PerfilState.SuccessUpdate
                } else {
                    _estado.value = PerfilState.Error("Error al actualizar: ${response.code()}")
                }
            } catch (e: Exception) {
                _estado.value = PerfilState.Error("Fallo de conexión: ${e.message}")
            }
        }
    }

    // 3. SUBIR EL DNI
    fun guardarDniEnBD(token: String, request: DniRecordRequest) {
        _estado.value = PerfilState.Loading
        viewModelScope.launch {
            try {
                val response = RetrofitClient.dotNetApiService.recordDniMetadata(token, request)
                if (response.isSuccessful) {
                    _estado.value = PerfilState.SuccessUploadDni
                } else {
                    _estado.value = PerfilState.Error("Error al guardar DNI en BD: ${response.code()}")
                }
            } catch (e: Exception) {
                _estado.value = PerfilState.Error("Fallo de red al guardar: ${e.message}")
            }
        }
    }
}