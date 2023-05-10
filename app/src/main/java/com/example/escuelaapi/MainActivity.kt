package com.example.escuelaapi

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.Menu
import android.view.MenuItem
import android.widget.Toast
import com.example.escuelaapi.profesor.ProfesoresActivity
import com.google.firebase.auth.FirebaseAuth

class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
    }

    //Menu de tres puntos
    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
        menuInflater.inflate(R.menu.menuopciones, menu)
        return true
    }

    //Funcionalidad del menú
    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        val id = item.itemId

        if (id == R.id.opcionAlumnos) {
            Toast.makeText(this, getString(R.string.mensaje_1), Toast.LENGTH_LONG).show();
            val intent = Intent(this, AlumnosActivity::class.java)
            startActivity(intent)
        }
        if (id == R.id.opcionProfesores) {
            Toast.makeText(this, getString(R.string.mensaje_2), Toast.LENGTH_LONG).show();
            val intent = Intent(this, ProfesoresActivity::class.java)
            startActivity(intent)
        }
        if (id == R.id.opcionCerrarSesion) {
            FirebaseAuth.getInstance().signOut().also {
                Toast.makeText(this, getString(R.string.mensaje_3), Toast.LENGTH_LONG).show()

                val intent = Intent(this, Registro::class.java)
                startActivity(intent)
                finish()
            }
        }
        return super.onOptionsItemSelected(item)
    }

}