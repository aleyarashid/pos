<template>
    <div>
        <label for="image" class="mb-3">
            <img :src="(preview || currentImage) ?? '/uploads/placeholder.png'" class="img-thumbnail" height="60px" width="60px" alt="">
        </label>
        &nbsp; &nbsp;
         
        <input @input="imageSelected($event)" type="file" id="image" name="image" class="form-control">
    </div>
</template>

<script setup>
import { ref } from 'vue';
const props = defineProps({
    productImage: String
});
const emit = defineEmits(['image']);
const preview = ref(null);
const currentImage = props.productImage;
const imageSelected = (e) => {
    const file = e.target.files[0];
    preview.value = URL.createObjectURL(file);
    emit('image', file);
};

</script>