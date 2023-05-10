package com.example.escuelaapi.api

import com.example.escuelaapi.model.Alumno
import com.example.escuelaapi.model.Profesor
import retrofit2.Call
import retrofit2.http.*

interface AlumnoApi {
    @GET("alumno.php")
    fun obtenerAlumnos(): Call<List<Alumno>>
    @GET("alumno.php/{id}")
    fun obtenerAlumnoPorId(@Path("id") id: Int): Call<Alumno>
    @POST("alumno.php")
    fun crearAlumno(@Body alumno: Alumno): Call<Alumno>
    @PUT("alumno.php/{id}")
    fun actualizarAlumno(@Path("id") id: Int, @Body alumno: Alumno): Call<Alumno>
    @PUT("alumno.php/{id}")
    fun eliminarAlumno(@Path("id") id: Int, @Body alumno: Alumno): Call<Void>
}

interface ProfesorApi {
    @GET("profesor.php")
    fun obtenerProfesores(): Call<List<Profesor>>
    @GET("profesor.php/{id}")
    fun obtenerProfesorPorId(@Path("id") id: Int): Call<Profesor>
    @POST("profesor.php")
    fun crearProfesor(@Body profesor: Profesor): Call<Profesor>
    @PUT("profesor.php/{id}")
    fun actualizarProfesor(@Path("id") id: Int, @Body profesor: Profesor): Call<Profesor>
    @PUT("profesor.php/{id}")
    fun eliminarProfesor(@Path("id") id: Int, @Body profesor: Profesor): Call<Void>
}