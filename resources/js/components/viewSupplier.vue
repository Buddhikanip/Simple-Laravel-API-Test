<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";

const supplierName = ref("");
const contactPerson = ref("");
const mobileNumber = ref("");
const products = ref([]);
const errorMessage = ref("");
const successMessage = ref("");
const router = useRouter();
const route = useRoute();

const supplierId = route.params.id;

onMounted(() => {
    fetchSupplier();
});

const fetchSupplier = async () => {
    try {
        const response = await axios.get(`/suppliers/${supplierId}`);
        if (response.data.success) {
            const supplier = response.data.supplier;
            supplierName.value = supplier.supplier_name;
            contactPerson.value = supplier.contact_person;
            mobileNumber.value = supplier.mobile_number;
            products.value = supplier.products.map((product) => ({
                name: product.product_name,
                price: product.product_price,
                image: product.product_image
                    ? `/storage/${product.product_image}`
                    : null,
            }));
        }
    } catch (error) {
        errorMessage.value = "Error fetching supplier data.";
        console.error(error);
    }
};

const deleteSupplier = async () => {
    const confirmed = confirm("Are you sure you want to delete this supplier?");
    if (!confirmed) return;

    try {
        const response = await axios.delete(`/suppliers/${supplierId}`);
        if (response.data.success) {
            successMessage.value = "Supplier deleted successfully.";
            setTimeout(() => {
                router.push("/");
            }, 1500);
        } else {
            errorMessage.value = "Failed to delete supplier.";
        }
    } catch (error) {
        errorMessage.value = "Error deleting supplier.";
        console.error(error);
    }
};

const goBack = () => {
    router.push("/");
};
</script>

<template>
    <div class="container mt-4">
        <h1 class="mb-4">Supplier Details</h1>

        <!-- Success Message -->
        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="alert alert-danger">
            {{ errorMessage }}
        </div>

        <!-- Supplier Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Supplier Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Supplier Name:</strong> {{ supplierName }}</p>
                <p><strong>Contact Person:</strong> {{ contactPerson }}</p>
                <p><strong>Mobile Number:</strong> {{ mobileNumber }}</p>

                <div v-if="products.length > 0">
                    <h5 class="mt-4">Products</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(product, index) in products"
                                :key="index"
                            >
                                <td>{{ product.name }}</td>
                                <td>{{ product.price }}</td>
                                <td>
                                    <img
                                        v-if="product.image"
                                        :src="product.image"
                                        :alt="product.name"
                                        style="width: 100px; height: auto"
                                    />
                                    <span v-else>No Image</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-muted">
                    No products found for this supplier.
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button @click="goBack" class="btn btn-primary">
                Back to Suppliers
            </button>
            <div>
                <a
                    @click.prevent="deleteSupplier"
                    class="btn btn-outline-danger mx-2"
                    >Delete</a
                >
                <a
                    :href="`/edit/${supplierId}`"
                    class="btn btn-outline-primary mx-2"
                >
                    Edit
                </a>
            </div>
        </div>
    </div>
</template>
