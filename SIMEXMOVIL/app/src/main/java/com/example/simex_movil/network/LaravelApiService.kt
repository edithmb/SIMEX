package com.example.simex_movil.network

import com.example.simex_movil.ui.PaginatedOperationsResponse
import com.example.simex_movil.ui.Request
import okhttp3.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.Header
import retrofit2.http.Headers
import retrofit2.http.POST

interface LaravelApiService {

    @POST("login")
    suspend fun login(@Body request: LoginRequest): retrofit2.Response<LoginResponse>

    // El @Headers va arriba, así no estorbamos los parámetros
    @Headers("Accept: application/json")
    @POST("client-requests-client")
    suspend fun crearSolicitud(
        @Header("Authorization") token: String,
        @Body request: Request
    ): retrofit2.Response<Void>

    // obtener las operaciones logisticas de los agentes
    @GET("logistics-operations")
    suspend fun getAllOperaciones(@Header("Authorization") token: String):
            retrofit2.Response<PaginatedOperationsResponse>
}