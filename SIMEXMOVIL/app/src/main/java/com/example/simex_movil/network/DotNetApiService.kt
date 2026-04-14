package com.example.simex_movil.network

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

    @GET("Clients/{id}")
    suspend fun getClientProfile(
        @Path("id") clientId: Int,
        @Header("Authorization") token: String
    ): Response<ClientResponse>

    //Actualizar los datos del perfil (PUT)
    @PUT("Clients/{id}")
    suspend fun updateClientProfile(
        @Path("id") clientId: Int,
        @Header("Authorization") token: String,
        @Body clientData: ClientResponse
    ): Response<String>

    //Descargar el DNI desencriptado
    @GET("DocumentsPerson/download/{id}")
    suspend fun downloadDni(
        @Path("id") documentId: Int,
        @Header("Authorization") token: String
    ): Response<ResponseBody>

    @Multipart
    @POST("DocumentsPerson/upload")
    suspend fun uploadDni(
        @Header("Authorization") token: String,
        @Part archive: okhttp3.MultipartBody.Part
    ): retrofit2.Response<Void>
}