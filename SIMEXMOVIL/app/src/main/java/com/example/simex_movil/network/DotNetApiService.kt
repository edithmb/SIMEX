package com.example.simex_movil.network

import com.example.simex_movil.ui.UpdateLogisticsStatusRequest
import com.example.simex_movil.ui.UpdateStatusResponse
import com.example.simex_movil.ui.UserProfileRequest
import com.example.simex_movil.ui.UserResponse
import okhttp3.MultipartBody
import okhttp3.ResponseBody
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.Header
import retrofit2.http.Multipart
import retrofit2.http.POST
import retrofit2.http.PUT
import retrofit2.http.Part
import retrofit2.http.Path

interface DotNetApiService {

// 1. OBTENER PERFIL (GET)
    @GET("Users/{id}")
    suspend fun getUserProfile(
        @Path("id") userId: Int,
        @Header("Authorization") token: String
    ): Response<UserResponse>

    // 2. ACTUALIZAR PERFIL (PUT)
    @PUT("Users/{id}")
    suspend fun updateUserProfile(
        @Path("id") userId: Int,
        @Header("Authorization") token: String,
        @Body userData: UserProfileRequest
    ): Response<Void>

    // 3. SUBIR DNI (POST Multipart)
    @Multipart
    @POST("DocumentsPerson/upload")
    suspend fun uploadDni(
        @Header("Authorization") token: String,
        @Part archive: MultipartBody.Part,
        @Part("clientId") clientId: okhttp3.RequestBody
    ): Response<Void>

    // cambio de estado
    @PUT("LogisticsOperations/{id}/status")
    suspend fun changeTrackingStatus(
        @Path("id") id: Int,
        @Header("Authorization") token: String,
        @Body request: UpdateLogisticsStatusRequest
    ): retrofit2.Response<UpdateStatusResponse>
}