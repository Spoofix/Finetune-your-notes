<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <div class="block rounded bg-gd-dusk">
        <div class="block-content bg-white-5">
            <div class="text-center">
            <h1 class="h2 fw-bold text-white ">{{ ($page.props.auth.user.role_id == 1) ? "Admin Dashboard" : "User Dashboard" }}</h1>
            </div>
        </div>
        </div>

        <div class="row" v-if="$page.props.auth.user.role_id == 1">
<!--            admin content-->
            <div>
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Users List</h3>
                        <div class="block-options">
                        </div>
                    </div>
                    <div class="block-content">
                        <table class="table table-center">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"></th>
                                <th>User</th>
                                <th>Organization</th>
                                <th>Last seen</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in domain" :key="index" :style="{ background: index % 2 === 0 ? 'white' : 'rgba(25, 0, 0, 0.1)'}">
                                <th class="text-center" scope="row">{{ ++index }}</th>
                                <td>{{ item }}</td>
                                <td>{{ item }}</td>
                                <td>{{ item }}</td>
                                <td><div class="inline-block relative">
                                    <select class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:border-blue-500 focus:shadow-outline">
                                        <option>view profile</option>
                                        <option>view domains</option>
                                        <option>view user</option>

                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M10 12l-6-6-1.414 1.414L10 14.828l7.414-7.414L16 6z"/>
                                        </svg>
                                    </div>
                                </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<!--            user-->
            <div class="row" v-if="$page.props.auth.user.role_id == 2">
                <div>
                    <div class="block block-rounded">
<!--                        <div class="block-header block-header-default">-->
<!--                            <h3 class="block-title">Domain List</h3>-->
<!--                            <div class="block-options">-->
<!--                            </div>-->
<!--                        </div>-->
                        <div class="block-content">
                            <table class="table table-center">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;"></th>
                                    <th>Your domains</th>
                                    <th>spoff</th>
                                    <th>status</th>
                                    <th>re_scan</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in domain" :key="index" :style="{ background: index % 2 === 0 ? 'white' : 'rgba(25, 0, 0, 0.1)'}">
                                    <th class="text-center" scope="row">{{ ++index }}</th>
                                    <td>{{ item }}</td>
                                    <td><div class="inline-block relative">
                                        <select class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:border-blue-500 focus:shadow-outline">
                                            <option>view profile</option>
                                            <option>view domains</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M10 12l-6-6-1.414 1.414L10 14.828l7.414-7.414L16 6z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    </td>
                                </tr>
                                <tr>Add_domain</tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Row #4 -->
<!--            <div class="col-md-4" >-->
<!--                <div class="block block-rounded block-transparent bg-primary">-->
<!--                <div class="block-content block-content-full">-->
<!--                    <div class="py-3 text-center">-->
<!--                    <div class="mb-3">-->
<!--                        <i class="fa fa-users fa-4x text-primary-lighter"></i>-->
<!--                    </div>-->
<!--                    <div class="fs-4 fw-semibold text-white">Total User</div>-->
<!--                    <div class="text-white-75">All registered user</div>-->
<!--                    <div class="pt-3">-->
<!--                        <a class="btn btn-alt-primary" href="javascript:void(0)">-->
<!--                        <i class="fa fa-users opacity-50 me-1"></i> View Users-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4">-->
<!--                <div class="block block-rounded block-transparent bg-info">-->
<!--                <div class="block-content block-content-full">-->
<!--                    <div class="py-3 text-center">-->
<!--                    <div class="mb-3">-->
<!--                        <i class="fa fa-envelope-open fa-4x text-info-light"></i>-->
<!--                    </div>-->
<!--                    <div class="fs-4 fw-semibold text-white">New Domain</div>-->
<!--                    <div class="text-white-75">Total domain From last Search</div>-->
<!--                    <div class="pt-3">-->
<!--                        <a class="btn btn-alt-info" href="javascript:void(0)">-->
<!--                        <i class="fa fa-envelope-open opacity-50 me-1"></i> View Domain-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4">-->
<!--                <div class="block block-rounded block-transparent bg-success">-->
<!--                <div class="block-content block-content-full">-->
<!--                    <div class="py-3 text-center">-->
<!--                    <div class="mb-3">-->
<!--                        <i class="fa fa-check fa-4x text-success-light"></i>-->
<!--                    </div>-->
<!--                    <div class="fs-4 fw-semibold text-white">Total Domain</div>-->
<!--                    <div class=" text-white-75">All Domain</div>-->
<!--                    <div class="pt-3">-->
<!--                        <a class="btn btn-alt-success" href="javascript:void(0)">-->
<!--                        <i class="fa fa-arrow-up opacity-50 me-1"></i> List Domain-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                </div>-->
<!--            </div>-->
            <!-- END Row #4 -->



    </AuthenticatedLayout>
</template>
