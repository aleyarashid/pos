<template>
    <main>
        <!-- start content -->
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
                                <Link
                                    href="/category-page"
                                    class="btn btn-success mx-3 btn-sm"
                                >
                                    Back
                                </Link>
                            </div>
                            <form @submit.prevent="submit">
                                <div class="card-body">
                                    <h4>Save Category</h4>
                                   
                                    <input
                                        id="name"
                                        name="name"
                                        v-model="form.name"
                                        placeholder="Category Name"
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
    </main>
</template>

<script setup>
import { Link, useForm, usePage, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({ position: "top-right" });
import { ref } from "vue";
const urlParams = new URLSearchParams(window.location.search);
let id = ref(parseInt(urlParams.get("id")));

const form = useForm({
    id: id,
    name: ""
});
const page = usePage();

let URL = "/create-category";
let category = page.props.category;
if(id.value !== 0 && category !== null){
    form.name = category.name;
    URL = "/update-category"; 
}

function submit(){
    if (form.name.length === 0) {
        toaster.warning("Category name is required");
        }
    else{
        form.post(
            URL,
            {
                onSuccess: () => {
                    router.get("/category-page");
                    toaster.success(page.props.flash.message);
                },
            }
        )
    }
}
</script>
