<template>
  <div class="card">
    <h2>Edit User</h2>

    <p v-if="loading">Loading...</p>

    <div v-else>
      <input v-model="form.name" placeholder="Name" />
      <input v-model="form.email" placeholder="Email" />

      <button class="btn" @click="updateUser">Update</button>
    </div>

    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../services/api";

const route = useRoute();
const router = useRouter();
const id = route.params.id;

const loading = ref(true);
const error = ref("");

const form = ref({
  name: "",
  email: "",
});

onMounted(async () => {
  try {
    const res = await api.get(`/users/${id}`);
    form.value = res.data;
  } catch (e) {
    error.value = "Failed to load user";
  } finally {
    loading.value = false;
  }
});

async function updateUser() {
  error.value = "";
  try {
    await api.put(`/users/${id}`, form.value);
    router.push("/users");
  } catch (e) {
    error.value = "Failed to update user";
  }
}
</script>

<style scoped>
.card {
  max-width: 600px;
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
}
.btn {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  cursor: pointer;
  background: #f8fafc;
  margin-top: 10px;
}
.error {
  color: #b91c1c;
  margin-top: 10px;
}
input {
  display: block;
  width: 100%;
  padding: 10px;
  margin: 8px 0;
  border: 1px solid #ddd;
  border-radius: 8px;
}
</style>
