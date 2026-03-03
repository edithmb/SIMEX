package com.example.simex_movil

import android.app.Activity
import android.content.ClipData
import android.content.Intent
import android.view.MenuItem
import com.google.android.material.bottomnavigation.BottomNavigationView

fun menuConfiguration(activity: Activity, bottomNav: BottomNavigationView) {

    bottomNav.setOnItemSelectedListener { item: MenuItem ->
        when(item.itemId) {
            R.id.nav_home -> {
                if (activity!is HomeClientActivity){
                    val intent = Intent(activity, HomeClientActivity::class.java)
                    activity.startActivity(intent)
                }
                true
            }
            R.id.nav_profile -> {
                if(activity !is ProfileActivity){
                    val intent = Intent (activity, ProfileActivity::class.java)
                    activity.startActivity(intent)
                }
                true
            }
            R.id.nav_game -> {
                if(activity !is GameActivity){
                    val intent = Intent (activity, GameActivity::class.java)
                    activity.startActivity(intent)
                }
                true
            }
            R.id.nav_chat -> {
                if(activity !is ChatActivity){
                    val intent = Intent(activity, ChatActivity::class.java)
                    activity.startActivity(intent)
                }
                true
            }
            R.id.nav_settings -> {
                if(activity !is SettingsActivity){
                    val intent = Intent(activity, SettingsActivity::class.java)
                    activity.startActivity(intent)
                }
                true
            } else -> false
        }
    }
}