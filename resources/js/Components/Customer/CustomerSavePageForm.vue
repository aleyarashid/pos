<template>
    <!-- start content -->
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <Link
                                href="/customer-save-page"
                                class="btn btn-success mx-3 btn-sm"
                            >
                                Back
                            </Link>
                        </div>
                        <form @submit.prevent="submit">
                            <div class="card-body">
                                <h4>Save Customer</h4>
                                
                                <input
                                    id="name"
                                    name="name"
                                    v-model="form.name"
                                    placeholder="Customer Name"
                                    class="form-control"
                                    type="text"
                                />
                                <br />
                                <input
                                    id="email"
                                    name="email"
                                    v-model="form.email"
                                    placeholder="Customer Email"
                                    class="form-control"
                                    type="email"
                                />
                                <br />
                                <input
                                    id="mobile"
                                    name="mobile"
                                    v-model="form.mobile"
                                    placeholder="Customer Mobile"
                                    class="form-control"
                                    type="text"
                                />
                                <br />
                                <button
                                    type="submit"
                                    class="btn w-100 btn-success"
                                >
                                    Save Change
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end content -->
</template>

<script setup>
import { useForm, usePage, router, Link } from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();
import { ref } from "vue";
import { parse } from 'vue/compiler-sfc';

const useParams = new URLSearchParams(window.location.search);
let id = ref(parseInt(useParams.get('id')));

const form = useForm({
    id: id,
    name: '',
    email: '',
    mobile: ''
});

const page = usePage();

let URL = "/create-customer";
let list = page.props.customer;
if (id.value !== 0 && list !== null) {
    form.name = list['name'];
    form.email = list['email'];
    form.mobile = list['mobile'];
    URL = "/update-customer";
}

function submit() {
    if (form.name.length === 0) {
        toaster.warning("Please Enter Name");
    }
    else if (form.email.length === 0) {
        toaster.warning("Please Enter Email");
    }
    else if (form.mobile.length === 0) {
        toaster.warning("Please Enter Mobile Number");
    }
    else {
        form.post(
            URL,
            {
                onSuccess: () => {
                    router.get("/customer-page");
                    toaster.success(page.props.flash.message);
                },
            }
        );
    }
}
</script>
