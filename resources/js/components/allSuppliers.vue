<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";

let suppliers = ref([]);
let query = ref("");
let currentPage = ref(1);
let lastPage = ref(1);
const errorMessage = ref("");
const successMessage = ref("");

onMounted(() => {
    fetchSuppliers();
});

const fetchSuppliers = async (page = 1) => {
    await axios
        .get(`/suppliers?page=${page}`)
        .then((response) => {
            suppliers.value = response.data.suppliers.data;
            currentPage.value = response.data.suppliers.current_page;
            lastPage.value = response.data.suppliers.last_page;
        })
        .catch((error) => console.error(error));
};

const searchSuppliers = async () => {
    await axios
        .get(`/suppliers/search?search=${query.value}`)
        .then((response) => {
            suppliers.value = response.data.suppliers.data;
            currentPage.value = response.data.suppliers.current_page;
            lastPage.value = response.data.suppliers.last_page;
        })
        .catch((error) => console.error(error));
};

const deleteSupplier = async (supplierId) => {
    const confirmed = confirm("Are you sure you want to delete this supplier?");
    if (!confirmed) return;

    try {
        const response = await axios.delete(`/suppliers/${supplierId}`);
        if (response.data.success) {
            successMessage.value = "Supplier deleted successfully.";
            setTimeout(() => {
                fetchSuppliers();
                successMessage.value = "";
            }, 1500);
        } else {
            errorMessage.value = "Failed to delete supplier.";
        }
    } catch (error) {
        errorMessage.value = "Error deleting supplier.";
        console.error(error);
    }
};
</script>

<template>
    <div class="container mt-4">
        <!-- Success Message -->
        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="alert alert-danger">
            {{ errorMessage }}
        </div>

        <!-- Search Box -->
        <input
            v-model="query"
            @input="searchSuppliers"
            class="form-control mb-4"
            placeholder="Search Suppliers by Name or Mobile Number"
        />

        <!-- Suppliers Table -->
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Supplier Name</th>
                    <th scope="col">Contact Person</th>
                    <th scope="col">Mobile Number</th>
                    <th scope="col">Products Count</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="supplier in suppliers" :key="supplier.id">
                    <th scope="row">
                        <a :href="`/view/${supplier.id}`">#{{ supplier.id }}</a>
                    </th>
                    <td>{{ supplier.supplier_name }}</td>
                    <td>{{ supplier.contact_person }}</td>
                    <td>{{ supplier.mobile_number }}</td>
                    <td>{{ supplier.products_count }}</td>
                    <td>
                        <a
                            :href="`/edit/${supplier.id}`"
                            class="btn btn-sm btn-primary px-3 mx-2"
                            >Edit</a
                        >
                        <a
                            @click.prevent="deleteSupplier(supplier.id)"
                            class="btn btn-sm btn-danger px-3 mx-2"
                            >Delete</a
                        >
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <a
                        class="page-link"
                        href="#"
                        @click.prevent="fetchSuppliers(currentPage - 1)"
                        >Previous</a
                    >
                </li>
                <li
                    class="page-item"
                    :class="{ disabled: currentPage === lastPage }"
                >
                    <a
                        class="page-link"
                        href="#"
                        @click.prevent="fetchSuppliers(currentPage + 1)"
                        >Next</a
                    >
                </li>
            </ul>
        </nav>
    </div>
</template>
