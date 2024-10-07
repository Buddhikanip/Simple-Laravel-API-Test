<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter, useRoute } from "vue-router";

const router = useRouter();
const route = useRoute();

const supplierId = route.params.id;
const supplierName = ref("");
const contactPerson = ref("");
const mobileNumber = ref("");
const products = ref([{ name: "", price: "", image: null }]);

const errors = ref([]);
const successMessage = ref("");

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
                image: null,
            }));
        }
    } catch (error) {
        errors.value.push("Error fetching supplier data.");
    }
};

const addProduct = () => {
    products.value.push({ name: "", price: "", image: null });
};

const removeProduct = (index) => {
    products.value.splice(index, 1);
};

const handleFileChange = (e, index) => {
    const file = e.target.files[0];
    products.value[index].image = file;
};

const submitForm = async () => {
    errors.value = [];
    successMessage.value = "";

    const formData = new FormData();
    formData.append("supplier_name", supplierName.value);
    formData.append("contact_person", contactPerson.value);
    formData.append("mobile_number", mobileNumber.value);

    products.value.forEach((product, index) => {
        formData.append(`products[${index}][name]`, product.name);
        formData.append(`products[${index}][price]`, product.price);

        if (product.image) {
            formData.append(`products[${index}][image]`, product.image);
        }
    });

    try {
        const response = await axios.post(
            `/suppliers/${supplierId}`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        if (response.data.success) {
            successMessage.value = "Supplier updated successfully!";
            setTimeout(() => {
                router.push("/");
            }, 1500);
        }
    } catch (error) {
        if (error.response.status == 422) {
            errors.value = [];

            Object.values(error.response.data.errors).forEach((fieldErrors) => {
                fieldErrors.forEach((message) => {
                    errors.value.push(message);
                });
            });
        } else {
            errors.value = [];
            errors.value.push("An unexpected error occurred.");
        }
    }
};
</script>

<template>
    <div class="container mt-4">
        <!-- Success Message -->
        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>

        <!-- Error Alert Box -->
        <div v-if="errors.length >= 1" class="alert alert-danger">
            <ul>
                <li v-for="(error, index) in errors" :key="index">
                    {{ error }}
                </li>
            </ul>
        </div>

        <!-- Supplier Edit Form -->
        <form @submit.prevent="submitForm">
            <div class="mb-3">
                <label for="supplierName" class="form-label"
                    >Supplier Name</label
                >
                <input
                    v-model="supplierName"
                    type="text"
                    class="form-control"
                    id="supplierName"
                    placeholder="Enter supplier name"
                />
            </div>

            <div class="mb-3">
                <label for="contactPerson" class="form-label"
                    >Contact Person</label
                >
                <input
                    v-model="contactPerson"
                    type="text"
                    class="form-control"
                    id="contactPerson"
                    placeholder="Enter contact person"
                />
            </div>

            <div class="mb-3">
                <label for="mobileNumber" class="form-label"
                    >Mobile Numbers</label
                >
                <input
                    v-model="mobileNumber"
                    type="text"
                    class="form-control"
                    id="mobileNumber"
                    placeholder="Enter mobile numbers"
                />
            </div>

            <div v-for="(product, index) in products" :key="index" class="mb-3">
                <label class="form-label">Product {{ index + 1 }}</label>
                <div class="d-flex">
                    <input
                        v-model="product.name"
                        type="text"
                        class="form-control me-2"
                        placeholder="Product Name"
                    />
                    <input
                        v-model="product.price"
                        type="number"
                        class="form-control me-2"
                        placeholder="Price"
                    />
                    <input
                        type="file"
                        class="form-control"
                        @change="(e) => handleFileChange(e, index)"
                    />
                    <button
                        type="button"
                        class="btn btn-danger ms-2"
                        @click="removeProduct(index)"
                        v-if="products.length > 1"
                    >
                        Remove
                    </button>
                </div>
            </div>

            <button
                type="button"
                class="btn btn-secondary mb-3 float-end"
                @click="addProduct"
            >
                Add Another Product
            </button>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</template>
