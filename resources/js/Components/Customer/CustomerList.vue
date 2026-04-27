

<template>
    <!-- start content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12"></div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h3>Customer</h3>
                        </div>
                        <hr />
                        <div class="float-end">
                            <a
                                href="/customer-save-page?id=0"
                                class="btn btn-success mx-3 btn-sm"
                            >
                                Add Customer
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
                                :items="Item"
                                :rows-per-page="10"
                                :search-field="searchField"
                                :search-value="searchValue"
                            >
                                <template #item-number="{ id, name }">
                                    <Link
                                        class="btn btn-success mx-3 btn-sm"
                                        :href="`/customer-save-page?id=${id}`"
                                        >Edit</Link
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
    import { usePage, router, Link } from '@inertiajs/vue3'
    import { createToaster } from "@meforma/vue-toaster";
    const toaster = createToaster();
    import { ref } from "vue";

    let page = usePage();

    const Header = [
        { text: "Name", value: "name" },
        { text: "Email", value: "email" },
        { text: "Mobile", value: "mobile" },
        { text: "Action", value: "number" },
    ];
    const Item = page.props.customers;
    const searchValue = ref("");

    const DeleteClick = (id) =>{
        let text = "Are you sure to delete?";
        if (confirm(text) == true) {
            router.get(`/delete-customer/${id}`);
            toaster.success("Customer Deleted Successfully");
        }
        else {
            toaster.warning("Customer Deletion Cancelled");
        }
    }
</script>