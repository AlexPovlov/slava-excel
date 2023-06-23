<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
import { onMounted, ref } from "vue";

const props = defineProps({
    rows: [],
});

onMounted(() => {
    Echo.private(`model`).listen(`.RowCreated`, ({ model }) => {
        props.rows.push(model);
    });
});

const row = { name: null };
const errors = ref({});

const addRow = () => {
    axios
        .post(route("row.store"), row)
        .then(() => {
            errors.value = {};
            row.name = null;
        })
        .catch(({ response }) => {
            console.log(response.data.errors);
            errors.value = response.data.errors;
        });
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>
                            <label for="formName" class="form-label"
                                >Name</label
                            >
                            <input
                                class="form-control mb-2"
                                type="text"
                                placeholder="Name"
                                id="formName"
                                name="name"
                                v-model="row.name"
                            />

                            <div v-for="error in errors?.name" :key="error">
                                <p class="text-danger">{{ error }}</p>
                            </div>

                            <button
                                @click="addRow()"
                                type="submit"
                                class="mb-3 btn btn-primary"
                            >
                                Добавить
                            </button>
                        </div>
                        <div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="row in props.rows" :key="row">
                                        <th scope="row">{{ row.id }}</th>
                                        <td>{{ row.name }}</td>
                                        <td>{{ row.created_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
