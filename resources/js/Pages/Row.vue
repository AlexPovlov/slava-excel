<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>
                            <form action="" method="post">
                                <label for="formName" class="form-label"
                                    >Excel file</label
                                >
                                <input
                                    class="form-control"
                                    type="text"
                                    placeholder="Name"
                                    id="formName"
                                    name="name"
                                />
                                <button
                                    type="submit"
                                    class="mt-3 mb-3 btn btn-primary"
                                >
                                    Добавить
                                </button>
                            </form>
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
                                    <tr v-for="row in rows" :key="row.id">
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
