

<template>
    <!-- start content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12"></div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h3>Category List</h3>
                        </div>
                        <hr />
                        <div class="float-end">
                            <a
                                href="/category-save-page?id=0"
                                class="btn btn-success mx-3 btn-sm"
                            >
                                Add Category
                            </a>
                        </div>

                        <!-- Modal -->

                        <div>
                            <input
                                placeholder="Search..."
                                class="form-control mb-2 w-auto form-control-sm"
                                type="text"
                                v-model="searchValue"
                            />
                            <EasyDataTable
                                buttons-pagination
                                alternating
                                :headers="Header"
                                :items="Items"
                                :rows-per-page="10"
                                :search-field="searchField"
                                :search-value="searchValue"
                                show-index
                            >
                                <template #item-number="{ id, name }">
                                    <a
                                        class="btn btn-success mx-3 btn-sm"
                                        :href="`/category-save-page?id=${id}`"
                                        >Edit</a
                                    >
                                    <button
                                        class="btn btn-danger btn-sm"
                                        @click="DeleteClick(id)"
                                    >
                                        Delete
                                    </button>
                                </template>
                            </EasyDataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end content -->
</template>

<script setup>
     import { Link, useForm, usePage, router } from "@inertiajs/vue3";
    import { createToaster } from "@meforma/vue-toaster";
    const toaster = createToaster({ position: "top-right" });
    import { ref } from "vue";

    let page = usePage();
    const Items = ref(page.props.categories);
    const Header = [
        
        { text: "Name", value: "name" },
        { text: "Action", value: "number" },
    ];

    const searchValue = ref("");

    const DeleteClick = (id) => {
        let text = "Do you went to delete"
        if(confirm(text)===true){
            router.get(`/delete-category/${id}`);
            toaster.success("Category Deleted successfully");
        }else{
            text = "you canceled!";
        }
    }
</script>
