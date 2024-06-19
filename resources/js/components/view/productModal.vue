<template>
    <div v-if="visible" class="modal-overlay" @click="close">
        <div class="modal-container" @click.stop>
            <button class="close-button" @click="close">×</button>
            <h2>Product Detail</h2>
            <div
                v-if="product && product.image && product.image.length > 0"
                class="image-wrapper"
            >
                <button class="nav-button left" @click="prevImage">←</button>
                <img :src="'/images/' + currentImage" />
                <button class="nav-button right" @click="nextImage">→</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        visible: Boolean,
        product: Object,
    },
    data() {
        return {
            currentImageIndex: 0,
        };
    },
    computed: {
        currentImage() {
            if (
                this.product &&
                this.product.image &&
                Array.isArray(this.product.image)
            ) {
                return this.product.image[this.currentImageIndex];
            }
            return this.product ? this.product.image : "";
        },
    },
    methods: {
        close() {
            this.$emit("close");
        },
        nextImage() {
            if (this.currentImageIndex < this.product.image.length - 1) {
                this.currentImageIndex++;
            }
        },
        prevImage() {
            if (this.currentImageIndex > 0) {
                this.currentImageIndex--;
            }
        },
    },
    watch: {
        product() {
            this.currentImageIndex = 0;
        },
    },
};
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-container {
    background: white;
    padding: 20px;
    border-radius: 8px;
    position: relative;
    max-width: 90%;
    max-height: 90%;
    overflow-y: auto;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
}

.image-wrapper {
    display: flex;
    align-items: center;
}

.nav-button {
    background: none;
    border: none;
    font-size: 2em;
    cursor: pointer;
}

.nav-button.left {
    margin-right: 10px;
}

.nav-button.right {
    margin-left: 10px;
}

img {
    max-width: 100%;
    height: auto;
    display: block;
}
</style>
