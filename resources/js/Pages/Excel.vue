<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive } from "vue";
import { router, useForm } from "@inertiajs/vue3";

defineProps({ errors: Object });

const form = useForm({
    file: null,
});

function upload_file({ target }) {
    form.file = target.files[0];
}
function sub({ target }) {
    form.post(route("excel.store"));
    form.clearErrors();
    target.reset();
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="sub">
                            <label for="formFile" class="form-label"
                                >Excel file</label
                            >
                            <input
                                class="form-control"
                                type="file"
                                id="formFile"
                                @change="upload_file"
                            />
                            <div v-if="form.errors.file" class="text-danger">
                                {{ form.errors.file }}
                            </div>
                            <input type="submit" class="mt-3 btn btn-primary" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
