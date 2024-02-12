<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import {Link} from "@inertiajs/vue3"
import { defineComponent } from 'vue';

defineComponent({
  components: {
    Link
  },
});
const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    provinces: {
        type: Object,
    },
    timezones: {
        type: Object,
    },
    auth_user: {
        type: Object,
    },
});


const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 7000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

const form = useForm({
    user_id: props.auth_user.id,
    name: props.auth_user.name,
    second_name: props.auth_user.second_name,
    phone_number: props.auth_user.phone_number,
    email: props.auth_user.email,
});

function submit() {
    if (form.name == null || form.phone_number == null || form.email == null || form.second_name == null) {
        Toast.fire({
            icon: 'error',
            title: 'All fields are required'
        })
        return;
    }

    router.post('/profile-update', form)

    Toast.fire({
        icon: 'success',
        title: 'Profile Created Successfully'
    })
}
</script>

<template>
    <Head title=" Update Profile" />

    <AuthenticatedLayout>
        <div class="mt-7 col-md-12">
            <div class="block block-rounded" href="javascript:void(0)">
                <div class="px-5 block-header">
                    <Link class="mr-3 btn btn-info text-capitalize" :href="route('profile')">Back</Link>
                    <h2 class="block-title" style="font-size: 20px; text-transform: uppercase;">Update Profile</h2>
                </div>
                <div class="px-5 pb-5 block-content">
                    <form  @submit.prevent="submit">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4 row">
                                    <div class="col-6">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control form-control-lg"  v-model="form.name" placeholder="Enter your first_name...">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Second Name</label>
                                        <input type="text" class="form-control form-control-lg"  v-model="form.second_name" placeholder="Enter your second_name...">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-12">
                                <div class="mb-4 row">
                                    <div class="col-12">
                                        <label class="form-label">Your domains</label>
                                        <input type="text" class="form-control form-control-lg"  v-model="form.domains" placeholder="Enter your domains comma ' , ' separated">
                                    </div>
                                </div>
                            </div> -->
                             <div class="col-md-12">
                            <div class="mb-4 row">
                                <div class="col-12">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control form-control-lg"  v-model="form.email" placeholder="Enter your email..">
                                </div>
                            </div>
                             <div class="mb-5 col-md-12">
                                <div class="col-12">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control form-control-lg"  v-model="form.phone_number" placeholder="Enter your phone number..">
                                </div>
                            </div>
                        </div>
                        
                            <!-- <div class="col-md-12">
                                <div class="mb-4 row">
                                    <div class="col-6">
                                        <label class="form-label">Province</label>
                                        <select class="form-select" v-model="form.province">
                                            <option :value="null">Select A Province</option>
                                            <option v-for="province in provinces" v-bind:key="province" :value="province">{{ province }}</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">TImezone</label>
                                        <select class="form-select" v-model="form.timezone">
                                            <option :value="null">Select A timezone</option>
                                            <option v-for="timezone in timezones" v-bind:key="timezone" :value="timezone.location">{{ timezone.time }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="mb-4">
                            <button class="btn btn-primary btn-primary-custom" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                <span class="spinner-border text-dark me-1" v-if="form.processing"></span>
                                <i class="opacity-50 fa fa-check me-1" v-else></i>
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-if="false">
            <div class="py-12">
                <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
                    <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
                    </div>

                    <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                        <UpdatePasswordForm class="max-w-xl" />
                    </div>

                    <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                        <DeleteUserForm class="max-w-xl" />
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
