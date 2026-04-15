package com.example.simex_movil.ui

import android.graphics.Mesh
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.simex_movil.network.LoginRequest
import com.example.simex_movil.network.RetrofitClient
import kotlinx.coroutines.launch

//Estados de la pantalla al momento de iniciar sesion
sealed class LoginState {
    object Loading : LoginState()
    data class Success(val token: String, val firstName: String, val roleName: String) : LoginState()
    data class Error(val message: String) : LoginState()
}

class LoginViewModel : ViewModel(){

    private val _loginState = MutableLiveData<LoginState>()
    val loginState: LiveData<LoginState> = _loginState

    //Se llama a la funcion cuando el usuario le de al boton de iniciar sesion
    fun starLogin(email: String, password: String) {
        _loginState.value = LoginState.Loading

        viewModelScope.launch {
            try {
                val request = LoginRequest(email, password)

                val response = RetrofitClient.apiService.login(request)

                if(response.isSuccessful && response.body() != null){
                    val data = response.body()!!

                    val token = data.token
                    val nombre = data.user.first_name
                    val nombreRol = data.user.role.name
                    _loginState.value = LoginState.Success(token, nombre, nombreRol)
                } else {
                    _loginState.value = LoginState.Error("Credenciales incorrectas")
                }
            } catch (e: Exception) {
                _loginState.value = LoginState.Error("fallo tecnico: ${e.message}")
            }
        }
    }

}