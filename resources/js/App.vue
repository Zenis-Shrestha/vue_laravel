<template>
  <div style="padding:20px">
    <div class="navbar">
      <div class="nav-left">
        <span class="brand">Auth Demo</span>
      </div>

      <div class="nav-right">
        <!-- Logged OUT -->
        <template v-if="!isAuthenticated">
          <router-link class="nav-btn" to="/login">Login</router-link>
          <router-link class="nav-btn" to="/register">Register</router-link>
        </template>

        <!-- Logged IN -->
        <template v-else>
          <router-link class="nav-btn" to="/dashboard">Dashboard</router-link>
          <router-link class="nav-btn" to="/about">Me</router-link>
          <router-link class="nav-btn" to="/users">Users</router-link>
          <!-- <router-link class="nav-btn" to="/users/create">Create</router-link> -->
          <button class="nav-btn danger" @click="logout">Logout</button>
        </template>

        <router-link class="nav-btn ghost" to="/about">About</router-link>
      </div>
    </div>

    <router-view />
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const token = ref(localStorage.getItem("token") || null);
const isAuthenticated = computed(() => !!token.value);

function setAxiosToken() {
  const t = localStorage.getItem("token");
  if (t) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${t}`;
    token.value = t;
  } else {
    delete axios.defaults.headers.common["Authorization"];
    token.value = null;
  }
}

async function me() {
  try {
    setAxiosToken();
    const res = await axios.get("/api/me");
    alert(JSON.stringify(res.data, null, 2));
  } catch (e) {
    alert("Not logged in or token invalid");
  }
}

function logout() {
  localStorage.removeItem("token");
  setAxiosToken();
  router.push("/login");
}

onMounted(() => {
  setAxiosToken();
});
</script>

<style scoped>
/* keep your navbar CSS same as before */
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: linear-gradient(90deg, #2563eb, #1d4ed8);
  padding: 12px 24px;
  border-radius: 10px;
  margin-bottom: 20px;
  box-shadow: 0 6px 18px rgba(37, 99, 235, 0.25);
}
.brand {
  color: #ffffff;
  font-size: 18px;
  font-weight: 700;
  letter-spacing: 0.5px;
}
.nav-right {
  display: flex;
  align-items: center;
  gap: 10px;
}
.nav-btn {
  background: rgba(255, 255, 255, 0.15);
  color: #ffffff;
  border: none;
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.25s ease;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
}
.nav-btn:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-1px);
}
.nav-btn.ghost {
  background: transparent;
  border: 1px solid rgba(255, 255, 255, 0.5);
}
.nav-btn.ghost:hover {
  background: rgba(255, 255, 255, 0.2);
}
.nav-btn.danger {
  background: #ef4444;
}
.nav-btn.danger:hover {
  background: #dc2626;
}
</style>
