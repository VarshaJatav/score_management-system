<template>
  <div class="flex h-screen items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-xl shadow-lg w-96">
      <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>
      
      <div v-if="error" class="text-red-500 mb-3 text-sm">{{ error }}</div>
      
      <form @submit.prevent="login">
        <div class="mb-4">
          <label class="block text-gray-700">Email</label>
          <input v-model="email" type="email" class="w-full border p-2 rounded" required />
        </div>
        
        <div class="mb-4">
          <label class="block text-gray-700">Password</label>
          <input v-model="password" type="password" class="w-full border p-2 rounded" required />
        </div>
        
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
          Login
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const email = ref('')
const password = ref('')
const error = ref('')

async function login() {
  try {
    const { data } = await axios.post('/api/login', {
      email: email.value,
      password: password.value
    })
    localStorage.setItem('token', data.token) // store token for later requests
    window.location.href = '/admin/match/1' // redirect to admin panel
  } catch (err) {
    error.value = err.response?.data?.message || "Login failed"
  }
}
</script>
